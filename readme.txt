=== List Restrictor Plugin ===
Contributors: Mahbub
Tags: Directorist, login redirect, registration messages, frontend tweaks
Requires at least: 5.0
Tested up to: 7.5
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
List Restrictor is a WordPress plugin for Directorist that allows you to:
1. Redirect users visiting wp-login.php to a custom page.
2. Redirect users after login to a selected page with custom query parameters.
3. Translate and customize registration messages, including the "Go to Dashboard" link.
4. Enqueue frontend JavaScript for Directorist UI customizations.

== Features ==
* Login Redirect: Users visiting wp-login.php are redirected to a selected page.
* After Login Redirect: Users are redirected to a chosen page with ?directory_type=preventa.
* Custom Registration Messages: Default messages replaced with Spanish translations.
  - Main text: "El área de cliente solo es accesible para usuarios que han iniciado sesión."
  - Link text: "Go to Dashboard" -> "Ir al Escritorio"
* Frontend JS Tweaks: Hide tabs, change logout button text, modify alert messages.
* Secure Redirects: Uses wp_safe_redirect() for safe URL redirects.

== Installation ==
1. Upload the plugin folder to /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Configure settings in the List Restrictor Settings submenu under your Directorist CPT menu.

== Usage ==
* Select Login Redirect Page: Set the page for users visiting wp-login.php.
* Select After Login Redirect Page: Set page for post-login redirection with ?directory_type=preventa.
* Custom Messages: Registration notices automatically translated.
* Frontend Tweaks: JS handles hiding tabs, changing logout text, and alert translations.

== Changelog ==
= 1.0 =
* Initial release
* Login redirect functionality
* After-login redirect functionality
* Registration message customization
* Frontend JS for Directorist UI tweaks


