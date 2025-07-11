#!/usr/bin/env bash

UNAMEOUT="$(uname -s)"

# Verify operating system is supported...
case "${UNAMEOUT}" in
    Linux*)             MACHINE=linux;;
    Darwin*)            MACHINE=mac;;
    *)                  MACHINE="UNKNOWN"
esac

if [ "$MACHINE" == "UNKNOWN" ]; then
    echo "Unsupported operating system [$(uname -s)]. Laravel Sail supports macOS, Linux, and Windows (WSL2)." >&2
    exit 1
fi

# Define environment variables...
export APP_PORT=${APP_PORT:-80}
export APP_SERVICE=${APP_SERVICE:-"laravel.test"}
export DB_PORT=${DB_PORT:-3306}
export WWWUSER=${WWWUSER:-$UID}
export WWWGROUP=${WWWGROUP:-$(id -g)}

export SAIL_FILES=${SAIL_FILES:-""}
export SAIL_SHARE_DASHBOARD=${SAIL_SHARE_DASHBOARD:-4040}
export SAIL_SHARE_SERVER_HOST=${SAIL_SHARE_SERVER_HOST:-"laravel-sail.site"}
export SAIL_SHARE_SERVER_PORT=${SAIL_SHARE_SERVER_PORT:-8080}
export SAIL_SHARE_SUBDOMAIN=${SAIL_SHARE_SUBDOMAIN:-""}

# Function that outputs Sail is not running...
function sail_is_not_running {
    echo -e "\033[1m\033[33mSail is not running.\033[0m" >&2
    echo "" >&2
    echo -e "\033[1m\033[33mYou may Sail using the following commands:\033[0m './sail up' or './sail up -d'" >&2
    exit 1
}

# Define Docker Compose command prefix...
if [ -n "$SAIL_FILES" ]; then
    # Convert SAIL_FILES to an array...
    IFS=':' read -ra SAIL_FILES_ARRAY <<< "$SAIL_FILES"

    for FILE in "${SAIL_FILES_ARRAY[@]}"; do
        if [ -f "$FILE" ]; then
            DOCKER_COMPOSE="${DOCKER_COMPOSE} -f $FILE"
        else
            echo -e "\033[1m\033[31mUnable to find Docker Compose file: '${FILE}'\033[0m" >&2
            exit 1
        fi
    done
else
    DOCKER_COMPOSE="docker-compose"
fi

EXEC="yes"

if [ -z "$SAIL_SKIP_CHECKS" ]; then
    # Ensure that Docker is running...
    if ! docker info > /dev/null 2>&1; then
        echo -e "\033[1m\033[31mDocker is not running.\033[0m" >&2
        exit 1
    fi

    # Determine if Sail is currently up...
    if "${DOCKER_COMPOSE}" ps "$APP_SERVICE" 2>&1 | grep 'Exit\|exited'; then
        echo -e "\033[1m\033[33mShutting down old Sail processes...\033[0m" >&2
        "${DOCKER_COMPOSE}" down > /dev/null 2>&1
        EXEC="no"
    elif [ -z "$("${DOCKER_COMPOSE}" ps -q)" ]; then
        EXEC="no"
    fi
fi

ARGS=()

# Proxy PHP commands to the "php" binary on the application container...
if [ "$1" == "php" ]; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" "php" "$@")
    else
        sail_is_not_running
    fi

# Proxy vendor binary commands on the application container...
elif [ "$1" == "bin" ]; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" ./vendor/bin/"$@")
    else
        sail_is_not_running
    fi

# Proxy Composer commands to the "composer" binary on the application container...
elif [ "$1" == "composer" ]; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" "composer" "$@")
    else
        sail_is_not_running
    fi

# Proxy Artisan commands to the "artisan" binary on the application container...
elif [ "$1" == "artisan" ] || [ "$1" == "art" ]; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" php artisan "$@")
    else
        sail_is_not_running
    fi

# Proxy Node commands to the "node" binary on the application container...
elif [ "$1" == "node" ]; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" node "$@")
    else
        sail_is_not_running
    fi

# Proxy NPM commands to the "npm" binary on the application container...
elif [ "$1" == "npm" ]; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" npm "$@")
    else
        sail_is_not_running
    fi

# Proxy NPX commands to the "npx" binary on the application container...
elif [ "$1" == "npx" ]; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" npx "$@")
    else
        sail_is_not_running
    fi

# Proxy YARN commands to the "yarn" binary on the application container...
elif [ "$1" == "yarn" ]; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" yarn "$@")
    else
        sail_is_not_running
    fi

# Initiate a Laravel Tinker session within the application container...
elif [ "$1" == "tinker" ] ; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" php artisan tinker)
    else
        sail_is_not_running
    fi

# Proxy the "test" command to the "php artisan test" command...
elif [ "$1" == "test" ]; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" php artisan test "$@")
    else
        sail_is_not_running
    fi

# Proxy the "phpunit" command to "php vendor/bin/phpunit"...
elif [ "$1" == "phpunit" ]; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" php vendor/bin/phpunit "$@")
    else
        sail_is_not_running
    fi

# Proxy the "pint" command to "php vendor/bin/pint"...
elif [ "$1" == "pint" ]; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" php vendor/bin/pint "$@")
    else
        sail_is_not_running
    fi

# Initiate a Bash shell within the application container...
elif [ "$1" == "shell" ] || [ "$1" == "bash" ]; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" bash "$@")
    else
        sail_is_not_running
    fi

# Initiate a root user Bash shell within the application container...
elif [ "$1" == "root-shell" ] || [ "$1" == "root-bash" ]; then
    shift 1

    if [ "$EXEC" == "yes" ]; then
        ARGS+=(exec)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" bash "$@")
    else
        sail_is_not_running
    fi

# Pass unknown commands to the "docker-compose" binary...
else
    ARGS+=("$@")
fi

# Run Docker Compose with the defined arguments...
"${DOCKER_COMPOSE}" "${ARGS[@]}"