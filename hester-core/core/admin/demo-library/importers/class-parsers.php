<?php
/**
 * WordPress eXtended RSS file parser implementations.
 *
 * Taken from WordPress Importer plugin.
 *
 * @package WordPress
 * @subpackage Importer
 */

// phpcs:ignoreFile
/**
 * WordPress Importer class for managing parsing of WXR files.
 */
class Hester_Core_WXR_Parser {
	public function parse( $file ) {
		// Allow forcing a specific parser via WXR_PARSER: simplexml|xml|regex|xmlprocessor
		$preferred_parser = defined( 'PREFERRED_WXR_PARSER' ) ? constant( 'PREFERRED_WXR_PARSER' ) : null;
		if ( $preferred_parser ) {
			$available_parsers = array(
				'simplexml'    => 'Hester_Core_WXR_Parser_SimpleXML',
				'xml'          => 'Hester_Core_WXR_Parser_XML',
				'regex'        => 'Hester_Core_WXR_Parser_Regex',
				'xmlprocessor' => 'Hester_Core_WXR_Parser_XML_Processor',
			);
			if ( isset( $available_parsers[ $preferred_parser ] ) ) {
				$parser = new $available_parsers[ $preferred_parser ]();
				$result = $parser->parse( $file );
			} else {
				_doing_it_wrong( __FUNCTION__, sprintf( __( 'Invalid parser specified: %s', 'hester-core' ), $preferred_parser ), '0.9.0' );
				$result = new WP_Error( 'invalid_parser', sprintf( __( 'Invalid parser specified: %s', 'hester-core' ), $preferred_parser ) );
			}

			// If XMLParser succeeds or this is an invalid WXR file then return the results
			if ( ! is_wp_error( $result ) || 'XML_parse_error' != $result->get_error_code() ) {
				return $result;
			}
 		} else {
			// Attempt to auto-select the best XML parser based on available extensions
			if ( extension_loaded( 'simplexml' ) ) {
				$parser = new Hester_Core_WXR_Parser_SimpleXML();
				$result = $parser->parse( $file );

				// If SimpleXML succeeds or this is an invalid WXR file then return the results
				if ( ! is_wp_error( $result ) || 'SimpleXML_parse_error' != $result->get_error_code() ) {
					return $result;
				}
			} elseif ( extension_loaded( 'xml' ) ) {
				$parser = new Hester_Core_WXR_Parser_XML();
				$result = $parser->parse( $file );

				// If XMLParser succeeds or this is an invalid WXR file then return the results
				if ( ! is_wp_error( $result ) || 'XML_parse_error' != $result->get_error_code() ) {
					return $result;
				}
			}
		}

		// We have a malformed XML file, so display the error and fallthrough to regex
		if ( isset( $result ) && defined( 'IMPORT_DEBUG' ) && IMPORT_DEBUG ) {
			echo '<pre>';
			if ( 'SimpleXML_parse_error' == $result->get_error_code() ) {
				foreach ( $result->get_error_data() as $error ) {
					echo esc_html( $error->line ) . ':' . esc_html( $error->column ) . ' ' . esc_html( $error->message ) . "\n";
				}
			} elseif ( 'XML_parse_error' == $result->get_error_code() ) {
				$error = $result->get_error_data();
				echo esc_html( $error[0] ) . ':' . esc_html( $error[1] ) . ' ' . esc_html( $error[2] );
			}
			echo '</pre>';
			echo '<p><strong>' . __( 'There was an error when reading this WXR file', 'hester-core' ) . '</strong><br />';
			echo __( 'Details are shown above. The importer will now try again with a different parser...', 'hester-core' ) . '</p>';
		}

		// use regular expressions if nothing else available or this is bad XML
		$parser = new Hester_Core_WXR_Parser_XML_Processor();
		return $parser->parse( $file );
	}
}

/**
 * WXR Parser that makes use of the SimpleXML PHP extension.
 */
