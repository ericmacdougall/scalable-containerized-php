FROM nginx:1.18.0

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf

COPY . /var/www/html

RUN chown -R nginx:nginx /var/www/html