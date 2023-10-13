.PHONY: install tests qa cs csf phpstan

install:
	composer install

tests:
	bin/phpunit --display-warnings tests

qa: phpstan cs

cs:
	bin/phpcs --standard=ruleset.xml --encoding=utf-8 --colors -nsp src tests

csf:
	bin/phpcbf --standard=ruleset.xml --encoding=utf-8 --colors -nsp src tests

phpstan:
	bin/phpstan analyse -c phpstan.neon
