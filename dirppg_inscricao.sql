CREATE TABLE `programas` (
  `id_programa` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nome`        VARCHAR(100) NOT NULL,
);


CREATE TABLE `cursos` (
  `id_curso`    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_programa` INT UNSIGNED NOT NULL,
  `tipo`        VARCHAR(20) NOT NULL
);

ALTER TABLE `cursos` 
  ADD CONSTRAINT fk_curso_programa 
  FOREIGN KEY (`id_programa`) 
  REFERENCES `programas`(`id_programa`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `areas_concentracao` (
  `id_area_concentracao` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_curso`             INT UNSIGNED NOT NULL,
  `nome`                 VARCHAR(150) NOT NULL,
  `inativa`              TINYINT(1) NOT NULL DEFAULT 0
);

ALTER TABLE `areas_concentracao` 
  ADD CONSTRAINT fk_ac_curso 
  FOREIGN KEY (`id_curso`) 
  REFERENCES `cursos`(`id_curso`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `linhas_pesquisa` (
  `id_linha_pesquisa`    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_area_concentracao` INT UNSIGNED NOT NULL,
  `nome`                 VARCHAR(150) NOT NULL,
  `inativa`              TINYINT(1) NOT NULL DEFAULT 0
);

ALTER TABLE `linhas_pesquisa`
  ADD CONSTRAINT fk_lp_ac 
  FOREIGN KEY (`id_area_concentracao`) 
  REFERENCES `areas_concentracao`(`id_area_concentracao`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `sublinhas` (
  `id_sublinha`       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_linha_pesquisa` INT UNSIGNED NOT NULL,
  `nome`              VARCHAR(150) NOT NULL,
  `inativa`           TINYINT(1) NOT NULL DEFAULT 0
);

ALTER TABLE `sublinhas`
  ADD CONSTRAINT fk_sublinha_lp 
  FOREIGN KEY (`id_linha_pesquisa`) 
  REFERENCES `linhas_pesquisa`(`id_linha_pesquisa`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `disciplinas` (
  `id_disciplina` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_curso`      INT UNSIGNED NOT NULL,
  `nome`          VARCHAR(200) NOT NULL,
  `inativa`       TINYINT(1) NOT NULL DEFAULT 0
);

ALTER TABLE `disciplinas`
  ADD CONSTRAINT fk_disciplina_curso 
  FOREIGN KEY (`id_curso`) 
  REFERENCES `cursos`(`id_curso`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `usuarios` (
  `id_usuario`    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nome`          VARCHAR(100) NOT NULL,
  `email`         VARCHAR(100) NOT NULL UNIQUE,
  `senha`         VARCHAR(255) NOT NULL,
  `tipo`          ENUM('candidato','professor','admin') NOT NULL DEFAULT 'candidato'
);


CREATE TABLE `candidatos` (
  `id_usuario` INT UNSIGNED PRIMARY KEY,
  `cpf`        CHAR(14) NOT NULL UNIQUE,
  `telefone`   VARCHAR(14) NOT NULL
);

ALTER TABLE `candidatos`
  ADD CONSTRAINT fk_candidato_usuario 
  FOREIGN KEY (`id_usuario`) 
  REFERENCES `usuarios`(`id_usuario`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `professor_programa` (
  `id_usuario`  INT UNSIGNED NOT NULL,
  `id_programa` INT UNSIGNED NOT NULL
);

ALTER TABLE `professor_programa`
  ADD CONSTRAINT fk_pp_usuario 
  FOREIGN KEY (`id_usuario`) 
  REFERENCES `usuarios`(`id_usuario`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `professor_programa`
  ADD CONSTRAINT fk_pp_programa 
  FOREIGN KEY (`id_programa`) 
  REFERENCES `programas`(`id_programa`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `editais` (
  `id_edital`                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_curso`                  INT UNSIGNED NOT NULL,
  `nome`                      VARCHAR(200) NOT NULL,
  `vigente`                   TINYINT(1) NOT NULL DEFAULT 1,
  `data_inscricao_inicio`     DATE NOT NULL,
  `data_inscricao_fim`        DATE NOT NULL,
  `data_recurso1_inicio`      DATE NOT NULL,
  `data_recurso1_fim`         DATE NOT NULL,
  `data_homologacao1_inicio`  DATE NOT NULL,
  `data_homologacao1_fim`     DATE NOT NULL,
  `data_recurso2_inicio`      DATE NOT NULL,
  `data_recurso2_fim`         DATE NOT NULL,
  `data_homologacao2_inicio`  DATE NOT NULL,
  `data_homologacao2_fim`     DATE NOT NULL
);

ALTER TABLE `editais` 
  ADD CONSTRAINT fk_edital_curso 
  FOREIGN KEY (`id_curso`) 
  REFERENCES `cursos`(`id_curso`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `inscricoes` (
  `id_inscricao`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_usuario`           INT UNSIGNED NOT NULL,
  `id_edital`            INT UNSIGNED NOT NULL,
  `id_linha_pesquisa`    INT UNSIGNED NULL,
  `id_sublinha`          INT UNSIGNED NULL,
  `deferido`             TINYINT(1) DEFAULT NULL,
  `motivo_indeferimento` VARCHAR(600) DEFAULT NULL,
  `data_criacao`         DATE DEFAULT CURRENT_DATE,
  `nome_orientador`      VARCHAR(100) DEFAULT NULL,
  `observacao`           VARCHAR(600) DEFAULT NULL
);

ALTER TABLE `inscricoes` 
  ADD CONSTRAINT fk_inscricao_usuario 
  FOREIGN KEY (`id_usuario`) 
  REFERENCES `usuarios`(`id_usuario`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `inscricoes` 
  ADD CONSTRAINT fk_inscricao_edital 
  FOREIGN KEY (`id_edital`) 
  REFERENCES `editais`(`id_edital`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `inscricoes` 
  ADD CONSTRAINT fk_inscricao_linha_pesquisa 
  FOREIGN KEY (`id_linha_pesquisa`) 
  REFERENCES `linhas_pesquisa`(`id_linha_pesquisa`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `inscricoes` 
  ADD CONSTRAINT fk_inscricao_sublinhas 
  FOREIGN KEY (`id_sublinha`) 
  REFERENCES `sublinhas`(`id_sublinha`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `inscricao_disciplina` (
  `id_inscricao`         INT UNSIGNED NOT NULL,
  `id_disciplina`        INT UNSIGNED NOT NULL,
  `deferido`             TINYINT(1) DEFAULT NULL,
  `motivo_indeferimento` VARCHAR(600) DEFAULT NULL,
);

ALTER TABLE `inscricao_disciplina` 
  ADD CONSTRAINT fk_isd_inscricao
  FOREIGN KEY (`id_inscricao`) 
  REFERENCES `inscricoes`(`id_inscricao`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `inscricao_disciplina` 
  ADD CONSTRAINT fk_isd_disciplina
  FOREIGN KEY (`id_disciplina`) 
  REFERENCES `disciplinas`(`id_disciplina`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `documento` (
  `id_documento`       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_inscricao`       INT UNSIGNED NOT NULL,
  `caminho_servidor`   VARCHAR(200) DEFAULT NULL,
  `tipo`               VARCHAR(30) NOT NULL,
  
);

ALTER TABLE `documento` 
  ADD CONSTRAINT fk_documento_inscricao
  FOREIGN KEY (`id_inscricao`) 
  REFERENCES `inscricoes`(`id_inscricao`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;




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








