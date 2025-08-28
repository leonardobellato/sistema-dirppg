CREATE TABLE `programa` (
  `id_programa` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
);


CREATE TABLE `curso` (
  `id_curso` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_programa` INT UNSIGNED NOT NULL,
  `tipo` VARCHAR(20) NOT NULL
);

ALTER TABLE `curso` 
  ADD CONSTRAINT fk_curso_programa 
  FOREIGN KEY (`id_programa`) 
  REFERENCES `programa`(`id_programa`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `area_concentracao` (
  `id_area_concentracao` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_curso` INT UNSIGNED NOT NULL,
  `nome` VARCHAR(150) NOT NULL,
  `ativo` TINYINT(1) DEFAULT 1
);

ALTER TABLE `area_concentracao` 
  ADD CONSTRAINT fk_ac_curso 
  FOREIGN KEY (`id_curso`) 
  REFERENCES `curso`(`id_curso`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `linha_pesquisa` (
  `id_linha_pesquisa` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_area_concentracao` INT UNSIGNED NOT NULL,
  `nome` VARCHAR(150) NOT NULL,
  `ativo` TINYINT(1) DEFAULT 1
);

ALTER TABLE `linha_pesquisa`
  ADD CONSTRAINT fk_lp_ac 
  FOREIGN KEY (`id_area_concentracao`) 
  REFERENCES `area_concentracao`(`id_area_concentracao`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `sublinha` (
  `id_sublinha` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_linha_pesquisa` INT UNSIGNED NOT NULL,
  `nome` VARCHAR(150) NOT NULL,
  `ativo` TINYINT(1) DEFAULT 1
);

ALTER TABLE `sublinha`
  ADD CONSTRAINT fk_sublinha_lp 
  FOREIGN KEY (`id_linha_pesquisa`) 
  REFERENCES `linha_pesquisa`(`id_linha_pesquisa`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `disciplina` (
  `id_disciplina` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_curso` INT UNSIGNED NOT NULL,
  `nome` VARCHAR(200) NOT NULL,
  `ativo` tinyint(1) DEFAULT 1
);

ALTER TABLE `disciplina`
  ADD CONSTRAINT fk_disciplina_curso 
  FOREIGN KEY (`id_curso`) 
  REFERENCES `curso`(`id_curso`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;






CREATE TABLE `professor` (
  `id_professor` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `nivel_acesso` VARCHAR(1) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `senha` VARCHAR(255) NOT NULL
);


CREATE TABLE `professor_programa` (
  `cod_programa` int(11) DEFAULT NULL,
  `cod_professor` int(11) DEFAULT NULL
);












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

ALTER TABLE `edital` 
  ADD CONSTRAINT fk_edital_curso 
  FOREIGN KEY (`id_curso`) 
  REFERENCES `curso`(`id_curso`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `admin_secretario` (
  `cod_user` int(11) NOT NULL,
  `nome` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `senha` VARCHAR(150) DEFAULT NULL,
  `nivel_acesso` VARCHAR(1) DEFAULT NULL,
  `profAvaliador` int(1) DEFAULT '1'
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