FROM php:8.3-fpm

WORKDIR /var/www/html

ARG HOST_UID=1000
ARG HOST_GID=1000
ARG DOCKER_GID=976

RUN apt-get update && apt-get install -y \
    libbz2-dev \
    zlib1g-dev \
    libpng-dev \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    libmagickwand-dev \
    curl \
 && docker-php-ext-configure gd --with-jpeg --with-webp \
 && docker-php-ext-install pdo mbstring exif pcntl bcmath gd zip bz2 \
 && pecl install redis imagick \
 && docker-php-ext-enable redis imagick \
 && rm -rf /var/lib/apt/lists/*


RUN groupadd -g ${HOST_GID} app \
  && groupadd -g ${DOCKER_GID} docker \
  && useradd -u ${HOST_UID} -g app -G docker -m app


RUN sed -i \
  -e "s/user = www-data/user = app/" \
  -e "s/group = www-data/group = app/" \
  /usr/local/etc/php-fpm.d/www.conf

USER app

CMD ["php-fpm"]
