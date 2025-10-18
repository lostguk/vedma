# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Vedma Shop** - Laravel 11 based e-commerce application for selling magical goods with a modern Docker-based development environment. The project uses Filament for admin panel and Scribe for API documentation.

## Technology Stack

- **PHP 8.3+** with strict typing (`declare(strict_types=1)`)
- **Laravel 11** with Sanctum for API authentication
- **Filament 3.3** for admin panel
- **Scribe** (knuckleswtf/scribe) for API documentation
- **Pest/PHPUnit** for testing
- **Docker** with multiple environments (Sail/Local, Dev, Production)
- **MySQL 8.0**, **Redis**
- **Nginx** as web server in containerized environments

## Development Commands

### Primary Development Script: `./dev.sh`

This is the main script for all development operations. Use it instead of direct Docker commands.

**Common workflows:**

```bash
# Development environment (DEV)
./dev.sh dev-up              # Start DEV environment (port 8000)
./dev.sh dev-down            # Stop DEV environment
./dev.sh dev-artisan [cmd]   # Run artisan command
./dev.sh freshdb-dev         # Reset DB with migrations + seeds
./dev.sh test-dev            # Run tests in DEV

# Local development (Laravel Sail)
./dev.sh up                  # Start Sail
./dev.sh down                # Stop Sail
./dev.sh artisan [cmd]       # Run artisan command
./dev.sh reset-db            # Reset DB with migrations + seeds
./dev.sh test                # Run tests

# Code quality & documentation
./dev.sh lint                # Format code with Pint
./dev.sh docs                # Generate API documentation
./dev.sh ide-helper          # Generate IDE helper files
./dev.sh cache               # Clear all caches

# Production
./dev.sh prod-build          # Build production image
./dev.sh prod-up             # Start production (port 8080)
./dev.sh prod-down           # Stop production
```

**Container access:**
```bash
./dev.sh shell               # Sail: Enter PHP container
./dev.sh dev-shell           # DEV: Enter app container
./dev.sh logs [service]      # View logs
./dev.sh status              # Show all container statuses
```

### Composer Scripts

The project has numerous composer scripts defined in `composer.json`:

```bash
# Via Sail/Docker (recommended)
./dev.sh composer fresh-seed      # Reset DB + run seeders
./dev.sh composer pint            # Run Pint formatter
./dev.sh composer docs            # Generate API docs

# Direct (if not using Docker)
composer fresh-seed
composer pint
composer docs
```

### Testing

```bash
# Run all tests
./dev.sh test                # Sail
./dev.sh test-dev            # DEV environment

# Run specific test
./dev.sh artisan test --filter=OrderStoreTest
```

Tests use Pest and are located in `tests/Feature/` and `tests/Unit/`.

## Architecture & Code Organization

### Repository-Service-Controller Pattern

The project strictly follows a layered architecture:

1. **Controllers** (`app/Http/Controllers/Api/V1/`) - Handle HTTP requests, delegate to services
   - Must be `final` classes
   - Must be readonly (no mutable properties)
   - Only inject services via constructor or method injection
   - Return JSON responses using `$this->successResponse()` from `ApiController`

2. **Services** (`app/Services/`) - Contain business logic
   - Must be `final readonly` classes
   - Orchestrate repositories and other services
   - Handle complex operations and transformations

3. **Repositories** (`app/Repositories/`) - Data access layer
   - Extend `BaseRepository` which implements `RepositoryInterface`
   - Must be `final readonly` classes
   - Handle all Eloquent operations
   - Constructor injection of Model: `public function __construct(protected readonly Model $model)`

4. **Resources** (`app/Http/Resources/V1/`) - Transform models to API responses
   - Use Laravel API Resources for consistent JSON structure
   - Located in versioned directories (V1, V2, etc.)

5. **Requests** (`app/Http/Requests/Api/V1/`) - Validation logic
   - Form Requests for all input validation
   - Define authorization rules
   - Custom validation messages when needed

### Models

All models (`app/Models/`) must be:
- `final` classes to prevent inheritance
- Use strict typing for all properties
- Define `$fillable` explicitly
- Include relationships, casts, scopes as needed
- Use appropriate Eloquent features (soft deletes, timestamps, etc.)

Example structure:
```php
final class Product extends Model
{
    protected $fillable = ['name', 'slug', 'price'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }
}
```

### Routing

- API routes are in `routes/api.php` with `api/v1` prefix
- Route groups organized by resource (auth, categories, products, orders)
- All routes must have explicit names: `->name('api.v1.products.show')`
- Separate route files for large features (see `routes/user.php`)
- Use Sanctum middleware for authenticated routes: `->middleware('auth:sanctum')`

### Filament Admin Panel

Located in `app/Filament/Resources/`:
- Create resources: `php artisan make:filament-resource ModelName`
- Group related fields logically in forms (see OrderResource for example)
- All labels and text must be in Russian
- Clear Filament cache after changes: `./dev.sh dev-artisan filament:clear-cache`

## Feature Implementation Workflow

When implementing new features, follow this strict order from `workflow.md`:

1. **Migrations, Seeds, Factories**
   - Check existing migrations first
   - Ensure proper foreign key relationships
   - Run: `./dev.sh freshdb-dev`

