#!/bin/sh

# Exit on error
set -e

# Check that APP_ENV is set to either local or production
if [ "$APP_ENV" != "local" ] && [ "$APP_ENV" != "production" ]; then
  echo "Environment variable APP_ENV is not set to a correct value such as [local] or [production], script will exit."
  exit 1
fi

# Check if APP_ENV is set to local
if [ "$APP_ENV" = "local" ]; then
  npm install
  echo "Npm dependencies installed for local environment"
else
  rm -rf node_modules
  npm install --production
  echo "Npm dependencies installed for production environment"
fi
