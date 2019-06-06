FROM php:7.3-fpm-alpine

RUN apk add --no-cache nginx \
  && sed -i 's/^user = www-data/user = nginx/' /usr/local/etc/php-fpm.d/www.conf \
  && sed -i 's/^group = www-data/group = nginx/' /usr/local/etc/php-fpm.d/www.conf \
  && rm -rf /etc/nginx/conf.d/default.conf \
  # fix for latest alpine nginx not running (https://github.com/gliderlabs/docker-alpine/issues/185)
  && mkdir -p /run/nginx \
  # redirect logs
  && rm /var/lib/nginx/logs \
  && mkdir -p /var/lib/nginx/logs \
  && ln -s /dev/stderr /var/lib/nginx/logs/error.log

COPY --chown=nginx:nginx docker/vhost.conf /etc/nginx/conf.d/
COPY --chown=nginx:nginx ./ /var/www/app

WORKDIR /var/www/app
CMD ["sh", "-c", "nginx && php-fpm"]
EXPOSE 80
