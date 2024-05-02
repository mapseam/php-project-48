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
test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
gendiff-json:
	./bin/gendiff file1.json file2.json
	./bin/gendiff file3.json file4.json
gendiff-yml:
	./bin/gendiff file1.yml file2.yml
	./bin/gendiff file3.yml file4.yml


