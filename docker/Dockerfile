FROM ubuntu:22.04

LABEL maintainer="David"

# Set default build arguments
ARG WWWUSER=1000
ARG WWWGROUP=1001
ARG NODE_VERSION=18
ARG POSTGRES_VERSION=14  # Specify the version if needed

# Set environment variables
ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

# Set the working directory
WORKDIR /var/www/html

# Configure timezone
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install necessary packages and PHP extensions
RUN apt-get update && \
    apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 cron && \
    mkdir -p /root/.gnupg && \
    chmod 700 /root/.gnupg && \
    echo "disable-ipv6" >> /root/.gnupg/dirmngr.conf && \
    curl -sLS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /usr/share/keyrings/yarn.gpg >/dev/null && \
    echo "deb [signed-by=/usr/share/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list && \
    curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - && \
    apt-get install -y \
    php8.1-cli \
    php8.1-dev \
    php8.1-pgsql \
    php8.1-sqlite3 \
    php8.1-gd \
    php8.1-curl \
    php8.1-imap \
    php8.1-mysql \
    php8.1-mbstring \
    php8.1-xml \
    php8.1-zip \
    php8.1-bcmath \
    php8.1-soap \
    php8.1-intl \
    php8.1-readline \
    php8.1-ldap \
    nodejs \
    yarn \
    mysql-client \
    postgresql-client-$POSTGRES_VERSION && \
    php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer && \
    apt-get -y autoremove && apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*


# Set capabilities for PHP to allow low ports
RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.1

# Create user and group
RUN groupadd --force -g ${WWWGROUP} sail \
    && useradd -ms /bin/bash --no-user-group -g ${WWWGROUP} -u ${WWWUSER} sail

RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R sail:sail /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy and set up the container start script, supervisor config, and PHP ini
COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /etc/php/8.1/cli/conf.d/99-sail.ini
RUN chmod +x /usr/local/bin/start-container

# Configure and schedule cron jobs
COPY scheduler /etc/cron.d/scheduler
RUN chmod 0644 /etc/cron.d/scheduler \
    && crontab /etc/cron.d/scheduler

# Expose the application port
EXPOSE 8000

# Set the entry point to start the container
ENTRYPOINT ["start-container"]
