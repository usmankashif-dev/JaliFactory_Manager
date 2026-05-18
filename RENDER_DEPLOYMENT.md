# Render Deployment Guide for Jali Factory Manager

This guide walks through deploying the Jali Factory Manager Laravel + Inertia.js application to Render.

## Prerequisites

- Render account (https://render.com)
- GitHub repository (project must be pushed to GitHub)
- PostgreSQL database (or MySQL, if preferred)
- Redis instance (optional, recommended for caching/queue)

## Step-by-Step Deployment

### 1. Prepare Your Repository

Make sure your project is pushed to GitHub with the following files:
- `Dockerfile` - Multi-stage Docker build configuration
- `render.yaml` - Render service configuration
- `.dockerignore` - Docker build optimization
- `docker/nginx.conf` - Nginx configuration
- `docker/default.conf` - Nginx server configuration
- `.env.production` - Production environment template

### 2. Create Render Services

#### Option A: Using render.yaml (Recommended)

1. Go to https://dashboard.render.com
2. Click "New +"
3. Select "Blueprint"
4. Connect your GitHub repository
5. Set the following:
   - **Name**: jali-factory-manager (or your preferred name)
   - **Branch**: main (or your default branch)
   - **Root Directory**: . (if project is at root)
6. Click "Apply"

Render will automatically create all services defined in `render.yaml`.

#### Option B: Manual Service Creation

If render.yaml doesn't work, create services manually:

**Web Service:**
1. Click "New +" → "Web Service"
2. Connect your GitHub repository
3. Set configuration:
   - **Name**: jali-factory-manager
   - **Environment**: Docker
   - **Build Command**: Leave blank (uses Dockerfile)
   - **Start Command**: `/app/start.sh`
   - **Instance Type**: Standard
   - **Auto-deploy**: ON (toggle enabled)

**PostgreSQL Database:**
1. Click "New +" → "PostgreSQL"
2. Set configuration:
   - **Name**: postgres-db
   - **Database Name**: jali_factory_manager
   - **User**: postgres
   - **Region**: Same as web service
   - **PostgreSQL Version**: 15 (or latest)

**Redis (Optional but Recommended):**
1. Click "New +" → "Redis"
2. Set configuration:
   - **Name**: redis-cache
   - **Region**: Same as web service

### 3. Configure Environment Variables

After services are created, go to your web service settings:

1. **Dashboard** → **Environment** → Add environment variables:

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key-here
APP_URL=https://your-app-name.onrender.com
APP_NAME=Jali Factory Manager

DB_CONNECTION=pgsql
DB_HOST=your-postgres-host.render.internal
DB_PORT=5432
DB_DATABASE=jali_factory_manager
DB_USERNAME=postgres
DB_PASSWORD=your-postgres-password
DB_SSLMODE=require

CACHE_DRIVER=redis
SESSION_DRIVER=database
QUEUE_CONNECTION=redis

REDIS_HOST=your-redis-host.render.internal
REDIS_PASSWORD=your-redis-password
REDIS_PORT=6379

LOG_CHANNEL=stack
```

**Note:** Replace placeholder values with actual credentials from your PostgreSQL and Redis services.

### 4. Generate App Key

Generate a secure APP_KEY:

```bash
# Locally (before deployment)
php artisan key:generate

# Copy the key value and set it in Render environment variables
```

Or, set a temporary key and regenerate on first deployment using the Dockerfile's initialization script.

### 5. Connect Database and Redis

Render provides internal hostnames for services within the same environment:

- **PostgreSQL Host**: `your-postgres-instance.render.internal`
- **Redis Host**: `your-redis-instance.render.internal`

These are automatically resolved within the Render network.

### 6. Deploy

The application will automatically deploy when:
- Code is pushed to your GitHub repository (if auto-deploy is enabled)
- You manually trigger a deployment from the dashboard

**Watch deployment logs:**
- Dashboard → Your Service → **Logs** tab
- Look for successful completion message

### 7. Run Database Migrations

After deployment, run migrations:

**Option 1: Use Render Shell**
1. Dashboard → Web Service → **Shell** tab
2. Run: `php artisan migrate --force`

**Option 2: SSH**
```bash
render.com ssh <service-id>
php artisan migrate --force
```

### 8. Seed Data (Optional)

```bash
php artisan db:seed
```

### 9. Monitor Your Application

- **Logs**: Dashboard → **Logs** tab
- **Metrics**: Dashboard → **Metrics** tab
- **Events**: Dashboard → **Events** tab

## Troubleshooting

### Build Failures

**Check logs:** Dashboard → Logs → review build errors

**Common issues:**
- Missing `.env` variables: Ensure all required variables are set
- Docker build timeout: Increase in `render.yaml`
- Memory issues: Upgrade instance type

### Application Errors

**Check application logs:**
```bash
# Via Render Shell
tail -f storage/logs/laravel.log
```

**Common issues:**
- Database connection: Verify `DB_*` variables
- Missing migrations: Run manually via Shell
- Permission errors: Check `storage/` and `bootstrap/cache/` permissions

### Database Connection Issues

1. Verify database is running: Render Dashboard → PostgreSQL Service → **Status**
2. Check credentials in environment variables
3. Ensure `DB_SSLMODE=require` is set for PostgreSQL
4. Test connection via Shell:
   ```bash
   php artisan tinker
   DB::connection()->getPdo();
   ```

### Performance Issues

- Enable caching: `CACHE_DRIVER=redis`
- Use queue: `QUEUE_CONNECTION=redis`
- Enable Gzip in nginx: Already configured in `docker/default.conf`
- Monitor logs for slow queries

## Production Best Practices

1. **Environment Variables**
   - Never commit `.env` files with real credentials
   - Use Render's environment variable management
   - Rotate secrets regularly

2. **Database Backups**
   - Enable automatic backups in Render PostgreSQL settings
   - Set retention period to at least 7 days

3. **Logging**
   - Monitor `storage/logs/laravel.log`
   - Set up log aggregation for production errors
   - Use `LOG_CHANNEL=errorlog` for better visibility

4. **Updates**
   - Keep Laravel and dependencies up-to-date
   - Test updates locally before deploying
   - Use staging environment for validation

5. **Security**
   - Keep APP_DEBUG=false in production
   - Enable HTTPS (automatic on Render)
   - Regularly review security advisories

## Deployment Checklist

- [ ] Repository pushed to GitHub
- [ ] `Dockerfile`, `render.yaml`, and configs committed
- [ ] Render account created and authenticated
- [ ] Services created (Web, PostgreSQL, Redis)
- [ ] Environment variables configured
- [ ] APP_KEY generated and set
- [ ] Initial deployment successful
- [ ] Database migrations completed
- [ ] Application accessible at public URL
- [ ] Logs checked for errors
- [ ] Performance monitoring enabled

## Additional Resources

- [Render Documentation](https://render.com/docs)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Docker Best Practices](https://docs.docker.com/develop/dev-best-practices/)
- [Inertia.js Documentation](https://inertiajs.com)

## Support

For issues with:
- **Render**: Contact [Render Support](https://render.com/support)
- **Laravel**: Visit [Laravel Community](https://laravel.com/community)
- **Docker**: Check [Docker Documentation](https://docs.docker.com)

---

**Last Updated**: May 2026
**Tested With**: Laravel 12, Inertia.js 2, PHP 8.2, Node 22
