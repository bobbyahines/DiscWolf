#####################################
##  BUILD STAGE  ####################
#####################################
FROM library/composer:latest AS build-stage
WORKDIR /srv
COPY . .
RUN composer install


#####################################
##  SERVE STAGE  ####################
#####################################
FROM conflabs/php:7.4-apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV APACHE_LOG_DIR /var/www/html/logs
COPY --from=build-stage /srv/vendor /var/www/html/vendor
