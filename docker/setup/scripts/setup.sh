#!/bin/bash
set -e

rm -f /var/www/html/.setup_done

scripts_folder=/usr/local/bin/scripts

bash "$scripts_folder"/setups/npm.sh
bash "$scripts_folder"/setups/composer.sh

touch /var/www/html/.setup_done
