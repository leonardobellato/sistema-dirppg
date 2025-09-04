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
  `inativo`              TINYINT(1) NOT NULL DEFAULT 0
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
  `inativo`              TINYINT(1) NOT NULL DEFAULT 0
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
  `inativo`           TINYINT(1) NOT NULL DEFAULT 0
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
  `inativo`       TINYINT(1) NOT NULL DEFAULT 0
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
  `vigente`                   TINYINT(1) NOT NULL DEFAULT 1
);

ALTER TABLE `editais` 
  ADD CONSTRAINT fk_edital_curso 
  FOREIGN KEY (`id_curso`) 
  REFERENCES `cursos`(`id_curso`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `fases_edital` (
  `id_fase` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_edital` INT UNSIGNED NOT NULL,
  `tipo` ENUM('inscricao','recurso','homologacao') NOT NULL,
  `ordem` TINYINT NOT NULL,
  `data_inicio` DATE NOT NULL,
  `data_fim` DATE NOT NULL,
);

ALTER TABLE `fases_edital` 
  ADD CONSTRAINT fk_fases_edital
  FOREIGN KEY (`id_edital`) 
  REFERENCES `editais`(`id_edital`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `inscricoes` (
  `id_inscricao`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_candidato`         INT UNSIGNED NOT NULL,
  `id_avaliador`         INT UNSIGNED DEFAULT NULL,
  `id_edital`            INT UNSIGNED NOT NULL,
  `id_linha_pesquisa`    INT UNSIGNED NULL,
  `id_sublinha`          INT UNSIGNED NULL,
  `deferido`             TINYINT(1) DEFAULT NULL,
  `motivo_indeferimento` VARCHAR(600) DEFAULT NULL,
  `nome_orientador`      VARCHAR(100) DEFAULT NULL,
  `observacao`           VARCHAR(600) DEFAULT NULL,
  `criado_em`            TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `inscricoes` 
  ADD CONSTRAINT fk_inscricao_candidato 
  FOREIGN KEY (`id_candidato`) 
  REFERENCES `usuarios`(`id_usuario`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `inscricoes` 
  ADD CONSTRAINT fk_inscricao_avaliador 
  FOREIGN KEY (`id_avaliador`) 
  REFERENCES `usuarios`(`id_usuario`)
  ON DELETE SET NULL
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
  `motivo_indeferimento` VARCHAR(600) DEFAULT NULL
);

ALTER TABLE `inscricao_disciplina`
  ADD CONSTRAINT pk_inscricao_disciplina 
  PRIMARY KEY (`id_inscricao`, `id_disciplina`);

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


CREATE TABLE `documentos` (
  `id_documento`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_inscricao`         INT UNSIGNED NOT NULL,
  `caminho_servidor`     VARCHAR(200) DEFAULT NULL,
  `tipo`                 VARCHAR(50) NOT NULL,
  `versao`               INT UNSIGNED DEFAULT 1,
  `deferido`             TINYINT(1) DEFAULT NULL,
  `motivo_indeferimento` VARCHAR(600) DEFAULT NULL,
  `criado_em`            TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `documentos` 
  ADD CONSTRAINT fk_documento_inscricao
  FOREIGN KEY (`id_inscricao`) 
  REFERENCES `inscricoes`(`id_inscricao`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE recurso (
  `id_recurso`        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_documento`      INT UNSIGNED NOT NULL,
  `versao_submetida`  INT UNSIGNED NOT NULL,
  `data_submissao`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `recurso` 
  ADD CONSTRAINT fk_recurso_documento
  FOREIGN KEY (`id_documento`) 
  REFERENCES `documentos`(`id_documento`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `entrevistas` (
  `id_entrevista` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_inscricao` INT UNSIGNED NOT NULL,
  `id_agendador` INT UNSIGNED NULL, -- secretário/professor que agendou
  `data_hora` DATETIME NOT NULL,
  `local` VARCHAR(200) NOT NULL,
  `status` ENUM('agendada','realizada','ausente','cancelada') DEFAULT 'agendada',
  `observacoes` VARCHAR(600) DEFAULT NULL,
  `criado_em` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `entrevistas` 
  ADD CONSTRAINT fk_entrevista_inscricao
  FOREIGN KEY (`id_inscricao`) 
  REFERENCES `inscricoes`(`id_inscricao`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `entrevistas` 
  ADD CONSTRAINT fk_entrevista_agendador
  FOREIGN KEY (`id_agendador`) 
  REFERENCES `usuarios`(`id_usuario`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;


CREATE TABLE auditoria (
  `id_auditoria` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_usuario`   INT UNSIGNED NULL,
  `tipo`         VARCHAR(50) NOT NULL, -- 'inscricao', 'documento'
  `operacao`     VARCHAR(50) NOT NULL, -- created, updated, deleted, login, erro_upload
  `sucesso`      BOOLEAN NOT NULL DEFAULT 0,
  `detalhes`     JSON NULL,
  `ip`           VARCHAR(45) NULL,
  `navegador`    VARCHAR(150) NULL,
  `criado_em`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);




