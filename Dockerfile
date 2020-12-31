FROM php:7.3-cli-alpine


# 安装 composer
ENV COMPOSER_ALLOW_SUPERUSER=true
COPY --from=composer /usr/bin/composer /usr/bin/composer

# 基础配置: 配置时区
RUN set -eux; \
    apk add --no-cache --virtual .tz-deps tzdata; \
    cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime; \
    echo "Asia/Shanghai" >  /etc/timezone; \
    date; \
    apk del .tz-deps


WORKDIR /var/www/laravel-edu
COPY . /var/www/laravel-edu

# 初始化项目和数据库
RUN composer install; \
	rm -rf database/database.sqlite; \
	touch database/database.sqlite; \
	cp .env.example .env; \
	php artisan key:generate; \
	php artisan migrate; \
	php artisan passport:install; \
	php artisan db:seed; \
	php artisan db:seed --class="\\Encore\\Admin\\Auth\\Database\\AdminTablesSeeder"; \
