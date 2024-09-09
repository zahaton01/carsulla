FROM nginx:1.25.3-alpine as base-nginx

COPY ./docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

RUN mkdir /etc/nginx/html

WORKDIR /var/www/html/public

FROM base-nginx as prod-nginx
COPY ./docker/nginx/conf.d/default.conf  /etc/nginx/conf.d/default.conf

FROM base-nginx as dev-nginx
COPY ./docker/nginx/conf.d/dev-nginx.conf  /etc/nginx/conf.d/default.conf
