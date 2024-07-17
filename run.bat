@echo off

docker ps -a -q --filter "name=mariadb_container" | findstr /r /c:".*" >nul
if %errorlevel%==0 (
    docker stop mariadb_container
    docker rm mariadb_container
    docker-compose -f ./back/.docker/docker-compose.yml down
    docker-compose -f ./front/.docker/docker-compose.yml down
) else (
docker network create symfony_app || echo Le reseau symfony_app existe deja

 docker run -d ^
     --name mariadb_container ^
     --network symfony_app ^
     -e MYSQL_ROOT_PASSWORD=%MYSQL_ROOT_PASSWORD% ^
     -e MYSQL_DATABASE=%MYSQL_DATABASE% ^
     -e MYSQL_USER=%MYSQL_USER% ^
     -e MYSQL_PASSWORD=%MYSQL_PASSWORD% ^
     -v db_app:/var/lib/mysql ^
     -p 3406:3306 ^
     mariadb:10.4.32 --default-authentication-plugin=mysql_native_password

 docker-compose -f ./back/.docker/docker-compose.yml up -d
 docker-compose -f ./front/.docker/docker-compose.yml up -d
)
