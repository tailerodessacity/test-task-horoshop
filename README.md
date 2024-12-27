# Symfony Docker 🐳⚡

A **Docker**-based installer and runtime for the powerful [Symfony](https://symfony.com) web framework, featuring [FrankenPHP](https://frankenphp.dev) and [Caddy](https://caddyserver.com/) for seamless development environments!

![CI](https://github.com/dunglas/symfony-docker/workflows/CI/badge.svg)

## 🚀 Getting Started

Follow these simple steps to get your Symfony project up and running in a Docker container:

### 1. Install Docker Compose

If you haven’t installed Docker Compose yet, follow the instructions to install the latest version (v2.10+):

- [Install Docker Compose](https://docs.docker.com/compose/install/)

### 2. Build Fresh Docker Images

Run the following command to build fresh images for the Symfony application:

```bash
docker compose build --no-cache
```

### 3. Start Your Symfony Project

Set up and start a fresh Symfony project by running:

```bash
docker compose up --pull always -d --wait
```

### 4. Access the Project

Open your web browser and visit `https://localhost`. You’ll need to accept the auto-generated TLS certificate. For more details on handling this, check [this StackOverflow post](https://stackoverflow.com/a/15076602/1352334).

### 5. Stop the Docker Containers

When you’re done, stop the Docker containers with:

```bash
docker compose down --remove-orphans
```

---

## 🧑‍💻 User Management API

### Description

The **User Management API** is a RESTful API built with Symfony, designed for managing users. It supports operations like creating, reading, updating, and deleting users. It also features secure authentication via Bearer tokens.

---

## 🛠️ Technologies

- **Language**: PHP 8.x
- **Framework**: Symfony 6.x
- **Database**: PostgreSQL
- **ORM**: Doctrine
- **Authentication**: Bearer Token
- **Validation**: Symfony Validator
- **Events**: Symfony Event Dispatcher
- **Logging**: PSR-3 Logger

---

## 📝 Installation

### 1. Clone the Repository

Clone the repository and navigate into the project directory:

```bash
git clone https://github.com/tailerodessacity/test-task-horoshop
cd test-task-horoshop
```

### 2. Install Dependencies

Install the required dependencies using Composer:

```bash
composer install
```

### 3. Configure Environment Variables

Copy the `.env` file and configure your database connection:

```bash
cp .env .env.dev
```

Then, edit `.env.dev` and update the following settings:

```env
DATABASE_URL="postgresql://app-php:!ChangeMe!@app-php:5432/app?serverVersion=16&charset=utf8"
APP_ENV=dev
APP_SECRET=your_secret_key
```

### 4. Run Migrations

Run the migrations to create the database schema:

```bash
php bin/console doctrine:migrations:migrate
```

---

## 🚀 Usage

### API Endpoint

The API is accessible at: `http://localhost/v1/api/users`

---

## 🔐 Authentication

The API uses **Bearer Token** for authentication. When a user is created, a unique `apiToken` is generated, which must be included in the `Authorization` header for protected routes.

**Example Header**:

```makefile
Authorization: Bearer your_api_token
```
---

## 🔐 Run unit tests

`./vendor/bin/simple-phpunit`

## 📜 Routes

### 1. **Get List of Users**

- **URL**: `/v1/api/users`
- **Method**: `GET`
- **Access**: `ROLE_USER` and above

**Example Request**:

```bash
curl -H "Authorization: Bearer your_api_token" http://localhost/v1/api/users
```

### 2. **Create a New User**

- **URL**: `/v1/api/users`
- **Method**: `POST`
- **Access**: `ROLE_ADMIN`

**Example Request**:

```bash
curl -X POST -H "Content-Type: application/json" -H "Authorization: Bearer admin_api_token" \
-d '{"login": "newuser", "phone": "12345678", "password": "securepassword"}' \
http://localhost:8000/v1/api/users
```

### 3. **Get User Information**

- **URL**: `/v1/api/users/{id}`
- **Method**: `GET`
- **Access**: `ROLE_USER` and above

**Example Request**:

```bash
curl -H "Authorization: Bearer your_api_token" http://localhost/v1/api/users/1
```

### 4. **Update a User**

- **URL**: `/v1/api/users/{id}`
- **Method**: `PUT`
- **Access**: `ROLE_USER` and above

**Example Request**:

```bash
curl -X PUT -H "Content-Type: application/json" -H "Authorization: Bearer your_api_token" \
-d '{"login": "updateduser", "phone": "87654321", "password": "newpassword"}' \
http://localhost/v1/api/users/1
```

### 5. **Delete a User**

- **URL**: `/v1/api/users/{id}`
- **Method**: `DELETE`
- **Access**: `ROLE_ADMIN`

**Example Request**:

```bash
curl -X DELETE -H "Authorization: Bearer admin_api_token" http://localhost/v1/api/users/1
```

---

## 🔧 Events and Subscribers

The API utilizes events to handle user-related actions:

- `user.created` — Triggered when a user is created.
- `user.updated` — Triggered when a user is updated.
- `user.deleted` — Triggered when a user is deleted.

The **UserEventSubscriber** listens to these events and logs the corresponding information.

---

## ⚠️ Exception Handling

The **ExceptionListener** class handles all exceptions and returns standardized JSON responses with error information.

---

## 🔒 Security

Security settings are configured in `config/packages/security.yaml`. The key components are:

- **User Providers**: Uses the User entity for authentication.
- **Password Hashing**: Automatically selects the optimal hashing algorithm.
- **Firewalls**: Protects API routes using Bearer Tokens and standard login forms for the main routes.
- **Role Hierarchy**: `ROLE_ADMIN` inherits `ROLE_USER` permissions.
- **Access Control**: Routes are restricted based on roles and HTTP methods.
