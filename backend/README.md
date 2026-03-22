# MacFit — Backend API

A RESTful API powering the MacFit Gym Management System. Built with **Laravel 12** and **MySQL**, it handles gym branch management, workout categories, and dynamic membership bundles for multi-location fitness centres.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 |
| Language | PHP 8.5 |
| Database | MySQL |
| Date/Time | Carbon |
| Dependency Manager | Composer |

---

## Application Flow

```
Client (Vue.js Frontend)
        │
        ▼
  Laravel Router  (/api/*)
        │
        ▼
  Controllers  (handle request logic)
        │
        ▼
  Eloquent Models  (interact with DB)
        │
        ▼
  MySQL Database
```

Requests arrive at `/api/*` endpoints, are routed to the appropriate controller, and data is retrieved or persisted via Eloquent ORM models. The frontend communicates exclusively through these API endpoints.

---

## Current Capabilities

### 🏋️ Gym Branch Management
- Create and manage multiple gym locations
- Store branch-specific data with structured naming (e.g. `GymName-WorkoutArea`)

### 🗂️ Workout Categories
Organise workouts by type including:
- Strength, Cardio, HIIT, Yoga, and more

### 📦 Membership Bundles
Create flexible membership packages with:
- Configurable session durations (in hours)
- Linked gym locations
- Daily start times and schedule-based logic
- Extendable structure for subscriptions

---

## API Endpoints

### Bundles

| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/saveBundle` | Create a new membership bundle |
| `GET` | `/api/getBundles` | List all available bundles |
| `GET` | `/api/getBundle/{id}` | Get details of a specific bundle |
| `POST` | `/api/updateBundles/{id}` | Update an existing bundle |
| `DELETE` | `/api/deleteBundle/{id}` | Delete a bundle |

> Base URL in development: `http://127.0.0.1:8000`

---

## Getting Started

### Prerequisites

Make sure the following are installed on your machine:
- [PHP](https://www.php.net/downloads) (8.1+)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/)

### 1. Clone the repository

```bash
git clone https://github.com/alexamita/MacFitness.git
cd MacFitness/backend
```

### 2. Install dependencies

```bash
composer install
```

### 3. Set up environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure your database

Open `.env` and update the following:

```env
DB_DATABASE=macfit_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Then create the database in MySQL:

```sql
CREATE DATABASE macfit_db;
```

### 5. Run migrations and seed data

```bash
php artisan migrate:fresh --seed
```

This creates all tables and populates them with gyms, roles, categories, equipment, and bundles.

### 6. Start the development server

```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000/api/`

---

## Project Structure

```
backend/
├── app/
│   ├── Http/Controllers/   # Request handlers
│   └── Models/             # Eloquent models
├── database/
│   ├── migrations/         # Table definitions
│   └── seeders/            # Sample data
├── routes/
│   └── api.php             # All API route definitions
└── .env.example            # Environment variable template
```

---

## Related

- [MacFitness Frontend →](../frontend/README.md)
- [Full Repository →](https://github.com/alexamita/MacFitness)
