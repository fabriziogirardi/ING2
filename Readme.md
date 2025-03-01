# Proyecto ING2

## Requisitos

- Docker
- Docker Compose

## Instalación

1. Clonar el repositorio:
   ```bash
   git clone <URL_DEL_REPOSITORIO>
   cd <NOMBRE_DEL_REPOSITORIO>
   ```

2. Crear el archivo `.env` a partir del archivo `.env.example`:
   ```bash
    cp .env.example .env
    ```
3. Rellenar los siguientes campos del archivo `.env` con lo que deseen:
   ```
   DB_DATABASE=
   DB_USERNAME=
   DB_PASSWORD=
   ```

4. Ejecutar el comando `docker-compose up -d` para levantar los servicios, teniendo en cuenta que el archivo `docker-compose-local.yaml` es el que se va a utilizar para el desarrollo local:
   ```bash
    docker-compose -f docker-compose-local.yaml up -d
   ```
   
5. Acceder a la URL `http://localhost` para visualizar la aplicación.

6. Para detener los servicios, ejecutar el comando `docker-compose stop`:
   ```bash
    docker-compose stop
   ```

## Notas adicionales

- Las dependencias de PHP se instalan de manera automática al levantar el contenedor `composer-init`
- Las dependencias de Node.js se instalan de manera automática al levantar el contenedor `npm-init`
- Las configuraciones de PHP, de Laravel y de la base de datos, así como las migraciones, se ejecutan de manera automática al levantar el contenedor `php-init`
- Se recomienda usar PHPStorm como IDE para el desarrollo del proyecto, que permite clona el repositorio y levantar los contenedores de Docker de manera automática.