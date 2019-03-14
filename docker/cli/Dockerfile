FROM php:7.2-cli

ARG DEBIAN_FRONTEND=noninteractive
ARG TIME_ZONE=Europe/Sofia

ENV XDEBUG_VERSION 2.6.0
ENV COMPOSER_HOME /composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV PATH /app/vendor/bin:/composer/vendor/bin:$PATH

WORKDIR /var/tmp

# Setup timezone
ENV TZ $TIME_ZONE
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN echo "date.timezone=$TZ" >> /usr/local/etc/php/conf.d/default.ini

# install source dependancies
RUN apt-get update && apt-get install -y \
    libmcrypt-dev \
    libssl-dev \
    libpng-dev \
    zlib1g-dev \
    unzip

# install php extensions
RUN docker-php-ext-install bcmath zip \
    && pecl install xdebug-${XDEBUG_VERSION} && docker-php-ext-enable xdebug

RUN groupadd -r -g 1000 appuser && useradd -r -m -u 1000 -g appuser appuser

# install composer
RUN apt-get install -y git \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chown -R appuser:appuser /composer

# clean up
RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/*

USER appuser
WORKDIR /app
