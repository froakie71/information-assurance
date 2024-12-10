# Information Assurance Project

## Overview
This repository contains materials and resources related to Information Assurance, focusing on security principles, best practices, and implementations. The project consists of three main components: a Laravel backend, Next.js frontend, and a mobile application.

## Table of Contents
- [Prerequisites](#prerequisites)
- [Installation](#installation)
  - [Backend Setup](#backend-setup)
  - [Frontend Setup](#frontend-setup)
  - [Mobile Setup](#mobile-setup)
- [Running the Project](#running-the-project)
- [Database Setup](#database-setup)
- [Project Structure](#project-structure)
- [Contributing](#contributing)
- [License](#license)

## Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js 16.x or higher
- npm or yarn
- MySQL or PostgreSQL
- Git

## Installation

### Backend Setup
1. Navigate to the backend directory:
```bash
cd backend
```

2. Install PHP dependencies:
```bash
composer install
```

3. Set up environment file:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database in the `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run database migrations and seeders:
```bash
php artisan migrate
php artisan db:seed
```

### Frontend Setup
1. Navigate to the frontend directory:
```bash
cd frontend
```

2. Install Node.js dependencies:
```bash
npm install
# or
yarn install
```

3. Create a `.env.local` file and configure environment variables:
```env
NEXT_PUBLIC_API_URL=http://localhost:8000
```

### Mobile Setup
Mobile setup instructions will be added as the mobile component develops.

## Running the Project

### Start Backend Server
```bash
cd backend
php artisan serve
```
The backend will be available at `http://localhost:8000`

### Start Frontend Development Server
```bash
cd frontend
npm run dev
# or
yarn dev
```
The frontend will be available at `http://localhost:3000`

## Database Setup
1. Create a new database for the project
2. Run migrations to set up the database schema:
```bash
cd backend
php artisan migrate
```

3. Seed the database with initial data:
```bash
php artisan db:seed
```

## Project Structure
```
information-assurance/
├── backend/          # Laravel PHP backend
├── frontend/         # Next.js frontend
├── mobile/          # Mobile application
└── README.md
```

## Contributing
1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License
This project is licensed under the MIT License - see the LICENSE file for details.

---
**Note:** Make sure all services (database, backend server, etc.) are running before starting the frontend application.
