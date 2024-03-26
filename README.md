# Microservice POC - E-Shopping Application

## Description

This is a proof of concept for a microservice-based E-Shopping application. The application is designed to demonstrate the practical use of microservices.

## Features

- Product browsing
- Shopping cart functionality
- Order placement and tracking

## Installation

1. Clone the repository:

   ```shell
      git clone git@github.com:huang63261/microservices-poc.git
   ```

2. Run services setup script:
   1. For `windows`, run the command:

      ```shell
      .\setup_services.ps1
      ```

   2. For `Unix/Linux/macOS`, run the command:

      ```shell
      ./setup_services.sh
      ```

3. To run the application, run the command:

      ```shell
      docker compose up -d
      ```

## Testing

1. Navigate to a service directory and run the command:

   ```shell
   php artisan test
   ```
