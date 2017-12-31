#!/usr/bin/env sh

readonly DH='/etc/nginx/external/dh.pem'
readonly DH_SIZE='1024'

if [ ! -e "$DH" ]; then
    openssl dhparam -out "$DH" $DH_SIZE
fi

if [ ! -e "/etc/nginx/external/cert.pem" ] || [ ! -e "/etc/nginx/external/key.pem" ]; then
    openssl req -x509 -newkey rsa:4086 \
        -subj '/C=XX/ST=XXXX/L=XXXX/O=XXXX/CN=localhost' \
        -keyout '/etc/nginx/external/key.pem' \
        -out '/etc/nginx/external/cert.pem' \
        -days 3650 -nodes -sha256
fi

exec "$@"
