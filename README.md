# PHP production enviroment setup using Docker, Docker Swarm Mode and GlusterFS
### Portainer, NGINX, PHP-FPM, MySQl, PHPMyAdmin, Redis

### Swarm Features:
- Automated, Highly Available, Scalable, Load Balanced, Fault Tolerent

## The Architecture Diagram


## Prerequistics

To build this enviroment you will need 3 nodes which can communicate with one another.

- 1 will be master node and 2 will be worker nodes.

- Add each other ip's in hosts file and use a friendly name for resolution if possible.

- Install Docker, Docker-Compose and put nodes in swarm mode.

- 2 worker node join the master node, now our cluster is ready.

- Install Certbot in master node only.

- Setup GlusterFS in all three nodes.

- Create a folder in all three nodes in same path for `www` and `letsencrypt`.

- Create bricks for these folders and mount them in same path, now our code and certificates will be replicated across nodes

## Git clone the repository into your path

## Modify the folder paths in these ymls

### docker-compose.yml

```
version: '3.7'

services:
    nginx:
        image: nginx:1.16.0
        ports:
            - "80:80"
            - "443:443"  
        volumes:
            - /home/bijay/docker-php-production/server/nginx/default.conf:/etc/nginx/conf.d/default.conf
            - /home/bijay/www:/var/www/html
            - /etc/letsencrypt:/etc/letsencrypt
        deploy:
            mode: replicated
            replicas: 2
            restart_policy:
                max_attempts: 5
            placement:
                constraints: [node.role == worker]
        networks:
            - docker_default 
    
    php:
        build: ./server
        image: php-ext:7-fpm
        volumes:
            - /home/bijay/docker-php-production/server/php/php.ini:/usr/local/etc/php/conf.d/php.ini
            - /home/bijay/www:/var/www/html
        deploy:
            mode: replicated
            replicas: 2
            restart_policy:
                max_attempts: 5
            placement:
                constraints: [node.role == worker]
        networks:
            - docker_default 
            
    mysql:
        image: bitnami/mysql:5.7
        environment:
            MYSQL_ROOT_PASSWORD: EhxTp7k7UpWQ9x8
            MYSQL_REPLICATION_MODE: master
            MYSQL_REPLICATION_USER: replicator
            MYSQL_REPLICATION_PASSWORD: 56789
            MYSQL_USER: bijay
            MYSQL_PASSWORD: 12345
            MYSQL_DATABASE: pctech
        volumes:
            - /home/bijay/mysql/data:/bitnami/mysql/data
            - /home/bijay/mysql/dumps:/docker-entrypoint-initdb.d
        deploy:
            mode: replicated
            replicas: 1
            restart_policy:
                max_attempts: 5
            placement:
                constraints: [node.role == manager]
        networks:
            - docker_default 
            
    mysql-replica:
        image: bitnami/mysql:5.7
        environment:
            MYSQL_REPLICATION_MODE: slave
            MYSQL_REPLICATION_USER: replicator
            MYSQL_REPLICATION_PASSWORD: 56789
            MYSQL_MASTER_HOST: mysql
            MYSQL_MASTER_PORT_NUMBER: 3306
            MYSQL_MASTER_ROOT_PASSWORD: EhxTp7k7UpWQ9x8
        volumes:
            - /home/bijay/mysql/data:/bitnami/mysql/data
            - /home/bijay/mysql/dumps:/docker-entrypoint-initdb.d
        deploy:
            mode: replicated
            replicas: 2
            restart_policy:
                max_attempts: 5
            placement:
                constraints: [node.role == worker]
        networks:
            - docker_default

    phpmyadmin:
        image: phpmyadmin/phpmyadmin
        ports:
            - "8000:80"
        environment:
            PMA_HOST: mysql
        deploy:
            mode: replicated    
            replicas: 1
            restart_policy:
                max_attempts: 5
            placement:
                constraints: [node.role == manager]
        networks:
            - docker_default 
            
networks:
    docker_default:
        external: true
```

### redis.yml

```
version: '3.7'

services:
    redis-sentinel:
        image: s7anley/redis-sentinel-docker
        environment:
            MASTER_NAME: redis-master
            MASTER: redis
            REDIS_PORT: 6379
            QUORUM: 2
        deploy:
            mode: global
            restart_policy:
                max_attempts: 5
        networks:
            - docker_default
            
    redis:
        image: redis
        command: redis-server /usr/local/etc/redis/redis.conf
        volumes:
            - /home/bijay/docker-php-production/server/redis/redis-master.conf:/usr/local/etc/redis/redis.conf
            - /home/bijay/redis-data:/data
        deploy:
            mode: replicated
            replicas: 1
            restart_policy:
                max_attempts: 5
            placement:
                constraints: [node.role == manager]
        networks:
            - docker_default 
    
    redis-replica:
        image: redis
        command: redis-server /usr/local/etc/redis/redis.conf
        volumes:
            - /home/bijay/docker-php-production/server/redis/redis-replica.conf:/usr/local/etc/redis/redis.conf
            - /home/bijay/redis-data:/data
        deploy:
            mode: replicated
            replicas: 2
            restart_policy:
                max_attempts: 5
            placement:
                constraints: [node.role == worker]
        networks:
            - docker_default 
             
networks:
    docker_default:
        external: true
```

### portainer-stack.yml

```
version: '3.7'

services:
    agent:
        image: portainer/agent:1.1.2
        environment:
            AGENT_CLUSTER_ADDR: tasks.agent
                             # LOG_LEVEL: debug
        volumes:
            - /var/run/docker.sock:/var/run/docker.sock
            - /var/lib/docker/volumes:/var/lib/docker/volumes
        deploy:
            mode: global
            
    portainer:
        image: portainer/portainer
        command: -H tcp://tasks.agent:9001 --tlsskipverify
        ports:
            - "9000:9000"
        volumes:
            - data:/data
        deploy:
            mode: replicated
            replicas: 1
            placement:
                constraints: [node.role == manager]
                
volumes:
    data:
```

## Use these commands to sucessfully deploy these services into the cluster

```
docker network create -d overlay docker_default
```

```
docker stack deploy -c portainer-stack.yml portainer
```

```
docker stack deploy -c redis.yml redis
```

```
docker stack deploy -c docker-compose.yml
```

## launch using the master node ip and 9000 port to acess portainer

- The stacks and servics can be checked easily using portainer

- You can use cluster visualizer to check the status of all the containers and repliation


## Add your code to `WWW` folder

- The project will be replicated across node by GlusterFS

## Generate certificates in master node using certbot

- The certificates will be replicated to all nodes by GlusterFS

## Access the website using the domain assiciated with master node ip

- Nginx and PHP is load balanced using round robin

- Redis and Mysql master service will be in master node while replicas in worker nodes

- In case of any faults in container, node docker swarm will handle the recovery proccess