# Clone the repo
git clone <repo-url> pharmacypos-v2
cd pharmacypos-v2

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Copy and configure environment
cp .env.example .env
php artisan key:generate
# Edit .env → set DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_URL, etc.

# Run migrations + seed
php artisan migrate --force
php artisan db:seed          # if seeders create initial business/admin user

# Build frontend assets
npm install
npm run tw:build             # Tailwind CSS
npm run build                # Vite (JS/CSS)

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 775 storage bootstrap/cache