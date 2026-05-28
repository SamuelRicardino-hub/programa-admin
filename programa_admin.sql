-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 28/05/2026 às 19:56
-- Versão do servidor: 10.11.14-MariaDB-0ubuntu0.24.04.1
-- Versão do PHP: 8.3.6

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
  `avaliacao_participacao` varchar(255) DEFAULT NULL,
  `pontos_positivos` text DEFAULT NULL,
  `pontos_negativos` text DEFAULT NULL,
  `temas_importantes` text DEFAULT NULL,
  `houve_mudanca` enum('sim','nao') DEFAULT NULL,
  `descricao_mudanca` text DEFAULT NULL,
  `gostou_grupo` enum('sim','nao') DEFAULT NULL,
  `sentimento_inicio` text DEFAULT NULL,
  `recomendaria` enum('sim','nao') DEFAULT NULL,
  `motivo_recomendacao` text DEFAULT NULL,
  `sugestoes` text DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp(),
  `vantagens_experiencia` text DEFAULT NULL,
  `desvantagens_experiencia` text DEFAULT NULL,
  `mudanca_visao_mundo` varchar(50) DEFAULT NULL,
  `relacao_grupo_pensamento` text DEFAULT NULL,
  `impacto_mudanca_costumes` text DEFAULT NULL,
  `mudanca_relacionamentos` text DEFAULT NULL,
  `o_que_mais_gostou` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ficha_avaliacao_final`
--

INSERT INTO `ficha_avaliacao_final` (`id`, `participante_id`, `sentimento_denuncia`, `acha_justa`, `motivo_denuncia`, `dificuldade_participar`, `motivo_dificuldade`, `avaliacao_participacao`, `pontos_positivos`, `pontos_negativos`, `temas_importantes`, `houve_mudanca`, `descricao_mudanca`, `gostou_grupo`, `sentimento_inicio`, `recomendaria`, `motivo_recomendacao`, `sugestoes`, `criado_em`, `vantagens_experiencia`, `desvantagens_experiencia`, `mudanca_visao_mundo`, `relacao_grupo_pensamento`, `impacto_mudanca_costumes`, `mudanca_relacionamentos`, `o_que_mais_gostou`) VALUES
(6, 173, 'Injustiçado', NULL, 'fdsgfds', NULL, 'gfdsg', 'boa_expressao', NULL, NULL, 'Regras de comportamento, Álcool e drogas, Inteligência emocional', NULL, NULL, NULL, 'Muito chato', NULL, 'gfsdgs', NULL, '2026-05-22 17:58:07', 'gszdf', 'gdsgds', 'Sim', 'gfdssg', 'gsdfgs', 'gfdsgsdf', 'gfsd');

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
  `participante_id` int(11) NOT NULL,
  `numero_caso` varchar(50) DEFAULT NULL,
  `numero_processo` varchar(50) DEFAULT NULL,
  `parentesco` varchar(100) DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `naturalidade` varchar(100) DEFAULT NULL,
  `relacionamento_atual` varchar(50) DEFAULT NULL,
  `relacionamento_detalhe` varchar(100) DEFAULT NULL,
  `trabalho_ocupacao` varchar(100) DEFAULT NULL,
  `religiao_outro` varchar(150) DEFAULT NULL,
  `escolaridade_outro` varchar(150) DEFAULT NULL,
  `renda_outro` varchar(150) DEFAULT NULL,
  `trabalho_outro` varchar(150) DEFAULT NULL,
  `moradia_outro` varchar(150) DEFAULT NULL,
  `relacionamento_outro` varchar(150) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `parentesco_denunciante` varchar(100) DEFAULT NULL,
  `relacionamento_amoroso_detalhe` varchar(100) DEFAULT NULL,
  `filhos_com_atual` varchar(50) DEFAULT NULL,
  `filhos_com_denunciante` varchar(50) DEFAULT NULL,
  `frequencia_ver_filhos` varchar(100) DEFAULT NULL,
  `conversa_criacao_filhos` varchar(50) DEFAULT NULL,
  `auxilio_licoes_casa` varchar(50) DEFAULT NULL,
  `reunioes_escola` varchar(50) DEFAULT NULL,
  `divisao_domestica` varchar(50) DEFAULT NULL,
  `relacionamento_parceira_atual` varchar(50) DEFAULT NULL,
  `frequencia_bares` varchar(50) DEFAULT NULL,
  `bebidas_comuns` text DEFAULT NULL,
  `praticou_violencia_ultimo_ano` varchar(100) DEFAULT NULL,
  `violencia_em_quem` varchar(100) DEFAULT NULL,
  `tipo_violencia_praticada` text DEFAULT NULL,
  `pai_presente_infancia` varchar(50) DEFAULT NULL,
  `conflitos_infancia` text DEFAULT NULL,
  `ja_foi_agredido_companheira` varchar(50) DEFAULT NULL,
  `tipo_violencia_sofrida` text DEFAULT NULL,
  `denunciou_motivo` text DEFAULT NULL,
  `indiciado_anteriormente` varchar(50) DEFAULT NULL,
  `tipo_violencia_anterior` text DEFAULT NULL,
  `uso_drogas_antes_fato` varchar(50) DEFAULT NULL,
  `indiciado_outro_motivo` text DEFAULT NULL,
  `historico_prisao` text DEFAULT NULL,
  `qtd_filhos` int(11) DEFAULT 0,
  `pessoas_na_casa` int(11) DEFAULT 0,
  `medicacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ficha_inclusao`
--

INSERT INTO `ficha_inclusao` (`id`, `cor`, `situacao_civil`, `religiao`, `escolaridade`, `renda_familiar`, `profissao`, `ocupacao_companheira`, `profissao_companheira`, `condicao_moradia`, `numero_filhos`, `numero_pessoas_casa`, `problemas_saude`, `uso_medicacao`, `uso_alcool`, `frequencia_bebida`, `drogas_utilizadas`, `violencia_praticada`, `violencia_sofrida`, `historico_familiar`, `situacao_juridica`, `expectativa_grupo`, `criado_em`, `participante_id`, `numero_caso`, `numero_processo`, `parentesco`, `idade`, `naturalidade`, `relacionamento_atual`, `relacionamento_detalhe`, `trabalho_ocupacao`, `religiao_outro`, `escolaridade_outro`, `renda_outro`, `trabalho_outro`, `moradia_outro`, `relacionamento_outro`, `nome`, `parentesco_denunciante`, `relacionamento_amoroso_detalhe`, `filhos_com_atual`, `filhos_com_denunciante`, `frequencia_ver_filhos`, `conversa_criacao_filhos`, `auxilio_licoes_casa`, `reunioes_escola`, `divisao_domestica`, `relacionamento_parceira_atual`, `frequencia_bares`, `bebidas_comuns`, `praticou_violencia_ultimo_ano`, `violencia_em_quem`, `tipo_violencia_praticada`, `pai_presente_infancia`, `conflitos_infancia`, `ja_foi_agredido_companheira`, `tipo_violencia_sofrida`, `denunciou_motivo`, `indiciado_anteriormente`, `tipo_violencia_anterior`, `uso_drogas_antes_fato`, `indiciado_outro_motivo`, `historico_prisao`, `qtd_filhos`, `pessoas_na_casa`, `medicacao`) VALUES
(10, 'Branca', NULL, 'Espírita', 'Ensino Superior', 'De 1 a 2 SM', 'das', NULL, NULL, 'Cedida / Casa de Parentes', NULL, NULL, 'dsa', NULL, NULL, NULL, 'Maconha, Cola, Cocaína', NULL, NULL, NULL, NULL, NULL, '2026-05-22 17:52:36', 173, '2424', NULL, NULL, NULL, 'ewq', 'Divorciado', NULL, 'Desempregado', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sda', 'Com a denunciante', '2', '2', 'Mensalmente', 'Frequentemente', 'Raramente', 'Frequentemente', 'Raramente', 'Bom', '1 vez na semana', 'Cerveja, Cachaça, Whisky', 'Em um homem', 'Pai', 'Psicológica', '', '', '', '', '', 'Sim', 'dsa', 'Sim', 'dsa', 'fds', 2, 3, 'dsa');

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
  `ip` varchar(45) DEFAULT NULL,
  `dados` text DEFAULT NULL,
  `criado_em` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `logs`
--

INSERT INTO `logs` (`id`, `usuario_id`, `acao`, `data`, `tipo`, `entidade`, `entidade_id`, `ip`, `dados`, `criado_em`) VALUES
(1, 3, 'Excluiu participante ID 1', '2026-03-19 16:30:13', 'DELETE', 'participantes', 1, NULL, NULL, '2026-05-28 19:51:25'),
(2, 3, 'Editou turma: Fisioterapia → Fisioterapia (ID 2)', '2026-03-19 16:30:26', 'UPDATE', 'turmas', 2, NULL, NULL, '2026-05-28 19:51:25'),
(3, 3, 'Editou turma: Fisioterapia → Fisioterapia (ID 2)', '2026-03-19 16:30:35', 'UPDATE', 'turmas', 2, NULL, NULL, '2026-05-28 19:51:25'),
(4, 3, 'Editou turma: Fisioterapia → Fisioterapia (ID 2)', '2026-03-19 16:39:23', 'UPDATE', 'turmas', 2, NULL, NULL, '2026-05-28 19:51:25'),
(6, 1, 'Pré-cadastro aprovado', '2026-04-16 16:42:04', 'APROVACAO', 'pre_cadastro', 32, '192.168.101.28', NULL, '2026-05-28 19:51:25'),
(7, 1, 'Pré-cadastro aprovado', '2026-04-16 16:42:17', 'APROVACAO', 'pre_cadastro', 30, '192.168.101.28', NULL, '2026-05-28 19:51:25'),
(8, 1, 'Editou participante: Juan Matheus Silva → Maria Gilvânia (ID 170)', '2026-04-16 16:43:44', 'UPDATE', 'participantes', 170, '192.168.101.28', NULL, '2026-05-28 19:51:25'),
(9, 1, 'Editou turma: Agressores → Turma 1 (ID 4)', '2026-04-16 16:49:20', 'UPDATE', 'turmas', 4, '192.168.101.28', NULL, '2026-05-28 19:51:25'),
(10, 1, 'Editou turma: Vítimas → Turma 2 (ID 3)', '2026-04-16 16:49:28', 'UPDATE', 'turmas', 3, '192.168.101.28', NULL, '2026-05-28 19:51:25'),
(11, 1, 'Excluiu participante ID 27', '2026-05-21 18:33:39', 'DELETE', 'participantes', 27, '192.168.1.15', NULL, '2026-05-28 19:51:25'),
(12, 1, 'Excluiu sessão ID 2', '2026-05-21 19:08:44', 'DELETE', 'turmas_sessoes', 2, '192.168.1.15', '1', '2026-05-28 19:51:25'),
(13, 1, 'Excluiu sessão ID 1', '2026-05-21 19:08:46', 'DELETE', 'turmas_sessoes', 1, '192.168.1.15', '1', '2026-05-28 19:51:25'),
(14, 1, 'Excluiu sessão ID 8', '2026-05-21 19:14:36', 'DELETE', 'turmas_sessoes', 8, '192.168.1.15', '1', '2026-05-28 19:51:25'),
(15, 1, 'Excluiu sessão ID 5', '2026-05-21 19:14:39', 'DELETE', 'turmas_sessoes', 5, '192.168.1.15', '1', '2026-05-28 19:51:25'),
(16, 1, 'Excluiu sessão ID 6', '2026-05-21 19:14:43', 'DELETE', 'turmas_sessoes', 6, '192.168.1.15', '1', '2026-05-28 19:51:25'),
(17, 1, 'Excluiu sessão ID 7', '2026-05-21 19:14:45', 'DELETE', 'turmas_sessoes', 7, '192.168.1.15', '1', '2026-05-28 19:51:25'),
(18, 1, 'Excluiu participante ID 170', '2026-05-21 19:31:07', 'DELETE', 'participantes', 170, '192.168.1.15', NULL, '2026-05-28 19:51:25'),
(19, 1, 'Excluiu sessão ID 4', '2026-05-21 19:33:34', 'DELETE', 'turmas_sessoes', 4, '192.168.1.15', '1', '2026-05-28 19:51:25'),
(20, 1, 'Excluiu participante ID 28', '2026-05-21 19:39:18', 'DELETE', 'participantes', 28, '192.168.1.15', NULL, '2026-05-28 19:51:25'),
(21, 1, 'Excluiu participante ID 171', '2026-05-21 19:39:20', 'DELETE', 'participantes', 171, '192.168.1.15', NULL, '2026-05-28 19:51:25'),
(22, 1, 'Excluiu participante ID 169', '2026-05-21 19:39:23', 'DELETE', 'participantes', 169, '192.168.1.15', NULL, '2026-05-28 19:51:25'),
(23, 1, 'Excluiu participante ID 172', '2026-05-21 19:39:25', 'DELETE', 'participantes', 172, '192.168.1.15', NULL, '2026-05-28 19:51:25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `participantes`
--

CREATE TABLE `participantes` (
  `id` int(11) NOT NULL,
  `nome` varchar(120) NOT NULL,
  `numero_processo` varchar(50) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `turma_id` int(11) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `caso_id` int(11) DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `total_passagens` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `participantes`
--

INSERT INTO `participantes` (`id`, `nome`, `numero_processo`, `cpf`, `data_nascimento`, `telefone`, `email`, `endereco`, `bairro`, `turma_id`, `observacoes`, `criado_em`, `caso_id`, `status`, `total_passagens`) VALUES
(173, 'Juan Matheus Silva', '261', NULL, NULL, NULL, NULL, NULL, NULL, 4, '', '2026-05-21 19:39:40', NULL, 'ativo', 2),
(174, 'Marcos Souza', '264', NULL, NULL, NULL, NULL, NULL, NULL, 4, '', '2026-05-21 19:39:58', NULL, 'ativo', 1),
(175, 'Lucas Ferreira', '266', NULL, NULL, NULL, NULL, NULL, NULL, 3, '', '2026-05-21 19:40:32', NULL, 'ativo', 1),
(176, 'Marcio Braga', '267', NULL, NULL, NULL, NULL, NULL, NULL, 3, '', '2026-05-21 19:41:00', NULL, 'ativo', 1);

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

--
-- Despejando dados para a tabela `presencas`
--

INSERT INTO `presencas` (`id`, `sessao_id`, `participante_id`, `status`, `observacao`, `registrado_em`) VALUES
(11, 3, 173, 'presente', '', '2026-05-22 18:24:03'),
(12, 3, 174, 'justificado', '', '2026-05-22 18:24:03'),
(19, 11, 173, 'presente', '', '2026-05-22 18:38:10'),
(20, 11, 174, 'presente', '', '2026-05-22 18:38:10');

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
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `turmas`
--

INSERT INTO `turmas` (`id`, `nome`, `descricao`, `responsavel`, `data_inicio`, `data_fim`, `status`, `criado_em`, `usuario_id`) VALUES
(3, 'Turma 2', '', 'Márcia', '2026-04-15', '2026-06-15', 'ativa', '2026-03-26 17:36:51', NULL),
(4, 'Turma 1', '', 'Juliana', '2026-04-07', '2026-12-31', 'ativa', '2026-04-07 18:00:32', NULL),
(5, 'Turma 4', '', '', '2026-05-22', '2026-06-05', 'ativa', '2026-05-22 19:10:23', 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas_participantes`
--

