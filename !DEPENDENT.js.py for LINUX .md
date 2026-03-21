# Install Node
    Este comando utiliza curl para descargar y ejecutar un script de instalación desde una URL específica. Vamos a   desglosarlo paso a paso:
    - curl -o-: curl es una herramienta de línea de comandos utilizada para transferir datos desde o hacia un servidor.
    - -o- le dice a curl que redirija la salida al flujo de salida estándar (es decir, directamente a la terminal) en lugar de guardar el archivo en el disco.
    - https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh: Esta es la URL desde la que curl descarga el script. En este caso, se trata del script de instalación de nvm (Node Version Manager) en su versión 0.39.1.
    - | (pipe): El símbolo de tubería | toma la salida del comando anterior (curl) y la pasa como entrada al siguiente comando (bash).
    - bash: bash es un intérprete de línea de comandos. En este contexto, bash ejecuta el script que se descargó con curl.
        > curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash

    
    - source: Este comando ejecuta el archivo que se le pasa como argumento dentro del contexto del shell actual. Esto significa que las funciones y variables definidas en el archivo se cargarán en el entorno actual del shell.
        > source ~/.nvm/nvm.sh
    
    - El comando nvm ls se utiliza para listar todas las versiones de Node.jsinstaladas en tu sistema mediante nvm (Node Version Manager). Cuando ejecutas este comando, verás una lista de las versiones disponibles, incluyendo la versión actual activa y las versiones predeterminadas configuradas.
        > nvm ls
    
    - Perfecto. El comando nvm install 18.20.4 se utiliza para instalar la versión 18.20.4 de Node.jsen tu sistema usando nvm (Node Version Manager).    
        > nvm install 18.20.4

    - El comando nvm use 18.20.4 se utiliza para cambiar la versión activa de Node.jsen tu sesión de terminal a la versión 18.20.4.
        > nvm use 18.20.4 

# Detependencias en node 
    - Instalacion typescript
        > npm install -g typescript
    - Instalacion Angular
        > npm install -g @angular/cli@18.0.3
    - Instalacion de Ionic 
        > npm install -g @ionic/cli@7.2.0

# Install Python

    Paso 1: Instalar las dependencias necesarias
        > sudo apt update
        > sudo apt install -y make build-essential libssl-dev zlib1g-dev libbz2-dev \
        libreadline-dev libsqlite3-dev wget curl llvm libncurses5-dev libncursesw5-dev \
        xz-utils tk-dev libffi-dev liblzma-dev python-openssl git

    Paso 2: Instalar pyenv
        > curl https://pyenv.run | bash
        > nano ~/.bashrc
            export PYENV_ROOT="$HOME/.pyenv"      // ultimas lineas
            export PATH="$PYENV_ROOT/bin:$PATH"
            eval "$(pyenv init --path)"
            eval "$(pyenv init -)"
        > source ~/.bashrc

    Paso 3: Instalar la versión específica de Python
        > pyenv install --list
        > pyenv install 3.9.13

    Paso 4: Establecer la versión de Python instalada como predeterminada
        > pyenv global 3.9.13 

    Paso 5: Verificar la instalación
        > python --version


# Instalar Python Extensiones
    pip install pip==24.2
    pip install Keras==2.7.0
    pip install Keras-Applications==1.0.8 
    pip install Keras-Preprocessing==1.1.2 
    pip install numpy==1.26.4 
    pip install pillow==10.4.0 
    pip install protobuf==3.20.3 
    pip install tensorflow-cpu==2.7.0
    pip install flatbuffers==2.0.7 
    pip install tensorflow-estimator==2.7.0 
    pip install tensorflow-io-gcs-filesystem==0.31.0 
    pip install libclang==18.1.1 
    pip install Markdown==3.6 
    pip install MarkupSafe==2.1.5 
    pip install opt-einsum==3.3.0 
    pip install PyYAML==6.0.2 
    pip install scipy==1.13.1 
    pip install setuptools==58.1.0 
    pip install six==1.16.0 
    pip install tensorboard==2.17.0 
    pip install tensorboard-data-server==0.7.2 
    pip install termcolor==2.4.0 
    pip install typing_extensions==4.12.2 
    pip install Werkzeug==3.0.3 
    pip install wheel==0.44.0 
    pip install wrapt==1.16.0 
    pip install zipp==3.21.0
    pip install opencv-python==4.5.5.64
    pip install matplotlib==3.5.3

