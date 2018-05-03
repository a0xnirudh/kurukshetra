## Uploads

This directory is used to write user submitted code which is then mounted into the docker while executing unittests.

Make sure the directory is owned by apache user (www-data) so that apache can write into this folder:
 `sudo chown www-data: uploads/`
 `sudo chmod u+w uploads/`
