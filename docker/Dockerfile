FROM php:8.2.4-apache

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions pdo_mysql intl

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
    mv composer.phar /usr/local/bin/composer

#RUN apt update && apt install -yqq nodejs npm

COPY . /var/www/

COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

RUN cd /var/www && \
    composer install --no-interaction --no-ansi --no-scripts --no-progress --prefer-dist && \
    rm -rf /var/www/vendor/composer/.git

WORKDIR /var/www/

EXPOSE 80




# # Utilise ici une image PHP avec Apache
# FROM php:8.1-apache

# # Installe les dépendances nécessaires
# RUN apt-get update && \
#     apt-get install -y \
#     libicu-dev \
#     zlib1g-dev \
#     libzip-dev \
#     unzip \
#     && docker-php-ext-install -j$(nproc) intl pdo_mysql zip

# # Configure apache
# RUN a2enmod rewrite && \
#     service apache2 restart

# # Définir le répertoire de travail
# WORKDIR /var/www/html

# # Copier les fichiers de l'application dans le conteneur
# COPY . .

# # Installer les dépendances Symfony
# RUN curl -sSkk https://getcomposer.org/installer | php -- --disable-tls
# RUN mv    composer.phar /usr/local/bin/composer

# # Expose le port 80
# EXPOSE 80

# # Commande pour lancer Apache
# CMD ["apache2-foreground"]