class Hester_Core_WXR_Parser_SimpleXML {
	public function parse( $file ) {
		$authors = $posts = $categories = $tags = $terms = array();

		$internal_errors = libxml_use_internal_errors( true );

		$dom = new DOMDocument();
		$success = $dom->loadXML( file_get_contents( $file ), LIBXML_NOENT | LIBXML_DTDLOAD );

		if ( ! $success || isset( $dom->doctype ) ) {

			libxml_clear_errors();
			libxml_use_internal_errors( $internal_errors );

			return new WP_Error( 'SimpleXML_parse_error', __( 'There was an error when reading this WXR file', 'hester-core' ), libxml_get_errors() );
		}

		$xml = simplexml_import_dom( $dom );
		unset( $dom );

		// halt if loading produces an error
		if ( ! $xml ) {

			libxml_clear_errors();
			libxml_use_internal_errors( $internal_errors );

			return new WP_Error( 'SimpleXML_parse_error', __( 'There was an error when reading this WXR file', 'hester-core' ), libxml_get_errors() );
		}

		$wxr_version = $xml->xpath( '/rss/channel/wp:wxr_version' );
		if ( ! $wxr_version ) {

			libxml_clear_errors();
			libxml_use_internal_errors( $internal_errors );

			return new WP_Error( 'WXR_parse_error', __( 'This does not appear to be a WXR file, missing/invalid WXR version number', 'hester-core' ) );
		}

		$wxr_version = (string) trim( $wxr_version[0] );
		// confirm that we are dealing with the correct file format
		if ( ! preg_match( '/^\d+\.\d+$/', $wxr_version ) ) {

			libxml_clear_errors();
			libxml_use_internal_errors( $internal_errors );

			return new WP_Error( 'WXR_parse_error', __( 'This does not appear to be a WXR file, missing/invalid WXR version number', 'hester-core' ) );
		}

		$base_url = $xml->xpath( '/rss/channel/wp:base_site_url' );
		$base_url = (string) trim( $base_url[0] );

		$blog_url = $xml->xpath( '/rss/channel/wp:base_blog_url' );
		$blog_url = (string) trim( $blog_url[0] );

		$namespaces = $xml->getDocNamespaces();
		if ( ! isset( $namespaces['wp'] ) ) {
			$namespaces['wp'] = 'http://wordpress.org/export/1.1/';
		}
		if ( ! isset( $namespaces['excerpt'] ) ) {
			$namespaces['excerpt'] = 'http://wordpress.org/export/1.1/excerpt/';
		}

		// grab authors
		foreach ( $xml->xpath( '/rss/channel/wp:author' ) as $author_arr ) {
			$a                 = $author_arr->children( $namespaces['wp'] );
			$login             = (string) $a->author_login;
			$authors[ $login ] = array(
				'author_id'           => (int) $a->author_id,
				'author_login'        => $login,
				'author_email'        => (string) $a->author_email,
				'author_display_name' => (string) $a->author_display_name,
				'author_first_name'   => (string) $a->author_first_name,
				'author_last_name'    => (string) $a->author_last_name,
			);
		}

		// grab cats, tags and terms
		foreach ( $xml->xpath( '/rss/channel/wp:category' ) as $term_arr ) {
			$t        = $term_arr->children( $namespaces['wp'] );
			$category = array(
				'term_id'              => (int) $t->term_id,
				'category_nicename'    => (string) $t->category_nicename,
				'category_parent'      => (string) $t->category_parent,
				'cat_name'             => (string) $t->cat_name,
				'category_description' => (string) $t->category_description,
			);

			foreach ( $t->termmeta as $meta ) {
				$category['termmeta'][] = array(
					'key'   => (string) $meta->meta_key,
					'value' => (string) $meta->meta_value,
				);
			}

			$categories[] = $category;
		}

		foreach ( $xml->xpath( '/rss/channel/wp:tag' ) as $term_arr ) {
			$t   = $term_arr->children( $namespaces['wp'] );
			$tag = array(
				'term_id'         => (int) $t->term_id,
				'tag_slug'        => (string) $t->tag_slug,
				'tag_name'        => (string) $t->tag_name,
				'tag_description' => (string) $t->tag_description,
			);

			foreach ( $t->termmeta as $meta ) {
				$tag['termmeta'][] = array(
					'key'   => (string) $meta->meta_key,
					'value' => (string) $meta->meta_value,
				);
			}

			$tags[] = $tag;
		}

		foreach ( $xml->xpath( '/rss/channel/wp:term' ) as $term_arr ) {
			$t    = $term_arr->children( $namespaces['wp'] );
			$term = array(
				'term_id'          => (int) $t->term_id,
				'term_taxonomy'    => (string) $t->term_taxonomy,
				'slug'             => (string) $t->term_slug,
				'term_parent'      => (string) $t->term_parent,
				'term_name'        => (string) $t->term_name,
				'term_description' => (string) $t->term_description,
			);

			foreach ( $t->termmeta as $meta ) {
				$term['termmeta'][] = array(
					'key'   => (string) $meta->meta_key,
					'value' => (string) $meta->meta_value,
				);
			}

			$terms[] = $term;
		}

		// grab posts
		foreach ( $xml->channel->item as $item ) {
			$post = array(
				'post_title' => (string) $item->title,
				'guid'       => (string) $item->guid,
			);

			$dc                  = $item->children( 'http://purl.org/dc/elements/1.1/' );
			$post['post_author'] = (string) $dc->creator;

			$content              = $item->children( 'http://purl.org/rss/1.0/modules/content/' );
			$excerpt              = $item->children( $namespaces['excerpt'] );
			$post['post_content'] = (string) $content->encoded;
			$post['post_excerpt'] = (string) $excerpt->encoded;

			$wp                     = $item->children( $namespaces['wp'] );
			$post['post_id']        = (int) $wp->post_id;
			$post['post_date']      = (string) $wp->post_date;
			$post['post_date_gmt']  = (string) $wp->post_date_gmt;
			$post['comment_status'] = (string) $wp->comment_status;
			$post['ping_status']    = (string) $wp->ping_status;
			$post['post_name']      = (string) $wp->post_name;
			$post['status']         = (string) $wp->status;
			$post['post_parent']    = (int) $wp->post_parent;
			$post['menu_order']     = (int) $wp->menu_order;
			$post['post_type']      = (string) $wp->post_type;
			$post['post_password']  = (string) $wp->post_password;
			$post['is_sticky']      = (int) $wp->is_sticky;

			if ( isset( $wp->attachment_url ) ) {
				$post['attachment_url'] = (string) $wp->attachment_url;
			}

			foreach ( $item->category as $c ) {
				$att = $c->attributes();
				if ( isset( $att['nicename'] ) ) {
					$post['terms'][] = array(
						'name'   => (string) $c,
						'slug'   => (string) $att['nicename'],
						'domain' => (string) $att['domain'],
					);
				}
			}

			foreach ( $wp->postmeta as $meta ) {
				$post['postmeta'][] = array(
					'key'   => (string) $meta->meta_key,
					'value' => (string) $meta->meta_value,
				);
			}

			foreach ( $wp->comment as $comment ) {
				$meta = array();
				if ( isset( $comment->commentmeta ) ) {
					foreach ( $comment->commentmeta as $m ) {
						$meta[] = array(
							'key'   => (string) $m->meta_key,
							'value' => (string) $m->meta_value,
						);
					}
				}

				$post['comments'][] = array(
					'comment_id'           => (int) $comment->comment_id,
					'comment_author'       => (string) $comment->comment_author,
					'comment_author_email' => (string) $comment->comment_author_email,
					'comment_author_IP'    => (string) $comment->comment_author_IP,
					'comment_author_url'   => (string) $comment->comment_author_url,
					'comment_date'         => (string) $comment->comment_date,
					'comment_date_gmt'     => (string) $comment->comment_date_gmt,
					'comment_content'      => (string) $comment->comment_content,
					'comment_approved'     => (string) $comment->comment_approved,
					'comment_type'         => (string) $comment->comment_type,
					'comment_parent'       => (string) $comment->comment_parent,
					'comment_user_id'      => (int) $comment->comment_user_id,
					'commentmeta'          => $meta,
				);
			}

			$posts[] = $post;
		}

		libxml_clear_errors();
		libxml_use_internal_errors( $internal_errors );

		return array(
			'authors'    => $authors,
			'posts'      => $posts,
			'categories' => $categories,
			'tags'       => $tags,
			'terms'      => $terms,
			'base_url'   => $base_url,
			'blog_url'   => $blog_url,
			'version'    => $wxr_version,
		);
	}
}