# Instalacion (PostgreSQL) 12.22 
    Paso 1: Instalar PostgreSQL
        > sudo apt install postgresql postgresql-contrib
            > sudo apt install postgresql-12 postgresql-contrib-12 # especificar una version(ej: 12)
        > psql --version # version postgresql
        > sudo apt install postgresql-12-postgis-3 # vers. postgresql-[VERSION]-postgis-3

    Paso 2: Verificar la instalación postgres
        > sudo systemctl status postgresql

    Paso 3: Configurar la contraseña del usuario postgres
        > sudo -u postgres psql

    Paso 3: dentro del shell de PostgreSQL
        >> \password postgres

    Paso 4: Instalar PgAdmin4 (opcional, para una interfaz gráfica)
        > sudo apt install pgadmin4

# Instalacion PostgreSQL (interfaz grafica, vers. actual)
    > curl -fsSL https://www.pgadmin.org/static/packages_pgadmin_org.pub | sudo gpg --dearmor -o /usr/share/keyrings/packages-pgadmin-org.gpg
    > echo "deb [signed-by=/usr/share/keyrings/packages-pgadmin-org.gpg] https://ftp.postgresql.org/pub/pgadmin/pgadmin4/apt/$(lsb_release -cs) pgadmin4 main" | sudo tee /etc/apt/sources.list.d/pgadmin4.list
    > sudo apt update
    > sudo apt install pgadmin4
    > sudo -u postgres psql
    >> CREATE ROLE pgadmin;    //shell postgreSQL

    ... 
    Acceder a la interfaz web de PgAdmin4:
    Desde el navegador web ve a https://localhost/pgadmin4. Ingresa con el usuario pgadmin y la contraseña que configuraste.


# Configuracion PostgreSQL (shell-Ubuntu 12.22-0ubuntu0.20.04.4) (PostgreSQL) 12.22 

    1) Ingresar Prompo de 'Postgres'
    sudo -u postgres psql 

    2) Crear un usuario y asignarle una contraseña:
    - CREATE USER tu_usuario WITH PASSWORD 'tu_contraseña';

    Asignar permisos (opcional):
    - ALTER USER tu_usuario WITH SUPERUSER;
     o ya sea
    - GRANT ALL PRIVILEGES ON DATABASE tu_base_de_datos TO tu_usuario;

    3) Crear BD Una vez en el prompt de PostgreSQL, usa el comando CREATE DATABASE para crear una nueva base de datos:
    - CREATE DATABASE nombre_de_tu_base_de_datos;
    - CREATE DATABASE siaagrob;

    4) ... # Modificar la Forma de Autenticacion (peer -> md5)

    5) Salir del prompt de PostgreSQL:
    - \q
    
    6) Verificar que la base de datos se ha creado correctamente:
    sudo -u postgres psql -c "\l"   // listar BD disponibles

    7) Importar el archivo SQL que contiene las tablas y datos (shell)
    - psql -U <usuario> -d <BD> -f <ruta/archivo.sql>
    - psql -U postgres -d siaagrob -f ~/Escritorio/siaAgro/DataBase/DataBaseMovil/siaAgroDataBase9-12-2024.sql

    Conéctar a la base de datos donde deseas crear la tabla:
    - sudo -u <usuario> psql -d nombre_de_tu_base_de_datos
    - sudo -u postgres psql -d siaagrob

    Habilitar postgis en BD (una vez dentro de la BD <sudo -u postgres psql -d nombre_de_tu_bd> )
    - CREATE EXTENSION postgis; # Dentro del prompt (nombre_de_tu_bd=#)
    - \dx # Para verificar
    
    Importar la base de datos desde la terminal:
    - psql -U tu_usuario -d nombre_de_tu_base_de_datos -f ruta/al/archivo.sql
    - psql -U postgres2 -d siaagrob -f /home/la/Escritorio/siaAgro/DataBase/DataBaseMovil/siaAgroDataBase20-11-2024.sql

    Consultar las tablas de la base de datos 
    - sudo -u <usuario> psql -d <BD>
    - sudo -u postgres psql -d siaagrob
    - \dt

