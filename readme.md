## Rick and Morty Application Importer

This application is designed to import all data from the Rick and Morty application.

## Table of Contents
- [Docker Image](#docker-image)
- [Configuration](#configuration)
- [Data Import](#data-import)
- [Web Page](#web-page)
- [Unit Tests](#unit-tests)

---

### Docker Image

The application contains a `docker-compose.yml` file located in the `docker` folder.  
Enter `docker` folder and run the following command:

```bash
sudo docker compose up -d --build
```

---

### Configuration

- Navigate to the `api` folder, create a `.env` file, and add the database connection configuration:
  ```dotenv
  DATABASE_URL="postgresql://user:password@postgres-container:5432/app_db"
  ```
- `user` and `password` are appropriate credentials.

---

### Data Import

- Enter the `php-container` using the following command:

```bash
docker exec -it php-container bash
```

- Use the CLI command to migrate data from the Rick and Morty API:
```bash
php bin/console app:import-character
```

---

### Web Page

- Open a browser and go to the following URL:
  [http://localhost:5173/characters](http://localhost:5173/characters)

---

### Unit Tests

- Unit tests have been created for the application.
- To run the tests, enter the `php-container`:
  ```bash
  docker exec -it php-container bash
  ```

- Inside the container, execute the following command:
  ```
  vendor/bin/phpunit
  ```

---
