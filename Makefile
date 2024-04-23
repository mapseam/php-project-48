install:
	composer install
validate:
	composer validate
autoload:
	composer dump-autoload
lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin tests
lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 src bin tests
test:
	composer exec --verbose phpunit tests
gendiff:
	./bin/gendiff file1.json file2.json