# Modificar la Forma de Autenticacion (peer -> md5)

    - sudo nano /etc/postgresql/12/main/pg_hba.conf

    > Modificar el método de autenticación:
        Busca las líneas que mencionan el método de autenticación peer y cámbialas a md5
        o password para permitir la autenticación basada en contraseña
    ...(modificar el metodo peer por md5):

    Database administrative login by Unix domain socket 
        local   all             postgres                                peer

    "local" is for Unix domain socket connections only
        local   all             all                                     peer

    - sudo systemctl restart postgresql             // reiniciar server postgres

# SISTEMA-WEB "ESPECIFICACIONES" (DEBE BASARSE EN LA VERSION DE COMPOSER)
    > php -v 
        PHP 7.4.3-4ubuntu2.29 (cli) (built: Mar 25 2025 18:57:03) ( NTS )
            Copyright (c) The PHP Group
            Zend Engine v3.4.0, Copyright (c) Zend Technologies
            with Zend OPcache v7.4.3-4ubuntu2.29, Copyright (c), by Zend Technologies
    > composer -v
        Composer version 2.2.24 2024-06-10 22:51:52
    
    NOTA: se debe respetar las siguientes dependencias "composer.json"
        "require": {
            "php": "7.4.*",
            "endroid/qr-code": "3.4.*",
            "proj4php/proj4php": "2.0.*"
        }

# Instalacion de NGINX
    > sudo apt update
    > sudo apt install nginx -y
    > sudo systemctl status nginx # verificar estado del servicio
        > sudo systemctl start nginx # iniciarlo
    # Configuración del Firewall
    > sudo ufw allow 'Nginx Full'

# Resumen comandos 
    Reiniciar (tras cambios en config): sudo systemctl restart nginx
    Recargar (sin cortar conexiones): sudo systemctl reload nginx
    Probar sintaxis (antes de romper nada): sudo nginx -t

#  Agregar mi sistema web a nginx
    > sudo mkdir -p /var/www/mi-sistema # Preparar la carpeta de tu proyecto
    > sudo chown -R $USER:$USER /var/www/mi-sistema # permisos a tu usuario
    > ... <Sube tus archivos (HTML, JS, CSS, etc.) a esa carpeta.>
    > sudo nano /etc/nginx/sites-available/mi-sistema # Crear el archivo de configuración
        > # Configuracion Base 
        server {
            listen 80;
            listen [::]:80;
        
            root /var/www/mi-sistema;
            index index.html index.htm;
        
            server_name tu-dominio.com www.tu-dominio.com;
        
            location / {
                try_files $uri $uri/ =404;
            }
        }
    > sudo ln -s /etc/nginx/sites-available/mi-sistema /etc/nginx/sites-enabled/ # Activar la configuración
    > sudo nginx -t # Verificar y Reiniciar
    > sudo systemctl restart nginx # Reiniciar NGINX

# Instalacion PHP
    # Instalar PHP y el procesador FPM
    > sudo apt install php-fpm php-mysql php-curl php-gd php-mbstring php-xml php-xmlrpc php-soap php-intl php-zip -y
        > php -v # verificar version
    # Configurar el Bloque de Servidor (Server Block)
        # NGINX, encuentre un archivo .php, se lo pase a PHP-FPM.
    > sudo nano /etc/nginx/sites-available/mi-sistema
        > ...
        server {
            listen 80;
            server_name tu-dominio.com;
            root /var/www/mi-sistema;
        
            # Importante: agregar index.php
            index index.php index.html index.htm;
        
            location / {
                try_files $uri $uri/ =404;
            }
        
            # Pasar scripts PHP a FastCGI
            location ~ \.php$ {
                include snippets/fastcgi-php.conf;
                
                # ASEGÚRATE de que la versión coincida con la instalada (ej. 8.1 o 8.2)
                fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
            }
        
    
            # Bloquear acceso a archivos .htaccess
            location ~ /\.ht {
                deny all;
            }
        }
    # Verificar y Reiniciar
    > sudo nginx -t
    > sudo systemctl restart nginx
    > sudo systemctl status php8.2-fpm # Asegurar que PHP-FPM esté corriendo
    # Prueba de sistema PHP
    > nano /var/www/mi-sistema/info.php
    > ...
        <?php
            phpinfo();
        ?>
        
