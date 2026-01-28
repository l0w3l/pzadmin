#!/bin/bash

source /root/.bashrc

cd /var/www/html && php artisan reverb:start --port 8080
