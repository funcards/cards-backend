FROM php:8.1-cli-alpine AS build

RUN apk add --update --no-cache bash pcre icu yaml libpq libuv libpng libjpeg libexif libzip protobuf \
    && apk add --update --no-cache --virtual .build-deps \
       autoconf g++ libtool pcre make icu-dev postgresql-dev \
	   postgresql-libs libsasl db yaml-dev libuv-dev freetype-dev libjpeg-turbo-dev jpeg-dev libexif-dev libpng-dev libzip-dev \
       libxslt-dev gettext-dev oniguruma-dev \
	&& docker-php-ext-configure gd --with-jpeg \
	&& docker-php-ext-configure opcache --enable-opcache \
	&& docker-php-ext-install -j $(nproc) pcntl opcache intl gd pdo_pgsql sockets exif zip simplexml xsl bcmath calendar mbstring gettext \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && pecl install yaml \
    && docker-php-ext-enable yaml \
    && docker-php-source delete \
    && runDeps="$( \
       scanelf --needed --nobanner --format '%n#p' --recursive /usr/local \
           | tr ',' '\n' \
           | sort -u \
           | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
      )" \
    && apk add --no-cache --virtual .php-rundeps $runDeps \
	&& apk del .build-deps

# Copy Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy RoadRunner
COPY --from=spiralscout/roadrunner:2.6.6 /usr/bin/rr /usr/bin/rr
COPY --from=spiralscout/roadrunner:2.6.6 /etc/rr.yaml /etc/rr.yaml

ENV PHP_MEMORY_LIMIT=-1
ENV PHP_MAX_EXECUTION_TIME=0
ENV PHP_MAX_INPUT_TIME=60
ENV PHP_ERROR_REPORTING="E_ALL & ~E_DEPRECATED & ~E_STRICT"
ENV PHP_DISPLAY_ERRORS=Off
ENV PHP_POST_MAX_SIZE=512M
ENV PHP_UPLOAD_MAX_FILESIZE=512M
ENV PHP_MAX_FILE_UPLOADS=20
ENV PHP_DEFAULT_MIMETYPE=application/json
ENV PHP_SESSION_STRICT_MODE=1
ENV PHP_SESSION_COOKIE_SECURE=1
ENV PHP_EXPOSE_PHP=Off
ENV PHP_DATE_TIMEZONE=UTC
ENV PHP_SHORT_OPEN_TAG=Off

ENV PHP_FFI_ENABLE=false
ENV PHP_FFI_PRELOAD=''

ENV PHP_OPCACHE_ENABLE=1
ENV PHP_OPCACHE_ENABLE_CLI=1
ENV PHP_OPCACHE_MEMORY_CONSUMPTION=512
ENV PHP_OPCACHE_INTERNED_STRINGS_BUFFER=16
ENV PHP_OPCACHE_MAX_ACCELERATED_FILES_AUTO=true
ENV PHP_OPCACHE_MAX_ACCELERATED_FILES=50000
ENV PHP_OPCACHE_REVALIDATE_FREQ=0
ENV PHP_OPCACHE_ENABLE_FILE_OVERRIDE=1
ENV PHP_OPCACHE_FILE_CACHE_ONLY=1

ENV PHP_XDEBUG_MODE=develop,debug,coverage
ENV PHP_XDEBUG_CLIENT_HOST=host.docker.internal
ENV PHP_XDEBUG_CLIENT_PORT=9003
ENV PHP_XDEBUG_START_WITH_REQUEST=yes
ENV PHP_XDEBUG_IDE_KEY=PHPSTORM

COPY --from=roquie/smalte:latest-alpine /app/smalte /usr/local/bin/smalte
COPY php.ini.tmpl /usr/local/etc/php/php.ini.tmpl
COPY configure.sh /

EXPOSE 8080
WORKDIR /app

ENTRYPOINT ["/configure.sh"]
CMD ["/usr/bin/rr", "serve", "-c", "/etc/rr.yaml", "-w", "/app", "-d"]
