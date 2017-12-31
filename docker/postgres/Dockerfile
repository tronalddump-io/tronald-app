FROM postgres:9.5.10-alpine

MAINTAINER Mathias Schilling <m@matchilling.com>

# Expect AWS CLI credentials & postgres connection details to be passed in as build arguments
ARG AWS_ACCESS_KEY_ID
ARG AWS_SECRET_ACCESS_KEY
ARG AWS_DEFAULT_REGION

RUN apk add --no-cache --update \
    python \
    python-dev \
    py-pip \
    build-base

# Install AWS CLI tools
RUN pip install awscli

# Download latest db backup from s3
ARG BACKUP_URI
RUN aws s3 cp $BACKUP_URI /docker-entrypoint-initdb.d/latest.sql

# Set default connection for psql
ENV PGDATABASE postgres
ENV PGHOST localhost
ENV PGPORT 5432
ENV PGUSER postgres
ENV PGPASSWORD postgres
