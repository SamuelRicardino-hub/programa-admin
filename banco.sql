
DROP DATABASE IF EXISTS programa_admin;

CREATE DATABASE programa_admin
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE programa_admin;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nivel ENUM('admin') DEFAULT 'admin',

    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE pre_cadastros (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    idade INT NOT NULL,
    naturalidade VARCHAR(100),

    status ENUM('pendente','aprovado','recusado')
        DEFAULT 'pendente',

    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT uk_pre_cpf UNIQUE (cpf)
) ENGINE=InnoDB;


CREATE TABLE ficha_inclusao (
    id INT AUTO_INCREMENT PRIMARY KEY,

    pre_cadastro_id INT NOT NULL,

    cor VARCHAR(50),
    situacao_civil VARCHAR(50),
    religiao VARCHAR(100),
    escolaridade VARCHAR(100),
    renda_familiar VARCHAR(100),

    ocupacao VARCHAR(150),
    profissao VARCHAR(150),

    ocupacao_companheira VARCHAR(150),
    profissao_companheira VARCHAR(150),

    condicao_moradia VARCHAR(100),
    numero_filhos INT,
    numero_pessoas_casa INT,

    problemas_saude TEXT,
    uso_medicacao TEXT,

    uso_alcool VARCHAR(100),
    frequencia_bebida VARCHAR(100),
    drogas_utilizadas TEXT,

    violencia_praticada TEXT,
    violencia_sofrida TEXT,
    historico_familiar TEXT,

    situacao_juridica TEXT,

    expectativa_grupo TEXT,

    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_ficha_pre
        FOREIGN KEY (pre_cadastro_id)
        REFERENCES pre_cadastros(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE turmas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE participantes (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    idade INT NOT NULL,
    naturalidade VARCHAR(100),

    telefone VARCHAR(20),
    email VARCHAR(150),

    turma_id INT NULL,

    status ENUM('ativo','inativo') DEFAULT 'ativo',

    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_participante_turma
        FOREIGN KEY (turma_id)
        REFERENCES turmas(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE ficha_avaliacao_final (
    id INT AUTO_INCREMENT PRIMARY KEY,

    participante_id INT NOT NULL,

    comportamento TEXT,
    participacao TEXT,
    cumprimento_regras TEXT,
    evolucao_pessoal TEXT,
    relacao_grupo TEXT,

    parecer_final TEXT,

    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_avaliacao_participante
        FOREIGN KEY (participante_id)
        REFERENCES participantes(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE INDEX idx_pre_status ON pre_cadastros(status);
CREATE INDEX idx_pre_criado_em ON pre_cadastros(criado_em);
CREATE INDEX idx_ficha_pre_id ON ficha_inclusao(pre_cadastro_id);
CREATE INDEX idx_participante_data ON participantes(data_aprovacao);
