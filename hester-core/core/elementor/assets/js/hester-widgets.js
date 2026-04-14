(function () {
    'use strict';

    const hesterDebugEnabled = !!window.hesterDebug;
    const hesterWarn = (...args) => {
        if (hesterDebugEnabled && window.console && typeof window.console.warn === 'function') {
            window.console.warn(...args);
        }
    };
    const hesterError = (...args) => {
        if (hesterDebugEnabled && window.console && typeof window.console.error === 'function') {
            window.console.error(...args);
        }
    };

    // Animated Heading
    window.HesterAnimatedHeading = function ($scope) {
        var $animatedHeading = $scope.find('.hester-animated-heading');

        if ($scope.is && $scope.is('.hester-animated-heading')) {
            $animatedHeading = $animatedHeading.add($scope);
        }

        $animatedHeading.each(function () {
            var $current = window.jQuery(this);
            var layout = $current.data('layout');
            var morphextInstance = $current.data('plugin_Morphext');

            if (morphextInstance && typeof morphextInstance.stop === 'function') {
                morphextInstance.stop();
                $current.removeData('plugin_Morphext');
            }

            if ($current.data('typed') && typeof $current.data('typed').destroy === 'function') {
                $current.data('typed').destroy();
                $current.removeData('typed');
            }

            if ('typed' === layout) {
                if (typeof window.Typed !== 'function') {
                    hesterWarn('Typed library not available for animated heading');
                    return;
                }

                $current.empty();
                $current.data('typed', new window.Typed(this, {
                    strings: $current.data('words'),
                    typeSpeed: parseInt($current.data('speed'), 10),
                    startDelay: parseInt($current.data('startdelay'), 10),
                    backSpeed: parseInt($current.data('backspeed'), 10),
                    backDelay: parseInt($current.data('backdelay'), 10),
                    loop: $current.data('loop') === true,
                    loopCount: Infinity,
                    showCursor: true,
                    cursorChar: '|'
                }));
            } else if ('morphext' === layout) {
                if (typeof window.jQuery.fn.Morphext === 'function') {
                    var words = $current.data('words');
                    if (typeof words === 'string') {
                        try {
                            words = JSON.parse(words);
                        } catch (err) {
                            words = words.split(',');
                        }
                    }

                    if (Array.isArray(words)) {
                        $current.text(words.join(', '));
                    }

                    $current.Morphext({
                        animation: $current.data('animation'),
                        separator: ',',
                        speed: parseInt($current.data('speed'), 10),
                        complete: function () { }
                    });
                } else {
                    hesterWarn('Morphext plugin not available for animated heading');
                }
            }
        });
    };

    // Tabs
    window.HesterTabs = {
        init: function () {
            const tabs = document.querySelectorAll('.hester-tabs');
            tabs.forEach(tab => this.initTabs(tab));
        },
        initTabs: function (tabContainer) {
            if (tabContainer.dataset.hesterTabsInited === '1') {
                return;
            }

            tabContainer.dataset.hesterTabsInited = '1';

            const activateTab = (tabNum) => {
                if (!tabNum) {
                    return;
                }

                const allTitles = tabContainer.querySelectorAll('.hester-tab-title');
                const allContents = tabContainer.querySelectorAll('.hester-tab-content');

                allTitles.forEach((title) => {
                    title.classList.remove('hester-active');
                });

                allContents.forEach((content) => {
                    content.classList.remove('hester-active');
                });

                tabContainer.querySelectorAll(`.hester-tab-title[data-tab="${tabNum}"]`).forEach((title) => {
                    title.classList.add('hester-active');
                });

                const targetContent = tabContainer.querySelector(`#hester-tab-content-${tabNum}`)
                    || tabContainer.querySelector(`.hester-tab-content[aria-labelledby$="${tabNum}"]`);

                if (targetContent) {
                    targetContent.classList.add('hester-active');
                }
            };

            tabContainer.addEventListener('click', (e) => {
                const tabTitle = e.target.closest('.hester-tab-title[data-tab]');
                if (!tabTitle || !tabContainer.contains(tabTitle)) {
                    return;
                }

                e.preventDefault();
                activateTab(tabTitle.dataset.tab);
            });

            // Fallback: ensure one tab is active.
            if (!tabContainer.querySelector('.hester-tab-title.hester-active')) {
                const first = tabContainer.querySelector('.hester-tab-title[data-tab]');
                if (first) {
                    activateTab(first.dataset.tab);
                }
            }
        }
    };

    // Off Canvas
    window.HesterOffCanvas = {
        init: function (scope) {
            const root = scope && scope.querySelectorAll ? scope : document;
            const offCanvas = root.querySelectorAll('[id^="hester-off-canvas-"]');
            offCanvas.forEach(el => this.initElement(el, root));
        },
        initElement: function (el, root) {
            if (el.dataset.hesterOffCanvasInit === '1') {
                return;
            }

            const buttonSelector = 'a[href="#' + el.id + '"]';
            const scopeRoot = root && root.querySelector ? root : document;
            const button = scopeRoot.querySelector(buttonSelector) || document.querySelector(buttonSelector);

            if (!button) {
                hesterWarn('Off-canvas button not found for', el.id);
                return;
            }

            const closePanel = () => {
                el.classList.remove('show');
            };

            // Toggle open/close
            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                el.classList.add('show');
            });

            // Close button handler
            const closeButton = el.querySelector('.hester-off-canvas-close');
            if (closeButton) {
                closeButton.addEventListener('click', () => {
                    closePanel();
                });
            }

            // Overlay handler
            const overlay = el.querySelector('.hester-off-canvas-overlay');
            if (overlay) {
                overlay.addEventListener('click', () => {
                    closePanel();
                });
            }

            // Close when clicking on a link inside the sidebar
            const sidebar = el.querySelector('.hester-off-canvas-sidebar');
            if (sidebar) {
                sidebar.addEventListener('click', (event) => {
                    // Only close if clicking on a link, not the sidebar itself
                    if (event.target.closest('a')) {
                        closePanel();
                    }
                });
            }

            // Close when clicking outside sidebar using event delegation (single listener per panel)
            const handleBackdropClick = (event) => {
                if (!el.classList.contains('show')) {
                    return;
                }

                const clickedInsideSidebar = !!(sidebar && sidebar.contains(event.target));
                const clickedToggle = !!(button && button.contains(event.target));

                if (!clickedInsideSidebar && !clickedToggle) {
                    closePanel();
                }
            };

            // Use capture phase for backdrop click to be more performant
            document.addEventListener('click', handleBackdropClick, true);

            // Keyboard handler
            const handleKeydown = (event) => {
                if (event.key === 'Escape' && el.classList.contains('show')) {
                    closePanel();
                }
            };

            document.addEventListener('keydown', handleKeydown);

            // Store references for cleanup if needed
            el.dataset.hesterOffCanvasInit = '1';
            el.dataset.backdropHandler = 'true';
        }
    };

    // Search
    window.HesterSearch = {
        init: function (scope) {
            const root = scope && scope.querySelectorAll ? scope : document;
            const searches = root.querySelectorAll('.hester-search-wrap');
            searches.forEach(search => this.initSearch(search));
        },
        initSearch: function (wrapper) {
            if (wrapper.dataset.hesterSearchInit === '1') {
                return;
            }

            const input = wrapper.querySelector('.hester-search-input, .hester-searchform input.field, input[name="s"]');
            const results = wrapper.querySelector('.hester-search-results');
            const loader = wrapper.querySelector('.hester-ajax-loading');
            const sourceField = wrapper.querySelector('input.post-type[name="post_type"]');
            const limit = parseInt(wrapper.dataset.resultsLimit || '5', 10) || 5;

            if (!input || !results) return;

            wrapper.dataset.hesterSearchInit = '1';

            let timeout;

            input.addEventListener('input', e => {
                clearTimeout(timeout);
                const query = e.target.value.trim();
                const minChars = parseInt(input.dataset.minChars) || 2;

                if (query.length < minChars) {
                    results.style.display = 'none';
                    return;
                }

                timeout = setTimeout(() => {
                    let source = (input.dataset.source || (sourceField ? sourceField.value : '') || 'all').toLowerCase();
                    if (source === 'any') {
                        source = 'all';
                    }
                    this.search(query, source, results, loader, wrapper.dataset.ajaxurl || '', limit, wrapper.dataset.nonce || '');
                }, 300);
            });

            // Close on document click
            document.addEventListener('click', e => {
                if (!wrapper.contains(e.target)) {
                    results.style.display = 'none';
                }
            });
        },
        search: function (query, source, resultsEl, loaderEl, ajaxUrl, limit, nonce) {
            const data = new FormData();
            data.append('action', 'hester_search');
            data.append('q', query);
            data.append('source', source);
            data.append('limit', String(limit || 5));
            const requestNonce = (window.hesterWidgetsData && window.hesterWidgetsData.nonce) || nonce || '';
            if (requestNonce) {
                data.append('_wpnonce', requestNonce);
            }

            if (loaderEl) {
                loaderEl.style.display = 'block';
            }

            const endpoint = ajaxUrl || (window.hesterWidgetsData && window.hesterWidgetsData.ajaxUrl) || window.ajaxurl || '/wp-admin/admin-ajax.php';

            fetch(endpoint, {
                method: 'POST',
                body: data
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.data.items) {
                        this.displayResults(data.data.items, data.data, resultsEl);
                        resultsEl.style.display = 'block';
                    } else {
                        resultsEl.innerHTML = '<div class="hester-no-search-results"><p>' + this.escapeHtml(data.data?.message || 'No results found') + '</p></div>';
                        resultsEl.style.display = 'block';
                    }
                })
                .catch(err => {
                    hesterError('Search error:', err);
                    resultsEl.style.display = 'none';
                })
                .finally(() => {
                    if (loaderEl) {
                        loaderEl.style.display = 'none';
                    }
                });
        },
        displayResults: function (items, meta, el) {
            if (!items || items.length === 0) {
                el.innerHTML = '<div class="hester-no-search-results"><p>No results found</p></div>';
                return;
            }

            const list = items.map(item => {
                const url = this.escapeHtml(item.url || '');
                const title = this.escapeHtml(this.highlight(item.title || ''));
                const thumb = item.thumb ? this.escapeHtml(item.thumb) : '';
                const thumbAlt = this.escapeHtml(item.title || 'Product image');
                const price = item.price ? this.sanitizePriceHtml(item.price) : '';

                let thumbHtml = '';
                if (thumb) {
                    thumbHtml = `<span class="result-thumb"><img src="${thumb}" alt="${thumbAlt}" loading="lazy"></span>`;
                }

                let priceHtml = '';
                if (item.type === 'product' && price) {
                    priceHtml = `<span class="result-price">${price}</span>`;
                }

                return `
					<li>
						<a href="${url}" class="search-result-link hester-search-result-item">
							${thumbHtml}
							<span class="result-content">
								<span class="result-title">${title}</span>
								${priceHtml}
							</span>
							<i class="icon fas fa-angle-right" aria-hidden="true"></i>
						</a>
					</li>
				`;
            }).join('');

            const total = parseInt(meta?.total || items.length, 10) || items.length;
            const allResultsUrl = this.escapeHtml(meta?.all_results_url || '');
            const showAllLink = allResultsUrl && total > items.length;

            let footer = '';
            if (showAllLink) {
                footer = `<li><a href="${allResultsUrl}" class="all-results"><span>View all ${total} results <i class="fas fa-angle-right" aria-hidden="true"></i></span></a></li>`;
            }

            el.innerHTML = `<ul>${list}${footer}</ul>`;
        },
        highlight: function (text) {
            return text.length > 100 ? text.substring(0, 100) + '...' : text;
        },
        escapeHtml: function (text) {
            const value = String(text || '');
            return value
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        },
        sanitizePriceHtml: function (html) {
            const template = document.createElement('template');
            template.innerHTML = String(html || '');

            const allowedTags = new Set(['SPAN', 'DEL', 'INS', 'B', 'STRONG', 'SMALL']);
            const allowedClassPrefix = ['woocommerce-', 'screen-reader-text'];

            const walk = (node) => {
                if (!node || !node.childNodes) {
                    return;
                }

                Array.from(node.childNodes).forEach((child) => {
                    if (child.nodeType === Node.ELEMENT_NODE) {
                        if (!allowedTags.has(child.tagName)) {
                            child.replaceWith(document.createTextNode(child.textContent || ''));
                            return;
                        }

                        Array.from(child.attributes).forEach((attr) => {
                            if ('class' !== attr.name) {
                                child.removeAttribute(attr.name);
                                return;
                            }

                            const safeClasses = (attr.value || '')
                                .split(/\s+/)
                                .filter((className) => className && allowedClassPrefix.some((prefix) => className.indexOf(prefix) === 0));

                            if (safeClasses.length) {
                                child.setAttribute('class', safeClasses.join(' '));
                            } else {
                                child.removeAttribute('class');
                            }
                        });
                    }

                    walk(child);
                });
            };

            walk(template.content);
            return template.innerHTML;
        }
    };

    // Woo Carousel (Swiper)
    window.HesterWooCarousel = {
        init: function (scope) {
            const root = scope && scope.querySelectorAll ? scope : document;
            const carousels = root.querySelectorAll('.hester-woo-carousel');
            carousels.forEach(carousel => this.initCarousel(carousel));
        },
        initCarousel: function (carouselEl) {
            if (carouselEl.dataset.hesterSwiperInit === '1' && carouselEl.swiperInstance) {
                carouselEl.swiperInstance.update();
                return;
            }

            const settingsJson = carouselEl.dataset.settings;
            if (!settingsJson) {
                hesterWarn('Swiper settings not found');
                return;
            }

            if (typeof window.Swiper === 'undefined') {
                hesterWarn('Swiper library not loaded');
                // Retry after a brief delay
                setTimeout(() => this.initCarousel(carouselEl), 500);
                return;
            }

            try {
                const settings = JSON.parse(settingsJson);
                const spacing = Number.isFinite(parseInt(settings.spacing, 10))
                    ? parseInt(settings.spacing, 10)
                    : 20;

                // Find the swiper container within this carousel
                const swiperContainer = carouselEl.querySelector('.swiper');
                if (!swiperContainer) {
                    hesterWarn('Swiper container not found');
                    return;
                }

                // Theme-agnostic fallback: ensure wrapper/slides have required classes.
                const productsWrapper = swiperContainer.querySelector('.products');
                if (productsWrapper && !productsWrapper.classList.contains('swiper-wrapper')) {
                    productsWrapper.classList.add('swiper-wrapper');
                }
                const slides = swiperContainer.querySelectorAll('.products > li.product, .products > li, .products > .product');
                slides.forEach((slide) => {
                    if (!slide.classList.contains('swiper-slide')) {
                        slide.classList.add('swiper-slide');
                    }
                });

                // Build Swiper configuration
                const swiperConfig = {
                    // Core settings
                    slidesPerView: settings.slidesToShow || 4,
                    slidesPerGroup: settings.slidesToScroll || 4,
                    spaceBetween: spacing,
                    loop: settings.infinite !== false,
                    speed: settings.speed || 500,
                    effect: settings.effect || 'slide',
                    autoHeight: !!settings.autoHeight,
                    grabCursor: true,
                    watchSlidesProgress: true,
                    allowTouchMove: settings.touchSwipe !== false,
                    observer: true,
                    observeParents: true,
                };

                // Autoplay configuration
                if (settings.autoplay) {
                    swiperConfig.autoplay = {
                        delay: settings.autoplaySpeed || 5000,
                        disableOnInteraction: !settings.pauseOnHover,
                    };
                }

                // Navigation arrows
                if (settings.arrows) {
                    swiperConfig.navigation = {
                        nextEl: carouselEl.querySelector('.swiper-button-next'),
                        prevEl: carouselEl.querySelector('.swiper-button-prev'),
                    };
                }

                // Pagination dots
                if (settings.dots) {
                    swiperConfig.pagination = {
                        el: carouselEl.querySelector('.swiper-pagination'),
                        type: 'bullets',
                        clickable: true,
                        // Keep bullets stable and centered; dynamic bullets inject
                        // inline width/left styles that can look off in some themes.
                        dynamicBullets: false,
                    };
                }

                // Mouse wheel control
                if (settings.mouseWheel) {
                    swiperConfig.mousewheel = {
                        invert: false,
                        forceToAxis: false,
                    };
                }

                // Free mode configuration
                if (settings.freeMode) {
                    swiperConfig.freeMode = true;
                    swiperConfig.freeModeMomentum = true;
                    swiperConfig.freeModeMomentumRatio = 0.5;
                }

                // Responsive breakpoints
                swiperConfig.breakpoints = this.getBreakpoints(settings, spacing);

                // Initialize Swiper
                const swiper = new window.Swiper(swiperContainer, swiperConfig);

                // Store swiper instance on element for potential API access
                carouselEl.swiperInstance = swiper;
                carouselEl.dataset.hesterSwiperInit = '1';

            } catch (error) {
                hesterError('Error initializing Swiper carousel:', error);
            }
        },
        getBreakpoints: function (settings, spacing) {
            const desktopShow = settings.slidesToShow || 4;
            const desktopScroll = settings.slidesToScroll || desktopShow;
            const tabletShow = settings.slidesToShowTablet || Math.min(desktopShow, 2);
            const tabletScroll = settings.slidesToScrollTablet || tabletShow;
            const mobileShow = settings.slidesToShowMobile || 1;
            const mobileScroll = settings.slidesToScrollMobile || mobileShow;
            const gap = Number.isFinite(spacing) ? spacing : 20;

            return {
                0: {
                    slidesPerView: mobileShow,
                    slidesPerGroup: mobileScroll,
                    spaceBetween: gap,
                },
                768: {
                    slidesPerView: tabletShow,
                    slidesPerGroup: tabletScroll,
                    spaceBetween: gap,
                },
                1025: {
                    slidesPerView: desktopShow,
                    slidesPerGroup: desktopScroll,
                    spaceBetween: gap,
                },
            };
        }
    };

    // Slides (Swiper)
    window.HesterSlides = {
        init: function (scope) {
            const root = scope && scope.querySelectorAll ? scope : document;
            const sliders = root.querySelectorAll('.hester-slides-wrapper.elementor-main-swiper.swiper');
            sliders.forEach((slider) => this.initSlider(slider));
        },

        normalizeBool: function (value) {
            return value === true || value === 'true' || value === 'yes' || value === '1' || value === 1;
        },

        getSettings: function (sliderEl, settingsOverride) {
            let settings = {};

            if (settingsOverride && typeof settingsOverride === 'object') {
                settings = settingsOverride;
            } else {
                const settingsJson = sliderEl.dataset.settings || '{}';

                try {
                    settings = JSON.parse(settingsJson);
                } catch (e) {
                    settings = {};
                }
            }

            const desktopSlides = Math.min(4, Math.max(1, parseInt(settings.slides_per_view, 10) || 1));
            const tabletSlides = Math.min(2, Math.max(1, parseInt(settings.slides_per_view_tablet, 10) || desktopSlides));
            const mobileSlides = 1;
            const desktopGap = Math.max(0, parseInt(settings.slides_gap, 10) || 0);
            const tabletGap = Math.max(0, parseInt(settings.slides_gap_tablet, 10) || desktopGap);
            const mobileGap = Math.max(0, parseInt(settings.slides_gap_mobile, 10) || tabletGap);

            return {
                navigation: settings.navigation || 'both',
                transition: settings.transition === 'fade' ? 'fade' : 'slide',
                autoplay: this.normalizeBool(settings.autoplay),
                pause_on_hover: this.normalizeBool(settings.pause_on_hover),
                pause_on_interaction: this.normalizeBool(settings.pause_on_interaction),
                autoplay_speed: Number.isFinite(parseInt(settings.autoplay_speed, 10)) ? parseInt(settings.autoplay_speed, 10) : 5000,
                infinite: this.normalizeBool(settings.infinite),
                transition_speed: Number.isFinite(parseInt(settings.transition_speed, 10)) ? parseInt(settings.transition_speed, 10) : 500,
                slides_per_view: desktopSlides,
                slides_per_view_tablet: tabletSlides,
                slides_per_view_mobile: mobileSlides,
                slides_gap: desktopGap,
                slides_gap_tablet: tabletGap,
                slides_gap_mobile: mobileGap,
            };
        },

        ensureSettingsObserver: function (sliderEl) {
            if (!sliderEl || sliderEl.hesterSlidesObserver || typeof MutationObserver === 'undefined') {
                return;
            }

            sliderEl.hesterSlidesObserver = new MutationObserver(() => {
                if (sliderEl.hesterSlidesObserverFrame) {
                    window.clearTimeout(sliderEl.hesterSlidesObserverFrame);
                }

                sliderEl.hesterSlidesObserverFrame = window.setTimeout(() => {
                    this.initSlider(sliderEl);
                }, 50);
            });

            sliderEl.hesterSlidesObserver.observe(sliderEl, {
                attributes: true,
                attributeFilter: ['data-settings', 'data-animation'],
            });
        },

        getAnimationTargetSlides: function (sliderEl, swiperInstance) {
            const targets = Array.from(
                sliderEl.querySelectorAll('.swiper-slide-visible, .swiper-slide-duplicate-visible, .swiper-slide-active, .swiper-slide-duplicate-active')
            ).filter((slide) => slide.querySelector('.swiper-slide-contents > *'));

            if (targets.length) {
                return Array.from(new Set(targets));
            }

            const activeSlide = sliderEl.querySelector('.swiper-slide-active')
                || (swiperInstance && swiperInstance.slides && swiperInstance.slides[swiperInstance.activeIndex])
                || null;

            return activeSlide ? [activeSlide] : [];
        },

        clearContentAnimation: function (sliderEl) {
            const contentItems = sliderEl.querySelectorAll('.swiper-slide-contents > *');

            contentItems.forEach((item) => {
                item.classList.remove('hester-slide-content-animated');
                item.style.animationName = '';
                item.style.animationDelay = '';
                item.style.opacity = '';
                item.style.visibility = '';
            });
        },

        applyContentAnimation: function (sliderEl, swiperInstance) {
            const animationRaw = sliderEl.dataset.animation || 'fadeInUp';
            const animationMap = {
                fadeinup: 'fadeInUp',
                fadeindown: 'fadeInDown',
                fadeinleft: 'fadeInLeft',
                fadeinright: 'fadeInRight',
                zoomin: 'zoomIn',
            };
            const animationName = animationMap[String(animationRaw).toLowerCase()] || animationRaw;

            if (!animationName) {
                return;
            }

            const targetSlides = this.getAnimationTargetSlides(sliderEl, swiperInstance);

            if (!targetSlides.length) {
                return;
            }

            sliderEl.classList.add('hester-slides--animation-ready');

            targetSlides.forEach((slide, slideIndex) => {
                const contentItems = slide.querySelectorAll('.swiper-slide-contents > *');

                contentItems.forEach((item) => {
                    item.classList.remove('hester-slide-content-animated');
                    item.style.animationName = 'none';
                    item.style.animationDelay = '';
                });

                void slide.offsetWidth;

                contentItems.forEach((item, itemIndex) => {
                    item.style.animationName = animationName;
                    item.style.animationDelay = `${(slideIndex * 120) + (itemIndex * 140)}ms`;
                    item.classList.add('hester-slide-content-animated');
                });
            });
        },

        initSlider: function (sliderEl, settingsOverride) {
            if (!sliderEl) {
                return;
            }

            this.ensureSettingsObserver(sliderEl);

            sliderEl.classList.remove('hester-slides--animation-ready');

            if (sliderEl.dataset.hesterSlidesInit === '1' && sliderEl.swiperInstance) {
                sliderEl.swiperInstance.destroy(true, true);
                sliderEl.swiperInstance = null;
                sliderEl.dataset.hesterSlidesInit = '0';
            }

            if (typeof window.Swiper === 'undefined') {
                return;
            }

            const settings = this.getSettings(sliderEl, settingsOverride);

            const navigationMode = settings.navigation || 'both';
            const showArrows = navigationMode === 'arrows' || navigationMode === 'both';
            const showDots = navigationMode === 'dots' || navigationMode === 'both';
            const effect = settings.transition;
            const slidesApi = this;
            const desktopSlides = settings.slides_per_view;
            const tabletSlides = settings.slides_per_view_tablet;
            const mobileSlides = settings.slides_per_view_mobile;
            const desktopGap = settings.slides_gap;
            const tabletGap = settings.slides_gap_tablet;
            const mobileGap = settings.slides_gap_mobile;

            const swiperConfig = {
                slidesPerView: mobileSlides,
                slidesPerGroup: mobileSlides,
                spaceBetween: mobileGap,
                breakpoints: {
                    768: {
                        slidesPerView: tabletSlides,
                        slidesPerGroup: tabletSlides,
                        spaceBetween: tabletGap,
                    },
                    1025: {
                        slidesPerView: desktopSlides,
                        slidesPerGroup: desktopSlides,
                        spaceBetween: desktopGap,
                    },
                },
                loop: !!settings.infinite,
                speed: Number.isFinite(parseInt(settings.transition_speed, 10)) ? parseInt(settings.transition_speed, 10) : 500,
                effect: effect,
                observer: true,
                observeParents: true,
                watchSlidesProgress: true,
                on: {
                    init: function () {
                        slidesApi.clearContentAnimation(sliderEl);
                        requestAnimationFrame(() => {
                            slidesApi.applyContentAnimation(sliderEl, this);
                        });
                    },
                    slideChangeTransitionStart: function () {
                        slidesApi.clearContentAnimation(sliderEl);
                        requestAnimationFrame(() => {
                            slidesApi.applyContentAnimation(sliderEl, this);
                        });
                    },
                },
            };

            if (effect === 'fade') {
                swiperConfig.fadeEffect = {
                    crossFade: true,
                };
            }

            if (settings.autoplay) {
                swiperConfig.autoplay = {
                    delay: Number.isFinite(parseInt(settings.autoplay_speed, 10)) ? parseInt(settings.autoplay_speed, 10) : 5000,
                    disableOnInteraction: !!settings.pause_on_interaction,
                    pauseOnMouseEnter: !!settings.pause_on_hover,
                };
            }

            if (showArrows) {
                swiperConfig.navigation = {
                    nextEl: sliderEl.querySelector('.elementor-swiper-button-next'),
                    prevEl: sliderEl.querySelector('.elementor-swiper-button-prev'),
                };
            }

            if (showDots) {
                swiperConfig.pagination = {
                    el: sliderEl.querySelector('.swiper-pagination'),
                    clickable: true,
                };
            }

            try {
                sliderEl.swiperInstance = new window.Swiper(sliderEl, swiperConfig);
                sliderEl.dataset.hesterSlidesInit = '1';
            } catch (error) {
                hesterError('Error initializing Hester Slides:', error);
            }
        },
    };

    // Posts Pagination (Load More / Infinite Scroll)
    // Handles load-on-click and infinite scroll pagination for Posts widget
    // Supports multiple widgets on same page with widget-scoped DOM insertion
    window.HesterPostsPagination = {
        loadedPostIds: new Set(), // Track loaded posts to prevent duplicates

        init: function (scope) {
            const root = scope && scope.querySelectorAll ? scope : document;
            const wrappers = root.querySelectorAll('.hester-posts[data-widget-id] .hester-posts__load-more');
            wrappers.forEach((wrapper) => this.initLoadMore(wrapper));
        },

        initLoadMore: function (wrapper) {
            if (!wrapper || wrapper.dataset.hesterPostsLoadMoreInit === '1') {
                return;
            }

            wrapper.dataset.hesterPostsLoadMoreInit = '1';
            if (!wrapper.dataset.loading) {
                wrapper.dataset.loading = '0';
            }

            const postsRoot = wrapper.closest('.hester-posts[data-widget-id]');
            const button = wrapper.querySelector('.hester-posts__load-more-button');
            const message = wrapper.querySelector('.hester-posts__load-more-message');
            const loader = wrapper.querySelector('.hester-posts__load-more-loader');

            if (!postsRoot) {
                hesterWarn('Hester Posts: Could not find posts container');
                return;
            }

            const isInfinite = wrapper.classList.contains('hester-posts__load-more--load_more_infinite_scroll');
            const widgetId = postsRoot.dataset.widgetId;
            let observer = null;
            let loadingUiDelayTimer = null;
            let loadingStartedAt = 0;
            const LOADING_UI_DELAY = 120;
            const MIN_LOADING_UI_DURATION = 260;

            const setLoadingUiVisible = (isVisible) => {
                wrapper.classList.toggle('is-loading', isVisible);

                if (loader) {
                    loader.setAttribute('aria-hidden', isVisible ? 'false' : 'true');
                }

                if (button) {
                    button.style.pointerEvents = isVisible ? 'none' : '';
                    button.setAttribute('aria-disabled', isVisible ? 'true' : 'false');
                    button.setAttribute('aria-busy', isVisible ? 'true' : 'false');
                }
            };

            const setLoadingState = (isLoading) => {
                if (loadingUiDelayTimer) {
                    window.clearTimeout(loadingUiDelayTimer);
                    loadingUiDelayTimer = null;
                }

                if (isLoading) {
                    wrapper.dataset.loading = '1';
                    wrapper.setAttribute('aria-busy', 'true');
                    loadingStartedAt = Date.now();

                    loadingUiDelayTimer = window.setTimeout(() => {
                        setLoadingUiVisible(true);
                    }, LOADING_UI_DELAY);

                    return;
                }

                const finalize = () => {
                    setLoadingUiVisible(false);
                    wrapper.setAttribute('aria-busy', 'false');
                    wrapper.dataset.loading = '0';
                };

                if (!wrapper.classList.contains('is-loading')) {
                    finalize();
                    return;
                }

                const elapsed = Date.now() - loadingStartedAt;
                const waitFor = Math.max(0, MIN_LOADING_UI_DURATION - elapsed);
                window.setTimeout(finalize, waitFor);
            };

            const triggerLoad = () => {
                const nextUrl = wrapper.dataset.nextUrl || '';

                // Prevent loading multiple times simultaneously
                if (!nextUrl || wrapper.dataset.loading === '1') {
                    return;
                }

                setLoadingState(true);

                // Fetch next page
                fetch(nextUrl, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                    .then((res) => {
                        if (!res.ok) {
                            throw new Error('Network response was not ok: ' + res.status);
                        }
                        return res.text();
                    })
                    .then((html) => {
                        // Parse the returned HTML
                        const parser = new DOMParser();
                        const docFragment = parser.parseFromString(html, 'text/html');

                        // Find the widget container in the response
                        const escapedWidgetId = (window.CSS && typeof window.CSS.escape === 'function')
                            ? window.CSS.escape(widgetId)
                            : String(widgetId || '').replace(/["\\]/g, '\\$&');
                        const selector = '.hester-posts[data-widget-id="' + escapedWidgetId + '"]';
                        let nextRoot = docFragment.querySelector(selector);

                        // In Elementor preview, IDs can mismatch in fetched markup; fallback to same widget index.
                        if (!nextRoot) {
                            const currentWidgets = Array.from(document.querySelectorAll('.hester-posts[data-widget-id]'));
                            const widgetIndex = currentWidgets.indexOf(postsRoot);

                            if (widgetIndex > -1) {
                                const nextWidgets = docFragment.querySelectorAll('.hester-posts[data-widget-id]');
                                nextRoot = nextWidgets[widgetIndex] || null;
                            }
                        }

                        if (!nextRoot) {
                            // No more posts found
                            this.handleNoMorePosts(wrapper, button, message);
                            return;
                        }

                        // Insert new posts into current grid
                        const currentGrid = postsRoot.querySelector('.hester-posts__grid');
                        const nextPosts = nextRoot.querySelectorAll('.hester-posts__grid > .hester-posts__post');
                        const insertedPosts = [];

                        let insertedCount = 0;
                        nextPosts.forEach((post) => {
                            const clonedPost = post.cloneNode(true);
                            clonedPost.classList.add('hester-posts__post--enter');
                            currentGrid.appendChild(clonedPost);
                            insertedPosts.push(clonedPost);
                            insertedCount++;
                        });

                        if (insertedPosts.length) {
                            const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                            if (reduceMotion) {
                                insertedPosts.forEach((post) => {
                                    post.classList.remove('hester-posts__post--enter');
                                });
                            } else {
                                window.requestAnimationFrame(() => {
                                    insertedPosts.forEach((post, index) => {
                                        const delay = Math.min(index * 28, 168);
                                        post.style.transitionDelay = delay + 'ms';
                                        window.setTimeout(() => {
                                            post.classList.add('hester-posts__post--enter-active');
                                            window.setTimeout(() => {
                                                post.classList.remove('hester-posts__post--enter');
                                                post.classList.remove('hester-posts__post--enter-active');
                                                post.style.transitionDelay = '';
                                            }, 520);
                                        }, delay);
                                    });
                                });
                            }
                        }

                        // Update pagination state from the next page
                        const nextLoad = nextRoot.querySelector('.hester-posts__load-more');
                        const nextNextUrl = nextLoad ? (nextLoad.dataset.nextUrl || '') : '';
                        wrapper.dataset.nextUrl = nextNextUrl;

                        // Update button visibility
                        if (button) {
                            if (nextNextUrl) {
                                button.href = nextNextUrl;
                                button.style.display = '';
                            } else {
                                button.style.display = 'none';
                            }
                        }

                        // Update message visibility
                        if (message) {
                            message.style.display = nextNextUrl ? 'none' : 'block';
                        }

                        // Trigger any Elementor events for new content
                        if (window.elementorFrontend && window.elementorFrontend.hooks && window.jQuery) {
                            window.elementorFrontend.hooks.doAction('frontend/element_ready/container', jQuery(currentGrid));
                        }

                        runWidgetInits(currentGrid);
                    })
                    .catch((err) => {
                        hesterError('Hester Posts load-more error:', err);
                    })
                    .finally(() => {
                        setLoadingState(false);
                    });
            };

            this.triggerLoad = triggerLoad.bind(this);

            // Click handler for load-more button
            if (button) {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    triggerLoad();
                });
            }

            // Infinite scroll with IntersectionObserver
            if (isInfinite && 'IntersectionObserver' in window) {
                observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting && wrapper.dataset.loading === '0') {
                            // Only trigger if there are more pages
                            if (wrapper.dataset.nextUrl) {
                                triggerLoad();
                            }
                        }
                    });
                }, {
                    rootMargin: '200px 0px 200px 0px',
                    threshold: 0.1,
                });

                observer.observe(wrapper);
                wrapper.dataset.observer = true;
            }
        },

        handleNoMorePosts: function (wrapper, button, message) {
            wrapper.dataset.nextUrl = '';

            if (button) {
                button.style.display = 'none';
            }

            if (message) {
                message.style.display = 'block';
            }
        }
    };

    const resolveScopeNode = function (scope) {
        if (scope && scope[0] && scope[0].querySelectorAll) {
            return scope[0];
        }

        if (scope && scope.querySelectorAll) {
            return scope;
        }

        return document;
    };

    const runWidgetInits = function (scope, options) {
        const initOptions = options || {};
        const root = resolveScopeNode(scope);
        if (typeof window.HesterAnimatedHeading === 'function' && window.jQuery) {
            window.HesterAnimatedHeading(window.jQuery(root));
        }
        if (!initOptions.skipSlides) {
            window.HesterSlides.init?.(root);
        }
        window.HesterTabs.init?.(root);
        window.HesterOffCanvas.init?.(root);
        window.HesterSearch.init?.(root);
        window.HesterWooCarousel.init?.(root);
        window.HesterPostsPagination.init?.(root);
    };

    const bindElementorHooks = function () {
        if (!window.elementorFrontend || !window.elementorFrontend.hooks || typeof window.elementorFrontend.hooks.addAction !== 'function') {
            return;
        }

        if (window.__hesterElementorHooksBound === true) {
            return;
        }

        window.__hesterElementorHooksBound = true;

        // Global fallback for any widget re-render in preview/editor.
        window.elementorFrontend.hooks.addAction('frontend/element_ready/widget', function (scope) {
            runWidgetInits(scope);
        });

        // Explicit hook for Posts widget in Elementor editor/preview.
        window.elementorFrontend.hooks.addAction('frontend/element_ready/hester-posts.default', function (scope) {
            runWidgetInits(scope);
        });

    };

    const bindElementorAnimatedHeadingHandler = function () {
        if (!window.elementorFrontend || !window.elementorFrontend.hooks || !window.elementorFrontend.elementsHandler || !window.elementorModules || !window.elementorModules.frontend || !window.elementorModules.frontend.handlers || !window.elementorModules.frontend.handlers.Base) {
            return;
        }

        if (window.__hesterAnimatedHeadingHandlerBound === true) {
            return;
        }

        window.__hesterAnimatedHeadingHandlerBound = true;

        const AnimatedHeadingHandler = window.elementorModules.frontend.handlers.Base.extend({
            onInit: function () {
                window.elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);
                window.HesterAnimatedHeading(this.$element);
            },

            onElementChange: function () {
                window.HesterAnimatedHeading(this.$element);
            },

            onDestroy: function () {
                const $animatedHeading = this.$element.find('.hester-animated-heading');

                $animatedHeading.each(function () {
                    const $current = window.jQuery(this);
                    const morphextInstance = $current.data('plugin_Morphext');

                    if (morphextInstance && typeof morphextInstance.stop === 'function') {
                        morphextInstance.stop();
                        $current.removeData('plugin_Morphext');
                    }

                    if ($current.data('typed') && typeof $current.data('typed').destroy === 'function') {
                        $current.data('typed').destroy();
                        $current.removeData('typed');
                    }
                });

                window.elementorModules.frontend.handlers.Base.prototype.onDestroy.apply(this, arguments);
            }
        });

        window.elementorFrontend.hooks.addAction('frontend/element_ready/hester-animated-heading.default', function ($scope) {
            window.elementorFrontend.elementsHandler.addHandler(AnimatedHeadingHandler, { $element: $scope });
        });
    };

    const bindElementorSlidesHandler = function () {
        if (!window.elementorFrontend || !window.elementorFrontend.hooks || !window.elementorFrontend.elementsHandler || !window.elementorModules || !window.elementorModules.frontend || !window.elementorModules.frontend.handlers || !window.elementorModules.frontend.handlers.Base) {
            return;
        }

        if (window.__hesterSlidesHandlerBound === true) {
            return;
        }

        window.__hesterSlidesHandlerBound = true;

        const SlidesHandler = window.elementorModules.frontend.handlers.Base.extend({
            getDefaultSettings: function () {
                return {
                    selectors: {
                        slider: '.hester-slides-wrapper.elementor-main-swiper.swiper'
                    }
                };
            },

            getDefaultElements: function () {
                const selectors = this.getSettings('selectors');

                return {
                    $slider: this.$element.find(selectors.slider)
                };
            },

            onInit: function () {
                window.elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

                if (this.elements.$slider.length) {
                    window.HesterSlides.initSlider(this.elements.$slider[0], this.getElementSettings());
                }
            },

            onElementChange: function () {
                if (this.elements.$slider.length) {
                    window.HesterSlides.initSlider(this.elements.$slider[0], this.getElementSettings());
                }
            },

            onDestroy: function () {
                if (this.elements.$slider.length) {
                    const sliderEl = this.elements.$slider[0];

                    if (sliderEl.swiperInstance) {
                        sliderEl.swiperInstance.destroy(true, true);
                        sliderEl.swiperInstance = null;
                        sliderEl.dataset.hesterSlidesInit = '0';
                    }
                }

                window.elementorModules.frontend.handlers.Base.prototype.onDestroy.apply(this, arguments);
            }
        });

        window.elementorFrontend.hooks.addAction('frontend/element_ready/hester-slides.default', function ($scope) {
            window.elementorFrontend.elementsHandler.addHandler(SlidesHandler, { $element: $scope });
        });
    };

    const bindElementorEditorHooks = function () {
        // Intentionally left blank.
        // Editor updates are handled via widget-specific element_ready hooks and Slides handler onElementChange.
    };

    const boot = function () {
        runWidgetInits(document);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }

    // Reinitialize on Elementor preview update.
    bindElementorHooks();
    bindElementorEditorHooks();
    bindElementorAnimatedHeadingHandler();
    bindElementorSlidesHandler();

    // In editor, elementorFrontend may not be ready at first script execution.
    if (window.jQuery) {
        window.jQuery(window).on('elementor/frontend/init', function () {
            bindElementorHooks();
            bindElementorEditorHooks();
            bindElementorAnimatedHeadingHandler();
            bindElementorSlidesHandler();
            runWidgetInits(document);
        });
    }
})();
