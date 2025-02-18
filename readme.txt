=== Ultimate Member - Account tabs ===

Author: umdevelopera
Author URI: https://github.com/umdevelopera
Plugin URI: https://github.com/umdevelopera/um-account-tabs
Tags: ultimate member, account, tabs
License: GNU Version 2 or Any Later Version
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Requires at least: 6.5
Tested up to: 6.7.1
Requires UM core at least: 2.6.8
Tested UM core up to: 2.9.2
Stable tag: 1.1.5

== Description ==

Adds custom tabs to the Account page menu.

= Key Features =

- Ability to create custom account tabs.
- Ability to embed the profile form fields into the custom account tab.
- Ability to embed the profile form header with cover and profile photo uploaders into the custom account tab.
- Ability to restrict custom account tabs for specific user roles.
- Ability to customise the custom account tab colour, icon and position.

= Documentation & Support =

This is a free extension created for the community. The Ultimate Member team does not provide support for this extension.
Open new issue in the GitHub repository if you are facing a problem or have a suggestion: https://github.com/umdevelopera/um-account-tabs/issues

Documentation is the README section in the GitHub repository: https://github.com/umdevelopera/um-account-tabs

== Installation ==

You can install this plugin from the ZIP file as any other plugin. Follow this instruction: https://wordpress.org/support/article/managing-plugins/#upload-via-wordpress-admin

Download ZIP file from GitHub or Google Drive. You can find download links here: https://github.com/umdevelopera/um-account-tabs

== Changelog ==

= 1.1.5: February 2, 2025 =

* Enhancements:

	- Tweak: Restore global user if it was changed in the tab content.
	- Tweak: Sanitization of the custom tab content is enabled by default. Use the filter hook `um_account_tabs_sanitize_tab` if you need to display unsafe content.

= 1.1.4: December 19, 2024 =

* Enhancements:

	- Added: The "Display the profile fields" setting.
	- Added: Placeholder {user_id} and placeholder {user_role}.

= 1.1.3: December 1, 2024 =

* Enhancements:

	- Added: The "Submit button text" setting.
	- Added: The tab updated successfully notice.
	- Tweak: Avoid using the "plugins_loaded" hook.
	- Tweak: Reduced space under the embedded form.

= 1.1.2: November 23, 2024 =

* Bugfixes:

	- Fixed: Radio fields in embedded forms.

= 1.1.1: November 19, 2024 =

* Bugfixes:

	- Fixed: Compatibility with the Ultimate Member core 2.9.1.
	- Fixed: Conditional fields in embedded forms.

= 1.1.0: November 10, 2024 =

* Enhancements:

	- Added: Integration with the "Ultimate Member - Polylang" extension. Custom account tabs are translatable now.
	- Added: The "Display the profile header" setting.
	- Added: The "Text color" setting.
	- Added: The "Tab slug" setting.
	- Added: Placeholders support for tab content.
	- Added: Gutenberg editor support for tab content.

* Bugfixes:

	- Fixed: Tabs position conflict. Now it will auto increment the position value if it exists.
	- Fixed: "Load textdomain just in time" issue.

= 1.0.6: September 5, 2024 =

	- Fixed: The "Icon" setting is updated to support a new icons library used in the Ultimate Member core plugin since version 2.8.6.

= 1.0.5: December 25, 2023 =

	- Added: The "Background color" setting used to set a color of the tab menu item.
	- Added: Columns "Position", "Embed form", "Roles restriction" to the "Account tabs" table.
	- Tweak: Documentation updated.

= 1.0.4: October 19, 2023 =

	- Fixed: Dropdown field in the embed profile form.

= 1.0.3: September 5, 2023 =

	- Fixed: Load classes manually to avoid an error that occurs if autoloader works wrong.
	- Fixed: Restore form nonce and form suffix to solve conflict with "Delete Account" feature.

= 1.0.2: July 22, 2023 =

	- Added: Redirect to the same page after updating the profile form in account.
	- Tweak: Check dependencies.
	- Tweak: Documentation updated.
	- Tweak: Unused code removed.

= 1.0.1: December 11, 2022 =

	- Added: Readme file.
	- Added: Translation pattern.
	- Tweak: WordPress Coding Standards.

= 1.0.0: November 08, 2022 =

	- Initial release.