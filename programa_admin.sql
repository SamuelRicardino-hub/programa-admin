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

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `acao` text DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `participantes` (
  `id` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `turma_id` int(11) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `pre_cadastros` (
  `id` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `status` enum('pendente','aprovado','rejeitado') DEFAULT 'pendente',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `turmas` (
  `id` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `descricao` text DEFAULT NULL,
  `responsavel` varchar(100) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `status` enum('ativa','encerrada') DEFAULT 'ativa',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `turmas` (`id`, `nome`, `descricao`, `responsavel`, `data_inicio`, `data_fim`, `status`, `criado_em`) VALUES
(1, 'Ginástica', '', 'Professor Pedro', '2026-03-15', '2026-04-15', 'ativa', '2026-03-13 19:46:12');

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel` enum('admin','atendente') DEFAULT 'atendente',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `ficha_avaliacao_final`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_avaliacao_participante` (`participante_id`);

ALTER TABLE `ficha_inclusao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ficha_pre_id` (`pre_cadastro_id`);

ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `participantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD KEY `turma_id` (`turma_id`);

ALTER TABLE `pre_cadastros`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `turmas`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `ficha_avaliacao_final`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ficha_inclusao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pre_cadastros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `turmas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ficha_avaliacao_final`
  ADD CONSTRAINT `fk_avaliacao_participante` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`id`) ON DELETE CASCADE;

ALTER TABLE `ficha_inclusao`
  ADD CONSTRAINT `fk_ficha_pre` FOREIGN KEY (`pre_cadastro_id`) REFERENCES `pre_cadastros` (`id`) ON DELETE CASCADE;

ALTER TABLE `participantes`
  ADD CONSTRAINT `participantes_ibfk_1` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`);
COMMIT;