jQuery(document).ready(function($) {
    // Hide the <li> containing a link with target="dashboard_preferences"
    $('li.directorist-tab__nav__item a[target="dashboard_preferences"]').closest('li').hide();


    /*Change sing in test to Cerrar Sesión */
    $('.directorist-btn--logout').each(function() {
        $(this).text('Cerrar Sesión');
    });
});
