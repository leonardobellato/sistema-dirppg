CREATE TABLE `programas` (
  `id_programa` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nome`        VARCHAR(100) NOT NULL UNIQUE
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
  `data_fim` DATE NOT NULL
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
  REFERENCES `candidatos`(`id_usuario`)
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


CREATE TABLE recursos (
  `id_recurso`        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_documento`      INT UNSIGNED NOT NULL,
  `versao_submetida`  INT UNSIGNED NOT NULL,
  `data_submissao`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `recursos` 
  ADD CONSTRAINT fk_recurso_documento
  FOREIGN KEY (`id_documento`) 
  REFERENCES `documentos`(`id_documento`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


CREATE TABLE `entrevistas` (
  `id_entrevista` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_inscricao`  INT UNSIGNED NOT NULL,
  `id_agendador`  INT UNSIGNED NULL, -- secretário/professor que agendou
  `data_hora`     DATETIME NOT NULL,
  `local`         VARCHAR(200) NOT NULL,
  `status`        ENUM('agendada','realizada','ausente','cancelada') NOT NULL DEFAULT 'agendada',
  `observacoes`   VARCHAR(600) DEFAULT NULL,
  `criado_em`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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


CREATE TABLE `auditorias` (
  `id_auditoria` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `id_usuario`   INT UNSIGNED NULL,
  `tipo`         VARCHAR(50) NOT NULL, -- 'inscricao', 'documento'
  `operacao`     VARCHAR(50) NOT NULL, -- created, updated, deleted, login, erro_upload
  `sucesso`      BOOLEAN NOT NULL DEFAULT 0,
  `detalhes`     TEXT NULL,
  `ip`           VARCHAR(45) NULL,
  `navegador`    VARCHAR(150) NULL,
  `criado_em`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `auditorias` 
  ADD CONSTRAINT fk_auditoria_usuario
  FOREIGN KEY (`id_usuario`) 
  REFERENCES `usuarios`(`id_usuario`)
  ON DELETE SET NULL
  ON UPDATE CASCADE;


-- --------------------------------------------------
-- POPULANDO TABELAS - VALORES PADRÃO

INSERT INTO `programas` (`nome`) VALUES
('Programa de Pós-Graduação em Ciência da Computação'),
('Programa de Pós-Graduação em Ensino de Ciência e Tecnologia'),
('Programa de Pós-Graduação em Engenharia Mecânica'),
('Programa de Pós-Graduação em Engenharia Elétrica'),
('Programa de Pós-Graduação em Engenharia de Produção'),
('Programa de Pós-Graduação em Biotecnologia'),
('Programa de Pós-Graduação em Engenharia Química');

INSERT INTO `cursos` (`id_programa`, `tipo`) VALUES
(1, 'Aluno Externo'),
(1, 'Mestrado'),
(1, 'PAPOS'),
(2, 'Aluno Externo'),
(2, 'Doutorado'),
(2, 'Mestrado'),
(2, 'PAPOS'),
(3, 'Aluno Externo'),
(3, 'Mestrado'),
(3, 'PAPOS'),
(4, 'Aluno Externo'),
(4, 'Mestrado'),
(4, 'PAPOS'),
(5, 'Aluno Externo'),
(5, 'Doutorado'),
(5, 'Mestrado'),
(5, 'PAPOS'),
(6, 'Aluno Externo'),
(6, 'Mestrado'),
(6, 'PAPOS'),
(7, 'Aluno Externo'),
(7, 'Mestrado'),
(7, 'PAPOS');

INSERT INTO `areas_concentracao` ( `id_curso`, `nome`) VALUES
(2, 'Sistemas e Métodos de Computação'),
(5, 'Ciência, Tecnologia e Ensino'),
(6, 'Ciência, Tecnologia e Ensino'),
(8, 'Fenômenos de Transporte e Mecânica dos Sólidos'),
(8, 'Materiais e Processos de Fabricação'),
(9, 'Desenvolvimento de Processos'),
(9, 'Materiais e Processos de Fabricação'),
(9, 'Fenômenos de Transporte e Mecânica dos Sólidos'),
(10, 'Materiais e Processos de Fabricação'),
(10, 'Fenômenos de Transporte e Mecânica dos Sólidos'),
(12, 'Controle e Processamento de Energia'),
(15, 'Gestão Industrial'),
(16, 'Gestão Industrial'),
(19, 'Biotecnologia'),
(22, 'Desenvolvimento de Processos'),
(23, 'Desenvolvimento de Processos');

INSERT INTO `linhas_pesquisa` (`id_area_concentracao`, `nome`) VALUES
(14, 'Biomoléculas Naturais'),
(14, 'Bioprocessos Industriais'),
(14, 'Biotecnologia Aplicada à Agropecuária'),
(6, 'Desenvolvimento e Aplicação de Materiais em Ciências Mecânicas'),
(9, 'Desenvolvimento e Aplicação de Materiais em Ciências Mecânicas'),
(5, 'Desenvolvimento e Aplicação de Materiais em Ciências Mecânicas'),
(7, 'Desenvolvimento e Aplicação de Materiais em Ciências Mecânicas'),
(2, 'Educação Tecnológica'),
(3, 'Educação Tecnológica'),
(4, 'Energia e Engenharia de Sistemas Térmicos'),
(8, 'Energia e Engenharia de Sistemas Térmicos'),
(10, 'Energia e Engenharia de Sistemas Térmicos'),
(7, 'Energia e Engenharia de Sistemas Térmicos'),
(2, 'Fundamentos e Metodologias para o Ensino de Ciências e Matemática'),
(3, 'Fundamentos e Metodologias para o Ensino de Ciências e Matemática'),
(13, 'Gestão da Produção e Manutenção'),
(12, 'Gestão do Conhecimento e Inovação'),
(13, 'Gestão do Conhecimento e Inovação'),
(11, 'Instrumentação e Controle'),
(11, 'Processamento de Energia'),
(4, 'Mecânica dos Sólidos e Vibrações'),
(8, 'Mecânica dos Sólidos e Vibrações'),
(10, 'Mecânica dos Sólidos e Vibrações'),
(7, 'Mecânica dos Sólidos e Vibrações'),
(1, 'Processamento de Imagens, Visão Computacional e Aprendizado de Máquina'),
(1, 'Sistemas de Informação e Computação'),
(1, 'Sistemas Inteligentes, Simulação e Jogos Computacionais'),
(1, 'Teoria da Computação'),
(6, 'Processos de Fabricação'),
(7, 'Processos de Fabricação'),
(9, 'Processos de Fabricação'),
(5, 'Processos de Fabricação'),
(15, 'Processos de Separação, Tecnologia Ambiental e Materiais'),
(16, 'Processos de Separação, Tecnologia Ambiental e Materiais'),
(15, 'Reatores e Biocombustíveis'),
(16, 'Reatores e Biocombustíveis');


INSERT INTO `sublinhas` (`id_linha_pesquisa`, `nome`) VALUES
(15, 'Ensino de Ciências'),
(15, 'Ensino de Estatística'),
(15, 'Ensino de Física'),
(15, 'Ensino de Matemática'),
(15, 'Ensino de Química'),
(15, 'Ciência, Arte e Teknè: diálogos interdisciplinares'),
(15, 'Ensino de Biologia'),
(15, 'Ensino e Inclusão'),
(9, 'Desenvolvimento de material instrucional para a Educação Tecnológica'),
(9, 'Ensino nas Engenharias e nas Tecnologias'),
(9, 'Informática no Ensino das Ciências e da Tecnologia'),
(9, 'Linguagem e Cognição no Ensino de Ciências e Tecnologia'),
(9, 'Relações entre Ciência, Tecnologia e Sociedade no Ensino-aprendizagem'),
(14, 'Ensino de Ciências Naturais'),
(14, 'Ensino de Estatística'),
(14, 'Ensino de Física'),
(14, 'Ensino de Matemática'),
(14, 'Ensino e Inclusão'),
(8, 'Desenvolvimento de produtos para a Educação Tecnológica'),
(8, 'Ensino nas Engenharias e nas Tecnologias'),
(8, 'Informática no Ensino das Ciências e da Tecnologia'),
(8, 'Linguagem e Cognição no Ensino de Ciências e Tecnologia'),
(8, 'Relações entre Ciência, Tecnologia e Sociedade no Ensino-aprendizagem'),
(18, 'Criação de Novos Produtos, seus Processos e suas Patentes'),
(18, 'Gestão da Inovação Agroindustrial'),
(18, 'Gestão de Transferência de Tecnologia'),
(18, 'Dinâmica e Controle de Sistemas Dinâmicos Lineares e Não Lineares'),
(18, 'Sistemas Produtivos Sustentáveis'),
(18, 'Sustentabilidade em Sistemas Produtivos - LESP'),
(18, 'Gestão de Recursos Humanos para o Ambiente Produtivo - GRHAP'),
(18, 'Engenharia Organizacional e Rede de Empresas'),
(18, 'Organizações e Sociedade'),
(18, 'EORE: Indústria 4.0 na Engenharia Organizacional e Redes de Empresas'),
(18, 'Processos de Geração de Energia Provenientes de Fontes Renováveis e suas Aplicações'),
(18, 'Apoio à Decisão em Manutenção Industrial'),
(18, 'Qualidade Ambiental Interior para a Melhoria da Saúde e Produtividade'),
(16, 'Apoio à Decisão em Manutenção Industrial - ADMI'),
(16, 'Bioprodução - BIOP'),
(16, 'Desenvolvimento de Produtos e Processos Sustentáveis para Geração de Bioenergia'),
(16, 'Ergonomia e Segurança do Trabalho'),
(16, 'Otimização e Tomada de Decisão - OTP'),
(16, 'Organizações e Sociedade'),
(16, 'Qualidade Ambiental Interior para a Melhoria da Saúde e Produtividade'),
(17, 'Bioprodução'),
(17, 'Gestão de Transferência de Tecnologia - GTT'),
(17, 'Otimização e Tomada de Decisão - OTP'),
(17, 'Engenharia Organizacional e Rede de Empresas'),
(17, 'Apoio à Decisão em Manutenção Industrial'),
(17, 'Criação de Novos Produtos, seus Processos e suas Patentes'),
(17, 'Desenvolvimento de Produtos e Processos Sustentáveis para Geração de Bioenergia'),
(17, 'Ergonomia e Segurança do Trabalho'),
(17, 'Organizações e Sociedade'),
(17, 'Processos de Geração de Energia Provenientes de Fontes Renováveis e suas Aplicações'),
(17, 'Qualidade Ambiental Interior para a Melhoria da Saúde e Produtividade'),
(17, 'Sistemas Produtivos Sustentáveis'),
(35, 'Caracterização dos Materiais, Relação Estrutura-Propriedades e Transformação de Fases'),
(35, 'Desenvolvimento de Algoritmo de Controle para Reatores Tipo CSTR'),
(35, 'Estudo de Processos de Extração e Purificação de Óleos Vegetais'),
(33, 'Tratamento de Água em Processo Contínuo por Fotocatálise Heterogênea'),
(33, 'Tratamentos Alternativos para Resíduos Industriais'),
(33, 'Determinação de Compostos Potencialmente Tóxicos em Amostras Ambientais e de Alimentos');

