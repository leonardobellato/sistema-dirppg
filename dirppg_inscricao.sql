CREATE TABLE `programa` (
  `id_programa` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(150) NOT NULL
);

CREATE TABLE `curso` (
  `id_curso` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_programa` INT UNSIGNED NOT NULL,
  `tipo` VARCHAR(20) NOT NULL
);

ALTER TABLE `curso` ADD CONSTRAINT fk_curso_programa FOREIGN KEY (`id_programa`) REFERENCES `programa`(`id_programa`);

CREATE TABLE `edital` (
  `id_edital` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_curso` INT UNSIGNED NOT NULL,
  `nome` VARCHAR(200) NOT NULL,
  `vigente` TINYINT(1) DEFAULT 1,
  `data_inscricao_inicio` DATE NOT NULL,
  `data_inscricao_fim` DATE NOT NULL,
  `data_recurso1_inicio` DATE NOT NULL,
  `data_recurso1_fim` DATE NOT NULL,
  `data_homologacao1_inicio` DATE NOT NULL,
  `data_homologacao1_fim` DATE NOT NULL,
  `data_recurso2_inicio` DATE DEFAULT NULL,
  `data_recurso2_fim` DATE DEFAULT NULL,
  `data_homologacao2_inicio` DATE DEFAULT NULL,
  `data_homologacao2_fim` DATE DEFAULT NULL
);

ALTER TABLE `edital` ADD CONSTRAINT fk_edital_curso FOREIGN KEY (`id_curso`) REFERENCES `curso`(`id_curso`);


CREATE TABLE `admin_secretario` (
  `cod_user` int(11) NOT NULL,
  `nome` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `senha` VARCHAR(150) DEFAULT NULL,
  `nivel_acesso` VARCHAR(1) DEFAULT NULL,
  `profAvaliador` int(1) DEFAULT '1'
);


CREATE TABLE `area_concentracao` (
  `cod_area_conc` int(11) NOT NULL,
  `cod_curso` int(11) DEFAULT NULL,
  `nome_area` VARCHAR(150) DEFAULT NULL
);



CREATE TABLE `aud_tentativa_inscricao` (
  `aud_cod_tentativa_inscricao` int(11) NOT NULL,
  `cod_candidato` int(11) DEFAULT NULL,
  `ip` VARCHAR(150) DEFAULT NULL,
  `navegador` VARCHAR(100) DEFAULT NULL,
  `memoria_disponivel_servidor` VARCHAR(600) DEFAULT NULL,
  `data_hora` datetime DEFAULT NULL,
  `cod_edital` int(11) DEFAULT NULL,
  `cod_linha_pes` int(11) DEFAULT NULL,
  `cod_sublinha` int(11) DEFAULT NULL,
  `observacao` VARCHAR(2000) DEFAULT NULL
);



CREATE TABLE `aud_tentativa_inscricao_disciplina` (
  `aud_cod_tentativa_inscricao_disciplina` int(11) NOT NULL,
  `aud_cod_tentativa_inscricao` int(11) DEFAULT NULL,
  `cod_disciplina` bigint(20) DEFAULT NULL
);



CREATE TABLE `aud_tentativa_submeter_documento` (
  `aud_cod_tentativa_submeter_documento` int(11) NOT NULL,
  `aud_cod_tentativa_inscricao` int(11) DEFAULT NULL,
  `nome_arquivo` VARCHAR(300) DEFAULT NULL,
  `tipo_arquivo` VARCHAR(150) DEFAULT NULL,
  `tamanho_arquivo_em_bytes` int(11) DEFAULT NULL,
  `cod_erro` int(11) DEFAULT NULL
);



CREATE TABLE `avaliacao` (
  `idAvaliacao` int(4) NOT NULL,
  `ip` VARCHAR(30) DEFAULT NULL,
  `admin_secretario` int(4) NOT NULL,
  `cod_edital` int(4) NOT NULL,
  `cod_candidato` int(4) NOT NULL,
  `tipoAvaliacao` int(4) NOT NULL,
  `dataAlteracao` date DEFAULT NULL
);



CREATE TABLE `candidato` (
  `cod_candidato` int(11) NOT NULL,
  `nome_candidato` VARCHAR(100) DEFAULT NULL,
  `cpf` VARCHAR(11) DEFAULT NULL,
  `rg` VARCHAR(15) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `telefone` VARCHAR(14) DEFAULT NULL,
  `nivel_acesso` VARCHAR(1) DEFAULT NULL,
  `senha` VARCHAR(150) DEFAULT NULL
);


