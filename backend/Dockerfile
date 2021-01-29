FROM php:8-fpm

ARG UserId
ARG Username

WORKDIR /var/www

RUN apt-get -o Acquire::Check-Valid-Until=false -o Acquire::Check-Date=false update

RUN apt-get install -y \
    gcc \ 
    make \ 
    autoconf \ 
    pkg-config \
    libc-dev \
    libzip-dev \
    libicu-dev \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxpm-dev \
    libfreetype6-dev \
    unzip 


RUN docker-php-ext-configure gd --with-freetype --with-jpeg

RUN groupadd -g ${UserId} ${Username}

RUN useradd -u ${UserId} -g ${UserId} -ms /bin/bash ${Username}

RUN pecl install redis \
    && pecl install memcache \
    && bash -c "echo extension=redis.so > /usr/local/etc/php/conf.d/redis.ini" \
    && bash -c "echo extension=memcache.so > /usr/local/etc/php/conf.d/memcache.ini" 

RUN docker-php-ext-install \
    gd \
    zip \
    intl \
    sockets \
    pdo_mysql 

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php ./composer-setup.php --install-dir=/usr/bin --filename=composer \
    && unlink ./composer-setup.php  


CMD ["php-fpm"]
