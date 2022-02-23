#!/bin/bash
set -eo pipefail

# https://github.com/nginxinc/docker-nginx/blob/master/stable/debian/docker-entrypoint.sh
# https://github.com/MariaDB/mariadb-docker/blob/master/docker-entrypoint.sh


log() {
	local type="$1"; shift
	printf '%s [%s] [Entrypoint]: %s\n' "$(date --rfc-3339=seconds)" "$type" "$*"
}

note() {
	log Note "$@"
}

warn() {
	log Warn "$@" >&2
}

error() {
	log ERROR "$@" >&2
	exit 1
}

docker_process_init_files() {
	if find "/docker-entrypoint.d/" -mindepth 1 -maxdepth 1 -type f -print -quit 2>/dev/null | read v; then
		note "$0: /docker-entrypoint.d/ is not empty, will attempt to perform configuration"

		find "/docker-entrypoint.d/" -follow -type f -print | sort -V | while read -r f; do
			case "$f" in
				*.sh)
					if [ -x "$f" ]; then
						note "$0: Launching $f";
						"$f"
					else
						note "$0: Sourcing $f";
						. "$f"
					fi
					;;
				*) warn "$0: Ignoring $f";;
			esac
		done

		note "$0: Configuration complete; ready for start up"
	else
		note "$0: No files found in /docker-entrypoint.d/, skipping configuration"
	fi
}

docker_process_init_files

exec "$@"
