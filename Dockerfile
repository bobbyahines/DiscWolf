#####################################
##  BUILD STAGE  ####################
#####################################
FROM library/composer:latest AS build-stage
WORKDIR /app
COPY . .
RUN composer install

#####################################
##  SERVE STAGE  ####################
#####################################
FROM library/php:8.0-apache
LABEL maintainer="Bobby Hines <bobbyahines@gmail.com>"
LABEL image='bobbyahines/discwolf:prod'

# Set root directory for apache2 web server
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV APACHE_LOG_DIR /var/www/log

# Update repos and install system/security updates
RUN apt-get update && apt-get dist-upgrade -y

# Install required utility programs
RUN apt-get install -y apt-utils \
    build-essential \
    curl \
    nano \
    wget

#Install/Enable gd library
RUN apt-get install -y libfontconfig1 libxrender1 libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) mysqli

# Install/Enable PDO mysql driver
#RUN docker-php-ext-install -j$(nproc) pdo_mysql \
#    && docker-php-ext-configure pdo_mysql

# Install/Enable API and PDO postgres drivers
#RUN apt-get update && apt-get install -y php8.0-pgsql
#RUN apt-get update && apt-get install -y libpq-dev
#RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
#RUN docker-php-ext-install pdo_pgsql pgsql

# Install/Enable PHP Zip extension
RUN apt-get install -y libzip-dev zlib1g-dev zip \
    && docker-php-ext-install zip

##  APACHE2  ################################################

# Establish directory for apache log files
RUN mkdir -p ${APACHE_LOG_DIR}

# Search and Replace baked in default configuration files with custom root directories
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable Pretty URLs
RUN ["a2enmod","rewrite"]

##  DOCKER  #################################################

COPY --from=build-stage /app /var/www/html
EXPOSE 80
WORKDIR /var/www/html
VOLUME /var/www/html
VOLUME /var/www/log
