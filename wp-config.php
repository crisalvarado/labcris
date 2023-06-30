# Imagen base
FROM wordpress:latest

# Directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Copiar el archivo wp-config.php personalizado
COPY wp-config.php .

# Permisos para el archivo wp-config.php
RUN chown www-data:www-data wp-config.php && chmod 600 wp-config.php

# Puerto expuesto por el contenedor
EXPOSE 80
[ec2-user@ip-172-31-82-113 virtualizacion-cris]$ ls^C
[ec2-user@ip-172-31-82-113 virtualizacion-cris]$ ls
Dockerfile  php.ini  readme.md  wp-config.php
[ec2-user@ip-172-31-82-113 virtualizacion-cris]$ cat wp-config.php
<?php
/** Configuración de la base de datos de WordPress */
define('DB_NAME', 'cristian');
define('DB_USER', 'admin');
define('DB_PASSWORD', 'Cr1st1an');
define('DB_HOST', 'databasecris-instance-1.cb1r6oy6pk8x.us-east-1.rds.amazonaws.com');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

/** Claves únicas de autenticación y sal */
define('AUTH_KEY',         'Cr1st1an');
define('SECURE_AUTH_KEY',  'Cr1st1an');
define('LOGGED_IN_KEY',    'Cr1st1an');
define('NONCE_KEY',        'Cr1st1an');
define('AUTH_SALT',        'Cr1st1an');
define('SECURE_AUTH_SALT', 'Cr1st1an');
define('LOGGED_IN_SALT',   'Cr1st1an');
define('NONCE_SALT',       'Cr1st1an');

/** Prefijo de la tabla de la base de datos */
$table_prefix = 'wp_';

/** Dirección URL de WordPress */
/** Dirección URL de WordPress */
define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST']);
define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST']);


/** Activar el modo de depuración (desactivar en producción) */
define('WP_DEBUG', false);

/** Configuración del límite de memoria */
define('WP_MEMORY_LIMIT', '256M');

/** Configuración del límite de tiempo de ejecución */
set_time_limit(300);

/** Habilitar la edición de temas y plugins desde el panel de administración */
define('DISALLOW_FILE_EDIT', false);

/** Deshabilitar las actualizaciones automáticas */
define('AUTOMATIC_UPDATER_DISABLED', true);

/** Salvar las revisiones de las entradas de forma ilimitada */
define('WP_POST_REVISIONS', false);

/** Activar el almacenamiento en caché de objetos de WordPress */
define('WP_CACHE', true);

/** Deshabilitar la edición de archivos a través del editor de temas y plugins */
define('DISALLOW_FILE_MODS', true);

/** Configuración de seguridad adicional */
define('FORCE_SSL_ADMIN', true);
define('COOKIE_SECURE', true);
define('DISALLOW_UNFILTERED_HTML', true);
define('DISABLE_WP_CRON', true);

/** Configuración de la zona horaria */
define('WP_TIMEZONE', 'America/Los_Angeles');

/** Configuración de idioma */
define('WPLANG', '');

/** ¡Eso es todo, deja de editar! ¡Feliz blogging! */

/** ¡Eso es todo, deja de editar! ¡Feliz blogging! */

/** Ruta absoluta al directorio de WordPress */
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(_FILE_) . '/');
}

/** Configuración de WordPress en variables globales y requerir archivos */
require_once(ABSPATH . 'wp-settings.php');
