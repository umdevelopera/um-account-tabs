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

You can install this plugin from the [ZIP file](https://drive.google.com/file/d/1yeczJuNODBHQYiAZcHUkURzCv46NIcgN/view?usp=sharing) as any other plugin. Follow [this instruction](https://wordpress.org/support/article/managing-plugins/#upload-via-wordpress-admin).

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
- **Display the profile fields** - Use this to display profile form fields under the profile header.
- **Submit button text** - Allows you to change the button text. "Update" by default.

4) Restrictions:
- **Show on these roles accounts** - Select user roles, in whose accounts you want to display the tab. The tab is displayed in all accounts if empty.

5) Appearance:
- **Icon** - Set the icon of the tab menu item. Default `+`.
- **Background color** - Set the background color of the tab menu item. Default `#E0E0E0`.
- **Text color** - Set the text and icon color of the tab menu item. Default `#404040`.
- **Tab position** - Set the position of the tab item in the menu. A number from 1 to 999. Default is `800`.
- **Tab slug** - Set the slug of the tab. This is a part of the account page URL specific for the tab. Default post slug.

### How to use placeholders, shortcodes and blocks

The tab content supports placeholders: {user_id}, {user_role}, {user_avatar}, {usermeta:_meta_key_}, {display_name}, {first_name}, {last_name}, {username}, {email}, {admin_email}, {site_name}, {site_url}, {user_account_link}, {user_profile_link}.
Just add a placeholder into the text in the Content section.
- **{user_id}** placeholder may be helpful for shortcodes that require the `user_id` attribute.
- **{usermeta:_meta_key_}** placeholder may be helpful to display a custom field value.

The Content section supports shortcodes and Gutenberg blocks.

**Example 1:** Placeholders and shortcodes.
![114a1+](https://github.com/user-attachments/assets/375f2e92-a6d6-406a-bc54-914d38f2e3fe)
___
![114e1](https://github.com/user-attachments/assets/1b939909-9ac8-4a58-b83b-780d4b886d11)

### How to embed the profile form fields

Account tabs can not contain forms.
Select the profile form you need in the **Embed a profile form** dropdown to embed its fields into the tab.

**Example 2:** Embeded profile form fields
![e2a+](https://github.com/user-attachments/assets/46b7b5eb-7d80-4188-b543-ab490cf1e080)
___
![114e2](https://github.com/user-attachments/assets/efc4f3b6-c41d-4c5e-be2e-9768354d5bc4)

### How to embed cover and profile photo uploaders

Select the profile form you need in the **Embed a profile form** dropdown and turn on the **Display the profile header** setting.

**Example 3:** Embeded cover and profile photo uploaders.
![114a3+](https://github.com/user-attachments/assets/eaecd354-edf4-457b-8964-9833b20030ad)
___
![114e3](https://github.com/user-attachments/assets/e9534c7d-1835-4251-80e6-92771d2eb871)

### How to translate

It is possible to translate custom account tabs if you use the [Polylang](https://wordpress.org/plugins/polylang/) multilingual plugin.
Install the [Ultimate Member - Polylang](https://github.com/umdevelopera/um-polylang) extension. Go to *wp-admin > Ultimate Member > Account Tabs* and click the **Create Tabs** button in the notice to duplicate tabs for all languages. Once the tabs are duplicated, you can manually edit the tab titles.

![WP, Ultimate Member, Account Tabs (Create Tabs)](https://github.com/user-attachments/assets/ed82a077-7727-424e-848e-ab9593013a6d)

## Support

This is a free extension created for the community. The Ultimate Member team does not provide support for this extension.
Open new [issue](https://github.com/umdevelopera/um-account-tabs/issues) if you are facing a problem or have a suggestion.

**Please give a star if you think this extension is useful. I wish to know how many people use it. Thanks.**

## Useful links

[Ultimate Member documentation](https://docs.ultimatemember.com)

[Ultimate Member core plugin info and download](https://wordpress.org/plugins/ultimate-member)

[Free extensions for Ultimate Member](https://docs.google.com/document/d/1wp5oLOyuh5OUtI9ogcPy8NL428rZ8PVTu_0R-BuKKp8/edit?usp=sharing)

[Code snippets for Ultimate Member](https://docs.google.com/document/d/1_bikh4JYlSjjQa0bX1HDGznpLtI0ur_Ma3XQfld2CKk/edit?usp=sharing)
