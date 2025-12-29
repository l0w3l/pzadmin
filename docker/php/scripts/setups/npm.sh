#!/bin/bash

source /root/.bashrc

cd /var/www/html && npm install
cd /var/www/html && npm run build
