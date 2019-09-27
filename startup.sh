#!/bin/bash

echo 'Spin up database'
docker-compose up -d

echo 'Symfony security check'
symfony security:check --dir=api

echo 'Install composer packages'
composer install --prefer-dist -d api

echo "Manouvre to API directory"
cd api

echo 'Add .env file'
cp .env.dist .env

echo 'Add initial database'
docker exec -i $(docker ps -aqf "name=findapitch_db_1") mysql -uuser -ppassword findapitch < data/initialdb.sql

echo 'Run tests'
cp phpunit.xml.dist phpunit.xml && ./vendor/bin/simple-phpunit tests/PitchControllerTests.php

echo 'Start development server'
symfony server:start

