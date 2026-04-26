/**
 * Hester Core Admin JavaScript
 *
 * @package Hester Core
 * @since 1.1.5
 */

(function ($) {
    'use strict';

    $(document).ready(function () {
        // Welcome notice dismiss handler
        $('#hester-core-welcome-notice').on('click', '#hester-core-dismiss-notice, .notice-dismiss', function (e) {
            e.preventDefault();

            var $button = $(this);
            var $notice = $('#hester-core-welcome-notice');
            var isCloseButton = $button.is('.notice-dismiss');

            // For close button: hide immediately, AJAX in background
            // For dismiss button: show loading state and wait for AJAX
            if (isCloseButton) {
                // Hide notice immediately
                $notice.fadeOut(300, function () {
                    $notice.remove();
                });
            } else {
                // Disable button and show loading state
                $button.prop('disabled', true).text(hester_core_admin.dismiss_text);
            }

            // Make AJAX request in background
            $.ajax({
                url: hester_core_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'hester_core_dismiss_welcome_notice',
                    nonce: hester_core_admin.nonce
                },
                success: function (response) {
                    if (response.success) {
                        // Only hide if not already hidden by close button
                        if (!isCloseButton && $notice.is(':visible')) {
                            $notice.fadeOut(300, function () {
                                $notice.remove();
                            });
                        }
                    } else {
                        // Failed - re-enable button if it's the dismiss button
                        if (!isCloseButton) {
                            $button.prop('disabled', false).text(hester_core_admin.dismiss_button_text);
                            alert(hester_core_admin.error_message);
                        } else {
                            // If close button failed, show the notice again
                            $notice.fadeIn();
                            alert(hester_core_admin.error_message);
                        }
                    }
                },
                error: function (xhr, status, error) {
                    // AJAX error
                    if (!isCloseButton) {
                        $button.prop('disabled', false).text(hester_core_admin.dismiss_button_text);
                        alert(hester_core_admin.error_message);
                    } else {
                        // If close button failed, show the notice again
                        $notice.fadeIn();
                        alert(hester_core_admin.error_message);
                    }
                    console.error('AJAX Error:', status, error);
                }
            });
        });
    });

})(jQuery);