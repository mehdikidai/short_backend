
# ziplink Documentation

## Table of Contents
- [Introduction](#introduction)
- [Installation](#installation)
- [Requirements](#requirements)
- [Setup Steps](#setup-steps)
- [User Guide](#user-guide)
- [Account Management](#account-management)
- [Creating Shortened Links](#creating-shortened-links)
- [Viewing Statistics](#viewing-statistics)
- [Generating QR Codes](#generating-qr-codes)
- [Developer Guide](#developer-guide)
- [Project Structure](#project-structure)
- [API Endpoints](#api-endpoints)
- [Database Schema](#database-schema)
- [Tables](#tables)
- [Maintenance](#maintenance)
- [FAQ](#faq)

---

## Introduction

**ziplink** is a robust URL-shortening application that empowers users to:
- Generate and manage shortened URLs.
- Create customizable QR codes.
- Analyze detailed statistics on visits, including location and device type.
- Manage user accounts securely.

This project leverages **Laravel** for the backend and **Vue.js** for the frontend, ensuring scalability and a seamless user experience.

---

## Installation

### Requirements
Ensure you have the following installed:
- **PHP** 8.2 or later.
- **Composer** (Dependency Manager for PHP).
- **Node.js** (for managing Vue.js dependencies).
- A relational database (e.g., MySQL).

### Setup Steps

#### Backend (Laravel)
1. **Clone the repository**:
```bash
git clone https://github.com/mehdikidai/short_backend.git
cd short_backend
```
2. **Install dependencies**:
```bash
composer install
```
3. **Set up the environment**:
   - Copy the example `.env` file:
     ```bash
     cp .env.example .env
     ```
   - Update database credentials and other configurations in the `.env` file.
4. **Generate application key**:
```bash
php artisan key:generate
```
5. **Run migrations and seed data**:
```bash
php artisan migrate --seed
```
6. **Start services**:
```bash
php artisan serve
php artisan queue:work
```

#### WebSocket (Node.js - Express.js/Socket.IO)
1. **Clone the repository**:
```bash
git clone https://github.com/mehdikidai/socket_io.git
cd socket_io
```
2. **Install dependencies**:
```bash
npm install
```
3. **Start the server**:
```bash
npm run dev
```

#### Frontend (Vue.js)
1. **Clone the repository**:
```bash
git clone https://github.com/mehdikidai/short_frontend.git
cd short_frontend
```
2. **Install dependencies**:
```bash
npm install
```
3. **Run the development server**:
```bash
npm run dev
```

---

## User Guide

### Account Management
- **Sign Up**: Register a new account using an email address and password.
- **Log In**: Access your account securely.
- **Reset Password**: Use the "Forgot Password" feature to recover access.

### Creating Shortened Links
1. Input the original URL into the form.
2. Optionally customize the alias for your link.
3. Set visibility preferences (e.g., public or private).

### Viewing Statistics
Gain insights into your shortened URLs:
- View geographic details (country, city, and coordinates).
- Analyze device usage (mobile, desktop, or tablet).
- Check browser types.

### Generating QR Codes
1. Customize QR code colors to match your branding.
2. Download the QR code as an image for sharing.

---

## Developer Guide

### Project Structure

- **Backend**:
  - `/app`: Business logic (models, controllers).
  - `/database`: Migrations and seeders for data management.
  - `/routes`: API and web route definitions.
- **Frontend**:
  - `/src`: Vue.js components and utilities.
  - `/assets`: Static files like images and styles.

### API Endpoints

**Authentication**:
- `POST /api/login` – User login.
- `POST /api/logout` – User logout.
- `POST /api/register` – User registration.
- `POST /api/password/reset` – Reset the user password.
- `POST /api/password/send-reset-code` – Send a reset code to the user’s email.
- `POST /api/email/verify` – Verify the user’s email address.
- `GET|HEAD /sanctum/csrf-cookie` – Retrieve a CSRF token for secure requests.

**User Management**:
- `GET|HEAD /api/user` – Get details of the currently authenticated user.
- `PUT /api/user` – Update user information.
- `PUT /api/user/update-password` – Update the user’s password.
- `DELETE /api/user/account` – Delete the currently authenticated user’s account.
- `GET|HEAD /api/user/users` – Retrieve a list of all users (admin feature).
- `DELETE /api/user/{id}` – Delete a specific user (admin feature).
- `POST /api/upload_photo_profile` – Upload a profile photo.

**URL Management**:
- `GET|HEAD /api/urls/{sort?}` – Retrieve a list of URLs, optionally sorted.
- `GET|HEAD /api/urls/{id}` – Get details of a specific shortened URL.
- `POST /api/urls` – Create a new shortened URL.
- `PUT /api/urls/{id}` – Update a shortened URL.
- `PUT /api/urls/{id}/visual` – Update visual properties of a URL (e.g., metadata or appearance).
- `DELETE /api/urls/{id}` – Delete a shortened URL.
- `PATCH /api/restore_url/{id}` – Restore a previously deleted URL.
- `DELETE /api/force_delete_url/{id}` – Permanently delete a URL.

**Analytics**:
- `GET|HEAD /api/analytics/{filter?}` – Retrieve analytics data based on optional filters.
- `GET|HEAD /api/locations/{filter?}` – Get location-based analytics filtered by specified criteria.

**Search and Filtering**:
- `GET|HEAD /api/search` – Search URLs or related data.
- `GET|HEAD /api/trash` – Retrieve a list of trashed URLs.

**Testing and Utilities**:
- `GET|HEAD /api/test` – Endpoint for testing purposes.
- `GET|HEAD /up` – Check the health/status of the application.
- `GET|HEAD /{code}` – Redirect using the shortened URL code.

### Database Schema

#### Tables

1. **users**: Handles user information (e.g., name, email, password, profile photo).
2. **urls**: Stores original and shortened URLs with metadata.
3. **clicks**: Tracks link clicks with details like:
   - IP address.
   - Country, city, and coordinates.
   - Browser and device type.
4. **roles**: Defines user roles (admin, regular user).
5. **role_user**: Links roles to users (many-to-many relationship).
6. **sessions**: Tracks user session activity.
7. **personal_access_tokens**: Manages API tokens for secure access.

---

## Maintenance
- **Clear Cache**:
```bash
php artisan cache:clear
```
- **Queue Management**:
```bash
php artisan queue:restart
```
- **Database Backups**: Use tools like `mysqldump` or Laravel backups.

---

## FAQ
1. **How do I reset my password?**
   - Use the "Forgot Password" option on the login page.
2. **Can I customize QR codes?**
   - Yes, you can select colors before generating the QR code.
3. **What browsers are supported?**
   - Modern browsers like Chrome, Firefox, Safari, and Edge.
