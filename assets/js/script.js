jQuery(document).ready(function($) {
    const id = 57; // listing type ID

    // Hide listings section for non-logged-in users initially
    if (!RESTRICT_LIST.is_login) {
        $('.directorist-archive-contents__listings').hide();
        showLoginNotice();
    }

    // Check listings visibility after page fully loads
    $(window).on('load', function() {
        checkAndToggleListings();
    });

    // Handle click on restricted listing type links
    $(document).on('click', `a[data-listing_type_id="${id}"]`, function(e) {
        if (!RESTRICT_LIST.is_login) {
            e.preventDefault();
            showLoader();
            showLoginNotice();
            checkAndToggleListings();
        }
    });

    // ==========================
    // MAIN AJAX FUNCTION
    // ==========================
    function checkAndToggleListings() {
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

                const $listingsContainer = $('.directorist-archive-contents__listings');
                const allArticles = $('article.directorist-listing-single');

                if (response.success && response.data) {
                    // ✅ Hide listings container first
                    $listingsContainer.hide();
                    showLoginNotice();

                    // Hide all listings initially
                    allArticles.hide();

                    // 🚫 Hide specific listings based on response IDs
                    response.data.forEach(function(listingId) {
                        const targetArticle = $(`.directorist-mark-as-favorite__btn[data-listing_id="${listingId}"]`)
                            .closest('article.directorist-listing-single');
                        if (targetArticle.length) {
                            targetArticle.fadeOut(400);
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

    // ==========================
    // LOGIN NOTICE
    // ==========================
    function showLoginNotice() {
        if ($('#login-notice').length === 0) {
            const loginUrl = RESTRICT_LIST.login_url || '/wp-login.php';
            const notice = `
                <div id="login-notice" style="
                    background: #fff3cd;
                    color: #856404;
                    border: 1px solid #ffeeba;
                    padding: 15px;
                    margin: 20px 0;
                    border-radius: 6px;
                    text-align: center;
                    font-size: 16px;
                ">
                    🔒 Please <a href="${loginUrl}" style="color: #0073aa; font-weight: bold;">log in</a> to see the pre-sale listings.
                </div>
            `;
            $('.directorist-archive-contents__listings').before(notice);
        }
    }

    // ==========================
    // LOADER FUNCTIONS
    // ==========================
    function createLoader() {
        if ($('#loader-overlay').length === 0) {
            const loader = $('<div id="loader-overlay"><div class="loader"></div></div>');
            $('body').append(loader);

            const css = `
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
                    display: none;
                }
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
