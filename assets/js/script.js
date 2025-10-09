jQuery(document).ready(function($) {
    const id = 57; // listing type id

    $(document).on('click', `a[data-listing_type_id="${id}"]`, function(e) {
        if (!RESTRICT_LIST.is_login) {
            e.preventDefault();
            alert('Please login to access this listing type!');
            showLoader();

            $.ajax({
                url: RESTRICT_LIST.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'hide_listing',
                    nonce: RESTRICT_LIST.nonce,
                    data_id: id
                },
                success: function(response) {
                    console.log('AJAX Response:', response);

                    // Example response.data = [99960, 99961, 99962]
                    if (response.success && response.data && response.data.length > 0) {
                        response.data.forEach(function(listingId) {
                            // Find the button or element with this listing ID
                            const targetArticle = $(`.directorist-mark-as-favorite__btn[data-listing_id="${listingId}"]`)
                                .closest('article.directorist-listing-single');

                            // Hide the entire listing card
                            if (targetArticle.length) {
                                targetArticle.fadeOut(400, function() {
                                    $(this).remove(); // optional: remove from DOM after fade
                                });
                            }
                        });
                    }

                    hideLoader();
                },
                error: function(xhr, status, error) {
                    hideLoader();
                    console.log('AJAX error:', error);
                }
            });
        }
    });

    // ===== Loader helper functions =====
    function createLoader() {
        if ($('#loader-overlay').length === 0) {
            const loader = $('<div id="loader-overlay"><div class="loader"></div></div>');
            $('body').append(loader);
            const css = `
                .loader {
                    border: 8px solid #f3f3f3;
                    border-top: 8px solid #3498db;
                    border-radius: 50%;
                    width: 60px;
                    height: 60px;
                    animation: spin 1s linear infinite;
                }
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                #loader-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(255,255,255,0.8);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                }`;
            $('<style>').prop('type', 'text/css').html(css).appendTo('head');
        }
    }

    function showLoader() {
        createLoader();
        $('#loader-overlay').fadeIn(200);
    }

    function hideLoader() {
        $('#loader-overlay').fadeOut(200);
    }
});
