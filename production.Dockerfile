FROM laravelphp/vapor:php74

RUN apk --update add imap-dev  \
    && docker-php-ext-configure imap --with-imap --with-imap-ssl \
    && docker-php-ext-install imap

COPY . /var/task
