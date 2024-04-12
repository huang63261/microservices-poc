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

2. Copy environment file:

   ```shell
      cp .env.example .env
   ```

3. Install services by using the utility container `workspace`:

   1. Entering `workspace` container

   ```shell
      docker compose run -it --rm workspace
   ```

    **For `windows` user, you may encounter a problem caused by the Line Feed in shell script. If you use vscode as your editor, please search the text "CRLF" at right bottom, and change it into LF**

   2. Run setup shell script:

   ```shell
      sh setup-services.sh
   ```

   3. Exit `workspace` container after finishing shell script.


4. To run the application, run the command:

      ```shell
      docker compose up -d
      ```

## Testing

1. Navigate to a service directory and run the command:

   ```shell
   php artisan test
   ```
