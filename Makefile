COMM=$(c)
VERTAG=$(v)

install:
	composer install

lint:
	composer --ansi run-script phpcs -- --standard=PSR2 --extensions=php routes tests app

lint-fix:
	composer run-script phpcbf -- --standard=PSR12 public routes app

test2:
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
	@docker exec app_php-cli_1 vendor/bin/phpunit --color=always

assets-install:
	@docker exec app_node_1 yarn install

assets-dev:
	@docker exec app_node_1 yarn run dev

assets-watch:
	@docker exec app_node_1 yarn run watch

perm:
	sudo chown ${USER}:${USER} bootstrap/cache -R
	sudo chown ${USER}:${USER} storage -R
	if [ -d "node_modules" ]; then sudo chown ${USER}:${USER} node_modules -R; fi
	if [ -d "public/build" ]; then sudo chown ${USER}:${USER} public/build -R; fi