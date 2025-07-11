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

# Ensure that Docker is running...
if ! docker info > /dev/null 2>&1; then
    echo -e "${WHITE}Docker is not running.${NC}" >&2

    exit 1
fi

# Determine if Sail is currently up...
if docker compose ps "$APP_SERVICE" 2>&1 | grep 'Exit\|exited'; then
    echo -e "${WHITE}Shutting down old Sail processes...${NC}" >&2

    docker compose down > /dev/null 2>&1

    EXEC="no"
elif [ -z "$(docker compose ps -q)" ]; then
    EXEC="no"
else
    EXEC="yes"
fi

# Define environment variables...
export APP_PORT=${APP_PORT:-80}
export APP_SERVICE=${APP_SERVICE:-"laravel.test"}
export DB_PORT=${DB_PORT:-5432}
export WWWUSER=${WWWUSER:-$UID}
export WWWGROUP=${WWWGROUP:-$(id -g)}
export SAIL_FILES=${SAIL_FILES:-""}
export SAIL_SHARE_DASHBOARD=${SAIL_SHARE_DASHBOARD:-4040}
export SAIL_SHARE_SERVER_HOST=${SAIL_SHARE_SERVER_HOST:-"laravel-sail.site"}
export SAIL_SHARE_SERVER_PORT=${SAIL_SHARE_SERVER_PORT:-8080}
export SAIL_SHARE_SUBDOMAIN=${SAIL_SHARE_SUBDOMAIN:-""}
export SAIL_SHARE_DOMAIN=${SAIL_SHARE_DOMAIN:-"$SAIL_SHARE_SERVER_HOST"}
export SAIL_SHARE_SERVER=${SAIL_SHARE_SERVER:-""}

# Function that outputs Sail is not running...
function sail_is_not_running {
    echo -e "${WHITE}Sail is not running.${NC}" >&2
    echo "" >&2
    echo -e "${WHITE}You may Sail using the following commands:${NC} './sail up' or './sail up -d'" >&2

    exit 1
}

# Define Docker Compose command prefix...
docker compose &> /dev/null
if [ $? == 0 ]; then
    DOCKER_COMPOSE=(docker compose)
else
    DOCKER_COMPOSE=(docker-compose)
fi

if [ -n "$SAIL_FILES" ]; then
    # Convert SAIL_FILES to an array...
    IFS=':' read -ra SAIL_FILES <<< "$SAIL_FILES"

    for FILE in "${SAIL_FILES[@]}"; do
        if [ -f "$FILE" ]; then
            DOCKER_COMPOSE+=(-f "$FILE")
        else
            echo -e "${WHITE}Unable to find Docker Compose file: '${FILE}'${NC}" >&2

            exit 1
        fi
    done
fi

EXEC="yes"

if [ -z "$SAIL_SKIP_CHECKS" ]; then
    # Ensure that Docker is running...
    if ! docker info > /dev/null 2>&1; then
        echo -e "${WHITE}Docker is not running.${NC}" >&2

        exit 1
    fi

    # Determine if Sail is currently up...
    if "${DOCKER_COMPOSE[@]}" ps "$APP_SERVICE" 2>&1 | grep 'Exit\|exited'; then
        echo -e "${WHITE}Shutting down old Sail processes...${NC}" >&2

        "${DOCKER_COMPOSE[@]}" down > /dev/null 2>&1

        EXEC="no"
    elif [ -z "$("${DOCKER_COMPOSE[@]}" ps -q)" ]; then
        EXEC="no"
    fi
fi

ARGS=()

# Proxy the "help" command...
if [ $# -gt 0 ]; then
    if [ "$1" == "help" ] || [ "$1" == "-h" ] || [ "$1" == "-help" ] || [ "$1" == "--help" ]; then
        ARGS+=(exec -u sail)
        [ ! -t 0 ] && ARGS+=(-T)
        ARGS+=("$APP_SERVICE" bash -c "cd /var/www/html && php artisan --help")
    # First, if the command is "artisan" or "art", proxy to "artisan"
    # inside a new container...
    elif [ "$1" == "artisan" ] || [ "$1" == "art" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" php artisan "$@")
        else
            sail_is_not_running
        fi

    # If "composer" is used, pass through to "composer"
    # inside a new container...
    elif [ "$1" == "composer" ] || [ "$1" == "comp" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" composer "$@")
        else
            sail_is_not_running
        fi

    # If "php" is used, pass through to "php"
    # inside a new container...
    elif [ "$1" == "php" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" php "$@")
        else
            sail_is_not_running
        fi

    # If "bin" is used, pass through to "bin"
    # inside a new container...
    elif [ "$1" == "bin" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" ./vendor/bin/"$@")
        else
            sail_is_not_running
        fi

    # If "npm" is used, pass through to "npm"
    # inside a new container...
    elif [ "$1" == "npm" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" npm "$@")
        else
            sail_is_not_running
        fi

    # If "npx" is used, pass through to "npx"
    # inside a new container...
    elif [ "$1" == "npx" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" npx "$@")
        else
            sail_is_not_running
        fi

    # If "pnpm" is used, pass through to "pnpm"
    # inside a new container...
    elif [ "$1" == "pnpm" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" pnpm "$@")
        else
            sail_is_not_running
        fi

    # If "pnpx" is used, pass through to "pnpx"
    # inside a new container...
    elif [ "$1" == "pnpx" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" pnpx "$@")
        else
            sail_is_not_running
        fi

    # If "bun" is used, pass through to "bun"
    # inside a new container...
    elif [ "$1" == "bun" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" bun "$@")
        else
            sail_is_not_running
        fi

    # If "bunx" is used, pass through to "bunx"
    # inside a new container...
    elif [ "$1" == "bunx" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" bunx "$@")
        else
            sail_is_not_running
        fi

    # If "psql" is used, pass through to "psql" inside a new container...
    elif [ "$1" == "psql" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=(pgsql bash -c)
            ARGS+=("PGPASSWORD=\${PGPASSWORD} psql -U \${POSTGRES_USER} \${POSTGRES_DB}")
        else
            sail_is_not_running
        fi

    # If "bash" is used, pass through to "bash"
    # inside a new container...
    elif [ "$1" == "bash" ] || [ "$1" == "shell" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" bash "$@")
        else
            sail_is_not_running
        fi

    # If "test" is used, run unit tests
    # inside a new container...
    elif [ "$1" == "test" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" php artisan test "$@")
        else
            sail_is_not_running
        fi

    # If "pest" is used, run pest
    # inside a new container...
    elif [ "$1" == "pest" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" ./vendor/bin/pest "$@")
        else
            sail_is_not_running
        fi

    # If "pint" is used, run pint
    # inside a new container...
    elif [ "$1" == "pint" ]; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" ./vendor/bin/pint "$@")
        else
            sail_is_not_running
        fi

    # If "tinker" is used, pass through to "tinker"
    # inside a new container...
    elif [ "$1" == "tinker" ] ; then
        shift 1

        if [ "$EXEC" == "yes" ]; then
            ARGS+=(exec -u sail)
            [ ! -t 0 ] && ARGS+=(-T)
            ARGS+=("$APP_SERVICE" php artisan tinker)
        else
            sail_is_not_running
        fi

    # Pass unknown commands to the "docker compose" binary...
    else
        ARGS+=("$@")
    fi
else
    # Pass unknown commands to the "docker compose" binary...
    ARGS+=("$@")
fi

# Run Docker Compose with the defined arguments...
"${DOCKER_COMPOSE[@]}" "${ARGS[@]}"