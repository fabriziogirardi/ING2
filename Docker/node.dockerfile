FROM node:23.6.1-alpine AS base

# Update system
RUN apk update && apk upgrade

FROM base AS init

COPY ./Docker/node/npm-init.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

FROM base AS local

# Run vite server
CMD ["npm", "run", "dev"]

FROM base AS production

# Build the application
CMD ["npm", "run", "build"]