.PHONY: php-stan
php-stan: 
	vendor/bin/phpstan analyse src templates

.PHONY: php-cs
php-cs:
	php-cs-fixer fix
 
.PHONY : tailwind-build
tailwind-build:
	php bin/console tailwind:build --watch

.PHONY :regenerate-db
regenerate-db:
	php bin/console doctrine:migrations:diff --from-empty-schema
	php bin/console doctrine:migrations:rollup

.PHONY : generate-db
generate-db:
	php bin/console doctrine:database:create --if-not-exists
	php bin/console doctrine:schema:create
	php bin/console doctrine:fixtures:load

.PHONY: cache-clear
cc:
	php bin/console cache:clear

.PHONY : install deploy
deploy: 
	ssh XXXXXXXXXXXX 'cd sites/XXXXXXXXXXX && git pull origin main && make install && make cc'

install : vendor/autoload.php
	php bin/console doctrine:migrations:migrate --no-interaction
	php bin/console importmap:install
	php bin/console asset-map:compile
	composer dump-env prod
# penser a mettre fixtures et faker en package non dev
# php bin/console doctrine:fixtures:load --no-interaction

vendor/autoload.php: composer.json composer.lock
	composer install --no-dev --optimize-autoloader
	touch vendor/autoload.php