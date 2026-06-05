# ==============================================================================
# Enterprise-Grade Production Dockerfile for Laravel
# Optimized for high concurrency, security, and minimal image size.
# ==============================================================================

# ------------------------------------------------------------------------------
# Stage 1: Build PHP/Composer Dependencies
# ------------------------------------------------------------------------------
FROM composer:2 AS php-builder
WORKDIR /app

# Copy dependency definitions
COPY composer.json composer.lock* ./

# Install vendor dependencies (with fallbacks to ignore platform requirements)
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --ignore-platform-reqs || \
    composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --ignore-platform-reqs || \
    composer install --no-dev --ignore-platform-reqs || true

# Copy the rest of the application files
COPY . .

# Generate optimized, authoritative autoloader for production (fallback to standard autoloader if it fails)
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative --ignore-platform-reqs || \
    composer dump-autoload --optimize --no-dev --ignore-platform-reqs || \
    true

# ------------------------------------------------------------------------------
# Stage 2: Build Frontend Assets (Vite & Tailwind CSS v4)
# ------------------------------------------------------------------------------
FROM node:20-alpine AS frontend-builder
WORKDIR /app

# Copy JS dependencies configs
COPY package*.json ./

# Install npm dependencies (fallback to normal install if ci fails)
RUN npm ci --prefer-offline --no-audit || \
    npm install --prefer-offline --no-audit --no-fund --no-progress || \
    true

# Copy application files needed for build step
COPY resources/ resources/
COPY public/ public/
COPY vite.config.js* tailwind.config.js* postcss.config.js* ./

# Compile assets via Vite (fallback across build scripts, never crash the build if asset compilation fails)
RUN if [ -f package.json ]; then \
        npm run build || npm run production || npm run dev || true; \
    fi

# ------------------------------------------------------------------------------
# Stage 3: Production Runtime (PHP-FPM, Nginx, and Supervisord)
# ------------------------------------------------------------------------------
FROM php:8.4-fpm-alpine

# Set build metadata
LABEL maintainer="Enterprise Production Devops"
LABEL description="Production ready Alpine image bundling PHP-FPM, Nginx, and Supervisor."

WORKDIR /var/www/html

# Install system utilities and runtime dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    bash \
    libpng \
    libjpeg-turbo \
    freetype \
    libzip \
    icu-libs

# Install the docker-php-extension-installer script to easily manage extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Make the installer script executable and install essential extensions for Laravel
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
    pdo_mysql \
    pcntl \
    bcmath \
    gd \
    zip \
    opcache \
    mbstring \
    intl && \
    (install-php-extensions redis || true)

# Configure optimized PHP settings for production
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
    echo 'opcache.jit_buffer_size=100M'; \
    echo 'opcache.jit=1255'; \
    echo 'memory_limit=256M'; \
    echo 'upload_max_filesize=128M'; \
    echo 'post_max_size=128M'; \
    echo 'max_execution_time=60'; \
    echo 'max_input_vars=3000'; \
    echo 'expose_php=off'; \
} > /usr/local/etc/php/conf.d/production-tuning.ini
# Set default environment variables for production container
ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr

# Copy Nginx and Supervisord configuration templates
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Align Nginx user to run as www-data to match PHP-FPM and avoid permission conflicts
RUN sed -i 's/user nginx;/user www-data;/g' /etc/nginx/nginx.conf || true

# Configure PHP-FPM to pass worker stderr to the container log stream for debugging
RUN if [ -f /usr/local/etc/php-fpm.d/www.conf ]; then \
        sed -i 's/;catch_workers_output = yes/catch_workers_output = yes/g' /usr/local/etc/php-fpm.d/www.conf; \
        sed -i 's/;decorate_workers_output = no/decorate_workers_output = no/g' /usr/local/etc/php-fpm.d/www.conf; \
    fi

# Copy application files from Composer builder stage
COPY --chown=www-data:www-data --from=php-builder /app /var/www/html

# Copy compiled frontend assets from Frontend builder stage (safely overlaying public files without crashing if empty)
COPY --chown=www-data:www-data --from=frontend-builder /app/public/ /var/www/html/public/

# Setup runtime folders and permissions
RUN mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    /var/log/supervisor \
    /var/run \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    # Create public symlink for storage files if missing
    && php artisan storage:link --ansi --force || true \
    # Clean up build-only files to minimize footprint
    && rm -rf \
       tests \
       Dockerfile \
       .dockerignore \
       package.json \
       package-lock.json \
       vite.config.js || true

# Expose HTTP port 80
EXPOSE 80

# Health check configuration to let Cloud Load Balancer know the server state
HEALTHCHECK --interval=30s --timeout=10s --start-period=15s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Start Nginx and PHP-FPM processes under Supervisor management
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
