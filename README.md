# labcris
# Wordpress en AWS ECS Cluster
## _Realizado por Cristian Alvarado_

![N|Solid](https://upload.wikimedia.org/wikipedia/commons/thumb/a/aa/Logo_DuocUC.svg/711px-Logo_DuocUC.svg.png)


En este proyecto, implementaremos un contenedor Fargate dentro de un clúster de WordPress conectado a un balanceador de carga de aplicaciones. Además, utilizaremos ECR para alojar el repositorio de contenedores y generar las configuraciones de tareas. 

Ocuparemos los siguientes servicios:
- Docker
- ECS
- ECR
- Balanceador de Carga
- Security Group
- RDS Aurora MySQL
- Git

## Creamos una instancia EC2 y nos conectamos via SSH

## Crear base de datos Aurora, esta es BD de Amazon

1. Ir a Amazon RDS

2. Click en crear base de datos

3. Seleccionar Creación estándar

4. Seleccionamos Aurora(MySQL Compatible)

5. En plantillas elegir desarrollo y pruebas

6. Identificador del clúster de base de datos, debemos escribir un nombre para esta

7. En credenciales elegir el nombre de usuario maestro, que en mi caso es Admin

8. Debemos elegir una contraseña, en mi caso Cr1st1an

9. En Cluster storage configuration elegir Aurora Standard

10. En Conectividad, elegiremos Conectarse a un recurso informático de EC2 esto nos sirve para establecer una conexion interna con nuestra instancia ec2 y el contenedor y asi crearemos los Security Group de conexion de RDS

11. En las Autenticaciónes de bases de datos debemos elegir Autenticación con contraseña

12. Por ultimo hacer click en crear base de datos

```
## Instalar mysql en server EC2 para usar comandos mysql
```sh
sudo yum install mariadb105-server-utils.x86_64
```
## Conectarse a mysql aurora
```sh
mysql -h database-1-instance-1.cb1r6oy6pk8x.us-east-1.rds.amazonaws.com  -P 3306 -u admin -p Cr1st1an
```
# Crear base de datos
```sh
create database 'nombre'
```
# Cambiar los cambios de privilegios
```sh
FLUSH PRIVILEGES;
```

## Editar archivo Dockerfile
Instrucciones a editar Dockerfile.

FROM wordpress:latest

# Directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Copiar el archivo wp-config.php personalizado
COPY wp-config.php .

# Permisos para el archivo wp-config.php
RUN chown www-data:www-data wp-config.php && chmod 600 wp-config.php

# Puerto expuesto por el contenedor
EXPOSE 80


## Editar archivo wp_config.php 

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

## Nos dirigimos a ECS para crear una definición de tarea para luego crear el cluster con esa imagen de nuestro repo

1. Crear una nueva definición de tarea
2. Familia de definición de tareas Especifique un nombre de familia de definición de tarea único.
3. Nombre y la uri que se copia del repo que creamos
4. Mapeos de puertos es el puerto 80 HTTP
5. click en Siguiente
6. Entorno de la aplicación Elegimos AWS FARGATE 
7. Sistema operativo/arquitectura Linux
8. Tamaño de la tarea 2 vCPU y 4 GB de memoria
9. Rol de tarea elegimos un rol con permisos en el caso mio como es labrole
10. Rol de ejecución de tareas labrole
11. Almacenamiento efímero 30 GB
12. Creamos
# Crear Cluster
1. Nombre del clúster
2. Redes elegimos todas
3. Creamos
# Crear Servicio
1. Opciones informáticas Estrategia de proveedor de capacidad
2. Configuración de implementación Servicio
3. Familia elegimos nuestra tarea y la version
4. Nombre del servicio
5. Tipo de servicio Réplica Tareas deseadas 1
6. Redes Subredes todas 
7.  Grupo de seguridad launch-wizard-1, task-sg, rds-ec2-1, ec2-rds-1
8.  Balanceo de carga
9.  Balanceador de carga de aplicaciones 
10.  Crear un nuevo balanceador de carga
11.  Nombre del balanceador de carga
12.  Crear nuevo agente de escucha puerto 80 http
13.  Grupo de destino Crear nuevo grupo de destino elegir el nombre 
14.  Crear servicio

Cuando se inicie el balanceador de carga debemos cambiar su security group por el de ALB-SG que tiene la regla de trafico

# Verificar el correcto funcionamiento debemos esperar a que se termine el cloudformation y copiar el dns de nuestro load balancer para probar el sitio wordpress

ingresamos el dns copiado con el siguiente formato

http://balanceador-201524708.us-east-1.elb.amazonaws.com/
```
### Ahora podemos ver la pagina en Wordpress
