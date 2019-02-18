COMM=$(c)
VERTAG=$(v)

install:
	composer install

lint:
	composer --ansi run-script phpcs -- --standard=PSR2 --extensions=php routes tests app

lint-fix:
	composer run-script phpcbf -- --standard=PSR12 public routes app

test:
	composer run-script phpunit tests

reload:
	composer dump-autoload -o

git: gitadd gitcom gitpush	

gitadd:
	git add -A .

gitcom:
	git commit -m "$(COMM)"

gitpush:
	git push

release:
	git tag v1.0.$(VERTAG)
	git push origin v1.0.$(VERTAG)

run:
	php -S localhost:8000 -t public
