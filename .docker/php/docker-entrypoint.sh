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
				*configuration.php)
					process_envs $f;
					move_file "$f.tmp" configuration.php;
					populate_sample;
					remove_folder installation;
					;;
				*) warn "$0: Ignoring $f";;
			esac
		done

		note "$0: Configuration complete; ready for start up"
	else
		note "$0: No files found in /docker-entrypoint.d/, skipping configuration"
	fi
}

process_envs() {
	local defined_envs

	if [ -z "${JOOMLA_DB_HOST}" -o -z "${JOOMLA_DB_USER}" -o -z "${JOOMLA_DB_PASSWORD}" ]; then
		error $'Unable to init default configuration.\n\tYou need to specify JOOMLA_DB_HOST, JOOMLA_DB_USER, JOOMLA_DB_PASSWORD environment variables.'
	fi

	defined_envs=$(printf '${%s} ' $(env | cut -d= -f1))

	note "$0: Running envsubst on $1"
	envsubst "$defined_envs" < "$1" > "$1.tmp"
}

move_file() {
	[ -f $1 ] || return 0
	mv $1 $2
}

remove_folder() {
	[ -d $1 ] || return 0
	rm -rf $1
}

populate_sample() {
	local sample="$(pwd)/installation/sql/mysql/sample_padrao_egov.sql"
	note "$0: Populate database sample for $(basename $sample)"

	[ -f $sample ] || return 0

	populate-sample.php $sample
}

get_env_vars() {
    local env_var="${1}"
    local env_var_file="${env_var}_FILE"

    if [[ -n "${!env_var_file}" ]]; then
        export "${env_var}"="$(cat "${!env_var_file}")"
    fi
}

get_env_vars JOOMLA_DB_HOST
get_env_vars JOOMLA_DB_USER
get_env_vars JOOMLA_DB_PASSWORD
get_env_vars JOOMLA_DB_NAME
get_env_vars JOOMLA_DB_PREFIX
get_env_vars JOOMLA_ROOT_USER
get_env_vars JOOMLA_ROOT_PASSWORD

docker_process_init_files

exec "$@"
