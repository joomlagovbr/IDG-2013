FROM node:slim

LABEL author="Rene Bentes Pinto <github.com/renebentes>"
LABEL name=joomlagov/gulp
LABEL version=0.1.0

WORKDIR /usr/src/joomlagov

# Installing latest npm, gulp-cli and dependencies
RUN npm i -g npm@latest gulp-cli; \
	npm i

CMD [ "bash" ]
