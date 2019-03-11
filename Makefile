COMM=$(c)
VERTAG=$(v)

install:
	composer install

lint:
	composer --ansi run-script phpcs -- --standard=PSR12 --extensions=php routes tests app

lint-fix:
	composer run-script phpcbf -- --standard=PSR12 --extensions=php routes tests app

testTravis:
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

docker-up:
	@docker-compose up -d

docker-down:
	@docker-compose down

docker-build:
	@docker-compose up --build -d

test:
	@docker-compose exec php-cli vendor/bin/phpunit

assets-install:
	@docker-compose exec node yarn install

assets-dev:
	@docker-compose exec node yarn run dev

assets-prod:
	@docker-compose exec node yarn run prod

assets-watch:
	@docker-compose exec node yarn run watch

perm:
	sudo chown ${USER}:docker bootstrap/cache -R
	sudo chown ${USER}:docker storage -R
	sudo chmod 777 storage/logs/*.log
	sudo chmod 777 bootstrap/cache -R
	sudo chmod 777 storage -R
	if [ -d "node_modules" ]; then sudo chown ${USER}:docker node_modules -R; fi
	if [ -d "public/build" ]; then sudo chown ${USER}:docker public/build -R; fi