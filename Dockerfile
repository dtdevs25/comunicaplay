# Usar a imagem oficial do PHP 8.2 com o servidor Apache
FROM php:8.2-apache

# -------------------------------- NOVO BLOCO ADICIONADO AQUI --------------------------------
# Instalar dependências e a extensão GD para manipulação de imagens
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
&& docker-php-ext-configure gd --with-freetype --with-jpeg \
&& docker-php-ext-install -j$(nproc) gd
# ------------------------------------ FIM DO NOVO BLOCO ------------------------------------

# Copiar todos os arquivos do seu site para a pasta padrão do servidor web
COPY . /var/www/html/

# Criar a pasta uploads antes de alterar permissões
RUN mkdir -p /var/www/html/uploads && chown -R www-data:www-data /var/www/html/uploads

# Habilita o módulo de reescrita do Apache (para URLs amigáveis)
RUN a2enmod rewrite

# Instala a extensão do PHP para conectar com o MySQL/MariaDB
RUN docker-php-ext-install mysqli pdo pdo_mysql