---
title: Managing Users
sidebar: mydoc_sidebar
permalink: manage_users.html
summary: New admins can be added by already existing administrators from the kurukshetra dashboard. Admins can also disable existing users and prevent them from logging in back to the framework.
---

Currently administrators have two critical abilities, the ability to create new adminstrators and the ability to disable existing users.

### Adding Administrators

Once logged in as **admin**, visit `/admin/users.php` to list all the users who has logged in (atleast once) into kurukshetra.

{% include image.html file="user_management.png" alt="managing users in kurukshetra" caption="Managing users in kurukshetra" max-width=850%}
 
Existing users can be granted admin privileges by just ticking the tickbox next to the user's name.
 
{% include important.html content="
Extensive care must be taken while adding new administrators because of the fact that an administrator also have the privilege to remove admin privileges of other administrators. "%}
 
 
### Disable users
 
Once logged in as **admin**, visit `/admin/users.php` to list all the users who has logged in (atleast once) into kurukshetra. Users can be denied access by simply unchecking the checkbox named **Enable?**. The users who have been denied access can be given access again by an admin by reverting the same.
 
{% include tip.html content="
By default, a new user logging into the framework is automatically enabled. To disable a user, an administrator has to manually disable the user login from the dashboard."%}