/**
 * WXR Parser that makes use of the XML Parser PHP extension.
 */
class Hester_Core_WXR_Parser_XML {
	var $wp_tags     = array(
		'wp:post_id',
		'wp:post_date',
		'wp:post_date_gmt',
		'wp:comment_status',
		'wp:ping_status',
		'wp:attachment_url',
		'wp:status',
		'wp:post_name',
		'wp:post_parent',
		'wp:menu_order',
		'wp:post_type',
		'wp:post_password',
		'wp:is_sticky',
		'wp:term_id',
		'wp:category_nicename',
		'wp:category_parent',
		'wp:cat_name',
		'wp:category_description',
		'wp:tag_slug',
		'wp:tag_name',
		'wp:tag_description',
		'wp:term_taxonomy',
		'wp:term_parent',
		'wp:term_name',
		'wp:term_description',
		'wp:author_id',
		'wp:author_login',
		'wp:author_email',
		'wp:author_display_name',
		'wp:author_first_name',
		'wp:author_last_name',
	);
	var $wp_sub_tags = array(
		'wp:comment_id',
		'wp:comment_author',
		'wp:comment_author_email',
		'wp:comment_author_url',
		'wp:comment_author_IP',
		'wp:comment_date',
		'wp:comment_date_gmt',
		'wp:comment_content',
		'wp:comment_approved',
		'wp:comment_type',
		'wp:comment_parent',
		'wp:comment_user_id',
	);

	public function parse( $file ) {
		$this->wxr_version = $this->in_post = $this->cdata = $this->data = $this->sub_data = $this->in_tag = $this->in_sub_tag = false;
		$this->authors     = $this->posts = $this->term = $this->category = $this->tag = array();

		$xml = xml_parser_create( 'UTF-8' );
		xml_parser_set_option( $xml, XML_OPTION_SKIP_WHITE, 1 );
		xml_parser_set_option( $xml, XML_OPTION_CASE_FOLDING, 0 );
		xml_set_character_data_handler( $xml, array( $this, 'cdata' ) );
		xml_set_element_handler( $xml, array( $this, 'tag_open' ), array( $this, 'tag_close' ) );

		if ( ! xml_parse( $xml, file_get_contents( $file ), true ) ) {
			$current_line   = xml_get_current_line_number( $xml );
			$current_column = xml_get_current_column_number( $xml );
			$error_code     = xml_get_error_code( $xml );
			$error_string   = xml_error_string( $error_code );
			return new WP_Error( 'XML_parse_error', 'There was an error when reading this WXR file', array( $current_line, $current_column, $error_string ) );
		}
		xml_parser_free( $xml );

		if ( ! preg_match( '/^\d+\.\d+$/', $this->wxr_version ) ) {
			return new WP_Error( 'WXR_parse_error', __( 'This does not appear to be a WXR file, missing/invalid WXR version number', 'hester-core' ) );
		}

		return array(
			'authors'    => $this->authors,
			'posts'      => $this->posts,
			'categories' => $this->category,
			'tags'       => $this->tag,
			'terms'      => $this->term,
			'base_url'   => $this->base_url,
			'version'    => $this->wxr_version,
		);
	}

	public function tag_open( $parse, $tag, $attr ) {
		if ( in_array( $tag, $this->wp_tags ) ) {
			$this->in_tag = substr( $tag, 3 );
			return;
		}

		if ( in_array( $tag, $this->wp_sub_tags ) ) {
			$this->in_sub_tag = substr( $tag, 3 );
			return;
		}

		switch ( $tag ) {
			case 'category':
				if ( isset( $attr['domain'], $attr['nicename'] ) ) {
					$this->sub_data['domain'] = $attr['domain'];
					$this->sub_data['slug']   = $attr['nicename'];
				}
				break;
			case 'item':
				$this->in_post = true;
			case 'title':
				if ( $this->in_post ) {
					$this->in_tag = 'post_title';
				} break;
			case 'guid':
				$this->in_tag = 'guid';
				break;
			case 'dc:creator':
				$this->in_tag = 'post_author';
				break;
			case 'content:encoded':
				$this->in_tag = 'post_content';
				break;
			case 'excerpt:encoded':
				$this->in_tag = 'post_excerpt';
				break;

			case 'wp:term_slug':
				$this->in_tag = 'slug';
				break;
			case 'wp:meta_key':
				$this->in_sub_tag = 'key';
				break;
			case 'wp:meta_value':
				$this->in_sub_tag = 'value';
				break;
		}
	}

	public function cdata( $parser, $cdata ) {
		if ( ! trim( $cdata ) ) {
			return;
		}

		if ( false !== $this->in_tag || false !== $this->in_sub_tag ) {
			$this->cdata .= $cdata;
		} else {
			$this->cdata .= trim( $cdata );
		}
	}

	public function tag_close( $parser, $tag ) {
		switch ( $tag ) {
			case 'wp:comment':
				unset( $this->sub_data['key'], $this->sub_data['value'] ); // remove meta sub_data
				if ( ! empty( $this->sub_data ) ) {
					$this->data['comments'][] = $this->sub_data;
				}
				$this->sub_data = false;
				break;
			case 'wp:commentmeta':
				$this->sub_data['commentmeta'][] = array(
					'key'   => $this->sub_data['key'],
					'value' => $this->sub_data['value'],
				);
				break;
			case 'category':
				if ( ! empty( $this->sub_data ) ) {
					$this->sub_data['name'] = $this->cdata;
					$this->data['terms'][]  = $this->sub_data;
				}
				$this->sub_data = false;
				break;
			case 'wp:postmeta':
				if ( ! empty( $this->sub_data ) ) {
					$this->data['postmeta'][] = $this->sub_data;
				}
				$this->sub_data = false;
				break;
			case 'item':
				$this->posts[] = $this->data;
				$this->data    = false;
				break;
			case 'wp:category':
			case 'wp:tag':
			case 'wp:term':
				$n = substr( $tag, 3 );
				array_push( $this->$n, $this->data );
				$this->data = false;
				break;
			case 'wp:author':
				if ( ! empty( $this->data['author_login'] ) ) {
					$this->authors[ $this->data['author_login'] ] = $this->data;
				}
				$this->data = false;
				break;
			case 'wp:base_site_url':
				$this->base_url = $this->cdata;
				break;
			case 'wp:base_blog_url':
				$this->blog_url = $this->cdata;
				break;
			case 'wp:wxr_version':
				$this->wxr_version = $this->cdata;
				break;

			default:
				if ( $this->in_sub_tag ) {
					$this->sub_data[ $this->in_sub_tag ] = ! empty( $this->cdata ) ? $this->cdata : '';
					$this->in_sub_tag                    = false;
				} elseif ( $this->in_tag ) {
					$this->data[ $this->in_tag ] = ! empty( $this->cdata ) ? $this->cdata : '';
					$this->in_tag                = false;
				}
		}

		$this->cdata = false;
	}
}

