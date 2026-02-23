# Development Guide

## Structure

Code is organized into layers, each with a clear responsibility.

**Controllers** handle HTTP requests and delegate to services.

**Services** contain the business logic. Each service has an interface and a concrete implementation under `Impl/`.

**Repositories** handle all database access via Eloquent. Services never touch the database directly. Like services, each repository has an interface and a concrete implementation under `Impl/`.

Repository method names should clearly describe what they do. Naming conventions:

- `find*` - returns a model or null
- `get*` - returns a model or throws an exception
- `*All*` - returns a collection
- `*By<CamelCaseField>` - indicates the field(s) used for lookup
- `save(model)` - inserts or updates a model
- `delete(model)` / `deleteById(id)` - removes a model

Examples:
```php
findById(string $id): ?Model
getById(string $id): Model // throws if not found
findAll(): Collection
findAllByCity(string $city): Collection
findByUrlAndDomain(string $url, string $domain): ?Model
save(Model $model): Model
delete(Model $model): void
```

**API clients** (`app/Api`) are thin wrappers around external HTTP services, returning typed DTOs.

**DTOs** (`app/Dto`) are typed data objects built with `spatie/laravel-data`, used to pass data between layers.

## Dependency Injection

All interface to implementation bindings are registered in `app/Providers/AppServiceProvider.php`. To swap an implementation, only that file needs to change.

## Testing

Unit tests live in `tests/Unit/Services/`. Each service is tested in isolation - dependencies are mocked via `$this->mock()` and LLM calls are faked with `Prism::fake()`. No database or network is required.

```bash
php artisan test
```