CREATE TABLE `turmas_participantes` (
  `id` int(11) NOT NULL,
  `turma_id` int(11) NOT NULL,
  `participante_id` int(11) NOT NULL,
  `data_vinculo` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `turmas_participantes`
--

INSERT INTO `turmas_participantes` (`id`, `turma_id`, `participante_id`, `data_vinculo`) VALUES
(9, 3, 175, '2026-05-21 19:41:18'),
(10, 3, 176, '2026-05-21 19:41:26'),
(12, 4, 173, '2026-05-22 17:46:44'),
(15, 4, 174, '2026-05-28 19:55:40');

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
(3, 4, '2026-04-10', 'Sessão do dia 10/04', '2026-04-09 17:45:48'),
(9, 3, '2026-05-22', '1° Encontro: Introdução', '2026-05-21 19:33:52'),
(10, 4, '2026-05-22', 'teste 2', '2026-05-22 18:37:56'),
(11, 4, '2026-05-22', 'teste 3', '2026-05-22 18:38:05');

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
(4, 'Atendente teste', 'teste@email.com', '$2y$10$Zk9ekLsRGEQBu2Orblejjeom9h0PpQIp6d0X3OOJpzb.Jixox3Iqy', 'atendente', '2026-05-22 17:39:16');

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
  ADD KEY `caso_id` (`caso_id`),
  ADD KEY `idx_nome` (`nome`);

--
-- Índices de tabela `presencas`
--
ALTER TABLE `presencas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sessao_id` (`sessao_id`,`participante_id`),
  ADD KEY `participante_id` (`participante_id`);

--
-- Índices de tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_turmas_usuario` (`usuario_id`);

--
-- Índices de tabela `turmas_participantes`
--
ALTER TABLE `turmas_participantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turma_id` (`turma_id`),
  ADD KEY `participante_id` (`participante_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `casos_andamento`
--
ALTER TABLE `casos_andamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `ficha_avaliacao_final`
--
ALTER TABLE `ficha_avaliacao_final`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `ficha_inclusao`
--
ALTER TABLE `ficha_inclusao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT de tabela `presencas`
--
ALTER TABLE `presencas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `turmas_participantes`
--
ALTER TABLE `turmas_participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `turmas_sessoes`
--
ALTER TABLE `turmas_sessoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Restrições para tabelas `turmas`
--
ALTER TABLE `turmas`
  ADD CONSTRAINT `fk_turmas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `turmas_participantes`
--
ALTER TABLE `turmas_participantes`
  ADD CONSTRAINT `turmas_participantes_ibfk_1` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `turmas_participantes_ibfk_2` FOREIGN KEY (`participante_id`) REFERENCES `participantes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `turmas_sessoes`
--
ALTER TABLE `turmas_sessoes`
  ADD CONSTRAINT `turmas_sessoes_ibfk_1` FOREIGN KEY (`turma_id`) REFERENCES `turmas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
