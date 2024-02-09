SHELL=/bin/bash

install-sail: ## Install sail
	php artisan sail:install

install-breeze: ## Install breeze
	composer require laravel/breeze --dev

install-vue: ## Install vue
	php artisan breeze:install vue

up: ## Run local docker containers
	./vendor/bin/sail up

db-cli: ## Show your database cli
	php artisan db

db-show: ## Show your local database
	php artisan db:show

run-migrations: ## Run migrations
	php artisan migrate

dev: ## Run vite
	./vendor/bin/sail npm run dev

migration: ## Create a new migration
	php artisan make:migration

controller: ## Create a new controller
	php artisan make:controller

model: ## Create a new model
	php artisan make:model

resource: ## Create a new resource
	php artisan make:resource

request: ## Create a new request
	php artisan make:request

help:
	@echo 'Usage: make [target] ...'
	@echo
	@echo 'targets:'
	@echo -e "$$(grep -hE '^\S+:.*##' $(MAKEFILE_LIST) | sed -e 's/:.*##\s*/:/' -e 's/^\(.\+\):\(.*\)/\\x1b[36m\1\\x1b[m:\2/' | column -c2 -t -s :)"

.DEFAULT_GOAL := help
