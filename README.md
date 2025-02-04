<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
    <a href="https://github.com/laravel/framework/actions">
        <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Status do Build">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Downloads Totais">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Última Versão Estável">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/l/laravel/framework" alt="Licença">
    </a>
</p>

## Sobre o Projeto

Este projeto foi desenvolvido utilizando o **Laravel**, um framework PHP moderno e robusto para construção de aplicações web escaláveis e eficientes. O projeto inclui recursos como:

- Sistema de busca avançada com filtros dinâmicos;
- Exibição de produtos com gerenciamento de estoque e preços;
- Carrinho de compras com cálculo automático de valores;
- Rotinas de validação e segurança utilizando as melhores práticas do Laravel;
- Armazenamento e exibição de imagens de produtos de maneira otimizada;
- Integração com banco de dados e estruturação eficiente dos dados;
- Design responsivo e interativo para melhor experiência do usuário.

## Tecnologias Utilizadas

- **Laravel** - Framework PHP para aplicações web;
- **Blade** - Engine de templates para views dinâmicas;
- **JavaScript (ES6+)** - Interatividade e requisições AJAX;
- **Bootstrap** - Estilização e layout responsivo;
- **MySQL / PostgreSQL** - Banco de dados relacional;
- **Eloquent ORM** - Manipulação de banco de dados de forma simplificada;
- **Git** - Controle de versão e colaboração.

## Instalação e Configuração

### Pré-requisitos
Antes de iniciar, certifique-se de ter instalado em sua máquina:
- PHP 8+
- Composer
- MySQL ou PostgreSQL
- Node.js (para compilação de assets)

### Passos para Instalação
1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/seu-projeto.git
   cd seu-projeto
   ```

2. Instale as dependências:
   ```bash
   composer install
   npm install && npm run dev
   ```

3. Configure o ambiente:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure o banco de dados no arquivo `.env` e execute as migrações:
   ```bash
   php artisan migrate
   ```

5. Inicie o servidor:
   ```bash
   php artisan serve
   ```

Agora o projeto estará rodando em `http://127.0.0.1:8000`.

## Funcionalidades

### 📌 Catálogo de Produtos
- Exibição dinâmica de produtos cadastrados no banco de dados.
- Filtros de categorias e subcategorias.
- Pesquisa otimizada com Laravel Query Builder.

### 🛒 Carrinho de Compras
- Adição e remoção de produtos.
- Cálculo de valores totais.
- Opção para solicitar orçamento.

### 🔍 Busca Inteligente
- Filtros dinâmicos para encontrar produtos rapidamente.
- Ordenação por preço, nome e estoque.

### 📸 Upload e Exibição de Imagens
- Gerenciamento de imagens de produtos.
- Ajuste automático de imagens para manter dimensões padronizadas.


## Segurança
Se encontrar alguma vulnerabilidade de segurança, por favor, envie um e-mail para o mantenedor do projeto jhonatanpantaroto@gmail.com.


