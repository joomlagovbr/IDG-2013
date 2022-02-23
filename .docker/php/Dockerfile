FROM php:7-apache

LABEL author="Rene Bentes Pinto <github.com/renebentes>"

ENV DEBIAN_FRONTEND noninteractive

RUN set -eux; \
    \
    a2enmod rewrite

RUN set -eux; \
    \
    apt-get update; \
    \
    apt-get install -y --no-install-recommends \
        gettext-base; \
    \
    savedAptMark="$(apt-mark showmanual)"; \
    \
    apt-get install -y --no-install-recommends \
        libbz2-dev \
        libgmp-dev \
        libjpeg-dev \
        libmcrypt-dev \
        libmemcached-dev \
        libpng-dev \
        libzip-dev \
    ; \
    \
    docker-php-ext-configure gd --with-jpeg; \
    debMultiarch="$(dpkg-architecture --query DEB_BUILD_MULTIARCH)"; \
    docker-php-ext-install -j "$(nproc)" \
        bz2 \
        gd \
        gmp \
        mysqli \
        pdo_mysql \
        zip \
    ; \
    \
    # pecl will claim success even if one install fails, so we need to perform each install separately
    pecl install APCu-5.1.21; \
    pecl install memcached-3.1.5; \
    pecl install redis-5.3.4; \
    \
    docker-php-ext-enable \
        apcu \
        memcached \
        redis \
    ; \
    rm -r /tmp/pear; \
    \
    # reset apt-mark's "manual" list so that "purge --auto-remove" will remove all build dependencies
    apt-mark auto '.*' > /dev/null; \
    apt-mark manual $savedAptMark; \
    ldd "$(php -r 'echo ini_get("extension_dir");')"/*.so \
        | awk '/=>/ { print $3 }' \
        | sort -u \
        | xargs -r dpkg-query -S \
        | cut -d: -f1 \
        | sort -u \
        | xargs -rt apt-mark manual\
    ; \
    \
    apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
    rm -rf /var/lib/apt/lists/*; \
    mkdir /docker-entrypoint.d

COPY .docker/php/docker-entrypoint.sh .docker/php/populate-sample.php /usr/local/bin/

COPY . .

ARG UID=1000
RUN useradd -G www-data,root -u $UID -d /home/joomlagov joomlagov

ENTRYPOINT [ "docker-entrypoint.sh" ]

CMD [ "apache2-foreground" ]
