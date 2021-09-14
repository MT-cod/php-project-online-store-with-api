start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	php artisan migrate
	npm install

test:
	php artisan test --coverage-clover clover.xml --verbose

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover clover.xml --verbose

lint:
	composer run-script phpcs
