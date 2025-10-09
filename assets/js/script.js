jQuery(document).ready(function($) {
    // Check login status from localized script
    var isLoggedIn = RESTRICT_LIST.is_login;

    var id = 57;

    // Use delegated event binding for dynamically added links
    $(document).on('click', `a[data-listing_type_id="${id}"]`, function(e) {
        if (!isLoggedIn) {
            e.preventDefault(); // Stop the default link behavior
            alert('Please login to access this listing type!');
            $.ajax({
                url: RESTRICT_LIST.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'hide_listing',
                    nonce: RESTRICT_LIST.nonce,
                    data_id: id,

                },
                success: function(response) {

                    console.log( response );
                    // if(response.success && response.data.hide_listing_type_id == 57) {
                    //     // Hide all links / listings with data-listing_type_id="57"
                    //     $('a[data-listing_type_id="57"]').closest('.directorist-row').hide();
                    // }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX error:', error);
                }
            });
            // Optional: redirect to login page
            // window.location.href = '/wp-login.php';
        }
    });

    // Function to create the loader if it doesn't exist
    function createLoader() {
        if ($('#loader-overlay').length === 0) {
            // Create loader HTML
            var loader = $('<div id="loader-overlay"><div class="loader"></div></div>');
            $('body').append(loader);

            // Add CSS dynamically
            var css = `
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
                }
            `;
            $('<style>').prop('type', 'text/css').html(css).appendTo('head');
        }
    }

    // Function to show the loader
    function showLoader() {
        createLoader();
        $('#loader-overlay').fadeIn(200);
    }

    // Function to hide the loader
    function hideLoader() {
        $('#loader-overlay').fadeOut(200);
    }

    showLoader();
});
