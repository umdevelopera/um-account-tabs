=== Ultimate Member - Account tabs ===

Author: umdevelopera
Author URI: https://github.com/umdevelopera
Plugin URI: https://github.com/umdevelopera/um-account-tabs
Tags: ultimate member, account, tabs
Requires at least: 6.5
Tested up to: 6.6.2
Requires UM core at least: 2.6.8
Tested UM core up to: 2.8.9
Stable tag: 1.1.0
License: GNU Version 2 or Any Later Version
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

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
Open new issue in the GitHub repository if you face a problem: https://github.com/umdevelopera/um-account-tabs/issues
Documentation is the README section in the GitHub repository: https://github.com/umdevelopera/um-account-tabs

== Installation ==

You can install this plugin from the ZIP file as any other plugin. Follow this instruction: https://wordpress.org/support/article/managing-plugins/#upload-via-wordpress-admin

Download ZIP file from GitHub or Google Drive. You can find download links here: https://github.com/umdevelopera/um-account-tabs

== Changelog ==

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
	- Fixed: The load_textdomain PHP notice.

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