default:
	@echo 'Enter command'

start: \
	down \
	up \
	composer-i \
	delete-uploaded-files-and-db \
	git-pull \
	composer-i \
	laravel-migrate \
	laravel-db-seed \
	laravel-ide-helper \
	laravel-optimize-clear \
	npm-i \
	npm-run-dev

up:
	docker compose up -d --build --remove-orphans

down:
	docker compose down -v --remove-orphans

git-pull:
	git pull

composer-i:
	docker compose exec php-fpm composer i

laravel-key-generate:
	docker compose exec php-fpm php artisan key:generate

laravel-storage-link:
	docker compose exec php-fpm php artisan storage:link

delete-uploaded-files-and-db:
	docker compose run --rm php-fpm bash -c 'rm -fr storage/app/public/*'
	docker compose run --rm php-fpm php artisan db:wipe

laravel-migrate:
	docker compose exec php-fpm php artisan migrate

laravel-db-seed:
	docker compose exec php-fpm php artisan db:seed

laravel-ide-helper:
	docker compose exec php-fpm php artisan ide-helper:generate
	docker compose exec php-fpm php artisan ide-helper:meta
	docker compose exec php-fpm php artisan ide-helper:models --reset --write

laravel-optimize-clear:
	docker compose exec php-fpm php artisan optimize:clear

bash:
	docker compose exec php-fpm bash

mysql:
	docker compose exec mysql bash

npm-i:
	docker compose exec php-fpm npm i

npm-run-dev:
	docker compose exec php-fpm npm run dev

npm-run-build:
	docker compose exec php-fpm npm run build

# make <target> run-with-caution=!
ifeq ($(run-with-caution), !)
# Copy the saved [.env.*] file and configure it
init-existing-project: \
	down \
	up \
	composer-i \
	laravel-key-generate \
	laravel-storage-link \
	laravel-migrate \
	laravel-db-seed \
	laravel-ide-helper \
	laravel-optimize-clear \
	npm-i \
	npm-run-dev

update-dev:
	git pull \
	&& cd app \
	&& rm -fr storage/app/public/* \
	&& php artisan db:wipe \
	&& composer i \
	&& php artisan migrate \
	&& php artisan db:seed \
	&& php artisan optimize:clear \
	&& npm i \
	&& npm run build
endif

update-prod:
	git pull \
	&& cd app \
	&& composer i \
	&& php artisan migrate \
	&& php artisan optimize:clear \
	&& php artisan event:cache \
	&& npm i \
	&& npm run build
