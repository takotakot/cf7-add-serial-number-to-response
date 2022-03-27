FROM wordpress:fpm


RUN apt update && \
    apt upgrade -y && \
    apt install -y mariadb-client && \
    apt clean && \
    rm -rf /var/lib/apt/lists/*

ARG ENVIRONMENT
RUN if [ "${ENVIRONMENT}" = "development" ]; then \
      pecl install xdebug; \
      docker-php-ext-enable xdebug; \
    fi

# stop copy wordpress files
ENTRYPOINT []
CMD ["php-fpm"]
