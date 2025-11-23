# Sistema de inscrição da DIRPPG

### Instalar dependências do Laravel
composer install

### Criar o arquivo .env (copiando do .env.example)
cp .env.example .env

### Gerar chave da aplicação
php artisan key:generate

### Linkar storage
php artisan storage:link

### Migrar tabelas do laravel
php artisan migrate

### Iniciar thread da queue de emails
php artisan queue:work