FROM node:lts-slim

LABEL author="Rene Bentes Pinto <github.com/renebentes>"

ENV DEBIAN_FRONTEND noninteractive

WORKDIR /usr/src/joomlagov

COPY ./package*.json ./gulpfile.babel.js ./.babelrc ./

# Installing latest npm, gulp-cli and dependencies
RUN set -eux; \
    \
    npm i -g gulp-cli --silent; \
    npm i --silent
