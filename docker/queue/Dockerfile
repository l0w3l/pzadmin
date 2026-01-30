FROM php:8.3-cli

ARG HOST_UID
ARG HOST_GID
ARG DOCKER_GID

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libgmp-dev \
 && docker-php-ext-install zip pcntl gmp \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

RUN echo "opcache.enable_cli=1" > /usr/local/etc/php/conf.d/opcache-cli.ini

RUN mkdir -p /usr/local/bin/scripts
COPY ./scripts /usr/local/bin/scripts

RUN bash /usr/local/bin/scripts/install.sh -d "${APP_XDEBUG}"

RUN groupadd -g ${HOST_GID} app \
  && groupadd -g ${DOCKER_GID} docker \
  && useradd -u ${HOST_UID} -g app -G docker -m app

USER app