/**
 * Hester_Core_WXR_Parser_Regex class that uses regular expressions. Fallback for installs without an XML parser.
 * @deprecated 0.9.0 Use Hester_Core_WXR_Parser_XML_Processor instead. The WXR_Parser_Regex class
 *             is no longer used by the importer or maintained with bug fixes. The only
 *             reason it is still included in the codebase is for backwards compatibility
 *             with plugins that directly reference it.
 */
class Hester_Core_WXR_Parser_Regex {
	var $authors    = array();
	var $posts      = array();
	var $categories = array();
	var $tags       = array();
	var $terms      = array();
	var $base_url   = '';
	var $blog_url   = '';

	public function __construct() {
		$this->has_gzip = is_callable( 'gzopen' );
	}

	public function parse( $file ) {
		$wxr_version = $in_multiline = false;

		$multiline_content = '';

		$multiline_tags = array(
			'item'        => array( 'posts', array( $this, 'process_post' ) ),
			'wp:category' => array( 'categories', array( $this, 'process_category' ) ),
			'wp:tag'      => array( 'tags', array( $this, 'process_tag' ) ),
			'wp:term'     => array( 'terms', array( $this, 'process_term' ) ),
		);

		$fp = $this->fopen( $file, 'r' );
		if ( $fp ) {
			while ( ! $this->feof( $fp ) ) {
				$importline = rtrim( $this->fgets( $fp ) );

				if ( ! $wxr_version && preg_match( '|<wp:wxr_version>(\d+\.\d+)</wp:wxr_version>|', $importline, $version ) ) {
					$wxr_version = isset( $version[1] ) ? $version[1] : '';
				}

				if ( false !== strpos( $importline, '<wp:base_site_url>' ) ) {
					preg_match( '|<wp:base_site_url>(.*?)</wp:base_site_url>|is', $importline, $url );
					$this->base_url = isset( $url[1] ) ? $url[1] : '';
					continue;
				}

				if ( false !== strpos( $importline, '<wp:base_blog_url>' ) ) {
					preg_match( '|<wp:base_blog_url>(.*?)</wp:base_blog_url>|is', $importline, $url );
					$this->blog_url = isset( $url[1] ) ? $url[1] : '';
					continue;
				}elseif ( empty( $this->blog_url ) ) {
					$this->blog_url = $this->base_url;
				}

				if ( false !== strpos( $importline, '<wp:author>' ) ) {
					preg_match( '|<wp:author>(.*?)</wp:author>|is', $importline, $author );
					 if ( isset( $author[1] ) ) {
						$a                                   = $this->process_author( $author[1] );
						$this->authors[ $a['author_login'] ] = $a;
					}
					continue;
				}

				foreach ( $multiline_tags as $tag => $handler ) {
					// Handle multi-line tags on a singular line
					if ( preg_match( '|<' . $tag . '>(.*?)</' . $tag . '>|is', $importline, $matches ) ) {
						$this->{$handler[0]}[] = call_user_func( $handler[1], isset( $matches[1] ) ? $matches[1] : '' );

					} elseif ( false !== ( $pos = strpos( $importline, "<$tag>" ) ) ) {
						// Take note of any content after the opening tag
						$multiline_content = trim( substr( $importline, $pos + strlen( $tag ) + 2 ) );

						// We don't want to have this line added to `$is_multiline` below.
						$importline   = '';
						$in_multiline = $tag;

					} elseif ( false !== ( $pos = strpos( $importline, "</$tag>" ) ) ) {
						$in_multiline       = false;
						$multiline_content .= trim( substr( $importline, 0, $pos ) );

						$this->{$handler[0]}[] = call_user_func( $handler[1], $multiline_content );
					}
				}

				if ( $in_multiline && $importline ) {
					$multiline_content .= $importline . "\n";
				}
			}

			$this->fclose( $fp );
		}

		if ( ! $wxr_version ) {
			return new WP_Error( 'WXR_parse_error', __( 'This does not appear to be a WXR file, missing/invalid WXR version number', 'hester-core' ) );
		}

		return array(
			'authors'    => $this->authors,
			'posts'      => $this->posts,
			'categories' => $this->categories,
			'tags'       => $this->tags,
			'terms'      => $this->terms,
			'base_url'   => $this->base_url,
			'version'    => $wxr_version,
		);
	}

	public function get_tag( $text, $tag ) {
		if ( null === $text ) {
 	        return '';
 	    }
		preg_match( "|<$tag.*?>(.*?)</$tag>|is", $text, $return );
		if ( isset( $return[1] ) ) {
			if ( substr( $return[1], 0, 9 ) == '<![CDATA[' ) {
				if ( strpos( $return[1], ']]]]><![CDATA[>' ) !== false ) {
					preg_match_all( '|<!\[CDATA\[(.*?)\]\]>|s', $return[1], $matches );
					$return = '';
					if ( isset( $matches[1] ) ) {
                        foreach ( $matches[1] as $match ) {
                            $return .= $match;
                        }
					}
				} else {
					$return = preg_replace( '|^<!\[CDATA\[(.*)\]\]>$|s', '$1', $return[1] );
				}
			} else {
				$return = $return[1];
			}
		} else {
			$return = '';
		}
		return $return;
	}

