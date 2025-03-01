#!/bin/sh

# Exit on error
set -e

# Check that APP_ENV is set to either local or production
if [ "$APP_ENV" != "local" ] && [ "$APP_ENV" != "production" ]; then
  echo "Environment variable APP_ENV is not set to a correct value such as [local] or [production], script will exit."
  exit 1
fi