2. **Models**
   - Create: `php artisan make:model ModelName`
   - Make `final`, use strict typing, define relationships and casts

3. **Filament Admin** (if needed)
   - Create: `php artisan make:filament-resource ModelName`
   - All text in Russian, group fields logically
   - Clear cache: `./dev.sh dev-artisan filament:clear-cache`

4. **Routes & Controllers**
   - Add routes with names in `routes/api.php`
   - Create controller extending `ApiController`
   - Controllers are `final` and readonly

5. **Validation**
   - Create: `php artisan make:request Api/V1/ModelStoreRequest`
   - Define all validation rules

6. **Repositories, Services, Resources**
   - Create repository (final readonly, extends BaseRepository)
   - Create service (final readonly)
   - Create API Resource: `php artisan make:resource ModelResource`

7. **API Documentation (Scribe)**
   - Add `@group` annotation to controller
   - Document endpoints with `@queryParam`, `@bodyParam`
   - Regenerate: `./dev.sh docs-dev`
   - Check at `http://localhost:8000/docs`

8. **Tests**
   - Create: `php artisan make:test ModelControllerTest`
   - Write feature tests for all endpoints
   - Run: `./dev.sh test-dev`

9. **Feature Documentation**
   - Create markdown file in `docs/features/`
   - Document endpoints, models, usage examples

## Code Standards

### PHP & Laravel Principles

- **Strict typing**: Always use `declare(strict_types=1);` at the top of every PHP file
- **PSR-12** code style (enforced by Pint)
- **SOLID principles** and clean architecture
- **Final classes**: Controllers, Services, Repositories must be `final`
- **Readonly classes**: Services and Repositories should be `readonly`
- **Type declarations**: Explicit return types for all methods
- **Short, concise code**: Prefer brevity without sacrificing clarity

### Docker Considerations

- Commands always assume Docker environment
- Use `./dev.sh` script for all operations
- Three environments: Sail (local), Dev (port 8000), Production (port 8080)
- Set HOST_UID and HOST_GID in `.env` for proper file permissions on macOS/Linux

### Git Hooks

Pre-commit hook runs Pint automatically:
```bash
chmod +x .githooks/pre-commit
git config core.hooksPath .githooks
```

If Pint finds errors, commit will be blocked until fixed.

## API Documentation with Scribe

- Documentation auto-generated from controller annotations
- Main config: `config/scribe.php`
- Available at: `http://localhost:8000/docs`
- Update after any API changes: `./dev.sh docs-dev`

Example controller documentation:
```php
/**
 * @group Категории
 * API для работы с категориями товаров
 */
final class CategoryController extends ApiController
{
    /**
     * Получение списка категорий
     *
     * @queryParam show_hidden boolean Показать скрытые категории. Example: false
     * @response {
     *   "data": [
     *     {"id": 1, "name": "Магические книги"}
     *   ]
     * }
     */
    public function index() { }
}
```

## Project-Specific Patterns

### API Controller Responses

All API controllers extend `ApiController` and use:
```php
return $this->successResponse($data, $message, $statusCode);
return $this->errorResponse($message, $statusCode);
```

### Database Queries

- Use Eloquent and Query Builder, avoid raw SQL
- Repository pattern for all data access
- Implement pagination for collections
- Add filters and sorting when appropriate

### Authentication

- Sanctum for API token authentication
- Middleware: `->middleware('auth:sanctum')`
- Auth routes in separate group in `routes/api.php`

## Docker Environment Details

### Three Docker Configurations

1. **Laravel Sail** (`docker-compose.yml`) - Local development with Sail
2. **Dev** (`docker-compose.dev.yml`) - Development environment, port 8000
3. **Production** (`docker-compose.production.yml`) - Production-ready, port 8080

### Container Structure

- **app/php** - PHP-FPM with Laravel
- **nginx** - Web server
- **mysql/mysql_dev** - MySQL 8.0
- **redis/redis_dev** - Redis cache

### Important Notes

- Mac-specific workarounds exist for Sail (see `docs/docker/MAC_SETUP.md`)
- Production uses unprivileged nginx user and security best practices
- Health checks configured for all services

## Common Issues & Solutions

- **Migration errors**: Check migration order and dependencies
- **Seeder problems**: Ensure factories and models are up-to-date
- **Filament not showing new resource**: Check resource registration, clear cache
- **Scribe warnings**: Add proper annotations, check `bodyParameters()` examples
- **Pre-commit hook not working**: Check permissions and `core.hooksPath` setting
- **File permission issues in Docker**: Set HOST_UID and HOST_GID in `.env`, rebuild container

## Admin Panel Access

- URL: `http://localhost:8000/admin` (Dev) or `http://localhost:8080/admin` (Production)
- Built with Filament 3.3
- All resources in `app/Filament/Resources/`

## Key Project Files

- `workflow.md` - Detailed feature implementation workflow (Russian)
- `.cursor/rules/laravel.mdc` - Laravel coding standards
- `dev.sh` - Main development script
- `config/scribe.php` - API documentation configuration
- `composer.json` - Useful composer scripts
- `phpunit.xml` - Test configuration

## Language

- **Code**: English (variables, methods, classes)
- **Documentation & Comments**: Russian (especially Filament admin, API docs)
- **Database content**: Russian (product names, categories, etc.)
