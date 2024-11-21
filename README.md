
# ziplink Documentation

## Table of Contents
- [ziplink Documentation](#ziplink-documentation)
	- [Table of Contents](#table-of-contents)
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
		- [Service Setup](#service-setup)
	- [Database Schema](#database-schema)
		- [Tables](#tables)
	- [Maintenance](#maintenance)
	- [FAQ](#faq)
	- [Contributions](#contributions)

---

## Introduction
**ziplink** is a powerful URL-shortening application that allows users to:
- Generate and manage shortened links.
- Create customizable QR codes.
- View detailed statistics on visits, including location and device type.
- Manage user accounts.

The project is built using **Laravel** for the backend and **Vue.js** for the frontend.

---

## Installation

### Requirements
- **PHP** 8.2 or later.
- **Composer**.
- **Node.js** (for Vue.js).
- A database (e.g., MySQL).

### Setup Steps Backend - laravel
1. **Clone the repository**:
   ```bash
   git clone https://github.com/mehdikidai/short_backend.git
   ```

2. **Install dependencies**:
   - For Laravel:
     ```bash
     composer install
     ```

3. **Configure the environment**:
   - Copy the example `.env` file and set the required variables:
     ```bash
     cp .env.example .env
     ```

4. **Generate the application key**:
   ```bash
   php artisan key:generate
   ```

5. **Run migrations and seed the database**:
   ```bash
   php artisan migrate --seed
   ```

6. **Run the development servers**:
   - For Laravel:
     ```bash
     php artisan serve
     ```
     ```bash
     php artisan queue:work
     ```

### Setup Steps Frontend - vue js
1. **Clone the repository**:
   ```bash
   git clone https://github.com/mehdikidai/short_frontend.git
   ```

2. **Install dependencies**:
   - Install dependencie:
     ```bash
     npm install
     ```
3. **Run the development servers**:
   - Run the development servers:
     ```bash
     npm run dev
     ```

---

## User Guide

### Account Management
- **Sign Up**: Create an account using an email address.
- **Log In**: Access your account using your email and password.
- **Reset Password**: Recover access using the password reset feature.

### Creating Shortened Links
- Enter the original URL in the input field.
- Optionally customize the link.
- Set the link's visibility.

### Viewing Statistics
- View details of link visits, such as:
  - Country, city, and coordinates.
  - Device type (mobile/desktop).
  - Browser information.

### Generating QR Codes
- Customize QR code colors.
- Download the QR code as an image.

---

## Developer Guide

### Project Structure
- **/app**: Contains backend logic (models, controllers).
- **/database**: Migrations and seeders.
- **/routes**: API and web route definitions.

### API Endpoints
Provide detailed documentation for each endpoint, including:
- **Authentication**: `/api/login`, `/api/register`.
- **URL Management**: `/api/urls`, `/api/urls/{id}`.
- **Statistics**: `/api/urls/{id}`.

---

## Database Schema
### Tables
1. **users**: Manages user information.
2. **urls**: Stores shortened URLs and associated data.
3. **clicks**: Tracks click details, such as location and device type.
4. **roles**: Defines user roles (e.g., admin, user).
5. **role_user**: Links roles to users.
6. **sessions**: Tracks active user sessions.
7. **personal_access_tokens**: Handles API tokens.

(Include diagrams or SQL examples as needed.)
