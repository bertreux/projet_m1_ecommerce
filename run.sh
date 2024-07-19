#!/bin/bash

# Function to stop and remove a container if it exists
stop_and_remove_container() {
  if docker ps -a -q --filter "name=$1" | grep -q .; then
    docker stop $1
    docker rm $1
  fi
}

# Stop and remove existing mariadb_container if it exists
stop_and_remove_container mariadb_container

# Stop and remove Docker Compose services
docker-compose -f ./back/.docker/docker-compose.yml down
docker-compose -f ./front/.docker/docker-compose.yml down

# Create Docker network if not exists
docker network create symfony_app || echo "Le réseau symfony_app existe déjà"

# Run mariadb_container
docker run -d \
  --name mariadb_container \
  --network symfony_app \
  -e MYSQL_ROOT_PASSWORD="$MYSQL_ROOT_PASSWORD" \
  -e MYSQL_DATABASE="$MYSQL_DATABASE" \
  -e MYSQL_USER="$MYSQL_USER" \
  -e MYSQL_PASSWORD="$MYSQL_PASSWORD" \
  -v db_app:/var/lib/mysql \
  -p 3406:3306 \
  mariadb:10.4.32 --default-authentication-plugin=mysql_native_password

# Ensure the MariaDB container is running
if [ "$(docker ps -q -f name=mariadb_container)" ]; then
  echo "MariaDB container started successfully."
else
  echo "Failed to start MariaDB container."
  exit 1
fi

# Start Docker Compose services
docker-compose -f ./back/.docker/docker-compose.yml up --build -d
docker-compose -f ./front/.docker/docker-compose.yml up --build -d

# Wait for MariaDB to be ready
echo "Waiting for MariaDB to be ready..."
until docker exec mariadb_container mysqladmin ping --silent; do
  sleep 1
done
