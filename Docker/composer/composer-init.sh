#!/bin/sh

# Exit on error
set -e

# Check that APP_ENV is set to either local or production
if [ "$APP_ENV" != "local" ] && [ "$APP_ENV" != "production" ]; then
  echo "Environment variable APP_ENV is not set to a correct value such as [local] or [production], script will exit."
  exit 1
fi

# Check if environment variable APP_ENV is set
if [ -d "vendor" ]; then
  echo "Composer dependencies are already installed. To update them, do it manually."
else
  if [ "$APP_ENV" = "local" ]; then
    composer install --no-interaction --no-progress --no-suggest
    echo "Composer dependencies installed for development"
  else
    composer install --no-interaction --no-progress --no-suggest --no-dev
    echo "Composer dependencies installed for production"
  fi
fi