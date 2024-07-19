#!/bin/bash

if docker ps -a -q --filter "name=mariadb_container" | grep -q .; then
    docker stop mariadb_container
    docker rm mariadb_container
    docker-compose -f ./back/.docker/docker-compose.yml down
    docker-compose -f ./front/.docker/docker-compose.yml down
else
    docker network create symfony_app || echo "Le réseau symfony_app existe déjà"

    docker run -d \
        --name mariadb_container \
        --network symfony_app \
        -e MYSQL_ROOT_PASSWORD="symfony" \
        -e MYSQL_DATABASE="app_db" \
        -e MYSQL_USER="symfony" \
        -e MYSQL_PASSWORD="symfony" \
        -v db_app:/var/lib/mysql \
        -p 3406:3306 \
        mariadb:10.4.32 --default-authentication-plugin=mysql_native_password

    docker-compose -f ./back/.docker/docker-compose.yml up --build -d
    docker-compose -f ./front/.docker/docker-compose.yml up --build -d
fi
