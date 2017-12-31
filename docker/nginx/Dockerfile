FROM nginx:latest

MAINTAINER Mathias Schilling <m@matchilling.com>

RUN apt-get update; apt-get install -y \
    openssl

RUN rm -rf /etc/nginx/conf.d/*; \
    mkdir -p /etc/nginx/external

RUN sed -i 's/access_log.*/access_log \/dev\/stdout;/g' /etc/nginx/nginx.conf; \
    sed -i 's/error_log.*/error_log \/dev\/stdout info;/g' /etc/nginx/nginx.conf; \
    sed -i 's/^pid/daemon off;\npid/g' /etc/nginx/nginx.conf

ADD ./docker/nginx/basic.conf /etc/nginx/conf.d/basic.conf

ADD ./docker/nginx/entrypoint.sh /opt/entrypoint.sh
RUN chmod a+x /opt/entrypoint.sh

ENTRYPOINT ["/opt/entrypoint.sh"]

CMD ["nginx"]
