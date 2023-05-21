FROM php:8.2.5RC1-apache-bullseye 

RUN apt-get update

RUN apt-get -y install \
    curl 

RUN docker-php-ext-install pdo pdo_mysql && \
    docker-php-ext-enable pdo pdo_mysql

USER www-data
    
