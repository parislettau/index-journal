# Use PHP 8.4 with Apache as base image
FROM php:8.4-apache

# Set timezone environment variable
ENV TZ=Australia/Melbourne

# Set geographic area using above variable
# This is necessary, otherwise building the image doesn't work
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Remove annoying messages during package installation
ARG DEBIAN_FRONTEND=noninteractive

# Install packages and PHP extensions
RUN apt-get update -o Acquire::ForceIPv4=true && apt-get install --no-install-recommends -y \
    git \
    ca-certificates \
    pandoc \
    imagemagick \
    # Libraries required for PHP extensions
    libcurl4-openssl-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install \
    curl \
    dom \
    gd \
    intl \
    mbstring \
    xml \
    zip

# Copy virtual host configuration from current path onto existing 000-default.conf
COPY default.conf /etc/apache2/sites-available/000-default.conf

# Ensure document root exists and remove any default files
RUN mkdir -p /var/www/html && rm -rf /var/www/html/*

# copy the Kirby site
WORKDIR /var/www/html
COPY ./ /var/www/html
RUN mkdir /var/www/html/content

# create env
# Set the environmental variables from EasyPanel during the build
# Set the environmental variables
ARG SENDGRID_PASSWORD
ARG URL
ARG DEBUG

# Create a .env file and set its contents to the environmental variables
RUN echo "SENDGRID_PASSWORD=$SENDGRID_PASSWORD" > .env && \
    echo "URL=$URL" >> .env && \
    echo "DEBUG=$DEBUG" >> .env

# Fix files and directories ownership
RUN chown -R www-data:www-data /var/www/html/

# Activate Apache modules headers & rewrite
RUN a2enmod headers rewrite

# Expose ports
EXPOSE 80
EXPOSE 443

# Start Apache web server
CMD [ "/usr/sbin/apache2ctl", "-DFOREGROUND" ]
