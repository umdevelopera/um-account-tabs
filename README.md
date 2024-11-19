# Ultimate Member - Account tabs

Adds custom tabs to the Account page menu.

## Key features
- Ability to create custom account tabs.
- Ability to embed the profile form fields into the custom account tab.
- Ability to embed the profile form header with cover and profile photo uploaders into the custom account tab.
- Ability to restrict custom account tabs for specific user roles.
- Ability to customise the custom account tab colour, icon and position.

## Installation

__Note:__ This plugin requires the [Ultimate Member](https://wordpress.org/plugins/ultimate-member/) plugin to be installed first.

### How to install from GitHub

Open git bash, navigate to the **plugins** folder and execute this command:

`git clone --branch=main git@github.com:umdevelopera/um-account-tabs.git um-account-tabs`

Once the plugin is cloned, enter your site admin dashboard and go to _wp-admin > Plugins > Installed Plugins_. Find the **Ultimate Member - Account tabs** plugin and click the **Activate** link.

### How to install from ZIP archive

You can install this plugin from the [ZIP file](https://drive.google.com/file/d/1Xto5-evu5n71seDhQwq_6lHfh9crKvdh/view) as any other plugin. Follow [this instruction](https://wordpress.org/support/article/managing-plugins/#upload-via-wordpress-admin).

## How to use

### How to create a tab

Go to *wp-admin > Ultimate Member > Account Tabs* and click the **Add New** button to create a draft.

![WP, Ultimate Member, Account Tabs](https://github.com/umdevelopera/um-account-tabs/assets/113178913/798283da-c8c7-489b-8cc6-19e1488e67fd)

Configure the account tab settings:

1) **Title** - The title of the tab (menu item text).

2) **Content** - The content of the tab. You can add text, Gutenberg blocks, shortcodes. **Note:** forms are not allowed!

3) Embed content:
- **Embed a profile form** - Use this tool if you need to embed profile form fields into the tab.
- **Display the profile header** - Use this tool if you need to embed cover and profile photo uploaders.

4) Restrictions:
- **Show on these roles accounts** - Select user roles, in whose accounts you want to display the tab. The tab is displayed in all accounts if empty.

5) Appearance:
- **Icon** - Set the icon of the tab menu item. Default `+`.
- **Background color** - Set the background color of the tab menu item. Default `#E0E0E0`.
- **Text color** - Set the text and icon color of the tab menu item. Default `#404040`.
- **Tab position** - Set the position of the tab item in the menu. A number from 1 to 999. Default is `800`.
- **Tab slug** - Set the slug of the tab. This is a part of the account page URL specific for the tab. Default post slug.

### How to use placeholders and shortcodes

The tab content supports placeholders: {display_name}, {first_name}, {last_name}, {username}, {gender}, {email}, {admin_email}, {site_name}, {site_url}, {user_account_link}, {user_profile_link}, {user_avatar}, {usermeta:meta_key}. Just add a placeholder into the text in the Content section.

The Content section supports shortcodes and other Gutenberg blocks.

**Example 1:** Placeholders and shortcode.
![e1a+](https://github.com/user-attachments/assets/d87f4718-bc36-4b38-b6b6-9cca7d720901)
![e1f](https://github.com/user-attachments/assets/623ab70d-273d-438b-9e9a-a7af7f6032de)

### How to embed the profile form fields

Account tabs can not contain forms.
Select the profile form you need in the **Embed a profile form** dropdown to embed its fields into the tab.

**Example 2:** Embeded profile form fields
![e2a+](https://github.com/user-attachments/assets/46b7b5eb-7d80-4188-b543-ab490cf1e080)
![e2f](https://github.com/user-attachments/assets/70edcdc0-3f0c-4f0b-812f-3bfc001a4e50)

### How to embed cover and profile photo uploaders

Select the profile form you need in the **Embed a profile form** dropdown and turn on the **Display the profile header** setting.

**Example 3:** Embeded cover and profile photo uploaders.
![e3a+](https://github.com/user-attachments/assets/4bea61f6-1d26-4be1-bcd3-cb933a53329e)
![e3f](https://github.com/user-attachments/assets/4f04692b-2dae-448f-9999-52297df0c841)

### How to translate

It is possible to translate custom account tabs if you use the [Polylang](https://wordpress.org/plugins/polylang/) multilingual plugin.
Install the [Ultimate Member - Polylang](https://github.com/umdevelopera/um-polylang) extension. Go to *wp-admin > Ultimate Member > Account Tabs* and click the **Create Tabs** button in the notice to duplicate tabs for all languages. Once the tabs are duplicated, you can manually edit the tab titles.

![WP, Ultimate Member, Account Tabs (Create Tabs)](https://github.com/user-attachments/assets/ed82a077-7727-424e-848e-ab9593013a6d)

## Support

This is a free extension created for the community. The Ultimate Member team does not provide support for this extension.
Open new [issue](https://github.com/umdevelopera/um-account-tabs/issues) if you are facing a problem or have a suggestion.

### Related links

Ultimate Member home page: https://ultimatemember.com

Ultimate Member documentation: https://docs.ultimatemember.com

Ultimate Member download: https://wordpress.org/plugins/ultimate-member

---

[Free extensions for Ultimate Member](https://docs.google.com/document/d/1wp5oLOyuh5OUtI9ogcPy8NL428rZ8PVTu_0R-BuKKp8/edit?usp=sharing)
