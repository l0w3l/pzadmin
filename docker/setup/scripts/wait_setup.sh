#!/bin/bash
set -e

sleep 1

while [ ! -f /var/www/html/.setup_done ]; do
  echo "Waiting for setup..."
  sleep 2
done