	public function process_category( $c ) {
		return array(
			'term_id'              => $this->get_tag( $c, 'wp:term_id' ),
			'cat_name'             => $this->get_tag( $c, 'wp:cat_name' ),
			'category_nicename'    => $this->get_tag( $c, 'wp:category_nicename' ),
			'category_parent'      => $this->get_tag( $c, 'wp:category_parent' ),
			'category_description' => $this->get_tag( $c, 'wp:category_description' ),
		);
	}

	public function process_tag( $t ) {
		return array(
			'term_id'         => $this->get_tag( $t, 'wp:term_id' ),
			'tag_name'        => $this->get_tag( $t, 'wp:tag_name' ),
			'tag_slug'        => $this->get_tag( $t, 'wp:tag_slug' ),
			'tag_description' => $this->get_tag( $t, 'wp:tag_description' ),
		);
	}

	public function process_term( $t ) {
		return array(
			'term_id'          => $this->get_tag( $t, 'wp:term_id' ),
			'term_taxonomy'    => $this->get_tag( $t, 'wp:term_taxonomy' ),
			'slug'             => $this->get_tag( $t, 'wp:term_slug' ),
			'term_parent'      => $this->get_tag( $t, 'wp:term_parent' ),
			'term_name'        => $this->get_tag( $t, 'wp:term_name' ),
			'term_description' => $this->get_tag( $t, 'wp:term_description' ),
		);
	}

	public function process_author( $a ) {
		return array(
			'author_id'           => $this->get_tag( $a, 'wp:author_id' ),
			'author_login'        => $this->get_tag( $a, 'wp:author_login' ),
			'author_email'        => $this->get_tag( $a, 'wp:author_email' ),
			'author_display_name' => $this->get_tag( $a, 'wp:author_display_name' ),
			'author_first_name'   => $this->get_tag( $a, 'wp:author_first_name' ),
			'author_last_name'    => $this->get_tag( $a, 'wp:author_last_name' ),
		);
	}

	public function process_post( $post ) {
		$post_id        = $this->get_tag( $post, 'wp:post_id' );
		$post_title     = $this->get_tag( $post, 'title' );
		$post_date      = $this->get_tag( $post, 'wp:post_date' );
		$post_date_gmt  = $this->get_tag( $post, 'wp:post_date_gmt' );
		$comment_status = $this->get_tag( $post, 'wp:comment_status' );
		$ping_status    = $this->get_tag( $post, 'wp:ping_status' );
		$status         = $this->get_tag( $post, 'wp:status' );
		$post_name      = $this->get_tag( $post, 'wp:post_name' );
		$post_parent    = $this->get_tag( $post, 'wp:post_parent' );
		$menu_order     = $this->get_tag( $post, 'wp:menu_order' );
		$post_type      = $this->get_tag( $post, 'wp:post_type' );
		$post_password  = $this->get_tag( $post, 'wp:post_password' );
		$is_sticky      = $this->get_tag( $post, 'wp:is_sticky' );
		$guid           = $this->get_tag( $post, 'guid' );
		$post_author    = $this->get_tag( $post, 'dc:creator' );

		$post_excerpt = $this->get_tag( $post, 'excerpt:encoded' );
		$post_excerpt = preg_replace_callback( '|<(/?[A-Z]+)|', array( &$this, '_normalize_tag' ), $post_excerpt );
		$post_excerpt = str_replace( '<br>', '<br />', $post_excerpt );
		$post_excerpt = str_replace( '<hr>', '<hr />', $post_excerpt );

		$post_content = $this->get_tag( $post, 'content:encoded' );
		$post_content = preg_replace_callback( '|<(/?[A-Z]+)|', array( &$this, '_normalize_tag' ), $post_content );
		$post_content = str_replace( '<br>', '<br />', $post_content );
		$post_content = str_replace( '<hr>', '<hr />', $post_content );

		$postdata = compact(
			'post_id',
			'post_author',
			'post_date',
			'post_date_gmt',
			'post_content',
			'post_excerpt',
			'post_title',
			'status',
			'post_name',
			'comment_status',
			'ping_status',
			'guid',
			'post_parent',
			'menu_order',
			'post_type',
			'post_password',
			'is_sticky'
		);

		$attachment_url = $this->get_tag( $post, 'wp:attachment_url' );
		if ( $attachment_url ) {
			$postdata['attachment_url'] = $attachment_url;
		}

		preg_match_all( '|<category domain="([^"]+?)" nicename="([^"]+?)">(.+?)</category>|is', $post, $terms, PREG_SET_ORDER );
		foreach ( $terms as $t ) {
			$post_terms[] = array(
				'slug'   => $t[2],
				'domain' => $t[1],
				'name'   => str_replace( array( '<![CDATA[', ']]>' ), '', $t[3] ),
			);
		}
		if ( ! empty( $post_terms ) ) {
			$postdata['terms'] = $post_terms;
		}

		preg_match_all( '|<wp:comment>(.+?)</wp:comment>|is', $post, $comments );
		$comments = $comments[1];
		if ( $comments ) {
			foreach ( $comments as $comment ) {
				preg_match_all( '|<wp:commentmeta>(.+?)</wp:commentmeta>|is', $comment, $commentmeta );
				$commentmeta = $commentmeta[1];
				$c_meta      = array();
				foreach ( $commentmeta as $m ) {
					$c_meta[] = array(
						'key'   => $this->get_tag( $m, 'wp:meta_key' ),
						'value' => $this->get_tag( $m, 'wp:meta_value' ),
					);
				}

				$post_comments[] = array(
					'comment_id'           => $this->get_tag( $comment, 'wp:comment_id' ),
					'comment_author'       => $this->get_tag( $comment, 'wp:comment_author' ),
					'comment_author_email' => $this->get_tag( $comment, 'wp:comment_author_email' ),
					'comment_author_IP'    => $this->get_tag( $comment, 'wp:comment_author_IP' ),
					'comment_author_url'   => $this->get_tag( $comment, 'wp:comment_author_url' ),
					'comment_date'         => $this->get_tag( $comment, 'wp:comment_date' ),
					'comment_date_gmt'     => $this->get_tag( $comment, 'wp:comment_date_gmt' ),
					'comment_content'      => $this->get_tag( $comment, 'wp:comment_content' ),
					'comment_approved'     => $this->get_tag( $comment, 'wp:comment_approved' ),
					'comment_type'         => $this->get_tag( $comment, 'wp:comment_type' ),
					'comment_parent'       => $this->get_tag( $comment, 'wp:comment_parent' ),
					'comment_user_id'      => $this->get_tag( $comment, 'wp:comment_user_id' ),
					'commentmeta'          => $c_meta,
				);
			}
		}
		if ( ! empty( $post_comments ) ) {
			$postdata['comments'] = $post_comments;
		}

		preg_match_all( '|<wp:postmeta>(.+?)</wp:postmeta>|is', $post, $postmeta );
		$postmeta = $postmeta[1];
		if ( $postmeta ) {
			foreach ( $postmeta as $p ) {
				$post_postmeta[] = array(
					'key'   => $this->get_tag( $p, 'wp:meta_key' ),
					'value' => $this->get_tag( $p, 'wp:meta_value' ),
				);
			}
		}
		if ( ! empty( $post_postmeta ) ) {
			$postdata['postmeta'] = $post_postmeta;
		}

		return $postdata;
	}

