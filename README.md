# API para Gerenciamento de Consultas Médicas

Este projeto é uma API desenvolvida em Laravel para gerenciar cidades, médicos, pacientes e consultas médicas. 
🔗 [Link da Aplicação](https://doctoron.lsouzapedroso.com/api/documentation)

## Requisitos

- **PHP** 8.2 ou superior
- **Laravel** 8 ou superior
- **Laravel Sail** para ambiente de desenvolvimento com Docker
- **MySQL** como banco de dados
- **JWT Authentication** para autenticação de usuários
- **Migrations, Factories e Seeders** para estruturação do banco de dados

##  Instalação

### 1️⃣ Clonar o repositório

```bash
git clone https://github.com/lsouzapedroso/DoctorON_api.git
cd DoctorON_api
```

### 2️⃣ Configurar o Laravel Sail

```bash
cp .env.example .env
composer install
php artisan key:generate
```

### 3️⃣ Subir os containers do Laravel Sail

```bash
./vendor/bin/sail up -d
```

### 4️⃣ Criar o banco de dados e popular com Seeders

```bash
./vendor/bin/sail artisan migrate --seed
```

### 5️⃣ Gerar a chave JWT

```bash
./vendor/bin/sail artisan jwt:secret
```

A API estará disponível em **http://localhost/api**.

##  Endpoints

### 1️⃣ **Cidades**
- `GET /cidades` → Listar cidades (público)

### 2️⃣ **Médicos**
- `GET /medicos` → Listar médicos (público)
- `POST /medicos` → Listar médicos (autenticado)
- `GET /cidades/{id_cidade}/medicos` → Listar médicos de uma cidade (autenticado)
- `POST /medicos/consulta` → Agendar consulta (autenticado)

### 3️⃣ **Pacientes**
- `GET /medicos/{id_medico}/pacientes` → Listar pacientes do médico (autenticado)
- `POST /pacientes` → Cadastrar paciente (autenticado)
- `PUT /pacientes/{id_paciente}` → Atualizar paciente (autenticado)

### 4️⃣ **Autenticação**
- `POST /auth/login` → Autenticação via JWT
- `GET /user` → Lsita informações do Usuario
- `POST /auth/logout` → Logout do usuário

## Tecnologias Utilizadas
- **Laravel**
- **Laravel Sail (Docker)**
- **MySQL**
- **JWT Authentication**
- **Postman (para testes dos endpoints)**

## Testando com Postman

A API pode ser testada usando a collection do Postman disponível em:

🔗 [Collection do Postman](https://web.postman.co/workspace/5ffbecf9-b61c-4d43-8a88-47a6a4aec7e0/collection/26108265-65071102-f584-4be1-9981-9eb4623caff9)

## Contribuição

Sinta-se à vontade para contribuir enviando PRs ou abrindo issues no repositório.

