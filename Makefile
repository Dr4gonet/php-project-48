install:
	composer install
validate:
	composer validate
lint:
	composer run-script  phpcs -- --standard=PSR12 src bin tests
check:
	./vendor/bin/phpstan analyse --level 6 src bin tests
fix:
	phpcbf --standard=PSR12 src bin tests
gendiff:
	./bin/gendiff
test:
	composer exec --verbose phpunit tests
test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
