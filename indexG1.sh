#!/bin/bash
#Index networks. This is a temporary approach.

php artisan index:all --network=1 &
php artisan index:all --network=2 &
php artisan index:all --network=3 &
php artisan index:all --network=4 &
php artisan index:all --network=5 &
php artisan index:all --network=6 &
