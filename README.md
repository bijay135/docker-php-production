# PHP enviroment setup using Docker
### Using NGINX, PHP-FPM, MySQl, PHPMyAdmin

## Overview

1. [Install prerequisites](#install-prerequisites)

    Before installing project make sure the following prerequisites have been met.

2. [Clone this repository](#clone-this-repository)

    Clone this repository into your server

3. [Install and configure your web project](#install-and-configure-your-web-project)

    Clone your web project inside

4. [Configure Nginx and PHP config](#configure-nginx-and-php-config)

    Configure the Nginx and PHP config files as per your web project
    
5. [Configure Dockerfile and docker-compose.yml](#configure-dockerfile-and-docker-composeyml)

    Configure the Dockerfile and docker-compose.yml as per your web project

6. [Run your project](#run-your-project)

    Now run the web project using docker commands
    
7. [Generate SSL certificates and setup auto renew](#generate-ssl-certificates-and-setup-auto-renew) [`Optional`]

    Additionally, generate SSL certificates for securing server and setup auto renew

8. [Update Nginx to load SSL certificates](#update-nginx-to-load-ssl-certificates) [`Optional`]

    Finally, update your Nginx config to load the SSL certificates and route https:// by default

9. [Using docker commands](#using-docker-commands)

    Use these docker commands for recurring operations
___

## Install Prerequisites

These are the required requisties for buildling a PHP enviroment :

* [Git](https://git-scm.com/downloads)

Check if `Git` is installed using the command :

```sh
git --version
```

___

* [Docker](https://docs.docker.com/engine/installation/)

Install `Docker` easily by using this script :

```sh
curl -sSL https://get.docker.com/ | sh
```

Enable `Docker` at startup

```sh
systemctl docker enable
```

Verify `Docker` version :

```sh
docker --version
```

___

* [Docker Compose](https://docs.docker.com/compose/install/)

Install `Docker Compose` using this script :

```sh
sudo curl -L "https://github.com/docker/compose/releases/download/1.23.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
```

Set file permissions :

```sh
sudo chmod +x /usr/local/bin/docker-compose
```

Verify `Docker Compose` version :

```sh
docker-compose --version
```

___

* [Certbot](https://certbot.eff.org/) [`Optional`]

Add the `Certbot` repository :

```sh
sudo add-apt-repository ppa:certbot/certbot
```

Update the package list :

```sh
sudo apt-get update
```

Finally, install `Certbot` package :

```sh
sudo apt-get install certbot
```

___

### Docker Image to use

* [Nginx](https://hub.docker.com/_/nginx/)
* [PHP-FPM](https://hub.docker.com/r/nanoninja/php-fpm/)
* [MySQL](https://hub.docker.com/_/mysql/)
* [PHPMyAdmin](https://hub.docker.com/r/phpmyadmin/phpmyadmin/)


This project uses the following ports :

| Server     | Port |
|------------|------|
| Nginx      | 80   |
| Nginx SSL  | 443  |
| MySql      | 3306 |
| PHPMyAdmin | 8000 |

___

## Clone this repository

Git clone this repository into your server :

```sh
git clone https://sakwo.sastodeal.com/bijay135/php-ci-cd
```

Go to the project directory :

```sh
cd php-ci-cd
```

### Repository Tree Structure

![Project Tree](doc/project-tree.png)

___

## Install and configure your web project

After moving inside the repository root directory. Move inside `www` folder :

```sh
cd www
```

Now `Git` clone your web project inside it :

```sh
git clone https://your_project_link
```

Change your `database` configuration. Use `mysql` as hostname to connect via a container

Change the `base_url` of your project to `/` since `Nginx` manages the `web_root` configuration

Now your project has been installed and configured to use this repository successfully

___

## Configure Nginx and PHP config

Open the Nginx config using `Vim` editor :

```sh
sudo vi server/nginx/default.conf
```

```sh
# Nginx configuration

server {
	listen 80;
	server_name host_name.com www.host_name.com;
	
	root /var/www/html/web_root;
	index index.php;
    
	location / {
		try_files $uri $uri/ /index.php;
	}
	
	location ~ \.php$ {
		fastcgi_pass php:9000;
		include fastcgi_params;
		fastcgi_index index.php;
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
	}
}
```

Replace the `host_name.com` with your domain name and `web_root` with top level directory of your web project in all occurrences.

Save the config file by pressing `escape` inside vim editor and typing the following :

```sh
:wq
```

Hit `enter` to save and quit the editor

___

Open the PHP config using Vim editor :

```sh
sudo vi server/php/php.ini
```

```sh
# PHP configuration

```

Add your required PHP `configuration` and press `escape` and type following to save and exit :

```sh
:wq
```

___

## Configure Dockerfile and docker-compose.yml

Edit the `Dockerfile` to include any additional required plugins :

```sh
sudo vi server/Dockerfile
```

```sh
FROM php:7-fpm
RUN docker-php-ext-install mysqli
```

Press `escape` and save the file :

```sh
:wq
```

___

Open the `Docker Compose` configuration file :

```sh
sudo vi docker-compose.yml
```

```sh
version: '3'

services:
    nginx:
        image: nginx:1.16.0
        container_name: nginx
        restart: always
        ports:
            - "80:80"
            - "443:443"  
        volumes:
            - ./server/nginx/default.conf:/etc/nginx/conf.d/default.conf
            - ./www:/var/www/html
            - /etc/letsencrypt:/etc/letsencrypt
        links:
            - php
            - mysql
        
    php:
        build: ./server
        image: php-ci-cd
        container_name: php-ci-cd
        restart: always
        volumes:
            - ./server/php/php.ini:/usr/local/etc/php/conf.d/php.ini
            - ./www:/var/www/html
           
    mysql:
        image: mysql:5.7
        container_name: mysql       
        restart: always
        ports: 
            - "3306:3306"
        environment:
            MYSQL_ROOT_PASSWORD: root    
        volumes:
            - ./mysql/data:/var/lib/mysql
            - ./mysql/dumps:/docker-entrypoint-initdb.d
            
    phpmyadmin:
        image: phpmyadmin/phpmyadmin
        container_name: phpmyadmin
        restart: always
        ports:
            - "8000:80"
        environment:
            MYSQL_ROOT_PASSWORD: root
        links: 
            - mysql:db
```

Under `php` service edit the `image` and `container_name` as per web project name.

The ports can be edited in `ports` tag. Port works as follows `external_port:internal_port`

The volumes can be mapped in `volumes` tag. Volumes works as follows `host_directory:container_directory`.
A symbloic link is established from host to container so any changes in host directroy is immediately seen inside container.

Hit `escape` and save the edited configuration :

```sh
:wq
```

___

## Run your project

Now everything is ready to run the web project.

Once inside the repository root. Type the command to build and run everything :

```sh
docker-compose up -d
```

`-d` flag means the task will run in deattached mode

To stop and remove all containers use the command :

```sh
docker-compose down
```

Verify if all the containers are up and running using :

```sh
docker ps -a
```

`-a` flag forces to show even stopped containers

![Docker Containers](doc/docker-containers.png)

The `STATUS` shows how long the containers are up and running whule `PORTS` shows the internal and external port binding

Now go to your public `ip address` or `domain_name` and verify that the project is running successfully.

___

## Generate SSL certificates and setup auto renew

If you followed all the previous steps, by now you should already have your web project up and running using `http`
You also should have `Certbot` fully installed and ready to run

Run the command below to generate `SSL certifificates` :

```sh
sudo certbot certonly --webroot -w ~/project/www/web_root -d domain_name.com
```

Replace `~/project/www/web_root` with your project path in your host and `domain_name.com` with your domain name

You will need to supply a valid `email address` to certbot to make the certificates fully secure

Certbot will do a reachability test using `webroot` and `domain_name.com` and save certificates in `/etc/letsencrypt` if
it was successfull.

You can manually check the certificates by using this command, replace `domain_name.com` with your domain naim supplied previously :

```sh
sudo ls /etc/letsencrypt/live/domain_name.com
```

![ssl-certificates](doc/ssl-certificates.png)

To check the renewal config file use the command, replace `domain_name.com` with your domain name :

```sh
sudo vi  /etc/letsencrypt/renewal/domain_name.com.conf
```

![renew-config](doc/renew-config.png)

Update the `webroot_path` with your proper host path if needed and add the command at the last like the figure above :

```sh
renew_hook = "docker exec -it nginx nginx -s reload"
```

Save and quit the config using `:wq`

In order to test whether the renew config is working properly, we can do a test renew run using the command below :

```sh
sudo certbot renew --dry-run
```

![dry-run](doc/dry-run.png)

If your output looks like above then the ssl certificates auto renew setup was successfull.

After actual renew run the command `docker exec -it nginx nginx -s reload` will also be executed automatically since we added
it in `renew config` as a `renew_hook`. This will make nginx load newly generated ssl certificates. 

Each certificates with this method will have about `3 Months` expiry date. When the expiry closes the `30 Days` the `Renew Config` will be
used to generate fresh certificates automatically.

___

## Update Nginx to load SSL certificates

By now, you should have your `Web Project` up and running along with `SSL Certificates` in host.

All, we need to do now is modify `Nginx Config` to load these `SSL Certificates` and also route alll `http requests on port 80`
to `https on port 443`.

First make sure you are at the repositroy root.

The Config for SSL Cerfificates has already been provided with the repository named `default_ssl.conf`.

First rename the `default.conf` :

```sh
sudo mv default.conf default.conf.bk
```

Now rename the `default_ssl.conf` into `default.conf` :

```sh
sudo mv default.ssl.conf default.conf
```

Now open the new default `Nginx Config` :

```sh
sudo vi server/nginx/default.conf
```

```sh
# Nginx configuration

server {
	listen 80;
	server_name host_name.com www.host_name.com;
	
	location /.well-known {
		alias /var/www/html/web_root/.well-known;
	}

	location / {
		return 301 https://$host$request_uri;
	}
}

server {
	listen 443 ssl;
	server_name host_name.com www.host_name.com;
	
	ssl_certificate /etc/letsencrypt/live/host_name.com/fullchain.pem;
	ssl_certificate_key /etc/letsencrypt/live/host_name.com/privkey.pem;
	include /etc/letsencrypt/options-ssl-nginx.conf;
	ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem;
	
	root /var/www/html/web_root;
	index index.php;
    
	location / {
		try_files $uri $uri/ /index.php;
	}
	
	location ~ \.php$ {
		fastcgi_pass php:9000;
		include fastcgi_params;
		fastcgi_index index.php;
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
	}
}

```

Like previously done replace `host_name.com` with your domain name and `web_root` with top level directory of your
domain name in all occurences.

Make sure you also replace `host_name.com` in `ssl certificates` section with the one ssl certificates is generated for

Now save and exit the config using `:wq`

Finally reload the nginx to load new `config` along with `ssl certificates` and `https routing` :

```sh
docker exec nginx nginx -s reload
```

Now, test your domain via internet it should be fully secured

![fully-secured](doc/fully-secured.png)

___

## Using docker commands

Here are some commonly re-occuring docker commands that may be helpful

Check docker containers :

```sh
docker ps -a
```

Check docker images :

```sh
docker images
```

Check docker networks :

```sh
docker network ls
```

Check docker volumes :

```sh
docker volume ls
```

Prune system of all clinging docker containers, images

```sh
docker system prune
```

`/bin/bash` inside docker container :

```sh
docker exec -it nginx /bin/bash
```

Up docker services using docker compose

```sh
docker-compose up -d
```

Stop and delete all docker services using docker compose

```sh
docker-compose down
```

Check container logs, replace `container_name` with your container like `Nginx`

```sh
docker container_name logs
```

Check docker-compose logs, first move into repository root then :

```sh
docker-compose logs
```
___