	public function _normalize_tag( $matches ) {
		return '<' . strtolower( $matches[1] );
	}

	public function fopen( $filename, $mode = 'r' ) {
		if ( $this->has_gzip ) {
			return gzopen( $filename, $mode );
		}
		return fopen( $filename, $mode );
	}

	public function feof( $fp ) {
		if ( $this->has_gzip ) {
			return gzeof( $fp );
		}
		return feof( $fp );
	}

	public function fgets( $fp, $len = 8192 ) {
		if ( $this->has_gzip ) {
			return gzgets( $fp, $len );
		}
		return fgets( $fp, $len );
	}

	public function fclose( $fp ) {
		if ( $this->has_gzip ) {
			return gzclose( $fp );
		}
		return fclose( $fp );
	}
}

/**
 * WordPress eXtended RSS file parser implementations
 *
 * @package WordPress
 * @subpackage Importer
 */
use WordPress\ByteStream\ReadStream\FileReadStream;
/**
 * WXR Parser that uses the XMLProcessor component.
 */
class Hester_Core_WXR_Parser_XML_Processor {
        public $authors       = array();
        public $posts         = array();
        public $categories    = array();
        public $tags          = array();
        public $terms         = array();
        public $base_url      = '';
        public $base_blog_url = '';
        /**
         * Parse a WXR file
         *
         * @param string $file Path to WXR file
         * @return array|WP_Error Parsed data or error object
         */
        public function parse( $file ) {
                // Trigger a warning for non-existent files to match legacy behavior and tests.
                if ( ! is_readable( $file ) ) {
                        // Intentionally trigger a PHP warning; return value is ignored.
                        file_get_contents( $file );
                }
                // Initialize variables
                $this->authors       = array();
                $this->posts         = array();
                $this->categories    = array();
                $this->tags          = array();
                $this->terms         = array();
                $this->base_url      = '';
                $this->base_blog_url = '';
                $wxr_version         = '';
                try {
                        $reader = $this->create_wxr_entity_reader( $file );
                        // Parse the XML document
                        $last_term = null;
                        while ( $reader->next_entity() ) {
                                $entity       = $reader->get_entity();
                                $trimmed_data = array();
                                foreach ( $entity->get_data() as $k => $v ) {
                                        if ( ! is_string( $v ) ) {
                                                $trimmed_data[ $k ] = $v;
                                                continue;
                                        }
                                        $trimmed_data[ $k ] = $v;
                                }
                                switch ( $entity->get_type() ) {
                                        case 'wxr_version':
                                                $wxr_version = $trimmed_data['wxr_version'];
                                                break;
                                        case 'site_option':
                                                if ( isset( $trimmed_data['option_name'], $trimmed_data['option_value'] ) ) {
                                                        switch ( $trimmed_data['option_name'] ) {
                                                                case 'wxr_version':
                                                                        $wxr_version = $trimmed_data['option_value'];
                                                                        break;
                                                                case 'siteurl':
                                                                        $this->base_url = $trimmed_data['option_value'];
                                                                        break;
                                                                case 'home':
                                                                        $this->base_blog_url = $trimmed_data['option_value'];
                                                                        break;
                                                        }
                                                }
                                                break;
                                        case 'user':
                                                $key                   = isset( $trimmed_data['author_login'] ) ? $trimmed_data['author_login'] : (
                                                        isset( $trimmed_data['author_email'] ) ? $trimmed_data['author_email'] : (
                                                                isset( $trimmed_data['author_id'] ) ? $trimmed_data['author_id'] : count( $this->authors )
                                                        )
                                                );
                                                $this->authors[ $key ] = $trimmed_data;
                                                break;
                                        case 'post':
                                                $this->posts[] = $trimmed_data;
                                                break;
                                        case 'post_meta':
                                                $last_post_key = count( $this->posts ) - 1;
                                                if ( ! isset( $this->posts[ $last_post_key ]['postmeta'] ) ) {
                                                        $this->posts[ $last_post_key ]['postmeta'] = array();
                                                }
                                                // Ensure only expected keys 'key' and 'value' are present to match tests
                                                if ( isset( $trimmed_data['post_id'] ) ) {
                                                        unset( $trimmed_data['post_id'] );
                                                }
                                                $this->posts[ $last_post_key ]['postmeta'][] = $trimmed_data;
                                                break;
                                        case 'comment':
                                                $last_post_key = count( $this->posts ) - 1;
                                                if ( ! isset( $this->posts[ $last_post_key ]['comments'] ) ) {
                                                        $this->posts[ $last_post_key ]['comments'] = array();
                                                }
                                                $trimmed_data['commentmeta']                 = array();
                                                $this->posts[ $last_post_key ]['comments'][] = $trimmed_data;
                                                break;
                                        case 'comment_meta':
                                                $last_post_key      = count( $this->posts ) - 1;
                                                $last_comment_index = count( $this->posts[ $last_post_key ]['comments'] ) - 1;
                                                if ( $last_comment_index >= 0 ) {
                                                        // Do not include comment_id in the final commentmeta array to match expected shape.
                                                        if ( isset( $trimmed_data['comment_id'] ) ) {
                                                                unset( $trimmed_data['comment_id'] );
                                                        }
                                                        $this->posts[ $last_post_key ]['comments'][ $last_comment_index ]['commentmeta'][] = $trimmed_data;
                                                }
                                                break;
                                        case 'category':
                                                if ( isset( $trimmed_data['term_id'] ) ) {
                                                        $trimmed_data['term_id'] = (int) $trimmed_data['term_id'];
                                                }
                                                unset( $trimmed_data['taxonomy'], $trimmed_data['term_description'] );
                                                $this->categories[] = $trimmed_data;
                                                $last_term_index    = count( $this->categories ) - 1;
                                                $last_term          = &$this->categories[ $last_term_index ];
                                                break;
                                        case 'tag':
                                                if ( isset( $trimmed_data['term_id'] ) ) {
                                                        $trimmed_data['term_id'] = (int) $trimmed_data['term_id'];
                                                }
                                                unset( $trimmed_data['taxonomy'], $trimmed_data['term_description'] );
                                                $this->tags[]    = $trimmed_data;
                                                $last_term_index = count( $this->tags ) - 1;
                                                $last_term       = &$this->tags[ $last_term_index ];
                                                break;
                                        case 'term':
                                                if ( isset( $trimmed_data['term_id'] ) ) {
                                                        $trimmed_data['term_id'] = (int) $trimmed_data['term_id'];
                                                }
                                                // unset($trimmed_data['taxonomy'], $trimmed_data['term_description']);
                                                // $trimmed_data['taxonomy'] id 'domain'
                                                // $trimmed_data['slug'] id 'nicename'
                                                $this->terms[]   = $trimmed_data;
                                                $last_term_index = count( $this->terms ) - 1;
                                                $last_term       = &$this->terms[ $last_term_index ];
                                                break;
                                        case 'termmeta':
                                        case 'term_meta':
                                                if ( ! isset( $last_term['termmeta'] ) ) {
                                                        $last_term['termmeta'] = array();
                                                }
                                                $last_term['termmeta'][] = $trimmed_data;
                                                break;
                                        case 'wxr_version':
                                                // Support entity-style wxr_version array or raw string
                                                if ( isset( $trimmed_data['wxr_version'] ) ) {
                                                        $wxr_version = $trimmed_data['wxr_version'];
                                                } else {
                                                        $wxr_version = $trimmed_data;
                                                }
                                                break;
                                        default:
                                                // Ignore unknown entity types silently to avoid emitting notices.
                                                break;
                                }
                        }
                } catch ( Exception $e ) {
                        return new WP_Error( 'WXR_parse_error', $e->getMessage() );
                }
                // Normalize per-post terms to legacy shape { domain, slug, name } when needed.
                foreach ( $this->posts as $idx => $post ) {
                        if ( isset( $post['terms'] ) && is_array( $post['terms'] ) ) {
                                foreach ( $post['terms'] as $tidx => $term ) {
                                        if ( ! isset( $term['domain'] ) && isset( $term['taxonomy'] ) ) {
                                                $mapped                                = array(
                                                        'domain' => $term['taxonomy'],
                                                        'slug'   => isset( $term['slug'] ) ? $term['slug'] : '',
                                                        'name'   => isset( $term['description'] ) ? $term['description'] : '',
                                                );
                                                $this->posts[ $idx ]['terms'][ $tidx ] = $mapped;
                                        }
                                }
                        }
                }
                // Validate WXR version
                if ( empty( $wxr_version ) || ! preg_match( '/^\d+\.\d+$/', $wxr_version ) ) {
                        return new WP_Error( 'WXR_parse_error', __( 'This does not appear to be a WXR file, missing/invalid WXR version number', 'hester-core' ) );
                }
                return array(
                        'authors'       => $this->authors,
                        'posts'         => $this->posts,
                        'categories'    => $this->categories,
                        'tags'          => $this->tags,
                        'terms'         => $this->terms,
                        'base_url'      => $this->base_url,
                        'base_blog_url' => $this->base_blog_url,
                        'version'       => $wxr_version,
                );
        }
        private function create_wxr_entity_reader( $file ) {
                // Every XML element is a combination of a long-form namespace and a
                // local element name, e.g. a syntax <wp:post_id> could actually refer
                // to a (https://wordpress.org/export/1.0/, post_id) element.
                //
                // Namespaces are paramount for parsing XML and cannot be ignored. Elements
                // element must be matched based on both their namespace and local name.
                //
                // Unfortunately, different WXR files defined the `wp` namespace in a different way.
                // Folks use a mixture of HTTP vs HTTPS protocols and version numbers. We must
                // account for all possible options to parse these documents correctly.
                $wxr_namespaces = array(
                        'http://wordpress.org/export/1.0/',
                        'https://wordpress.org/export/1.0/',
                        'http://wordpress.org/export/1.1/',
                        'https://wordpress.org/export/1.1/',
                        'http://wordpress.org/export/1.2/',
                        'https://wordpress.org/export/1.2/',
                );
                $known_entities = array(
                        'item' => array(
                                'type'   => 'post',
                                'fields' => array(
                                        'title'       => 'post_title',
                                        'guid'        => 'guid',
                                        'description' => 'post_excerpt',
                                        '{http://purl.org/dc/elements/1.1/}creator' => 'post_author',
                                        '{http://purl.org/rss/1.0/modules/content/}encoded' => 'post_content',
                                        '{http://wordpress.org/export/1.0/excerpt/}encoded' => 'post_excerpt',
                                        '{http://wordpress.org/export/1.1/excerpt/}encoded' => 'post_excerpt',
                                        '{http://wordpress.org/export/1.2/excerpt/}encoded' => 'post_excerpt',
                                ),
                        ),
                );
                $known_site_options = array();
                foreach ( $wxr_namespaces as $wxr_namespace ) {
                        $known_site_options               = array_merge(
                                $known_site_options,
                                array(
                                        '{' . $wxr_namespace . '}base_blog_url' => 'home',
                                        '{' . $wxr_namespace . '}base_site_url' => 'siteurl',
                                        '{' . $wxr_namespace . '}wxr_version' => 'wxr_version',
                                        'title'                               => 'blogname',
                                )
                        );
                        $known_entities['item']['fields'] = array_merge(
                                $known_entities['item']['fields'],
                                array(
                                        '{' . $wxr_namespace . '}post_id'     => 'post_id',
                                        '{' . $wxr_namespace . '}status'      => 'status',
                                        '{' . $wxr_namespace . '}post_date'   => 'post_date',
                                        '{' . $wxr_namespace . '}post_date_gmt' => 'post_date_gmt',
                                        '{' . $wxr_namespace . '}post_modified' => 'post_modified',
                                        '{' . $wxr_namespace . '}post_modified_gmt' => 'post_modified_gmt',
                                        '{' . $wxr_namespace . '}comment_status' => 'comment_status',
                                        '{' . $wxr_namespace . '}ping_status' => 'ping_status',
                                        '{' . $wxr_namespace . '}post_name'   => 'post_name',
                                        '{' . $wxr_namespace . '}post_parent' => 'post_parent',
                                        '{' . $wxr_namespace . '}menu_order'  => 'menu_order',
                                        '{' . $wxr_namespace . '}post_type'   => 'post_type',
                                        '{' . $wxr_namespace . '}post_password' => 'post_password',
                                        '{' . $wxr_namespace . '}is_sticky'   => 'is_sticky',
                                        '{' . $wxr_namespace . '}attachment_url' => 'attachment_url',
                                )
                        );
                        $known_entities                   = array_merge(
                                $known_entities,
                                array(
                                        '{' . $wxr_namespace . '}comment'     => array(
                                                'type'   => 'comment',
                                                'fields' => array(
                                                        '{' . $wxr_namespace . '}comment_id'   => 'comment_id',
                                                        '{' . $wxr_namespace . '}comment_author' => 'comment_author',
                                                        '{' . $wxr_namespace . '}comment_author_email' => 'comment_author_email',
                                                        '{' . $wxr_namespace . '}comment_author_url' => 'comment_author_url',
                                                        '{' . $wxr_namespace . '}comment_author_IP' => 'comment_author_IP',
                                                        '{' . $wxr_namespace . '}comment_date' => 'comment_date',
                                                        '{' . $wxr_namespace . '}comment_date_gmt' => 'comment_date_gmt',
                                                        '{' . $wxr_namespace . '}comment_content' => 'comment_content',
                                                        '{' . $wxr_namespace . '}comment_approved' => 'comment_approved',
                                                        '{' . $wxr_namespace . '}comment_type' => 'comment_type',
                                                        '{' . $wxr_namespace . '}comment_parent' => 'comment_parent',
                                                        '{' . $wxr_namespace . '}comment_user_id' => 'comment_user_id',
                                                ),
                                        ),
                                        '{' . $wxr_namespace . '}commentmeta' => array(
                                                'type'   => 'comment_meta',
                                                'fields' => array(
                                                        '{' . $wxr_namespace . '}meta_key' => 'key',
                                                        '{' . $wxr_namespace . '}meta_value' => 'value',
                                                ),
                                        ),
                                        '{' . $wxr_namespace . '}author'      => array(
                                                'type'   => 'user',
                                                'fields' => array(
                                                        '{' . $wxr_namespace . '}author_id'    => 'author_id',
                                                        '{' . $wxr_namespace . '}author_login' => 'author_login',
                                                        '{' . $wxr_namespace . '}author_email' => 'author_email',
                                                        '{' . $wxr_namespace . '}author_display_name' => 'author_display_name',
                                                        '{' . $wxr_namespace . '}author_first_name' => 'author_first_name',
                                                        '{' . $wxr_namespace . '}author_last_name' => 'author_last_name',
                                                ),
                                        ),
                                        '{' . $wxr_namespace . '}postmeta'    => array(
                                                'type'   => 'post_meta',
                                                'fields' => array(
                                                        '{' . $wxr_namespace . '}meta_key' => 'key',
                                                        '{' . $wxr_namespace . '}meta_value' => 'value',
                                                ),
                                        ),
                                        '{' . $wxr_namespace . '}term'        => array(
                                                'type'   => 'term',
                                                'fields' => array(
                                                        '{' . $wxr_namespace . '}term_id' => 'term_id',
                                                        '{' . $wxr_namespace . '}term_taxonomy' => 'term_taxonomy',
                                                        '{' . $wxr_namespace . '}term_slug' => 'slug',
                                                        '{' . $wxr_namespace . '}term_parent' => 'term_parent',
                                                        '{' . $wxr_namespace . '}term_name' => 'term_name',
                                                        '{' . $wxr_namespace . '}term_description' => 'term_description',
                                                ),
                                        ),
                                        '{' . $wxr_namespace . '}termmeta'    => array(
                                                'type'   => 'term_meta',
                                                'fields' => array(
                                                        '{' . $wxr_namespace . '}meta_key' => 'key',
                                                        '{' . $wxr_namespace . '}meta_value' => 'value',
                                                ),
                                        ),
                                        '{' . $wxr_namespace . '}tag'         => array(
                                                'type'   => 'tag',
                                                'fields' => array(
                                                        '{' . $wxr_namespace . '}term_id'  => 'term_id',
                                                        '{' . $wxr_namespace . '}tag_slug' => 'tag_slug',
                                                        '{' . $wxr_namespace . '}tag_name' => 'tag_name',
                                                        '{' . $wxr_namespace . '}tag_description' => 'tag_description',
                                                ),
                                        ),
                                        '{' . $wxr_namespace . '}category'    => array(
                                                'type'   => 'category',
                                                'fields' => array(
                                                        '{' . $wxr_namespace . '}term_id'  => 'term_id',
                                                        '{' . $wxr_namespace . '}category_nicename' => 'category_nicename',
                                                        '{' . $wxr_namespace . '}category_parent' => 'category_parent',
                                                        '{' . $wxr_namespace . '}cat_name' => 'cat_name',
                                                        '{' . $wxr_namespace . '}category_description' => 'category_description',
                                                ),
                                        ),
                                )
                        );
                }
                return WordPress\DataLiberation\EntityReader\WXREntityReader::create(
                        FileReadStream::from_path( $file ),
                        null,
                        array(
                                'known_site_options'        => $known_site_options,
                                'known_entities'            => $known_entities,
                                'use_legacy_post_term_keys' => true,
                                'remap_wp_author'           => false,
                        )
                );
        }
}
