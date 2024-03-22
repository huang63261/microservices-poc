#!/bin/bash

# navigate to the apps directory
cd apps || exit

# loop through each directory in the apps directory
for service in */ ; do
    echo "Configuring $service"
    cd $service

    # installation steps
    composer install
    cp .env.example .env
    php artisan key:generate

    cd ..
done

echo "All services configured."
