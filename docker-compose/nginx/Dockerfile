FROM alpine:3.4

MAINTAINER Vincent Composieux <vincent.composieux@gmail.com>

RUN apk add --update nginx
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

ADD nginx.conf /etc/nginx/

ADD ./dev/server.crt /etc/nginx/ssl/
ADD ./dev/server.key /etc/nginx/ssl/
ADD ./dev/symfony.conf /etc/nginx/conf.d/

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

RUN adduser -D -g '' -G www-data www-data
RUN mkdir -p /var/nginx/client_body_temp
RUN chown -R www-data:www-data /var/nginx/

COPY ./wait-for.sh /var/www/wait-for.sh
RUN chmod a+x /var/www/wait-for.sh

CMD ["/var/www/wait-for.sh", "--timeout=300", "php:9000", "--", "nginx"]

EXPOSE 80
EXPOSE 443
