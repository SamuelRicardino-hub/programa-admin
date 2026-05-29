-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14/05/2026 às 20:41
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
-- Banco de dados: `projetoser`
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
  `avaliacao_participacao` enum('otima','boa','ruim') DEFAULT NULL,
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
(2, 28, 'medo', 'sim', '', 'nao', '', '', 'Entender qual foi meu erro e tentar melhorar', 'Ter perdido contato com minha ex mulher', 'Aprender a controlar os sentimentos', 'sim', '', NULL, NULL, 'sim', '', NULL, '2026-04-07 16:32:05', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 169, 'tranquilo', 'sim', NULL, 'nao', NULL, 'boa', 'Porque mudou meu pensamento.', NULL, 'Autoresponsabilidade, Violencia Baseada no Genero, Saude do Homem, Paternidade', 'sim', 'Hoje eu penso de maneira totalmente diferente e pretendo mudar daqui em diante.', '', NULL, 'sim', 'Grupo muito bom, aprendi bastante', NULL, '2026-04-30 16:08:26', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
  `trabalho` varchar(100) DEFAULT NULL,
  `religiao_outro` varchar(150) DEFAULT NULL,
  `escolaridade_outro` varchar(150) DEFAULT NULL,
  `renda_outro` varchar(150) DEFAULT NULL,
  `trabalho_outro` varchar(150) DEFAULT NULL,
  `moradia_outro` varchar(150) DEFAULT NULL,
  `relacionamento_outro` varchar(150) DEFAULT NULL,
  `nome` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `ficha_inclusao`
--

INSERT INTO `ficha_inclusao` (`id`, `cor`, `situacao_civil`, `religiao`, `escolaridade`, `renda_familiar`, `profissao`, `ocupacao_companheira`, `profissao_companheira`, `condicao_moradia`, `numero_filhos`, `numero_pessoas_casa`, `problemas_saude`, `uso_medicacao`, `uso_alcool`, `frequencia_bebida`, `drogas_utilizadas`, `violencia_praticada`, `violencia_sofrida`, `historico_familiar`, `situacao_juridica`, `expectativa_grupo`, `criado_em`, `participante_id`, `numero_caso`, `numero_processo`, `parentesco`, `idade`, `naturalidade`, `relacionamento_atual`, `relacionamento_detalhe`, `trabalho`, `religiao_outro`, `escolaridade_outro`, `renda_outro`, `trabalho_outro`, `moradia_outro`, `relacionamento_outro`, `nome`) VALUES
(5, 'Preta', 'Divorciada', 'Católica', 'Ensino Superior completo', '7000', 'Diretora Escolar', NULL, NULL, 'Boa', 2, 3, 'Hipertensão', 'Remédio para pressão arterial de 12 em 12h.', 'Não', 'Nunca', 'Nenhuma', 'Defesa pessoal', 'Agressões físicas', '', 'Em julgamento', 'Superar o trauma, aprender e ajudar mais mulheres a lidar com isso.', '2026-04-07 16:24:46', 27, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 'Preta', NULL, 'Católica', 'Ensino Superior', NULL, 'Inspetor Escolar', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-17 15:57:29', 169, '1', '1', 'Ex-marido', 46, 'Brasileiro', NULL, NULL, 'Empregado Carteira Assinada', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 'Parda', NULL, 'Sem Religião', 'Ensino Superior', 'De 1 a 2 SM', 'Operador de Máquinas Pesadas', NULL, NULL, 'Própria', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-14 14:53:30', 170, '2026-002', '22', 'Ex-marido', 45, 'Paracambi, RJ', 'Solteiro', 'Outro', 'Empregado (CLT)', '', '', '', '', '', '', 'Alex Lima');

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
  `dados` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `logs`
--

INSERT INTO `logs` (`id`, `usuario_id`, `acao`, `data`, `tipo`, `entidade`, `entidade_id`, `ip`, `dados`) VALUES
(1, 3, 'Excluiu participante ID 1', '2026-03-19 16:30:13', 'DELETE', 'participantes', 1, NULL, NULL),
(2, 3, 'Editou turma: Fisioterapia → Fisioterapia (ID 2)', '2026-03-19 16:30:26', 'UPDATE', 'turmas', 2, NULL, NULL),
(3, 3, 'Editou turma: Fisioterapia → Fisioterapia (ID 2)', '2026-03-19 16:30:35', 'UPDATE', 'turmas', 2, NULL, NULL),
(4, 3, 'Editou turma: Fisioterapia → Fisioterapia (ID 2)', '2026-03-19 16:39:23', 'UPDATE', 'turmas', 2, NULL, NULL),
(6, 1, 'Pré-cadastro aprovado', '2026-04-16 16:42:04', 'APROVACAO', 'pre_cadastro', 32, '192.168.101.28', NULL),
(7, 1, 'Pré-cadastro aprovado', '2026-04-16 16:42:17', 'APROVACAO', 'pre_cadastro', 30, '192.168.101.28', NULL),
(8, 1, 'Editou participante: Juan Matheus Silva → Maria Gilvânia (ID 170)', '2026-04-16 16:43:44', 'UPDATE', 'participantes', 170, '192.168.101.28', NULL),
(9, 1, 'Editou turma: Agressores → Turma 1 (ID 4)', '2026-04-16 16:49:20', 'UPDATE', 'turmas', 4, '192.168.101.28', NULL),
(10, 1, 'Editou turma: Vítimas → Turma 2 (ID 3)', '2026-04-16 16:49:28', 'UPDATE', 'turmas', 3, '192.168.101.28', NULL);

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
  `total_passagens` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `participantes`
--

INSERT INTO `participantes` (`id`, `nome`, `numero_processo`, `cpf`, `data_nascimento`, `telefone`, `email`, `endereco`, `bairro`, `turma_id`, `observacoes`, `criado_em`, `caso_id`, `status`, `total_passagens`) VALUES
(27, 'Pedro Henrique', '26', '46235979489', '1995-11-25', '(21) 99872-4565', 'joanabarcelos@gmail.com', 'Rua da Esperança, 999', 'Sabugo', 3, '', '2026-04-07 18:00:53', 6, 'ativo', 1),
(28, 'João Cláudio', '45', '16529748559', '2001-06-25', '(21) 99875-2659', 'joaoclaudio@gmail.com', 'Avenida Juan Freytes, 22', 'Guarajuba', 4, '', '2026-04-07 18:38:00', 6, 'ativo', 1),
(169, 'Mario Junior', '15', '05296440706', '1979-11-09', '21998758429', 'eversonfonseca@gmail.com', 'Avenida Jonas Leal, 769', 'Lages', 4, '', '2026-04-16 19:42:04', NULL, 'ativo', 1),
(170, 'Alex Lima', '22', '52998224725', '2008-01-21', '21987546884', 'juanmatheus@email.com', 'Avenida Pedro II, 154', 'Jardim Nova Era', 3, '', '2026-04-16 19:42:17', NULL, 'ativo', 1);

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
(1, 3, 28, 'presente', '', '2026-04-09 17:47:19'),
(7, 2, 27, 'presente', '', '2026-05-08 19:13:39'),
(8, 2, 170, 'falta', '', '2026-05-08 19:13:39'),
(9, 1, 27, 'presente', '', '2026-05-08 19:13:45'),
(10, 1, 170, 'presente', '', '2026-05-08 19:13:45');

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
(3, 'Turma 2', '', 'Márcia', '2026-04-15', '2026-06-15', 'ativa', '2026-03-26 17:36:51'),
(4, 'Turma 1', '', 'Juliana', '2026-04-07', '2026-12-31', 'ativa', '2026-04-07 18:00:32');

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
(2, 3, '2026-03-18', NULL, '2026-03-27 18:21:58'),
(3, 4, '2026-04-10', 'Sessão do dia 10/04', '2026-04-09 17:45:48');

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
(1, 'Administrador', 'admin@email.com', '$2y$10$/jPwG0Pinwrzcawe5dcz..ZeYdnLnmjpXf9dUCdZpwg.u.2mcacYq', 'admin', '2026-03-17 17:49:48');

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `ficha_inclusao`
--
ALTER TABLE `ficha_inclusao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT de tabela `presencas`
--
ALTER TABLE `presencas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `turmas_participantes`
--
ALTER TABLE `turmas_participantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `turmas_sessoes`
--
ALTER TABLE `turmas_sessoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
