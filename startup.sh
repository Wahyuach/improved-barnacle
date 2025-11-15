#!/bin/bash

# 1. Perbaiki konfigurasi Nginx untuk menunjuk ke folder /public
sed -i "s|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g" /etc/nginx/sites-available/default

# 2. Jalankan server Nginx
nginx -g daemon off;