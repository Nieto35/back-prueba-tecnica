FROM php:8.2-fpm-alpine3.20
ARG USER_ID=1000
ARG GROUP_ID=1000

# Force UTC as server time
RUN echo "UTC" > /etc/timezone

# Install essential tools
RUN apk add --no-cache \
    ca-certificates \
    bash \
    curl \
    tar \
    autoconf \
    openssh-client \
    git \
    rsync \
    gcc \
    g++ \
    make \
    supervisor


# Install Composer manually
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install pdo
RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-enable pdo_mysql && \
    rm -rf /tmp/*

# Install pcntl extension
RUN docker-php-ext-install pcntl && \
    docker-php-ext-enable pcntl && \
    rm -rf /tmp/*

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Clean up
RUN rm -rf /tmp/*

# Create user
RUN addgroup -g $GROUP_ID project && \
    adduser -G project -u $USER_ID project -D

# Setup Composer
RUN mkdir /home/project/.composer && \
    chown $USER_ID:$GROUP_ID /home/project/.composer


# Prepare psysh config directory
RUN mkdir -p /home/project/.config/psysh && \
    chown -R $USER_ID:$GROUP_ID /home/project/.config

# Copy the supervisord configuration file
COPY --chown=$USER_ID:$GROUP_ID ./docker/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create the necessary directories for supervisor logs
RUN mkdir -p /var/log/supervisor && \
    chown -R $USER_ID:$GROUP_ID /var/log/supervisor
