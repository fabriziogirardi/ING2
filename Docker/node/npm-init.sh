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
  if [ -d "node_modules" ]; then
    echo "Npm dependencies are already installed, to update them, do it manually."
  else
    npm install
    echo "Npm dependencies installed"
  fi
else
  rm -rf node_modules
  npm install --omit=dev
  echo "Npm dependencies installed"
fi