#!/bin/bash
set -euo pipefail

[ -f .env ] || { echo ".env not found"; exit 1; }

set -a
source <(grep -v '^#' .env | sed '/^$/d')
set +a

: "${STORAGE_NGINX:?}"
: "${STORAGE_FPM:?}"
: "${STORAGE_SQLITE:?}"
: "${STORAGE_APP:?}"

DIRS=(
  "$STORAGE_NGINX"
  "$STORAGE_FPM"
  "$STORAGE_SQLITE"
)

for dir in "${DIRS[@]}"; do
  mkdir -p "$dir"
done

LOG_FILES=(
  "$STORAGE_FPM/logs/fpm-php.www.log"
  "$STORAGE_NGINX/logs/access.log"
  "$STORAGE_NGINX/logs/error.log"
)

for file in "${LOG_FILES[@]}"; do
  mkdir -p "$(dirname "$file")"
  touch "$file"
  chmod 664 "$file"
done

DOCKER_ARGS=()

while getopts 'bf' opt; do
  case "$opt" in
    b) DOCKER_ARGS+=(--build) ;;
    f) DOCKER_ARGS+=(--force-recreate) ;;
  esac
done

echo "docker compose up -d ${DOCKER_ARGS[*]}"

docker compose \
  --env-file .env \
  -f docker-compose.yml \
  up -d "${DOCKER_ARGS[@]}"
