# Microservice POC - E-Shopping Application

## Description

This is a proof of concept for a microservice-based E-Shopping application. The application is designed to demonstrate the practical use of microservices.

## Features

- Product browsing
- Shopping cart functionality
- Order placement and tracking

## Installation

1. Clone the repository: `git clone <repo_url>`
2. Navigate to each service directory: `cd apps/<service_directory>`
3. Install dependencies: `composer install`
4. Copy .env: `cp .env.example .env`
5. Configure environment settings:
   1. Set up the database connection
   2. Configure other necessary settings
   3. Run the command: `php artisan key:generate`
6. Repeat steps 2 to 5 for each service
7. To run the application:
   1. Navigate to the repository: `cd your/path/microsoft-poc`
   2. Run the command `docker compose up -d`

## Testing

1. Navigate to a service directory and run the command: `php artisan test`
