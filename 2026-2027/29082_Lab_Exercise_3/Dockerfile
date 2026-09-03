FROM php:8.2-fpm

#install requirment 
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

#install php mysqli
RUN docker-php-ext-install mysqli pdo pdo_mysql
CMD ["php-fpm"]
