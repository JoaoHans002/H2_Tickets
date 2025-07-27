# H2 Tickets 🎫

Sistema simples de compra e venda de ingressos para eventos.

Desenvolvido em PHP puro com SQLite, orientação a objetos e estrutura modular para aprendizado e prática.

---

## 🚀 Como executar o projeto

### 1. Pré-requisitos

- PHP 8.4.8 instalado
- Composer 2.8.10 instalado
- Navegador moderno (Google Chrome, Firefox etc.)

### 2. Rodar localmente

No terminal, dentro da pasta public, execute:

php -S localhost:8000

Em seguida, acesse: http://localhost:8000/login.php

## 📌 Funcionalidades

### 👤 Usuário (Organizador)
 
 Cadastro e login

 Cadastrar eventos

 Listar apenas seus próprios eventos

 Ver lista de clientes que compraram ingressos dos seus eventos

### 👥 Cliente (Comprador)
 
 Cadastro e login

 Listar eventos disponíveis

 Comprar ingressos

### ⚙️ Regras de Acesso

Para testar, comece cadastrando um usuário do tipo vendedor e cadastre eventos, depois crie um usuário do tipo comprador e compre os ingressos dos eventos cadastrados.

Organizador não vê eventos nem clientes de outros usuários

Cliente só possui acesso para comprar ingressos

## 🧠 Diagrama Simples do Funcionamento

<img width="544" height="669" alt="image" src="https://github.com/user-attachments/assets/a91326e6-f16c-42a9-aa56-d46fc72d857c" />

## ✅ Checklist de Funcionalidades

### 🧑‍💼 Usuário (Vendedor)
- [x] Criar (cadastro via formulário HTML)
- [x] Editar e deletar (somente próprios dados)
- [x] Ver (lista restrita)

### 🎫 Produtos / Ingressos
- [x] Criar, editar, deletar, visualizar
- [x] Reserva de estoque em tempo real
- [x] Bloqueio por 2 minutos ao acessar o ingresso

### 🧍 Cliente (Comprador)
- [x] Criar, editar, deletar (restrito por usuário)
- [x] Visualização restrita por cliente (não veem outros clientes)
- [x] Compra de ingressos

### 🛒 Compras
- [x] Comprar produto, com controle de estoque
- [x] Cancelar reserva após timeout (2 minutos)
- [x] Exibir mensagem de "Produto indisponível" se esgotado

### 🛠️ Geral
- [x] Conexão com banco SQLite
- [x] Autoload com Composer
- [x] Estrutura com PHP orientado a objetos
