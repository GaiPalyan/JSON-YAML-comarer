install:
	composer install
update:
	composer update
validate:
	composer validate
lint:
	composer run-script phpcs -- --standard=PSR12 src bin
lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 src tests
test:
	composer exec --verbose phpunit tests
test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
