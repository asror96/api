FROM php:7.4-fpm
RUN apt-get update
WORKDIR /app
ADD . .
EXPOSE 8080/tcp
USER $user
CMD php -S 0.0.0.0:8080 -t public
