Items API: Gestión de Recursos con SQLite (PHP)

Esta guía detalla los requisitos, los pasos de instalación y la configuración necesaria para implementar y ejecutar una API REST simple para la gestión de ítems, utilizando PHP y SQLite.

1. Requisitos Mínimos
Asegúrese de que su entorno cumpla con lo siguiente:
* Sistema Operativo: Windows 10/11 o Linux.
* PHP: Versión 8.5 CLI configurada (o superior).
* Composer: Versión 2.x.
* Extensiones PHP Habilitadas: pdo_sqlite, sqlite3, fileinfo, zip, curl, mbstring.

2. Instalación y Configuración del Entorno

2.1. Instalación en Windows
1. Descargar PHP: Obtenga la versión PHP x64 Thread Safe y extraiga el contenido en C:\php.
2. Configurar php.ini:
    * Renombre C:\php\php.ini-production a php.ini.
    * Edite php.ini para habilitar las extensiones necesarias:
        extension_dir="C:\php\ext"
        extension=pdo_sqlite
        extension=sqlite3
        extension=fileinfo
        extension=zip
        extension=curl
        extension=mbstring
3. Configurar PATH: Agregue la ruta C:\php a las variables de entorno del sistema (PATH).
4. Instalar Composer: Instale Composer desde getcomposer.org, seleccionando la ruta del ejecutable de PHP: C:\php\php.exe.
5. Verificación:
    php -v
    composer -V

2.2. Instalación en Linux

Debian/Ubuntu
Instale las dependencias necesarias:
sudo apt-get update
sudo apt-get install php php-cli php-sqlite3 php-curl php-zip php-mbstring composer
php -v
composer -V

Arch/Manjaro
Instale los paquetes requeridos:
sudo pacman -S php php-sqlite composer

3. Configuración del Proyecto

3.1. Instalación de Dependencias
Navegue al directorio del proyecto y ejecute:
composer install

3.2. Archivo de Configuración .env
Cree un archivo llamado .env en la raíz del proyecto con la configuración de la base de datos SQLite:
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
DB_FOREIGN_KEYS=true

3.3. Creación de la Base de Datos
Cree el directorio database y el archivo database.sqlite:

Linux:
mkdir database
touch database/database.sqlite

Windows (PowerShell):
mkdir database
New-Item -ItemType File -Path database\database.sqlite -Force

3.4. Ejecución de Migraciones
Aplique la estructura inicial de la base de datos:
php migrate.php

4. Ejecución y Endpoints de la API

4.1. Ejecutar el Servidor
Inicie el servidor web de desarrollo de PHP:
php -S localhost:8000 -t public

La API estará disponible en: http://localhost:8000/api/items

4.2. Endpoints (Recurso: /api/items)
* GET /items: Listar todos los Items.
* GET /items/{id}: Obtener un Item por ID.
* POST /items: Crear un nuevo Item.
* PUT /items/{id}: Actualizar un Item existente.
* DELETE /items/{id}: Eliminar un Item.

5. Pruebas de la API
Utilice los scripts de prueba proporcionados para verificar la funcionalidad de los endpoints.

Windows (PowerShell):
powershell -ExecutionPolicy Bypass -File .\test_api.ps1

Linux:
chmod +x test_api.sh
./test_api.sh