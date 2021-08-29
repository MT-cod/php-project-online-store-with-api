start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	php artisan migrate
	php artisan db:seed
	npm install

watch:
	npm run watch

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test --coverage-clover clover.xml --verbose

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover clover.xml --verbose

deploy:
	git push heroku

lint:
	composer run-script phpcs

lint-fix:
	composer phpcbf
