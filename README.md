# Ultimate Member - Account tabs

Adds custom tabs to the Account page menu.

## Key features
- Ability to create custom account tabs with custom title, content and icon.
- Ability to restrict custom account tabs for specific user roles.
- Ability to embed a profile form into the custom account tab.

## Installation

__Note:__ This plugin requires the [Ultimate Member](https://wordpress.org/plugins/ultimate-member/) plugin to be installed first.

### How to install from GitHub

Open git bash, navigate to the **plugins** folder and execute this command:

`git clone --branch=main git@github.com:umdevelopera/um-account-tabs.git um-account-tabs`

Once the plugin is cloned, enter your site admin dashboard and go to _wp-admin > Plugins > Installed Plugins_. Find the "Ultimate Member - Account tabs" plugin and click the "Activate" link.

### How to install from ZIP archive

You can install this plugin from the [ZIP file](https://drive.google.com/file/d/1PlDvFg7zS26av5U7GaxXnOIl_sthELHM/view) as any other plugin. Follow [this instruction](https://wordpress.org/support/article/managing-plugins/#upload-via-wordpress-admin).

## How to use

## How to create a tab

Go to *wp-admin > Ultimate Member > Account Tabs* and click the **Add New** button to create a draft.

Image - How to create a new account tab.
![WP, Ultimate Member, Account Tabs](https://user-images.githubusercontent.com/113178913/200563260-7c127190-2933-4b93-94b7-ddf190706bb9.png)

Configure the account tab settings:

- Title - The title of the tab (menu item text).

- Content - The content of the tab. You can use text, HTML, and shortcodes. Note: shortcodes that display a form are not allowed.

- Pre-defined content:
-- Embed a profile form - Select a form if you wish to embed a profile form into the tab.

- Display Settings:
-- Show on these roles accounts - Select user roles, in whose accounts you want to display the tab. The tab is displayed in all accounts if empty.

- Appearance:
-- Background color - Set the color of the tab menu item. Default `#E0E0E0`
-- Icon - Set the icon of the tab menu item. Default `+`
-- Position - Set the position of the tab item in the menu. A number from 1 to 999. Default is `800`. The value for each tab must be unique.

Image - Custom account tab settings.
![WP, Ultimate Member, Account Tabs, Add (Edit) Tab](https://user-images.githubusercontent.com/113178913/200563285-df4bd1bc-536c-4be4-8354-bf19365b75a9.png)

### How to embed a profile form

Select the profile form you want to embed to the tab in the dropdown **Embed a profile form**.

Image - An example of the embed profile form.
![example - custom tab with profile form in account](https://user-images.githubusercontent.com/113178913/200563307-c69e7d23-e568-41c6-acb6-712ea32e87a2.png)

## Support

This is a free extension created for the community. The Ultimate Member team does not provide support for this extension.
Open new [issue](https://github.com/umdevelopera/um-account-tabs/issues) if you are facing a problem or have a suggestion.

### Related links

Ultimate Member home page: https://ultimatemember.com

Ultimate Member documentation: https://docs.ultimatemember.com

Ultimate Member download: https://wordpress.org/plugins/ultimate-member

Articles: [Account Tab](https://docs.ultimatemember.com/article/40-account-tab), [Extend Ultimate Member Account page with custom tabs/content](https://docs.ultimatemember.com/article/65-extend-ultimate-member-account-page-with-custom-tabs-content), [How to display custom fields in Account](https://docs.ultimatemember.com/article/1504-how-to-display-custom-fields-in-account)