CREATE TABLE `comprovante` (
  `cod_comprovante` bigint(20) NOT NULL,
  `codigo_validacao` VARCHAR(35) CHARACTER SET utf8 NOT NULL,
  `data_comprovante` datetime NOT NULL,
  `cod_inscricao` int(11) NOT NULL,
  `tipo_comprovante` VARCHAR(30) DEFAULT NULL
);







CREATE TABLE `disciplina` (
  `cod_disciplina` bigint(20) NOT NULL,
  `nome_disciplina` VARCHAR(2000) NOT NULL,
  `cod_curso` int(11) DEFAULT NULL,
  `visivel_inscricao` tinyint(4) DEFAULT NULL
);


CREATE TABLE `documento` (
  `id_documento` int(11) NOT NULL,
  `cod_inscricao` int(11) DEFAULT NULL,
  `documento` VARCHAR(200) DEFAULT NULL
);






CREATE TABLE `inscricao` (
  `cod_inscricao` int(11) NOT NULL,
  `cod_candidato` int(11) DEFAULT NULL,
  `cod_edital` int(11) DEFAULT NULL,
  `cod_linha_pes` int(11) DEFAULT NULL,
  `cod_sublinha` int(11) DEFAULT NULL,
  `mensagem` VARCHAR(600) DEFAULT NULL,
  `status_inscricao` VARCHAR(1) DEFAULT NULL,
  `data_inscricao` date DEFAULT NULL,
  `situacao_cand_programa` VARCHAR(1) DEFAULT NULL,
  `coorientador` VARCHAR(200) DEFAULT NULL,
  `orientador` VARCHAR(200) DEFAULT NULL,
  `observacao` VARCHAR(600) DEFAULT NULL,
  `recurso_primeira_fase` VARCHAR(200) DEFAULT NULL,
  `recurso_segunda_fase` VARCHAR(200) DEFAULT NULL,
  `recurso_terceira_fase` VARCHAR(200) DEFAULT NULL,
  `recurso_quarta_fase` VARCHAR(200) DEFAULT NULL,
  `motivo_indeferimento` VARCHAR(2000) DEFAULT NULL,
  `motivo_indeferimento_recurso_primeira_fase` VARCHAR(2000) DEFAULT NULL,
  `motivo_indeferimento_recurso_segunda_fase` VARCHAR(2000) DEFAULT NULL,
  `motivo_indeferimento_recurso_terceira_fase` VARCHAR(2000) DEFAULT NULL,
  `motivo_indeferimento_recurso_quarta_fase` VARCHAR(2000) DEFAULT NULL,
  `status_recurso_primeira_fase` int(11) DEFAULT NULL,
  `status_recurso_segunda_fase` int(11) DEFAULT NULL,
  `status_recurso_terceira_fase` int(11) DEFAULT NULL,
  `status_recurso_quarta_fase` int(11) DEFAULT NULL
);


CREATE TABLE `inscricao_disciplina` (
  `cod_inscricao` int(11) NOT NULL,
  `cod_disciplina` bigint(20) NOT NULL,
  `motivo_indeferimento` VARCHAR(2000) DEFAULT NULL,
  `status_inscricao` VARCHAR(1) DEFAULT NULL
);



CREATE TABLE `linha_pesquisa` (
  `cod_linha_pes` int(11) NOT NULL,
  `cod_area_conc` int(11) DEFAULT NULL,
  `nome_linha_pes` VARCHAR(150) DEFAULT NULL
);




CREATE TABLE `professor` (
  `cod_professor` int(11) NOT NULL,
  `nome_professor` VARCHAR(150) DEFAULT NULL,
  `nivel_acesso` VARCHAR(1) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `senha` VARCHAR(150) DEFAULT NULL
);



CREATE TABLE `professor_programa` (
  `cod_programa` int(11) DEFAULT NULL,
  `cod_professor` int(11) DEFAULT NULL
);





CREATE TABLE `sublinha` (
  `cod_sublinha` int(11) NOT NULL,
  `cod_linha_pes` int(11) DEFAULT NULL,
  `nome_sublinha` VARCHAR(150) DEFAULT NULL,
  `inativo` int(11) DEFAULT NULL
);


ALTER TABLE `admin_secretario`
  ADD PRIMARY KEY (`cod_user`);

--
-- Índices para tabela `area_concentracao`
--
ALTER TABLE `area_concentracao`
  ADD PRIMARY KEY (`cod_area_conc`),
  ADD KEY `cod_curso` (`cod_curso`);

