#!/bin/bash
set -e

scripts_folder=/usr/local/bin/scripts

bash "$scripts_folder"/wait_setup.sh

cd /var/www/html
php artisan schedule:work
