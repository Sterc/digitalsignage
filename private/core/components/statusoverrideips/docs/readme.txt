StatusOverrideIPs: override site_status per IP
==============================================
Version: 1.0.0 pl (2013-07-16)
Author: Jeroen Kenters Web Development / www.kenters.com
License: GNU GENERAL PUBLIC LICENSE, Version 2

Description
-----------
StatusOverrideIPs allows you to add IP addresses which can access the front end of your website when the site is unavailable.
This is useful when you are developing a site and want your client to see progress without the need to login. All other users (different IP addresses) will see the regular 'site unavailable' page.

Installation
------------
You can install this package through Package Management.

Configuration
-------------
* In System Settings, set 'site_status' to false
* Check you can no longer access the site (when not logged in)
* Go to Components -> StatusOverrideIPs
* Add 1 or more IP addresses (including your own IP address)
* Check you can access the site again (when not logged in)