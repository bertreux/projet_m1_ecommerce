.PHONY: help docker docker_init composer_docker recreate_db_test test_back test_front test test_back_docker test_front_docker test_docker recreate_db_test_docker create_database_docker

help:
	@echo "Options disponibles dans ce Makefile :"
	@echo "  docker                 : Lance le fichier run.sh"
	@echo "  docker_init            : Lance le fichier run.sh et crée la base de données"
	@echo "  composer_docker        : Lance 'composer install' dans les containers back_php_1 et front_php_1"
	@echo "  recreate_db_test       : Recrée la base de données pour les tests"
	@echo "  test_back              : Lance les tests PHPUnit pour le backend (back/bin/phpunit)"
	@echo "  test_front             : Lance les tests PHPUnit pour le frontend (front/bin/phpunit)"
	@echo "  test                   : Lance les tests_back et test_front"
	@echo "  create_database_docker : Met à jour la base de données dans le container MySQL"
	@echo "  test_back_docker       : Lance les tests PHPUnit pour le backend depuis le container docker"
	@echo "  test_front_docker      : Lance les tests PHPUnit pour le frontend depuis le container docker"
	@echo "  test_docker            : Lance les tests_back_docker et test_front_docker"
	@echo "  recreate_db_test_docker: Drop et crée la bdd de test avec les fixtures sur docker"
	@echo "  recreate_db_test       : Drop et crée la bdd de test avec les fixtures"
	@echo "  create_database_docker : Crée la bdd et intègre les données du document bdd/e-commerce-data.sql"

docker:
	./run.sh

docker_init: docker composer_docker create_database_docker

composer_docker:
	docker exec back-php-1 bash -c "composer install"
	docker exec front-php-1 bash -c "composer install"

recreate_db_test:
	php front/bin/console doctrine:database:drop --env=test --force -n
	php front/bin/console doctrine:database:create --env=test -n
	php front/bin/console doctrine:migrations:migrate --env=test -n
	php front/bin/console doctrine:fixtures:load --env=test -n

recreate_db_test_docker:
	docker exec front-php-1 bash -c "php bin/console doctrine:database:drop --env=test --force -n"
	docker exec front-php-1 bash -c "php bin/console doctrine:database:create --env=test -n"
	docker exec front-php-1 bash -c "php bin/console doctrine:migrations:migrate --env=test -n"
	docker exec front-php-1 bash -c "php bin/console doctrine:fixtures:load --env=test -n"

test_back_docker: recreate_db_test_docker
	docker exec back-php-1 bash -c "php bin/phpunit tests/fonctionnel"
	docker exec back-php-1 bash -c "php bin/phpunit tests/e2e"

test_front_docker: recreate_db_test_docker
	docker exec front-php-1 bash -c "php bin/phpunit"

test_back: recreate_db_test
	cd back && php bin/phpunit

test_back_without_drop: recreate_db_test_docker_without_drop
	cd back && php bin/phpunit

recreate_db_test_docker_without_drop:
	docker exec front-php-1 bash -c "php bin/console doctrine:database:create --env=test -n"
	docker exec front-php-1 bash -c "php bin/console doctrine:migrations:migrate --env=test -n"
	docker exec front-php-1 bash -c "php bin/console doctrine:fixtures:load --env=test -n"

test_front: recreate_db_test
	cd front && php bin/phpunit

test: test_back test_front

create_database_docker:
	docker exec front-php-1 bash -c "php bin/console d:m:m -n"
	docker cp ./bdd/e-commerce-data.sql mariadb_container:/home/e-commerce-data.sql
	docker exec mariadb_container bash -c "mysql -usymfony -psymfony app_db < /home/e-commerce-data.sql"
