# Sistema de inscrição da DIRPPG

Abaixo segue os passos para execução do sistema. É necessário ter previamente instalado na máquina:
- Um servidor web (como Apache ou Nginx);
- PHP-FPM 8.2
- Composer PHP
- Banco de dados MySQL

### Clone esse repositório na sua máquina
git clone https://github.com/leonardobellato/sistema-dirppg.git
cd sistema-dirppg

### Instalar dependências do Laravel
composer install

### Criar o arquivo .env (copiando do .env.example)
cp .env.example .env

### (Opcional) Edite os arquivo .env com os dados do servidor MySQL e de e-mail
nano .env

### No servidor MySQL, rode o script de geração do banco
mysql -u root -p < ./database/database_script.sql

### Gerar chave da aplicação
php artisan key:generate

### Linkar storage
php artisan storage:link

### Migrar tabelas do laravel
php artisan migrate

### Iniciar thread da queue de emails
php artisan queue:work
