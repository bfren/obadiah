# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Obadiah** is a PHP web application that integrates Church Suite and Baserow to provide:
- Calendar and rota feeds for church services
- Monthly prayer calendars with church member information
- Daily Bible reading plans
- Safer recruitment form processing and management

The application requires PHP 8.5+ and is containerized with Docker.

## Build and Development Commands

### Running the Development Server

Build a development Docker image and run the application locally:

```bash
./build.sh
```

This builds the Docker image and starts the server on `http://127.0.0.1:3000`.

### Running Code Quality Checks

Run PHPStan (static code analysis at level 7):

```bash
./check.sh
```

Or manually:

```bash
php vendor/bin/phpstan analyse --level=7 src
```

### Running CLI Commands

Execute CLI commands (e.g., password hashing) using the CLI Docker container:

```bash
./cmd.sh <command> [args]
```

Example (hash a password):

```bash
./cmd.sh pwhash -p "MyPassword"
```

### Installing Dependencies

```bash
composer install
```

## Project Architecture

### Routing System

Obadiah uses a custom router-based architecture:
- Routes are registered in `src/public/index.php` using `Router::map_endpoint()`
- Each endpoint maps to a page class (in `src/pages/`) or API class (in `src/api/`)
- Routes can require authentication (`requires_auth`) or admin privileges (`requires_admin`)

Example route mapping:
```php
Router::map_endpoint(P\Prayer\Prayer::class);  // Maps /prayer
Router::map_endpoint(A\Ajax\Ajax::class, uri_prefix: "api");  // Maps /api/ajax
```

### Auto-loading and Class Structure

- Classes are auto-loaded from `src/classes/` based on PHP namespace
- Namespace `\Obadiah\FolderName\ClassName` maps to `src/classes/folder-name/class-name.class.php`
- API endpoints are in `src/api/<endpoint>/`
- Page endpoints are in `src/pages/<page>/`

### Core Classes and Modules

- **App** (`src/app.class.php`): Application initialization, version management, and checks
- **Router** (`src/classes/router/`): Route mapping and endpoint matching
- **Request** (`src/classes/request/`): HTTP request handling
- **Response** (`src/classes/response/`): Response generation (HTML, JSON, iCalendar, redirects, etc.)
- **Cache** (`src/classes/cache/`): Caching system for performance
- **Config** (`src/classes/config/`): Configuration management (loads from YAML)
- **Helpers** (`src/classes/helpers/`): Utility functions for arrays, curl, datetime, escaping, hashing, logging, etc.

### Integration Modules

- **Baserow** (`src/classes/baserow/`): Integration with Baserow API
- **Church Suite** (`src/classes/churchsuite/`): Integration with Church Suite API
- **Calendar** (`src/classes/calendar/`): iCalendar (ICS) generation and timezone handling
- **Bible** (`src/classes/bible/`): Bible reading plans and lectionary integration
- **Prayer** (`src/classes/prayer/`): Prayer calendar generation
- **Rota** (`src/classes/rota/`): Service rota management

### Configuration

Configuration is loaded from a YAML file (typically `config.yml` in the data directory):
- Church information (name, domain, production mode)
- API credentials and URLs (Baserow, Church Suite)
- Cache settings
- Event/service configuration (timezone, date formats)
- Login credentials (hashed with argon2)
- Prayer and rota settings

See `config-sample.yml` for all available configuration options.

## Common Development Tasks

### Running the Application

```bash
./build.sh  # Builds and runs dev server on port 3000
```

### Checking Code Quality

```bash
./check.sh  # Runs PHPStan analysis
```

### Adding a New Page Endpoint

1. Create a folder in `src/pages/<page-name>/`
2. Create the main class extending `Endpoint` in `src/pages/<page-name>/<page-name>.class.php`
3. Register the endpoint in `src/public/index.php` using `Router::map_endpoint(P\<PageName>\<PageName>::class)`

### Adding a New API Endpoint

1. Create a folder in `src/api/<endpoint-name>/`
2. Create the main class extending `Endpoint` in `src/api/<endpoint-name>/<endpoint-name>.class.php`
3. Register the endpoint in `src/public/index.php` using `Router::map_endpoint(A\<EndpointName>\<EndpointName>::class, uri_prefix: "api")`

### Working with Cache

- Cache is managed by the `Cache` class
- Cache duration is configurable in `config.yml` (default 3600 seconds)
- Cache is initialized during app startup

### Working with Configuration

Configuration values are accessible via the `Config` class:
```php
$config_value = C::$section->key;  // e.g., C::$general->church_name
```

## CI/CD Pipeline

The project uses GitHub Actions for continuous integration:

- **dev.yml**: Runs on all pushes
  - Runs PHPStan checks
  - Builds Docker image for linux/amd64, linux/arm/v7, linux/arm64
  - Pushes to DockerHub and GitHub Container Registry

- **auto-pr.yml**: Automatically creates PRs when branches are created
- **auto-release.yml**: Automatically creates releases when PRs are merged to main

## Security Notes

- Session cookies are configured with secure, HttpOnly, and SameSite=Strict attributes
- Authentication is required by default for most endpoints (see `requires_auth` parameter)
- Passwords are stored using argon2 hashing
- The app includes a security check that must pass (`App::check()`)

## Key Files

- `src/public/index.php` - Route mapping entry point
- `src/app.class.php` - Application initialization
- `src/classes/router/router.class.php` - Routing engine
- `config-sample.yml` - Configuration template
- `check.sh` - PHPStan analysis script
- `build.sh` - Development build and run script
- `.github/workflows/dev.yml` - CI/CD pipeline
