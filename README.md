# Project Setup with Docker

This project is set up to run using Docker. Below are the instructions to get the project running locally with Docker, including database migration and seeding.

## Prerequisites

Ensure that you have Docker and Docker Compose installed on your machine. You can download them from:

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Getting Started

Follow the steps below to get your local development environment set up:

### 1. Build, Start, and Set Up the Project

To build and start the project with Docker, and run the database migrations and seeding, follow these steps:

1. **Build and start the Docker containers**:

    Run the following command to build the Docker containers and start the project:

    ```bash
    docker compose up --build
    ```

    This will pull the necessary Docker images and start the containers for your application, including PHP, MySQL, and any other services configured in the `docker-compose.yml`.

2. **Access the PHP container shell**:

    Once the containers are running, access the PHP container shell to run migrations and seed the database:

    ```bash
    docker compose exec php bash
    ```

3. **Run Database Migrations and Seeding**:

    Inside the PHP container, run the following command to reset the database and seed it with the initial data:

    ```bash
    php artisan migrate:fresh --seed
    ```

    This command will drop all tables, re-run the migrations, and seed the database.

4. **Access the Application**:

    You can access it through tools like Postman or other client. The application can be accessed at the following endpoints:

    ```
    http://localhost:8080/api/comments
    ```