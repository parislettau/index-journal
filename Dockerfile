# Use latest offical ubuntu image
FROM ubuntu:latest

# Set timezone environment variable
ENV TZ=Australia/Melbourne

# Set geographic area using above variable
# This is necessary, otherwise building the image doesn't work
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Remove annoying messages during package installation
ARG DEBIAN_FRONTEND=noninteractive

# Install packages: web server Apache, PHP and extensions
RUN apt-get update && apt-get install --no-install-recommends -y \
    apache2 \
    apache2-utils \
    ca-certificates \
    git \
    php \
    libapache2-mod-php \
    php-curl \
    php-dom \
    php-gd \
    php-intl \
    php-json \
    php-mbstring \
    php-xml \
    php-zip && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install imagemagick and utilities
RUN apt-get update && \
    apt-get install -y --no-install-recommends apt-utils imagemagick --fix-missing && \
    apt-get clean && rm -rf /var/lib/apt/lists/*
# Copy virtual host configuration from current path onto existing 000-default.conf
COPY default.conf /etc/apache2/sites-available/000-default.conf

# Remove default content (existing index.html)
RUN rm /var/www/html/*

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