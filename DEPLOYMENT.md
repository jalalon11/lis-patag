# 🚀 Patag Elementary School LIS - Deployment Guide

This comprehensive guide will help you deploy the Patag Elementary School Learning Information System from scratch.

## 📋 System Requirements

### Minimum Requirements
- **PHP**: >= 8.2
- **MySQL**: >= 8.0
- **Node.js**: >= 18.x
- **Memory**: 2GB RAM minimum
- **Storage**: 5GB free space
- **Web Server**: Apache 2.4+ or Nginx 1.18+

### Recommended Requirements
- **PHP**: 8.3+
- **MySQL**: 8.0+
- **Node.js**: 20.x LTS
- **Memory**: 4GB RAM
- **Storage**: 10GB free space
- **SSL Certificate**: For production deployment

## 🛠️ Step-by-Step Installation

### 1. Clone the Repository

```bash
# Clone from GitHub
git clone https://github.com/jalalon11/lis-patag.git
cd lis-patag

# Or download and extract ZIP file
# wget https://github.com/jalalon11/lis-patag/archive/main.zip
# unzip main.zip && cd lis-patag-main
```

### 2. Install PHP Dependencies

```bash
# Install Composer if not already installed
# curl -sS https://getcomposer.org/installer | php
# sudo mv composer.phar /usr/local/bin/composer

# Install PHP dependencies
composer install --optimize-autoloader --no-dev
```

### 3. Install Node.js Dependencies

```bash
# Install Node.js dependencies
npm install

# Or using Yarn
# yarn install
```

### 4. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Configure Environment Variables

Edit the `.env` file with your specific configuration:

```env
# Application Settings
APP_NAME="Patag Elementary School LIS"
APP_ENV=production
APP_KEY=base64:your_generated_key_here
APP_DEBUG=false
APP_TIMEZONE=Asia/Manila
APP_URL=https://yourdomain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lis_patag
DB_USERNAME=your_db_username
DB_PASSWORD=your_secure_password

# Mail Configuration (Optional)
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email@domain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Patag Elementary School"

# Session Configuration
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache Configuration
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

### 6. Database Setup

```bash
# Create database (MySQL)
mysql -u root -p
CREATE DATABASE lis_patag CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'lis_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON lis_patag.* TO 'lis_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
php artisan migrate

# Seed the database with initial data
php artisan db:seed
```

### 7. File Permissions

```bash
# Set proper permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# For development (less secure)
# chmod -R 777 storage bootstrap/cache
```

### 8. Create Storage Link

```bash
php artisan storage:link
```

### 9. Build Frontend Assets

```bash
# For production
npm run build

# For development
# npm run dev
```

### 10. Optimize for Production

```bash
# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear any existing caches
php artisan cache:clear
php artisan config:clear
```

## 🌐 Web Server Configuration

### Apache Configuration

Create a virtual host file `/etc/apache2/sites-available/lis-patag.conf`:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/lis-patag/public
    
    <Directory /var/www/lis-patag/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/lis-patag_error.log
    CustomLog ${APACHE_LOG_DIR}/lis-patag_access.log combined
</VirtualHost>

# SSL Configuration (Recommended)
<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /var/www/lis-patag/public
    
    SSLEngine on
    SSLCertificateFile /path/to/your/certificate.crt
    SSLCertificateKeyFile /path/to/your/private.key
    
    <Directory /var/www/lis-patag/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/lis-patag_ssl_error.log
    CustomLog ${APACHE_LOG_DIR}/lis-patag_ssl_access.log combined
</VirtualHost>
```

Enable the site:
```bash
sudo a2ensite lis-patag.conf
sudo a2enmod rewrite ssl
sudo systemctl reload apache2
```

### Nginx Configuration

Create `/etc/nginx/sites-available/lis-patag`:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/lis-patag/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}

# SSL Configuration
server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    root /var/www/lis-patag/public;
    index index.php index.html;

    ssl_certificate /path/to/your/certificate.crt;
    ssl_certificate_key /path/to/your/private.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable the site:
```bash
sudo ln -s /etc/nginx/sites-available/lis-patag /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 🔐 Default Login Credentials

After successful installation, use these credentials to access the system:

```
Email: admin@patag-elementary.edu.ph
Password: admin123
```

**⚠️ Important**: Change the default password immediately after first login!

## 🔧 Post-Installation Configuration

### 1. Change Default Credentials
- Login with default credentials
- Navigate to Account Settings
- Update admin email and password

### 2. Configure School Information
- Update school details in the dashboard
- Set current school year
- Configure system preferences

### 3. Setup Backup System
```bash
# Create backup script
sudo nano /usr/local/bin/lis-backup.sh

#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/lis-patag"
DB_NAME="lis_patag"
DB_USER="lis_user"
DB_PASS="your_password"

mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/database_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/lis-patag/storage

# Keep only last 7 days of backups
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

# Make executable
sudo chmod +x /usr/local/bin/lis-backup.sh

# Add to crontab for daily backups
sudo crontab -e
# Add: 0 2 * * * /usr/local/bin/lis-backup.sh
```

## 🚨 Troubleshooting

### Common Issues

1. **Permission Errors**
   ```bash
   sudo chown -R www-data:www-data /var/www/lis-patag
   sudo chmod -R 755 /var/www/lis-patag
   sudo chmod -R 775 /var/www/lis-patag/storage /var/www/lis-patag/bootstrap/cache
   ```

2. **Database Connection Issues**
   - Verify database credentials in `.env`
   - Check MySQL service: `sudo systemctl status mysql`
   - Test connection: `php artisan tinker` then `DB::connection()->getPdo();`

3. **Asset Loading Issues**
   ```bash
   npm run build
   php artisan config:clear
   php artisan cache:clear
   ```

4. **500 Internal Server Error**
   - Check Laravel logs: `tail -f storage/logs/laravel.log`
   - Verify file permissions
   - Check web server error logs

### Performance Optimization

1. **Enable OPcache** (php.ini):
   ```ini
   opcache.enable=1
   opcache.memory_consumption=256
   opcache.max_accelerated_files=20000
   ```

2. **Configure MySQL** (my.cnf):
   ```ini
   innodb_buffer_pool_size=1G
   query_cache_type=1
   query_cache_size=256M
   ```

3. **Use Redis for Caching** (optional):
   ```bash
   sudo apt install redis-server
   # Update .env: CACHE_DRIVER=redis
   ```

## 📞 Support

If you encounter issues during deployment:

1. Check the [GitHub Issues](https://github.com/jalalon11/lis-patag/issues)
2. Review Laravel logs in `storage/logs/`
3. Verify all requirements are met
4. Contact the developer for assistance

## 🔄 Updates

To update the system:

```bash
# Backup first!
git pull origin main
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan migrate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

**Happy Deployment! 🎉**

The Patag Elementary School LIS is now ready to serve your educational institution.