--
-- Índices para tabela `aud_tentativa_inscricao`
--
ALTER TABLE `aud_tentativa_inscricao`
  ADD PRIMARY KEY (`aud_cod_tentativa_inscricao`),
  ADD KEY `cod_candidato` (`cod_candidato`),
  ADD KEY `cod_edital` (`cod_edital`),
  ADD KEY `cod_linha_pes` (`cod_linha_pes`),
  ADD KEY `cod_sublinha` (`cod_sublinha`);

--
-- Índices para tabela `aud_tentativa_inscricao_disciplina`
--
ALTER TABLE `aud_tentativa_inscricao_disciplina`
  ADD PRIMARY KEY (`aud_cod_tentativa_inscricao_disciplina`),
  ADD KEY `aud_cod_tentativa_inscricao` (`aud_cod_tentativa_inscricao`),
  ADD KEY `cod_disciplina` (`cod_disciplina`);

--
-- Índices para tabela `aud_tentativa_submeter_documento`
--
ALTER TABLE `aud_tentativa_submeter_documento`
  ADD PRIMARY KEY (`aud_cod_tentativa_submeter_documento`),
  ADD KEY `aud_cod_tentativa_inscricao` (`aud_cod_tentativa_inscricao`);

--
-- Índices para tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  ADD PRIMARY KEY (`idAvaliacao`);

--
-- Índices para tabela `candidato`
--
ALTER TABLE `candidato`
  ADD PRIMARY KEY (`cod_candidato`);

--
-- Índices para tabela `comprovante`
--
ALTER TABLE `comprovante`
  ADD PRIMARY KEY (`cod_comprovante`),
  ADD KEY `fk_inscricao_comprovante` (`cod_inscricao`);

--
-- Índices para tabela `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`cod_curso`),
  ADD KEY `cod_programa` (`cod_programa`);

--
-- Índices para tabela `disciplina`
--
ALTER TABLE `disciplina`
  ADD PRIMARY KEY (`cod_disciplina`),
  ADD KEY `cod_curso` (`cod_curso`);

--
-- Índices para tabela `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `cod_inscricao` (`cod_inscricao`);

--
-- Índices para tabela `edital`
--
ALTER TABLE `edital`
  ADD PRIMARY KEY (`cod_edital`),
  ADD KEY `cod_curso` (`cod_curso`),
  ADD KEY `cod_periodo` (`cod_periodo`);

--
-- Índices para tabela `inscricao`
--
ALTER TABLE `inscricao`
  ADD PRIMARY KEY (`cod_inscricao`),
  ADD KEY `cod_candidato` (`cod_candidato`),
  ADD KEY `cod_edital` (`cod_edital`),
  ADD KEY `cod_linha_pes` (`cod_linha_pes`),
  ADD KEY `cod_sublinha` (`cod_sublinha`);

--
-- Índices para tabela `inscricao_disciplina`
--
ALTER TABLE `inscricao_disciplina`
  ADD PRIMARY KEY (`cod_inscricao`,`cod_disciplina`),
  ADD KEY `cod_disciplina` (`cod_disciplina`);

--
-- Índices para tabela `linha_pesquisa`
--
ALTER TABLE `linha_pesquisa`
  ADD PRIMARY KEY (`cod_linha_pes`),
  ADD KEY `cod_area_conc` (`cod_area_conc`);

--
-- Índices para tabela `periodo_edital`
--
ALTER TABLE `periodo_edital`
  ADD PRIMARY KEY (`cod_periodo`);

--
-- Índices para tabela `professor`
--
ALTER TABLE `professor`
  ADD PRIMARY KEY (`cod_professor`);

--
-- Índices para tabela `professor_programa`
--
ALTER TABLE `professor_programa`
  ADD KEY `cod_programa` (`cod_programa`),
  ADD KEY `cod_professor` (`cod_professor`);

--
-- Índices para tabela `programa_pos_graduacao`
--
ALTER TABLE `programa_pos_graduacao`
  ADD PRIMARY KEY (`cod_programa`);

