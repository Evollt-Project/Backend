FROM composer:latest

WORKDIR /var/www/app

ENTRYPOINT ["composer", "--ignore-platform-reqs"]
