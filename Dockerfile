FROM php:7.1

WORKDIR /var/project

COPY bin/ ./bin/
COPY src/ ./src/
COPY Tests/ ./Tests/
COPY composer.json ./composer.json
COPY phpunit.xml ./phpunit.xml
COPY .gitignore ./.gitignore

RUN apt-get update && \
    DEBIAN_FRONTEND=noninteractive \
    apt-get install -yq --no-install-recommends \
        curl \
        git \
        nano \
        unzip \
        libicu-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    echo 'alias ll="ls -la"' >> ~/.bashrc && \
    composer install --no-interaction -o && \
    bin/phpunit

