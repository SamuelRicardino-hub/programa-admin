-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07/04/2026 às 21:54
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `programa_admin`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `anexos`
--

CREATE TABLE `anexos` (
  `id` int(11) NOT NULL,
  `caso_id` int(11) DEFAULT NULL,
  `participante_id` int(11) DEFAULT NULL,
  `nome_arquivo` varchar(255) DEFAULT NULL,
  `caminho` varchar(255) DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `casos`
--

CREATE TABLE `casos` (
  `id` int(11) NOT NULL,
  `descricao` text DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp(),
  `status` enum('ativo','em_acompanhamento','encerrado') DEFAULT 'ativo',
  `usuario_responsavel_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `casos`
--

INSERT INTO `casos` (`id`, `descricao`, `criado_em`, `status`, `usuario_responsavel_id`) VALUES
(6, NULL, '2026-04-07 16:32:31', 'ativo', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `casos_andamento`
--

CREATE TABLE `casos_andamento` (
  `id` int(11) NOT NULL,
  `caso_id` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `criado_em` datetime DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL,
  `tipo` enum('atendimento','visita','observacao','encerramento') DEFAULT 'observacao'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ficha_avaliacao_final`
--

CREATE TABLE `ficha_avaliacao_final` (
  `id` int(11) NOT NULL,
  `participante_id` int(11) NOT NULL,
  `sentimento_denuncia` varchar(50) DEFAULT NULL,
  `acha_justa` enum('sim','nao') DEFAULT NULL,
  `motivo_denuncia` text DEFAULT NULL,
  `dificuldade_participar` enum('sim','nao') DEFAULT NULL,
  `motivo_dificuldade` text DEFAULT NULL,
  `avaliacao_participacao` varchar(100) DEFAULT NULL,
  `sentimento_inicio` varchar(100) DEFAULT NULL,
  `outro_sentimento` text DEFAULT NULL,
  `pontos_positivos` text DEFAULT NULL,
  `pontos_negativos` text DEFAULT NULL,
  `temas_importantes` text DEFAULT NULL,
  `houve_mudanca` enum('sim','nao') DEFAULT NULL,
  `descricao_mudanca` text DEFAULT NULL,
  `gostou_grupo` text DEFAULT NULL,
  `impacto_relacionamentos` enum('melhorou','dificultou') DEFAULT NULL,
  `motivo_impacto` text DEFAULT NULL,
  `mudou_pensamento` enum('sim','nao') DEFAULT NULL,
  `explicacao_pensamento` text DEFAULT NULL,
  `recomendaria` enum('sim','nao') DEFAULT NULL,
  `motivo_recomendacao` text DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ficha_avaliacao_final`
--

INSERT INTO `ficha_avaliacao_final` (`id`, `participante_id`, `sentimento_denuncia`, `acha_justa`, `motivo_denuncia`, `dificuldade_participar`, `motivo_dificuldade`, `avaliacao_participacao`, `sentimento_inicio`, `outro_sentimento`, `pontos_positivos`, `pontos_negativos`, `temas_importantes`, `houve_mudanca`, `descricao_mudanca`, `gostou_grupo`, `impacto_relacionamentos`, `motivo_impacto`, `mudou_pensamento`, `explicacao_pensamento`, `recomendaria`, `motivo_recomendacao`, `criado_em`) VALUES
(2, 28, 'medo', 'sim', '', 'nao', '', 'Razoável', NULL, NULL, 'Entender qual foi meu erro e tentar melhorar', 'Ter perdido contato com minha ex mulher', 'Aprender a controlar os sentimentos', 'sim', '', NULL, 'melhorou', '', 'sim', '', 'sim', '', '2026-04-07 16:32:05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ficha_inclusao`
--

CREATE TABLE `ficha_inclusao` (
  `id` int(11) NOT NULL,
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
  `criado_em` datetime DEFAULT current_timestamp(),
  `participante_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ficha_inclusao`
--

INSERT INTO `ficha_inclusao` (`id`, `cor`, `situacao_civil`, `religiao`, `escolaridade`, `renda_familiar`, `ocupacao`, `profissao`, `ocupacao_companheira`, `profissao_companheira`, `condicao_moradia`, `numero_filhos`, `numero_pessoas_casa`, `problemas_saude`, `uso_medicacao`, `uso_alcool`, `frequencia_bebida`, `drogas_utilizadas`, `violencia_praticada`, `violencia_sofrida`, `historico_familiar`, `situacao_juridica`, `expectativa_grupo`, `criado_em`, `participante_id`) VALUES
(5, 'Preta', 'Divorciada', 'Católica', 'Ensino Superior completo', '7000', '', 'Diretora Escolar', NULL, NULL, 'Boa', 2, 3, 'Hipertensão', 'Remédio para pressão arterial de 12 em 12h.', 'Não', 'Nunca', 'Nenhuma', 'Defesa pessoal', 'Agressões físicas', '', 'Em julgamento', 'Superar o trauma, aprender e ajudar mais mulheres a lidar com isso.', '2026-04-07 16:24:46', 27);

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `acao` text DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `tipo` varchar(50) DEFAULT NULL,
  `entidade` varchar(50) DEFAULT NULL,
  `entidade_id` int(11) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `logs`
--

INSERT INTO `logs` (`id`, `usuario_id`, `acao`, `data`, `tipo`, `entidade`, `entidade_id`, `ip`) VALUES
(1, 3, 'Excluiu participante ID 1', '2026-03-19 16:30:13', 'DELETE', 'participantes', 1, NULL),
(2, 3, 'Editou turma: Fisioterapia → Fisioterapia (ID 2)', '2026-03-19 16:30:26', 'UPDATE', 'turmas', 2, NULL),
(3, 3, 'Editou turma: Fisioterapia → Fisioterapia (ID 2)', '2026-03-19 16:30:35', 'UPDATE', 'turmas', 2, NULL),
(4, 3, 'Editou turma: Fisioterapia → Fisioterapia (ID 2)', '2026-03-19 16:39:23', 'UPDATE', 'turmas', 2, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `participantes`
--

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
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `caso_id` int(11) DEFAULT NULL,
  `tipo` enum('vitima','autor') DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `participantes`
--

INSERT INTO `participantes` (`id`, `nome`, `cpf`, `data_nascimento`, `telefone`, `email`, `endereco`, `bairro`, `turma_id`, `observacoes`, `criado_em`, `caso_id`, `tipo`, `status`) VALUES
(27, 'Joana Barcelos', '462.359.794-89', '1995-11-25', '(21) 99872-4565', 'joanabarcelos@gmail.com', 'Rua da Esperança, 999', 'Sabugo', 3, NULL, '2026-04-07 18:00:53', 6, 'vitima', 'ativo'),
(28, 'João Cláudio', '165.297.485-59', '2001-06-25', '(21) 99875-2659', 'joaoclaudio@gmail.com', 'Avenida Juan Freytes, 22', 'Guarajuba', 4, NULL, '2026-04-07 18:38:00', 6, 'autor', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `presencas`
--

CREATE TABLE `presencas` (
  `id` int(11) NOT NULL,
  `sessao_id` int(11) NOT NULL,
  `participante_id` int(11) NOT NULL,
  `status` enum('presente','falta','justificado') DEFAULT 'falta',
  `observacao` text DEFAULT NULL,
  `registrado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pre_cadastros`
--

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

--
-- Despejando dados para a tabela `pre_cadastros`
--

INSERT INTO `pre_cadastros` (`id`, `nome`, `cpf`, `data_nascimento`, `telefone`, `email`, `endereco`, `bairro`, `status`, `criado_em`) VALUES
(1, 'Cláudio Castro', '465.265.781-56', '2002-02-15', '21987654321', 'claudiocastro@email.com', '', '', 'aprovado', '2026-03-19 19:07:38'),
(2, 'João da Silva', '123.456.789-01', '1990-05-12', '(21) 99876-1234', 'joao@gmail.com', 'Rua A, 123', 'Centro', 'aprovado', '2026-03-19 19:19:51'),
(3, 'Maria Oliveira', '234.567.890-12', '1985-08-22', '(21) 99765-4321', 'maria@gmail.com', 'Rua B, 45', 'Lages', 'aprovado', '2026-03-19 19:19:51'),
(4, 'Carlos Souza', '345.678.901-23', '1992-11-03', '(21) 99654-9876', 'carlos@gmail.com', 'Rua C, 78', 'Guarajuba', 'aprovado', '2026-03-19 19:19:51'),
(5, 'Ana Santos', '456.789.012-34', '1998-02-14', '(21) 99543-8765', 'ana@gmail.com', 'Rua D, 90', 'Centro', 'aprovado', '2026-03-19 19:19:51'),
(6, 'Lucas Pereira', '567.890.123-45', '2000-01-10', '(21) 99432-7654', 'lucas@gmail.com', 'Rua E, 11', 'Parque Mambucaba', 'aprovado', '2026-03-19 19:19:51'),
(7, 'Fernanda Costa', '678.901.234-56', '1995-07-18', '(21) 99321-6543', 'fernanda@gmail.com', 'Rua F, 22', 'Centro', 'aprovado', '2026-03-19 19:19:51'),
(8, 'Rafael Lima', '789.012.345-67', '1989-09-25', '(21) 99210-5432', 'rafael@gmail.com', 'Rua G, 33', 'Jardim Nova Era', 'aprovado', '2026-03-19 19:19:51'),
(9, 'Juliana Rocha', '890.123.456-78', '1993-04-30', '(21) 99109-4321', 'juliana@gmail.com', 'Rua H, 44', 'Centro', 'aprovado', '2026-03-19 19:19:51'),
(10, 'Bruno Alves', '901.234.567-89', '1987-06-11', '(21) 99098-3210', 'bruno@gmail.com', 'Rua I, 55', 'Lages', 'aprovado', '2026-03-19 19:19:51'),
(11, 'Camila Ribeiro', '012.345.678-90', '1999-12-01', '(21) 98987-2109', 'camila@gmail.com', 'Rua J, 66', 'Guarajuba', 'aprovado', '2026-03-19 19:19:51'),
(12, 'Diego Martins', '111.222.333-44', '1991-03-17', '(21) 98876-1098', 'diego@gmail.com', 'Rua K, 77', 'Centro', 'aprovado', '2026-03-19 19:19:51'),
(13, 'Patricia Gomes', '222.333.444-55', '1986-10-08', '(21) 98765-0987', 'patricia@gmail.com', 'Rua L, 88', 'Parque Mambucaba', 'aprovado', '2026-03-19 19:19:51'),
(14, 'Eduardo Barros', '333.444.555-66', '1994-01-27', '(21) 98654-9876', 'eduardo@gmail.com', 'Rua M, 99', 'Centro', 'aprovado', '2026-03-19 19:19:51'),
(15, 'Larissa Teixeira', '444.555.666-77', '1997-09-05', '(21) 98543-8765', 'larissa@gmail.com', 'Rua N, 100', 'Lages', 'aprovado', '2026-03-19 19:19:51'),
(16, 'Marcelo Freitas', '555.666.777-88', '1988-07-13', '(21) 98432-7654', 'marcelo@gmail.com', 'Rua O, 21', 'Guarajuba', 'aprovado', '2026-03-19 19:19:51'),
(17, 'Renata Carvalho', '666.777.888-99', '1996-05-19', '(21) 98321-6543', 'renata@gmail.com', 'Rua P, 32', 'Centro', 'aprovado', '2026-03-19 19:19:51'),
(18, 'Felipe Andrade', '777.888.999-00', '2001-11-23', '(21) 98210-5432', 'felipe@gmail.com', 'Rua Q, 43', 'Jardim Nova Era', 'aprovado', '2026-03-19 19:19:51'),
(19, 'Vanessa Lopes', '888.999.000-11', '1990-08-09', '(21) 98109-4321', 'vanessa@gmail.com', 'Rua R, 54', 'Centro', 'aprovado', '2026-03-19 19:19:51'),
(20, 'Gustavo Nunes', '999.000.111-22', '1984-02-28', '(21) 98098-3210', 'gustavo@gmail.com', 'Rua S, 65', 'Parque Mambucaba', 'aprovado', '2026-03-19 19:19:51'),
(21, 'Aline Batista', '101.202.303-44', '1993-06-16', '(21) 97987-2109', 'aline@gmail.com', 'Rua T, 76', 'Centro', 'aprovado', '2026-03-19 19:19:51'),
(22, 'Pedro Neto', '195.497.865-55', '2001-04-15', '(21) 99451-5629', 'pedroneto@email.com', 'Avenida João Alberto, 447', 'Cascata', 'aprovado', '2026-03-24 18:00:47'),
(23, 'Marina da Silva', '974.568.231-59', '1995-08-24', '(21) 99748-5628', 'marina.silva@gmail.com', 'Rua dos Macacos, 463', 'Cascata', 'aprovado', '2026-03-26 17:38:27'),
(24, 'Juliana Soares', '578.598.162-36', '2001-05-30', '(21) 99658-4875', 'juliana.soares@gmail.com', 'Rua Bezerra de Menezes, 637', 'Lages', 'aprovado', '2026-03-26 17:50:19'),
(25, 'Joana Barcelos', '462.359.794-89', '1995-11-25', '(21) 99872-4565', 'joanabarcelos@gmail.com', 'Rua da Esperança, 999', 'Sabugo', 'aprovado', '2026-03-27 18:28:12'),
(26, 'Mariana de Souza', '487.596.265-58', '1992-12-15', '(21) 99745-4815', 'marianasouza@gmail.com', 'Rua da Alegria, 877', 'Bom Jardim', 'aprovado', '2026-03-27 18:29:40'),
(27, 'João Cláudio', '165.297.485-59', '2001-06-25', '(21) 99875-2659', 'joaoclaudio@gmail.com', 'Avenida Juan Freytes, 22', 'Guarajuba', 'aprovado', '2026-04-07 18:35:49');

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas`
--

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

--
-- Despejando dados para a tabela `turmas`
--

INSERT INTO `turmas` (`id`, `nome`, `descricao`, `responsavel`, `data_inicio`, `data_fim`, `status`, `criado_em`) VALUES
(3, 'Vítimas', '', 'Márcia', '2026-04-15', '2026-06-15', 'ativa', '2026-03-26 17:36:51'),
(4, 'Agressores', '', 'Juliana', '2026-04-07', '2026-12-31', 'ativa', '2026-04-07 18:00:32');

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas_sessoes`
--

CREATE TABLE `turmas_sessoes` (
  `id` int(11) NOT NULL,
  `turma_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `turmas_sessoes`
--

INSERT INTO `turmas_sessoes` (`id`, `turma_id`, `data`, `descricao`, `criado_em`) VALUES
(1, 3, '2026-03-27', NULL, '2026-03-27 17:58:00'),
(2, 3, '2026-03-18', NULL, '2026-03-27 18:21:58');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel` enum('admin','atendente') DEFAULT 'atendente',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `nivel`, `criado_em`) VALUES
(1, 'Administrador', 'admin@email.com', '$2y$10$/jPwG0Pinwrzcawe5dcz..ZeYdnLnmjpXf9dUCdZpwg.u.2mcacYq', 'admin', '2026-03-17 17:49:48'),
(2, 'Pedrinho Games', 'pedrinho@email.com', '$2y$10$WtFyjjYIaHCmekuZI62xJeF5hDbS.1sdbBvuPWF0.Y6bjhSqIG2Z6', 'atendente', '2026-03-17 19:32:20'),
(3, 'Samuel', 'samuel@email.com', '$2y$10$l2Sm420K4Zx1kR53DqkRdOBZ3PqU4ah7iRp05MbSZo2FSyhHakrrO', 'admin', '2026-03-17 19:34:47');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `anexos`
--
ALTER TABLE `anexos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caso_id` (`caso_id`),
  ADD KEY `participante_id` (`participante_id`);

--
-- Índices de tabela `casos`
--
ALTER TABLE `casos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_caso_usuario` (`usuario_responsavel_id`);

--
-- Índices de tabela `casos_andamento`
--
ALTER TABLE `casos_andamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `caso_id` (`caso_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `ficha_avaliacao_final`
--
ALTER TABLE `ficha_avaliacao_final`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `participante_id` (`participante_id`);

--
-- Índices de tabela `ficha_inclusao`
--
ALTER TABLE `ficha_inclusao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `participante_id` (`participante_id`),
  ADD UNIQUE KEY `participante_id_2` (`participante_id`);

--
-- Índices de tabela `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `participantes`
--
ALTER TABLE `participantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD KEY `turma_id` (`turma_id`),
  ADD KEY `caso_id` (`caso_id`);

--
-- Índices de tabela `presencas`
--
ALTER TABLE `presencas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sessao_id` (`sessao_id`,`participante_id`),
  ADD KEY `participante_id` (`participante_id`);

--
-- Índices de tabela `pre_cadastros`
--
ALTER TABLE `pre_cadastros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Índices de tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `turmas_sessoes`
--
ALTER TABLE `turmas_sessoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turma_id` (`turma_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `anexos`
--
ALTER TABLE `anexos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `casos`
--
ALTER TABLE `casos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `casos_andamento`
--
ALTER TABLE `casos_andamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ficha_avaliacao_final`
--
ALTER TABLE `ficha_avaliacao_final`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `ficha_inclusao`
--
ALTER TABLE `ficha_inclusao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `presencas`
--
ALTER TABLE `presencas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pre_cadastros`
--
ALTER TABLE `pre_cadastros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `turmas_sessoes`
--
ALTER TABLE `turmas_sessoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `anexos`
--
ALTER TABLE `anexos`
  ADD CONSTRAINT `anexos_ibfk_1` FOREIGN KEY (`caso_id`) REFERENCES `casos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `anexos_ibfk_2` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `casos`
--
ALTER TABLE `casos`
  ADD CONSTRAINT `fk_caso_usuario` FOREIGN KEY (`usuario_responsavel_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `casos_andamento`
--
ALTER TABLE `casos_andamento`
  ADD CONSTRAINT `casos_andamento_ibfk_1` FOREIGN KEY (`caso_id`) REFERENCES `casos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `casos_andamento_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `ficha_avaliacao_final`
--
ALTER TABLE `ficha_avaliacao_final`
  ADD CONSTRAINT `ficha_avaliacao_final_ibfk_1` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ficha_inclusao`
--
ALTER TABLE `ficha_inclusao`
  ADD CONSTRAINT `fk_ficha_participante` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `participantes`
--
ALTER TABLE `participantes`
  ADD CONSTRAINT `participantes_ibfk_1` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`),
  ADD CONSTRAINT `participantes_ibfk_2` FOREIGN KEY (`caso_id`) REFERENCES `casos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `presencas`
--
ALTER TABLE `presencas`
  ADD CONSTRAINT `presencas_ibfk_1` FOREIGN KEY (`sessao_id`) REFERENCES `turmas_sessoes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `presencas_ibfk_2` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `turmas_sessoes`
--
ALTER TABLE `turmas_sessoes`
  ADD CONSTRAINT `turmas_sessoes_ibfk_1` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
