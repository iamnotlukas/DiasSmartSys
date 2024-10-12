-- Criação do banco de dados
CREATE DATABASE diassmartsys;

-- Seleciona o banco de dados para uso
USE diassmartsys;

-- Criação da tabela 'usuarios'
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,         -- ID auto-incrementado, chave primária
    nome_completo VARCHAR(255) NOT NULL,       -- Nome completo
    cpf CHAR(11) NOT NULL,                     -- CPF com 11 caracteres
    data_nascimento DATE NOT NULL,             -- Data de nascimento
    observacoes VARCHAR(50)                    -- Observações com até 50 caracteres
);

-- Adiciona um índice único no CPF para garantir que não haja duplicatas
ALTER TABLE usuarios ADD UNIQUE (cpf);

CREATE TABLE registros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(11) NOT NULL,
    nome_completo VARCHAR(100) NOT NULL,
    data_nascimento DATE NOT NULL,
    observacoes VARCHAR(50),
    hora_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cpf) REFERENCES usuarios(cpf)
);

