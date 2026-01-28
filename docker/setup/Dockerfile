FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libgmp-dev \
 && docker-php-ext-install zip pcntl gmp \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /usr/local/bin/scripts
COPY ./scripts /usr/local/bin/scripts

RUN bash /usr/local/bin/scripts/install.sh -d "${APP_XDEBUG}"
