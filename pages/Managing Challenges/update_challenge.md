---
title: Uploading/Updating/Disabling challenges
sidebar: mydoc_sidebar
permalink: update_challenge.html
summary: New challenges can be uploaded or existing challenges can be updated/disabled via the Admin dashboard of Kurukshetra.
---
As we can write new challenges now, let's try to upload the challenges in to kurukshetra so that other people can work on these challenges and solve them.

{% include tip.html content="
Uploading/Updating/Approving/Disabling - These functionalities are restricted to **Administrators** and normal users will not have access to any of these functionalities.
"%}

### Uploading challenges

Once logged in as **admin**, visit `/admin/add_new.php` to add a new challenge to the framework. Some of the essential information required are challenge Name, type/difficulty/language, challenge code, unittest, introduction and instructions (should be written in points where every new point should be written in a new line).

{% include image.html file="add_challenge.png" alt="Add challenge to kurukshetra" caption="Adding a new challenge to kurukshetra" %}

Challenge code and unittests should be uploaded as a file and its contents are saved in database after encoding with `base64` (so as to preserve the indentation of the code).


### Disabling challenges

Once logged in as **admin**, visit `/admin/view_edit.php` to list all the challenges from which admin can approve/enable challenges. All challenges which gets uploaded are enabled by default. Any challenges which needs to be disabled should be manually disabled from the admin dashboard.

{% include image.html file="enable_challenge.png" alt="Enable challenges on kurukshetra" caption="Enabling/Disabling a challenge on kurukshetra" max-width=850 %}

All the challenges present in the framework are listed above and one can simply click on the checkbox, to enable/disable any challenges.


### Editing challenges

Once logged in as **admin**, visit `/admin/view_edit.php` to list down all the challenge and click on `edit` button at the end of the challenge which can be used to edit already uploaded challenges into the framework. 

{% include image.html file="edit_challenge.png" alt="Edit challenges on kurukshetra" caption="Edit challenges on kurukshetra" %}

By default, all the fields already present in the database will be pre-populated. One can edit the information, upload the code again and submit it back to the framework (which will update the DB). 

{% include note.html content="
While editing the challenge, challenge source code and unittests should always be re-uploaded (even if there is no change). This will be updated in the future to use existing code if new ones are not uploaded.
"%}
