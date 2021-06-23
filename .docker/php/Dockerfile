FROM php:7-apache

LABEL author="Rene Bentes Pinto <github.com/renebentes>"
LABEL name=joomlagov/php
LABEL version=0.2.0

ENV DEBIAN_FRONTEND noninteractive

# Enable Apache Rewrite Module
RUN a2enmod rewrite

RUN set -eux; \
    \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get dist-upgrade -y

RUN set -eux; \
    \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        gettext-base; \
    \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN set -eux; \
    \
    savedAptMark="$(apt-mark showmanual)"; \
    \
    apt-get update; \
    \
    apt-get install -y --no-install-recommends \
        libbz2-dev \
        libgmp-dev \
        libjpeg-dev \
        libldap2-dev \
        libmcrypt-dev \
        libmemcached-dev \
        libpng-dev \
        libpq-dev \
        libzip-dev \
    ; \
    \
    docker-php-ext-configure gd --with-jpeg; \
    debMultiarch="$(dpkg-architecture --query DEB_BUILD_MULTIARCH)"; \
    docker-php-ext-configure ldap --with-libdir="lib/$debMultiarch"; \
    docker-php-ext-install -j "$(nproc)" \
        bz2 \
        gd \
        gmp \
        ldap \
        mysqli \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
        zip \
    ; \
    \
    # pecl will claim success even if one install fails, so we need to perform each install separately
    pecl install APCu-5.1.20; \
    pecl install memcached-3.1.5; \
    pecl install redis-5.3.4; \
    \
    docker-php-ext-enable \
        apcu \
        memcached \
        redis \
    ; \
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

COPY .docker/php/docker-entrypoint.sh /
COPY .docker/php/populate-sample.php /

COPY . .

ENTRYPOINT [ "/docker-entrypoint.sh" ]

CMD [ "apache2-foreground" ]
