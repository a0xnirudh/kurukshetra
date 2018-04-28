# Kurukshetra

<p align="center">
  <img src="/staticfiles/img/logo.png" alt="Kurukshetra"/>
</p>

[![Github Release Version](https://img.shields.io/badge/release-V1.0-green.svg)](https://github.com/a0xnirudh/kurukshetra)
[![Github Release Version](https://img.shields.io/badge/php-2.7-green.svg)](https://github.com/a0xnirudh/kurukshetra)
[![License](https://img.shields.io/badge/License-GPL%20v3-green.svg)](https://github.com/a0xnirudh/kurukshetra/blob/master/LICENSE)
[![RTA loves Open source](https://badges.frapsoft.com/os/v1/open-source.svg?v=103)](https://github.com/a0xnirudh/kurukshetra)

Secure Coding Battle Playground

## Installation

### Supported Platforms

Kurukshetra has been tested both on **Ubuntu/Debian** (apt-get based distros) and as well as **Mac OS**. It should ideally work with any linux based distributions with PHP 7.2, MySQL and Docker (along with [remote API enabled](https://docs.docker.com/engine/api/v1.24/)) installed.

### Prerequisites:

There are a few packages which are necessary before proceeding with the installation:

* Git client: `sudo apt-get install git`
* PHP 7.2: Read the [instructions](https://askubuntu.com/a/856794) on how to install on ubuntu (along with php-curl - `sudo apt-ge install php7.2-curl`)
* MySQL: `sudo apt-get install mysql-server`
* Docker: Read the [official installation](https://docs.docker.com/install/) guide (Also: [ubuntu installation](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-16-04))
* Enable Docker API: Read - [How do I enable the remote Docker API](https://success.docker.com/article/how-do-i-enable-the-remote-api-for-dockerd)
* Create a folder `/var/config/` with write permission to `www-data` user


### Installing

Installing is as simple as moving the downloaded files into webroot:

```bash
git clone https://github.com/a0xnirudh/kurukshetra.git
cd kurukshetra
cp -r * /var/www/html/
chmod 755 -R /var/www/html
```

* Move all the files into webroot (which is usually `/var/www/html`): `cp -r kurukshetra/* /var/www/html`
* Give appropriate permissions for the moved files: `chmod 755 -R /var/www/html/`
* Give `challenges/uploads` directory write permissions for `www-data` user (see `uploads/README.md`).
* Visit `http://localhost` or `http://127.0.0.1` to navigate into installation (will auto redirect into /installation/).

<p align="center">
<img src="/staticfiles/img/install.png">
</p>

* Enter the MySQL DB credentials (user should have the permission to create database) and click on validate to see if the credentials are correct.
* Enter the Google OAuth `Client ID` and `Client secret` and make sure the redirect URL is set to the path `http://your-domain.com/login/index.php`

### Configuring Docker

Kurukshetra make uses of Dockers API's for running the user submitted code. A one time configuration is required before we can make use of the docker API's which is as follows:

* Pull the docker image: `docker pull phusion/baseimage:latest`
* Goto installation directory: `cd installation/optional/`
* Build kurukshetra image from the Dockerfile: `docker build -t kurukshetra .`

Alternatively, you can just run `python install.py` from within the directory `installation/optional` which will go ahead and install Docker (if not installed already) and will configure the Kurukshetra docker image automatically.

## Roadmap

The following are couple of ideas which we have in mind to do going ahead with Kurukshetra. If you have any ideas/feature requests which is not listed below, feel free to raise an [issue in github](https://github.com/a0xnirudh/kurukshetra/issues).

* Support for more languages including but not limited to JAVA, NodeJs and Ruby on Rails.

* Write more challenges along with unittests to cover all the  OWASP Top 10 vulnerabilities.


## Contributors

Awesome people who built this project:

##### Lead Developers:

Anirudh Anand ([@a0xnirudh](https://twitter.com/a0xnirudh))

##### Project Contributors:

Mohan KK ([@MohanKallepalli](https://twitter.com/MohanKallepalli))  
Ankur Bhargava ([@_AnkurB](https://twitter.com/_AnkurB))  
Prajal Kulkarni ([@prajalkulkarni](https://twitter.com/prajalkulkarni))  