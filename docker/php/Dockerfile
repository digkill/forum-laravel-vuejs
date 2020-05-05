FROM php:7.4-fpm

LABEL maintainer="Vitaliy Edifanov <vitaliy@edifanov.com>"

# Arguments defined in docker-compose.yml
ARG USER_ID
ARG GROUP_ID

#ADD ./php.ini /usr/local/etc/php/php.ini

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
                               --no-install-recommends

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd pdo_pgsql

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
#RUN usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data

RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user


USER "${USER_ID}:${GROUP_ID}"

WORKDIR /var/www/app

EXPOSE 9000

CMD ["php-fpm", "-F"]
