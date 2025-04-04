FROM php:8.2-apache
COPY . /var/www/html/
RUN docker-php-ext-install mysqli
EXPOSE 80

# Utiliser une image PHP avec Composer et Apache
FROM php:8.1-apache

# Installer Composer
RUN apt-get update && apt-get install -y unzip curl git \
    && curl -sS https://getcomposer.org/installer | php -- \
    && mv composer.phar /usr/local/bin/composer

# Copier les fichiers du projet dans le conteneur
WORKDIR /var/www/html
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Exposer le port 80 pour Apache
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]

