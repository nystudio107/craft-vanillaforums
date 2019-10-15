# Vanilla Forums plugin for Craft CMS 3.x

Single Sign On plugin for Vanilla Forums/jsConnect and CraftCMS

![Screenshot](resources/img/plugin-logo.png)

## Requirements

This plugin requires Craft CMS 3.0.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require nystudio107/craft-vanillaforums

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Vanilla Forums.

## Vanilla Forums Overview

-Insert text here-

## Configuring Vanilla Forums

1. Install jsConnect.  Make sure you have installed the [jsConnect plugin](http://vanillaforums.org/addon/jsconnect-plugin), and follow the documentation for [Vanilla SSO](http://docs.vanillaforums.com/features/sso/) setup.

2. jsConnect Auto SignIn.  You might also consider installing and using the [Vanilla jsConnect Auto SignIn](http://vanillaforums.org/addon/jsconnectautosignin-plugin) plugin to make the SSO process smoother for the end user. 

3. Go to your Vanillaforums AdminCP, under Users click on **jsConnect**, then click on **Add Connection**.  Click on **Generate Client ID and Secret** to generate random Client ID and Secret fields, and then fill in the rest of the fields as appropriate

4. Next in the Craft Admin CP, go to Settings->Plugins->Vanillaforums and enter the same Client ID and Secret from step 3

## Using Vanilla Forums

### Site-Wide Single Sign On (SSO)

Assuming you've set up everything properly, all you have to do for [Site-Wide SSO](https://blog.vanillaforums.com/jsconnect-technical-documentation/) is create a template in your CraftCMS that has only the following in it:

    {{ vanillaforumsSSO() }}

This will generate a properly configured `jsonp` response for the jsConnect SSO.

Then fill in the **Authenticate Url** field in your jsConnect connection with the URL to this template.  You can test that it's working by clicking on the **Test URL** link under Users->jsConnect, it should look something like this:

    test({"uniqueid":"1","name":"Admin","email":"admin@testsite.com","photourl":"http:\/\/testsite.com\/cpresources\/userphotos\/admin\/100\/profilepic.jpg?x=abF7BLdua","client_id":"12345678","signature":"b1670c794d13a5214b3d0ddd3d9a2293"})


### Embedded Single Sign On (SSO)

Assuming you've set up everything properly, all you have to do for [Embedded SSO](https://blog.vanillaforums.com/jsconnect-technical-documentation-for-embedded-sso/) (for things like blog comments, etc.) is to go to your VanillaForums AdminCP, click on Forum->Blog Comments->Universial Code and follow the instructions there.

You'll need to add a line after the `var vanilla_identifier` that looks like this to enable SSO for embedded comments:

    var vanilla_sso = '{{ vanillaforumsSSOEmbed() }}'; // Your SSO string.

That will output an encoded string of characters that should look something like this:

    eyJ1bmlxdWVpZCI6IjEiLCJuYW1lIjoiQWRtaW4iLCJlbWFpbCI6ImFuZHJld0BtZWdhbG9tYW5pYWMuY29tIiwicGhvdG91cmwiOiJodHRwOlwvXC9UYXN0eVN0YWtlcy5jb21cL2NwcmVzb3VyY2VzXC91c2VycGhvdG9zXC9hbmRyZXdAbWVnYWxvbWFuaWFjLmNvbVwvMTAwXC9mcmFua19sZy5qcGc/eD1LTVFrMWl0aDciLCJjbGllbnRfaWQiOiIxODY0MjUyMjMwIn0= da4d6c328a730a9c7096bdbd53d2a408f5a5958c 1438711686 hmacsha1


## Vanilla Forums Roadmap

Some things to do, and ideas for potential features:

* Release it

Brought to you by [nystudio107](https://nystudio107.com/)
