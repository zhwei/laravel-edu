# 前端构建
FROM node:latest as build-frontend
WORKDIR /app
COPY front-end /app
RUN yarn install && yarn build


# 后端构建
FROM php:7.3-cli-alpine as build-backend
WORKDIR /app
# 安装 composer
ENV COMPOSER_ALLOW_SUPERUSER=true
COPY --from=composer /usr/bin/composer /usr/bin/composer
# 初始化项目和数据库
COPY . /app
RUN set -eux; \
    ln -sf .env.example .env; \
    composer install; \
    composer reset-database



FROM php:7.3-cli-alpine
# 基础配置: 配置时区
RUN set -eux; \
    apk add --no-cache --virtual .tz-deps tzdata; \
    cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime; \
    echo "Asia/Shanghai" >  /etc/timezone; \
    date; \
    apk del .tz-deps
# 复制代码，启动开发 server
WORKDIR /app
COPY --from=build-backend /app /app
COPY --from=build-frontend /app/dist /app/public/dashboard
CMD ["/usr/local/bin/php", "artisan", "serve", "--host=0.0.0.0"]
