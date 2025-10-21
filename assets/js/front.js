jQuery(document).ready(function($) {
    // Hide the <li> containing a link with target="dashboard_preferences"
    $('li.directorist-tab__nav__item a[target="dashboard_preferences"]').closest('li').hide();


    /*Change sing in test to Cerrar Sesión */
    $('.directorist-btn--logout').each(function() {
        $(this).text('Cerrar Sesión');
    });

    // $('.directorist-alert-warning').each(function() {
    //     // Replace only the first text node (the warning message)
    //     $(this).contents().filter(function() {
    //         return this.nodeType === 3 && $.trim(this.nodeValue).length > 0;
    //     }).first().replaceWith('El área de cliente solo es accesible para usuarios que han iniciado sesión. ');

    //     // Replace the link text
    //     $(this).find('a').text('Ir al Escritorio');
    // });
});

