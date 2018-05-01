---
title: Setting up the environment
tags: [introduction]
sidebar: mydoc_sidebar
permalink: settings.html
summary: Setting up the environment to install kurukshetra
---


Before installing kurukshetra, we have to configure the environment so that all the installation prerequisites are taken care of.

## Supported Platforms

Kurukshetra has been tested both on **Ubuntu/Debian** (apt-get based distros) and **Mac OS**. It should ideally work with any linux based distributions with PHP 7.2, MySQL and Docker (along with [remote API enabled](https://docs.docker.com/engine/api/v1.24/){:target="_blank"}) installed.

{% include note.html content="
This documentation would be focusing on installing and configuring kurukshetra on ubuntu server 16.04. General installation instructions for all the prerequisites are also provided.
"%}

## Prerequisites:

There are a few packages which are necessary before proceeding with the installation:


### Installing PHP 7.2

In ubuntu, the following commands can be run to install **PHP 7.2** along with all the necessary extensions:

```bash
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update

# Installing php7.2 along with MySQL and other dependencies
sudo apt-get install php7.2 php7.2-curl php7.2-mbstring php7.2-mysql mysql-server
```
Read the official [instructions](http://php.net/manual/en/install.php){:target="_blank"} on how to install PHP on other distributions.


### Installing Docker

In ubuntu, following commands can be run to install **Docker** (add the GPG key from official repository and install it):

```bash
# add the GPG key for the official Docker repository
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -

# Add the Docker repository to APT sources
sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"

# Update and install docker-ce
sudo apt-get update
sudo apt-get install -y docker-ce
```
Enabling Docker [Remote API](https://success.docker.com/article/how-do-i-enable-the-remote-api-for-dockerd){:target="_blank"}:

```sh
# Add the following lines to **/etc/systemd/system/docker.service.d/override.conf**
[Service]
ExecStart=
ExecStart=/usr/bin/dockerd -H fd:// -H tcp://0.0.0.0:2376
```

Read the [official installation](https://docs.docker.com/install/){:target="_blank"} guide for installing on other distributions.


### Configuring directories

Create a folder `/var/config/` with write permission to `www-data` user. All the config files will be saved under this directory which will contain the MySQL credentials and Google OAuth credentials.

```bash
# Create the directory
sudo mkdir /var/config

# Make www-data the owner of the directory
sudo chown www-data: /var/config

```

Now kurukshetra can be installed via the browser by logging onto `http://127.0.0.1/`.
