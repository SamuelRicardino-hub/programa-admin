
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `ficha_avaliacao_final` (
  `id` int(11) NOT NULL,
  `participante_id` int(11) NOT NULL,
  `comportamento` text DEFAULT NULL,
  `participacao` text DEFAULT NULL,
  `cumprimento_regras` text DEFAULT NULL,
  `evolucao_pessoal` text DEFAULT NULL,
  `relacao_grupo` text DEFAULT NULL,
  `parecer_final` text DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `ficha_inclusao` (
  `id` int(11) NOT NULL,
  `pre_cadastro_id` int(11) NOT NULL,
  `cor` varchar(50) DEFAULT NULL,
  `situacao_civil` varchar(50) DEFAULT NULL,
  `religiao` varchar(100) DEFAULT NULL,
  `escolaridade` varchar(100) DEFAULT NULL,
  `renda_familiar` varchar(100) DEFAULT NULL,
  `ocupacao` varchar(150) DEFAULT NULL,
  `profissao` varchar(150) DEFAULT NULL,
  `ocupacao_companheira` varchar(150) DEFAULT NULL,
  `profissao_companheira` varchar(150) DEFAULT NULL,
  `condicao_moradia` varchar(100) DEFAULT NULL,
  `numero_filhos` int(11) DEFAULT NULL,
  `numero_pessoas_casa` int(11) DEFAULT NULL,
  `problemas_saude` text DEFAULT NULL,
  `uso_medicacao` text DEFAULT NULL,
  `uso_alcool` varchar(100) DEFAULT NULL,
  `frequencia_bebida` varchar(100) DEFAULT NULL,
  `drogas_utilizadas` text DEFAULT NULL,
  `violencia_praticada` text DEFAULT NULL,
  `violencia_sofrida` text DEFAULT NULL,
  `historico_familiar` text DEFAULT NULL,
  `situacao_juridica` text DEFAULT NULL,
  `expectativa_grupo` text DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `participantes` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `idade` int(11) NOT NULL,
  `naturalidade` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `turma_id` int(11) DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `participantes` (`id`, `nome`, `cpf`, `idade`, `naturalidade`, `telefone`, `email`, `turma_id`, `status`, `criado_em`, `atualizado_em`) VALUES
(2, 'Ana Banana', '123.456.789-10', 23, 'Brasil', '21987564213', 'ana.banana23@gmail.com', 1, 'ativo', '2026-02-27 14:59:07', '2026-02-27 14:59:07'),
(3, 'Maria Joana', '987.654.321-00', 19, 'Brasil', '21912349876', 'maria.joanaa@gmail.com', 2, 'ativo', '2026-02-27 15:03:40', '2026-02-27 15:03:40');


CREATE TABLE `pre_cadastros` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `idade` int(11) NOT NULL,
  `naturalidade` varchar(100) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `status` enum('pendente','aprovado','recusado') DEFAULT 'pendente',
  `criado_em` datetime DEFAULT current_timestamp(),
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `pre_cadastros` (`id`, `nome`, `cpf`, `idade`, `naturalidade`, `telefone`, `email`, `status`, `criado_em`, `atualizado_em`) VALUES
(1, 'SAMUEL RICARDINO FONSECA', '195.058.317-14', 18, 'Brasil', '21998635898', 'samelricardino@gmaill.com', '', '2026-02-26 14:44:39', '2026-02-27 14:59:09'),
(2, 'Adalberto Castro', '164.578.267.15', 42, 'Brasil', '21987651234', 'adalberto.castro@gmail.com', 'aprovado', '2026-02-26 16:25:45', '2026-02-27 14:57:37'),
(3, 'Ana Banana', '123.456.789-10', 23, 'Brasil', '21987564213', 'ana.banana23@gmail.com', 'aprovado', '2026-02-27 14:58:41', '2026-02-27 14:59:07'),
(4, 'Maria Joana', '987.654.321-00', 19, 'Brasil', '21912349876', 'maria.joanaa@gmail.com', 'aprovado', '2026-02-27 15:00:52', '2026-02-27 15:03:40');


CREATE TABLE `turmas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('ativa','inativa') DEFAULT 'ativa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `turmas` (`id`, `nome`, `descricao`, `data_criacao`, `status`) VALUES
(1, 'Ginástisca', '', '2026-02-27 17:55:35', 'ativa'),
(2, 'Autismo', '', '2026-02-27 17:55:59', 'ativa');


CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel` enum('admin') DEFAULT 'admin',
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `nivel`, `criado_em`) VALUES
(1, 'Administrador', 'admin@email.com', '$2y$10$ZPPLCwjYoJdXmM4kHwbhGe9ApMGfYDk0YV5YS11IIPTagcaS78PCi', 'admin', '2026-02-26 15:04:37');
ALTER TABLE `ficha_avaliacao_final`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_avaliacao_participante` (`participante_id`);


ALTER TABLE `ficha_inclusao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ficha_pre_id` (`pre_cadastro_id`);

ALTER TABLE `participantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD KEY `fk_participante_turma` (`turma_id`);


ALTER TABLE `pre_cadastros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`);


ALTER TABLE `turmas`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `ficha_avaliacao_final`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ficha_inclusao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;


ALTER TABLE `pre_cadastros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `turmas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `ficha_avaliacao_final`
  ADD CONSTRAINT `fk_avaliacao_participante` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`id`) ON DELETE CASCADE;

ALTER TABLE `ficha_inclusao`
  ADD CONSTRAINT `fk_ficha_pre` FOREIGN KEY (`pre_cadastro_id`) REFERENCES `pre_cadastros` (`id`) ON DELETE CASCADE;

ALTER TABLE `participantes`
  ADD CONSTRAINT `fk_participante_turma` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;
