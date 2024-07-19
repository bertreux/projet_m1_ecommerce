@echo OFF
set OPT=%1

if %OPT%. == docker. (
    call ./run.bat
) else if %OPT%. == docker_init. (
    call ./run.bat
    call :composer_docker
    call :create_database_docker
) else if %OPT%. == composer_docker. (
    call :composer_docker
) else if %OPT%. == recreate_db_test. (
    call :recreate_db_test
) else if %OPT%. == test_back. (
    call :test_back
) else if %OPT%. == test_front. (
    call :test_front
) else if %OPT%. == test. (
    call :test_back
    call :test_front
) else if %OPT%. == test_back_docker. (
    call :test_back_docker
) else if %OPT%. == test_front_docker. (
    call :test_front_docker
) else if %OPT%. == test_docker. (
    call :test_back_docker
    call :test_front_docker
) else if %OPT%. == recreate_db_test_docker. (
    call :recreate_db_test_docker
) else if %OPT%. == recreate_db_test. (
    call :recreate_db_test
) else if %OPT%. == create_database_docker. (
    call :create_database_docker
) else (
    goto help
)

exit /B

:composer_docker
docker exec -it back-php-1 bash -c "composer install"
docker exec -it front-php-1 bash -c  "composer install"

:recreate_db_test
php front/bin/console doctrine:database:drop --env=test --force -n
php front/bin/console doctrine:database:create --env=test -n
php front/bin/console doctrine:migrations:migrate --env=test -n
php front/bin/console doctrine:fixtures:load --env=test -n
goto :eof

:recreate_db_test_docker
docker exec -it front-php-1 bash -c  "php bin/console doctrine:database:drop --env=test --force -n"
docker exec -it front-php-1 bash -c  "php bin/console doctrine:database:create --env=test -n"
docker exec -it front-php-1 bash -c  "php bin/console doctrine:migrations:migrate --env=test -n"
docker exec -it front-php-1 bash -c  "php bin/console doctrine:fixtures:load --env=test -n"
goto :eof

:test_back_docker
call :recreate_db_test_docker
docker exec -it back-php-1 bash -c  "php bin/phpunit tests/fonctionnel"
docker exec -it back-php-1 bash -c  "php bin/phpunit tests/e2e"

goto :eof

:test_front_docker
call :recreate_db_test_docker
docker exec -it front-php-1 bash -c  "php bin/phpunit"
goto :eof

:test_back
call :recreate_db_test
cd back
php bin/phpunit
cd ..
goto :eof

:test_front
call :recreate_db_test
cd front
php bin/phpunit
cd ..
goto :eof

:create_database_docker
docker exec -it front-php-1 bash -c "php bin/console d:m:m -n"
docker cp ./bdd/e-commerce-data.sql mariadb_container:/home/e-commerce-data.sql
docker exec -i mariadb_container bash -c "mysql -usymfony -psymfony app_db < /home/e-commerce-data.sql"
goto :eof

:help
@echo. Options disponibles dans ce Makefile :
@echo.   docker                 : Lance le fichier run.bat
@echo.   docker_init            : Lance le fichier run.bat et creer la base de donnees
@echo.   composer_docker        : Lance 'composer install' dans les containers back_php_1 et front_php_1
@echo.   recreate_db_test       : Recrer la base de donnees pour les tests
@echo.   test_back              : Lance les tests PHPUnit pour le backend (back/bin/phpunit)
@echo.   test_front             : Lance les tests PHPUnit pour le frontend (front/bin/phpunit)
@echo.   test                   : Lance les tests_back et test_front
@echo.   create_database_docker : Met a jour la base de donnees dans le container MySQL
@echo.   test_back_docker       : Lance les tests PHPUnit pour le backend depuis le container docker
@echo.   test_front_docker      : Lance les tests PHPUnit pour le frontend depuis le container docker
@echo.   test_docker            : Lance les tests_back_docker et test_front_docker
@echo.   recreate_db_test_docker: Drop et create la bdd de test avec les fixtures sur docker
@echo.   recreate_db_test       : Drop et create la bdd de test avec les fixtures
@echo.   create_database_docker : Creer la bdd et integre les donnees du document bdd/e-commerce-data.sql

exit /B
