FROM php:8.3-cli

ARG HOST_UID=1000
ARG HOST_GID=1000

RUN docker-php-ext-install pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl

RUN mkdir -p /usr/local/bin/scripts
COPY ./scripts /usr/local/bin/scripts

RUN bash /usr/local/bin/scripts/install.sh -d "${APP_XDEBUG}"
