install:
	composer install
validate:
	composer validate
lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin
check:
	./vendor/bin/phpstan analyse --level 5 src bin
