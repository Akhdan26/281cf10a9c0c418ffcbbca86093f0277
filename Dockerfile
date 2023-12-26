FROM php:7.4-apache

WORKDIR /var/www/html
COPY . /var/www/html/

RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

EXPOSE 80

ENV PGHOST=postgres
ENV PGDATABASE=Levart
ENV PGUSER=postgres
ENV PGPASSWORD=Akhdan123

CMD ["php", "-S", "0.0.0.0:8080", "-t", "/var/www/html"]