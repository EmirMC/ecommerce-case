# Symfony E-Commerce Project Case

## Getting Started

1. `git clone https://github.com/EmirMC/ecommerce-case.git`
2. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
3. Run `make build` to build and up image
4. Running `http://localhost:8080`
5. Open [Open Postman JSON Documentation](https://www.getpostman.com/collections/006581f8d2d672913022)
6. Run `make down` to stop the Docker containers.

# Postman Document Introduction

1. Authentication
2. POST Order Create
3. GET Orders
4. Try Get Order By Order Code
5. PUT Order Update
6. PUT Order ShippingDate Update

## Authentication

Request: POST Login
Endpoint: /api/login_check
Current Users:

### First User

```json
{
    "email": "demo0@demo.com",
    "password": "demo123"
}
```

### Second User

```json
{
    "email": "demo1@demo.com",
    "password": "demo123"
}
```

### Thirth User

```json
{
    "email": "demo2@demo.com",
    "password": "demo123"
}
```

1. Copy to token code
2. Open Variables and paste token in accessToken input
   Token expiry time 1 hours
