install:
	composer install
update:
	composer update
validate:
	composer validate
lint:
	composer run-script phpcs -- --standard=PSR12 src bin