--
-- Índices para tabela `sublinha`
--
ALTER TABLE `sublinha`
  ADD PRIMARY KEY (`cod_sublinha`),
  ADD KEY `cod_linha_pes` (`cod_linha_pes`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `admin_secretario`
--
ALTER TABLE `admin_secretario`
  MODIFY `cod_user` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `area_concentracao`
--
ALTER TABLE `area_concentracao`
  MODIFY `cod_area_conc` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `aud_tentativa_inscricao`
--
ALTER TABLE `aud_tentativa_inscricao`
  MODIFY `aud_cod_tentativa_inscricao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `aud_tentativa_inscricao_disciplina`
--
ALTER TABLE `aud_tentativa_inscricao_disciplina`
  MODIFY `aud_cod_tentativa_inscricao_disciplina` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `aud_tentativa_submeter_documento`
--
ALTER TABLE `aud_tentativa_submeter_documento`
  MODIFY `aud_cod_tentativa_submeter_documento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avaliacao`
--
ALTER TABLE `avaliacao`
  MODIFY `idAvaliacao` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `candidato`
--
ALTER TABLE `candidato`
  MODIFY `cod_candidato` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comprovante`
--
ALTER TABLE `comprovante`
  MODIFY `cod_comprovante` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `curso`
--
ALTER TABLE `curso`
  MODIFY `cod_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `disciplina`
--
ALTER TABLE `disciplina`
  MODIFY `cod_disciplina` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `documento`
--
ALTER TABLE `documento`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `edital`
--
ALTER TABLE `edital`
  MODIFY `cod_edital` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `inscricao`
--
ALTER TABLE `inscricao`
  MODIFY `cod_inscricao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `linha_pesquisa`
--
ALTER TABLE `linha_pesquisa`
  MODIFY `cod_linha_pes` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `periodo_edital`
--
ALTER TABLE `periodo_edital`
  MODIFY `cod_periodo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `professor`
--
ALTER TABLE `professor`
  MODIFY `cod_professor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `programa_pos_graduacao`
--
ALTER TABLE `programa_pos_graduacao`
  MODIFY `cod_programa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `sublinha`
--
ALTER TABLE `sublinha`
  MODIFY `cod_sublinha` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `aud_tentativa_inscricao`
--
ALTER TABLE `aud_tentativa_inscricao`
  ADD CONSTRAINT `aud_tentativa_inscricao_ibfk_1` FOREIGN KEY (`cod_candidato`) REFERENCES `candidato` (`cod_candidato`),
  ADD CONSTRAINT `aud_tentativa_inscricao_ibfk_2` FOREIGN KEY (`cod_edital`) REFERENCES `edital` (`cod_edital`),
  ADD CONSTRAINT `aud_tentativa_inscricao_ibfk_3` FOREIGN KEY (`cod_linha_pes`) REFERENCES `linha_pesquisa` (`cod_linha_pes`),
  ADD CONSTRAINT `aud_tentativa_inscricao_ibfk_4` FOREIGN KEY (`cod_sublinha`) REFERENCES `sublinha` (`cod_sublinha`);

--
-- Limitadores para a tabela `aud_tentativa_inscricao_disciplina`
--
ALTER TABLE `aud_tentativa_inscricao_disciplina`
  ADD CONSTRAINT `aud_tentativa_inscricao_disciplina_ibfk_1` FOREIGN KEY (`aud_cod_tentativa_inscricao`) REFERENCES `aud_tentativa_inscricao` (`aud_cod_tentativa_inscricao`),
  ADD CONSTRAINT `aud_tentativa_inscricao_disciplina_ibfk_2` FOREIGN KEY (`cod_disciplina`) REFERENCES `disciplina` (`cod_disciplina`);

--
-- Limitadores para a tabela `aud_tentativa_submeter_documento`
--
ALTER TABLE `aud_tentativa_submeter_documento`
  ADD CONSTRAINT `aud_tentativa_submeter_documento_ibfk_1` FOREIGN KEY (`aud_cod_tentativa_inscricao`) REFERENCES `aud_tentativa_inscricao` (`aud_cod_tentativa_inscricao`);

--
-- Limitadores para a tabela `disciplina`
--
ALTER TABLE `disciplina`
  ADD CONSTRAINT `disciplina_ibfk_1` FOREIGN KEY (`cod_curso`) REFERENCES `curso` (`cod_curso`);

--
-- Limitadores para a tabela `inscricao_disciplina`
--
ALTER TABLE `inscricao_disciplina`
  ADD CONSTRAINT `inscricao_disciplina_ibfk_1` FOREIGN KEY (`cod_inscricao`) REFERENCES `inscricao` (`cod_inscricao`),
  ADD CONSTRAINT `inscricao_disciplina_ibfk_2` FOREIGN KEY (`cod_disciplina`) REFERENCES `disciplina` (`cod_disciplina`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
