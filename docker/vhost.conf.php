<VirtualHost *:80>
    ServerAdmin abdrkasali@gmail.com
    DocumentRoot "/var/www/public"

    <Directory /var/www/public>
    AllowOverride None
    Order Allow,Deny
    Allow from All

    <IfModule mod_rewrite.c>
        Options -MultiViews
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^(.*)$ index.php [QSA,L]
    </IfModule>
    </Directory>

    <Directory /var/www/public/bundles>
    <IfModule mod_rewrite.c>
        RewriteEngine Off
    </IfModule>
    </Directory>

    ServerName localhost
    SetEnv APP_ENV "<?= getenv("APP_ENV") ?>"
    SetEnv APP_SECRET "<?= getenv("APP_SECRET") ?>"
    SetEnv DATABASE_URL "<?= getenv("DATABASE_URL") ?>"
    SetEnv DB_HOST "<?= getenv('DB_HOST') ?>"
    ErrorLog "${APACHE_LOG_DIR}/error.log"
    CustomLog "${APACHE_LOG_DIR}/access.log" common
</VirtualHost>