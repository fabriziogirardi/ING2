#!/bin/sh

# Exit on error
set -e

# Check that APP_ENV is set to either local or production
if [ "$APP_ENV" != "local" ] && [ "$APP_ENV" != "production" ]; then
  echo "Environment variable APP_ENV is not set to a correct value such as [local] or [production], script will exit."
  exit 1
fi

# Copy the .env.example file to .env file without overwriting the existing .env file
# Also sleep for 10 seconds, because on shared volumes, the .env file may not be available immediately
cp -n .env.example .env
sleep 10

# Check if the .env file exists, if not, copy the .env.example file
echo "Checking if the .env file exists, if not, copying the .env.example file"
if [ -f .env ]; then
  echo ".env file exists"
else
  echo ".env file does not exist, copying the .env.example file"
fi

# Delete the cache files
echo "Deleting the cache files"
rm -rf bootstrap/cache/*.php

# Delete the compiled views
echo "Deleting the compiled views"
rm -rf storage/framework/views/*.php

# Delete storage link
# echo "Deleting the storage link"
# rm -f public/storage

# Check if .env file ends with a newline
echo "Checking if the .env file ends with a newline"
if [ -z "$(tail -c1 .env)" ]; then
  echo ".env file ends with a newline"
else
  echo ".env file does not end with a newline, adding one"
  echo "" >> .env
fi

# Replace the environment variables in the .env file
echo "Replacing the environment variables in the .env file"
for var in $(printenv | sort | grep -v -e "HOSTNAME" -e "PHP_INI_DIR" -e "SHLVL" -e "HOME" \
  -e "PHP_LDFLAGS" -e "PHP_CFLAGS" -e "PHP_VERSION" -e "GPG_KEYS" -e "PHP_CPPFLAGS" \
  -e "PHP_ASC_URL" -e "PHP_URL" -e "TERM" -e "PATH" -e "PHPIZE_DEPS" -e "PWD" -e "PHP_SHA256")
do
  if [ -n "$var" ]; then
    echo "Setting environment variable: $var"
    variable=$(echo "$var" | cut -d= -f1)
    value=$(echo "$var" | cut -d= -f2)
    if grep -q "^$variable=.*" .env; then
      sed -i "s|^$variable=.*|$variable=$value|g" .env
    else
      echo "$variable=$value" >> .env
    fi
  else
    echo "No environment variables found"
  fi
done
echo ""

# Force replace APP_KEY to an empty string
echo "Forcing replace APP_KEY to an empty string"
sed -i "s|^APP_KEY=.*|APP_KEY=|g" .env

# Run default Laravel commands
echo "Running default Laravel commands"
php artisan key:generate
php artisan storage:link

# Run the migrations and seed the database only if the environment is set to local, if not, run only the migrations
if [ "$APP_ENV" = "local" ]; then
  echo "Running the migrations and seeding the database"
  php artisan migrate:fresh --seed
else
  echo "Running the migrations"
  php artisan migrate --force
fi

# Clear the cache
echo "Clearing the cache"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run cache commands only if the environment is set to production
if [ "$APP_ENV" = "production" ]; then
  echo "Running cache commands for production"
  php artisan route:cache
  php artisan view:cache
  php artisan config:cache
fi

# Permission denied fix
if [ "$APP_ENV" = "local" ]; then
  echo "Fixing permissions"
  chown -R ${USER}:${USER} /var/www/html

  # Set permissions for directories
  chmod -R 755 /var/www/html
  chmod -R 776 /var/www/html/storage
  chmod -R 776 /var/www/html/bootstrap/cache
fi

echo "Laravel initialization script completed successfully"