# Docker Development & Local Testing Guide

This guide helps you develop and test the Jali Factory Manager application using Docker locally before deploying to Render.

## Prerequisites

- Docker Desktop installed (https://www.docker.com/products/docker-desktop)
- Docker Compose (included with Docker Desktop)
- Git

## Quick Start

### 1. Start Services with Docker Compose

```bash
# Build and start all services
docker-compose up --build

# Run in background (detached mode)
docker-compose up -d --build
```

This starts:
- **App**: http://localhost:8000
- **PostgreSQL**: localhost:5432
- **Redis**: localhost:6379

### 2. Run Database Migrations

```bash
# In new terminal
docker-compose exec app php artisan migrate

# Or with seeding
docker-compose exec app php artisan migrate:fresh --seed
```

### 3. Access the Application

Open your browser and go to: http://localhost:8000

## Available Commands

### Application Commands

```bash
# SSH into container
docker-compose exec app bash

# Run artisan commands
docker-compose exec app php artisan tinker
docker-compose exec app php artisan queue:work

# View logs
docker-compose logs -f app
docker-compose logs -f postgres
docker-compose logs -f redis

# Run tests
docker-compose exec app php artisan test
docker-compose exec app ./vendor/bin/pest
```

### Database Commands

```bash
# Access PostgreSQL
docker-compose exec postgres psql -U postgres -d jali_factory_manager

# Useful SQL queries
# List all tables
\dt

# Connect to database
\c jali_factory_manager

# Exit
\q
```

### Container Management

```bash
# Stop all services
docker-compose down

# Stop and remove volumes
docker-compose down -v

# Restart services
docker-compose restart

# Rebuild specific service
docker-compose build app

# View running containers
docker-compose ps
```

## Development Workflow

### 1. Code Changes

- Edit code locally in VS Code
- Changes are automatically reflected (if using volume mounts)
- For Laravel changes, no restart needed
- For config changes, may need service restart

### 2. Frontend Development

```bash
# Inside container
docker-compose exec app npm run dev

# In new terminal, watch for changes
docker-compose logs -f app
```

### 3. Database Schema Changes

```bash
# Create new migration
docker-compose exec app php artisan make:migration create_table_name

# Run migrations
docker-compose exec app php artisan migrate

# Rollback and rerun
docker-compose exec app php artisan migrate:refresh
```

### 4. Testing

```bash
# Run all tests
docker-compose exec app php artisan test

# Run specific test file
docker-compose exec app ./vendor/bin/pest tests/Feature/AuthTest.php

# Run with coverage
docker-compose exec app ./vendor/bin/pest --coverage
```

## Troubleshooting

### Container Won't Start

```bash
# Check logs
docker-compose logs app

# Rebuild from scratch
docker-compose down -v
docker-compose build --no-cache
docker-compose up
```

### Database Connection Error

```bash
# Verify PostgreSQL is running
docker-compose ps

# Check if database exists
docker-compose exec postgres psql -U postgres -l

# Recreate database
docker-compose exec postgres psql -U postgres -c "CREATE DATABASE jali_factory_manager;"
```

### Permission Issues

```bash
# Inside container
docker-compose exec app bash

# Fix permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Port Already in Use

If ports 8000, 5432, or 6379 are already in use:

Edit `docker-compose.yml`:
```yaml
ports:
  - "8001:8080"  # Change 8000 to 8001
  - "5433:5432"  # Change 5432 to 5433
  - "6380:6379"  # Change 6379 to 6380
```

## Monitoring & Debugging

### View Real-time Logs

```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app

# Last 50 lines
docker-compose logs --tail=50 app
```

### Access Application Shell

```bash
# PHP Shell
docker-compose exec app php artisan tinker

# MySQL/PostgreSQL Shell
docker-compose exec postgres psql -U postgres -d jali_factory_manager

# Container Bash
docker-compose exec app bash
```

### Performance Monitoring

```bash
# View resource usage
docker stats

# View running processes in container
docker-compose exec app ps aux
```

## Cleaning Up

### Remove Everything

```bash
# Stop and remove containers
docker-compose down

# Remove images
docker rmi jali_factory_manager-app

# Remove volumes
docker volume rm jali_factory_manager_postgres_data
docker volume rm jali_factory_manager_redis_data
```

### Clean Build

```bash
# Complete clean
docker-compose down -v
docker rmi $(docker images -q 'jali*')
docker-compose up --build
```

## Production vs. Development

### Development Settings

- `APP_ENV=local`
- `APP_DEBUG=true`
- Volume mounts for code changes
- SQLite or PostgreSQL for testing

### Production Settings (Render)

- `APP_ENV=production`
- `APP_DEBUG=false`
- No volume mounts
- Persistent PostgreSQL

See [RENDER_DEPLOYMENT.md](./RENDER_DEPLOYMENT.md) for production deployment details.

## Docker Image Size Optimization

The Dockerfile uses multi-stage builds to keep the final image small:

1. **Frontend stage**: Builds Vue/Vite assets
2. **PHP stage**: Contains only production dependencies

Final image size: ~1GB (suitable for Render)

To check image size:
```bash
docker images | grep jali-factory-app
```

## Tips & Best Practices

1. **Use .dockerignore**: Already configured to exclude unnecessary files
2. **Environment Variables**: Store in `.env` file, not in docker-compose.yml for production
3. **Volume Mounts**: Use for development, remove for production
4. **Health Checks**: Monitor container status with `docker-compose ps`
5. **Database Backups**: Always backup before testing migrations
6. **Redis Memory**: Monitor with `docker-compose exec redis redis-cli INFO`

## Further Reading

- [Docker Documentation](https://docs.docker.com)
- [Docker Compose Reference](https://docs.docker.com/compose/compose-file/)
- [Laravel Docker](https://laravel.com/docs/deployment#docker)
- [Render Documentation](https://render.com/docs)

---

**Last Updated**: May 2026
**Tested With**: Docker Desktop 4.x, Laravel 12, PostgreSQL 15, Redis 7
