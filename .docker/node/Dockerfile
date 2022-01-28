FROM node:lts-slim

LABEL author="Rene Bentes Pinto <github.com/renebentes>"

ENV DEBIAN_FRONTEND noninteractive

ENV NPM_CONFIG_PREFIX=/home/node/.npm-global

ENV PATH=$PATH:/home/node/.npm-global/bin

WORKDIR /joomlagov

COPY --chown=node:node ./package*.json ./

RUN set -eux; \
    \
    npm i -g gulp-cli; \
    npm i; \
    chown -R node:node node_modules

USER node

CMD [ "bash" ]