/* Script com tabelas do banco de dados, além de alguns registros pré-criados necessários para funcionamento do sistema */

CREATE TABLE `programas` (
  `id_programa` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nome`        VARCHAR(100) NOT NULL UNIQUE,
  `sigla`       VARCHAR(10) NOT NULL UNIQUE
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
  `brasileiro` TINYINT(1) NOT NULL DEFAULT 1,
  `telefone`   VARCHAR(20) NOT NULL
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
  `link`                      VARCHAR(200) DEFAULT NULL,
  `data_publicacao`           DATETIME DEFAULT CURRENT_TIMESTAMP,
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
  `tipo` ENUM('inscricao', 'interposicao', 'resultado') NOT NULL,
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

INSERT INTO `programas` (`nome`, `sigla`) VALUES
('Programa de Pós-Graduação em Ciência da Computação', 'PPGCC'),
('Programa de Pós-Graduação em Ensino de Ciência e Tecnologia', 'PPGECT'),
('Programa de Pós-Graduação em Engenharia Mecânica', 'PPGEM'),
('Programa de Pós-Graduação em Engenharia Elétrica', 'PPGEE'),
('Programa de Pós-Graduação em Engenharia de Produção', 'PPGEP'),
('Programa de Pós-Graduação em Biotecnologia', 'PPGBIOTEC'),
('Programa de Pós-Graduação em Engenharia Química', 'PPGEQ');

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


INSERT INTO `disciplinas` (`id_curso`, `nome`) VALUES
(1, 'CC41G - Métodos Formais'),
(1, 'CC41J - Redes de Computadores'),
(1, 'CC41L - Teoria dos Grafos'),
(1, 'CC41M - Tópicos Avançados em Engenharia de Software'),
(1, 'CC41C - Engenharia de Software'),
(1, 'CC41H - Modelagem e Simulação Computacional'),
(1, 'CC41I - Processamento de Imagens'),
(1, 'CC41N - Tópicos Avançados em Inteligência Artificial'),
(1, 'CC41O - Tópicos Avançados em Métodos Computacionais'),
(1, 'CC41P - Tópicos Avançados em Processamento de Imagens'),
(1, 'CC41Q - Tópicos avançados em redes de computadores'),
(1, 'CC41A - Análise e Projeto de Algoritmos'),
(1, 'CC41E - Inteligência Artificial'),
(1, 'CC41K - Sistemas Embarcados'),
(1, 'CC41V - Metaheurísticas De Otimização Bio-Inspiradas'),
(1, 'CC41W - Redes Neurais'),
(1, 'CC41Z – Jogos Computadores'),
(1, 'CC41R – Estudos Dirigidos'),
(1, 'CC41X - Agentes Inteligentes E Sistemas Multiagentes'),
(1, 'CC41B - Arquitetura De Computadores'),
(1, 'CC41F - Metodologia da Pesquisa'),
(1, 'Linguagens Formais e Autômatos'),
(1, 'CCTAM-Tópicos em Aprendizagem de Máquina'),
(1, 'Algoritimos Aleatorizados'),
(1, 'Complexidade Computacional'),
(1, 'Estrutura de dados'),
(1, 'Tópicos em Processamento de Sinais'),
(1, 'CCBMT - Biometria'),
(1, 'CCCQ - Computação Quântica'),
(1, 'CC41AA - Linguagens Formais, Autômatos e Computabilidade'),
(1, 'CC41F-Metodologia de Pesquisa'),
(4, 'EC41D - Tópicos de Estatística Aplicada'),
(4, 'EC41T - Fundamentos Epistemológicos e Metodológicos do Artigo Científico'),
(4, 'EC41H - Docência e Empreendedorismo'),
(4, 'EC41I - Ambientes Informatizados de Ensino-Aprendizagem'),
(4, 'EC41J - Tópicos de Biologia '),
(4, 'EC41E - Tópicos de Matemática '),
(4, 'EC41O - Tópicos de Tecnologia '),
(4, 'EC41A - Fundamentos Históricos e Epistemológicos da Ciência e Tecnologia e o Ambiente de Ensino   '),
(4, 'EC41R - Fundamentos para o Ensino da Matemática'),
(4, 'EC41P - Ensino de Ciências para o Ensino Fundamental'),
(4, 'EC41Q - Tópicos de Ensino de Física'),
(4, 'EC42A - Aspectos de Linguagem e Cognição em Ensino de Ciência e Tecnologia'),
(4, 'EC41Z - Ensino e Inclusão'),
(4, 'EC41M - Tópicos Especiais'),
(4, 'CC41M - Topicos  Avançados em Engenharia Software'),
(4, 'CC41Q – Tópicos Avançados em Redes de Computadores'),
(4, 'CC41E - Inteligência Artificial'),
(4, 'CC41W - Redes Neurais'),
(4, 'CC41K - Sistemas Embarcados'),
(4, 'CC41P - Tópicos em Processamento de Imagens'),
(4, 'CC41A - Análise e Projeto de Algoritmos'),
(4, 'CC41L - Teoria dos Grafos'),
(4, 'CC41Z – Jogos Computador'),
(4, 'EC41M - Tópicos Especiais: Elaboração e Análise de Tarefas Matemáticas'),
(4, 'EC41L - Problematização Ambiental'),
(4, 'EC41G - Tópicos de Linguuagem Acadêmica'),
(4, 'EC42D - Desafios Docentes Contemporâneos no Ensino de Ciência e Tecnologia'),
(4, 'EC42F - Escrita Acadêmica para Internacionalização'),
(4, 'EC42E - Raciocínio Matemático e formação de professores em Ciências e Tecnologia'),
(4, 'EC42G - Multimídia e Recursos Educativos Digitais'),
(8, 'EM42E - Técnicas de caracterização de materiais'),
(8, 'EM42H - Engenharia da combustão e gaseificação'),
(8, 'EM42A - Fundamentos da usinagem'),
(8, 'EM42I - Tubos de calor e termossifões'),
(8, 'PD42A - Projeto de dissertação'),
(8, 'EM41D - Metalurgia física'),
(8, 'SE41A - Seminários'),
(8, 'EM42G - Método dos volumes finitos em condução-convecção'),
(8, 'EM41J - Sistema gás-sólido'),
(8, 'EM41L - Termodinâmica'),
(8, 'EM42N - Materiais com efeito de memória de forma'),
(8, 'EM41A - Planejamento Estatístico de Experimentos'),
(8, 'EM41B - Ciência dos Materiais'),
(8, 'EM41F - Matemática Aplicada às Ciências Térmicas'),
(8, 'EM41G - Transferência de Calor'),
(8, 'EM41H - Mecânica dos Fluidos'),
(8, 'EM41M - Análise Higrotérmica e Energética de Ambientes'),
(8, 'EM42C - Fundamento Metais'),
(8, 'EM42J - Métodos Experimentais e Técnicas de Medida'),
(8, 'EM42D - Processamento e Metalurgia de Materiais Semissólidos'),
(8, 'EM42O - Projeto de Ligas e Solidificação de Metais'),
(8, 'EM42K - Estruturas e Materiais Inteligentes'),
(8, 'EM42C - Processo de Fundição dos Metais'),
(8, 'EM42P - Difração de Raios X: Princípios e Aplicações'),
(8, 'EM42B - Processos de Soldagem e Revestimentos'),
(8, 'EM41N - Engenharia de Superfícies'),
(8, 'EM42Q - Processos de Deposição por Aspersão Térmica'),
(8, 'MMEM29 - Vibrações Mecânicas'),
(8, 'MMEM19 - Fundamentos de Usinagem'),
(8, 'MMEM08 - Redes Neurais e Inteligência Artificial Aplicada em Estruturas'),
(8, 'MMEM07 - Comportamento Mecânico dos Materiais'),
(8, 'MMEM26 - Tópicos Especiais II (Sistemas Computacionais Inteligentes Aplicados na Mecânica)'),
(8, 'MMEM10 - Análise Higrotérmica e Energética de Ambientes'),
(8, 'MMEM15 - Termodinâmica'),
(8, 'MMEM20 - Processamento e Metalurgia de Materiais Semi-Sólidos'),
(8, 'MMEM12 - Mecânica dos Fluidos'),
(8, 'MMEM16 - Transferência de Calor'),
(8, 'MMEM06 - Mecânica Clássica'),
(8, 'MMEM27 - Tribologia'),
(8, 'MMEM03 - Teoria da Elasticidade'),
(8, 'MMEM31 - Processos de Deposição por Aspersão Térmica'),
(8, 'MMEM32 - Difração de Raios X: Princípios e Aplicações'),
(8, 'MMEM22 - Processos de Soldagem e Revestimentos'),
(8, 'MMEM14 - Sistema Gás-Sólido'),
(8, 'MMEM13 - Método dos Volumes Finitos em Condução-Convecção'),
(8, 'MMEM17 - Tubos de Calor e Termossifões'),
(8, 'MMEM30 - Tratamento de Superfícies e Revestimentos'),
(8, 'MMEM09: Tópicos Especiais I (Tópicos Especiais em Estruturas)'),
(8, 'MMEM28 - Fundamentos de Fabricação e Manufatura Sustentável'),
(8, 'MMEM35 - Teoria das Ligas e Diagramas de Fases'),
(8, 'MMEM24 - Fabricação de Sistemas Mecânicos Nanoestruturados'),
(8, 'MMEM34 - Manufatura Aditiva de Materiais Metálicos'),
(8, 'MMEM11 - Engenharia da Combustão e Gaseificação'),
(8, 'MMEM31 - Processos De Deposição Por Aspersão Térmica 5N1 a 5N4'),
(8, 'MMEM36 - Aços Inoxidáveis Austeníticos e Dúplex'),
(11, 'EL41D2-Controle Por Realimentação De Estados'),
(11, 'EL41F2 - Modelagem E Controle De Conversores Estáticos'),
(11, 'EL41H2 - Conversores Estáticos Trifásicos'),
(11, 'EL41I2 - Sistemas Não Lineares'),
(11, 'EL41N2 - Instrumentação 2'),
(11, 'EL41R2 - Tópicos Avançados Em Eletrônica De Potência'),
(11, 'EL41U2 - Sistemas Automotivos Híbridos E Elétricos'),
(11, 'EL41A2 - Álgebra Linear'),
(11, 'EL41O2 - Fundamentos de Controle'),
(11, 'EL41P2  - Fundamentos de Redes Neurais Artificiais'),
(11, 'EL41K2 - Processamento Digital de Sinais'),
(11, 'EL41B2 - Conversores Estáticos Monofásicos'),
(11, 'EL41Y2 - Artificial Intelligence (Em inglês)'),
(11, 'EL41W2 - Modelagem de Sistemas Dinâmicos'),
(11, 'EL41N2 - Sensores e Transdutores'),
(11, 'Metaheurísticas De Otimização Bio-Inspiradas'),
(11, 'EL41J2 - Controle Ótimo'),
(11, 'EL41L2 - Tópicos Especiais em Processamento de Energia: Correção do Fator de Potência'),
(11, 'EL41E2-Instrumentação Virtual'),
(11, 'EL42A2-Estatística aplicada'),
(11, 'EL41M-Tópicos Especiais em Instrumentação e Controle Fundamentos de Aerodinâmica, Aeroelasticidade e Controle de Voo em Aplicações em Engenharia'),
(14, 'EP41DC - Antropotecnologia'),
(14, 'EP41DE - Apoio Multicritério à Decisão'),
(14, 'EP41AB - Sustentabilidade Organizacional'),
(14, 'EP41AS - Smart Cities'),
(14, 'EP41S - Conforto Termo-Ambiental '),
(14, 'EP41M - Métodos Estatísticos'),
(14, 'EP41AA - Inovação Agroindustrial'),
(14, 'EP41AI - Avaliação do Ciclo de Vida'),
(14, 'EP41E - Engenharia Ergonômica da Produção'),
(14, 'EP41DG - Redes Horizontais de Empresas'),
(14, 'EP41H - Gestão da Manutenção'),
(14, 'EP41C - Técnicas de Gestão Industrial'),
(14, 'EP41AJ - Knowledge and Technology Transfer'),
(14, 'EP41AL - Gestão da Segurança e Saúde do Trabalho no Sistema Produtivo'),
(14, 'EP41AB - Gestão do Conhecimento'),
(14, 'EP41AK - Fundamentos da Engenharia da Qualidade'),
(14, 'EP41C - Produtos Inovadores e suas Patentes'),
(14, 'EP41AK - Indústria 4.0'),
(14, 'EP41AN - Dinâmica e controle aplicados no sistema produtivo'),
(14, 'EP41Y - Sustentabilidade na Produção Agroindustrial'),
(14, 'EP41AO - Systematic Review of Literature'),
(14, 'EP41AQ - Tecnologias Limpas para Gestão Energética Industrial'),
(14, 'EP41AR - Métodos Multicritérios – Técnicas e Aplicação'),
(14, 'EP41AX - Sistemas Produtivos Sustentáveis'),
(14, 'EP41AT - Redes Neurais Artificiais'),
(14, 'EP41AU - Estudo Dirigido'),
(14, 'EP41DB - Sócio-Ergonomia'),
(14, 'EP41AZ - Metaheurísticas De Otimização Bio-Inspiradas'),
(14, 'SUBLINHA: Teste 2'),
(14, 'EP41AO-Revisão Sistemática da Literatura'),
(14, 'EP41AA-Tópicos Especiais Em Inovação Agroindustrial'),
(14, 'EP41A-Metodologia da Pesquisa'),
(14, 'EP41AV-Tópicos em Qualidade Ambiental Interior'),
(14, 'EP41BA-Docência no Ensino Superior'),
(14, 'EP41AM4-Controle de Processos Industriais: Modelagem Matemática, Simulação Numérica e Projeto de Controle'),
(18, 'BT41J - Planejamento e otimização de experimentos - PG'),
(18, 'BT41F - Genética e biologia molecular de microrganismos - PG'),
(18, 'BT41H - Processos fermentativos - PG'),
(18, 'BT41D - Métodos instrumentais de análise - PG'),
(18, 'BT41M - Bioeletroquímica - PG'),
(18, 'BT41C - Tópicos especiais em biotecnologia - DV'),
(18, 'BT41F - Genética e biologia molecular de microrganismos - DV'),
(18, 'BT41H - Processos fermentativos - DV'),
(18, 'BT41D - Métodos instrumentais de análise - DV'),
(18, 'BT41N - Microbiologia Aplicada - PG'),
(18, 'BT41R - Meta-Análise e Revisão Cienciométrica - DV'),
(18, 'BT41K - Compostos Bioativos - PG'),
(18, 'BT41G - Bioquímica de microorganismos - PG'),
(18, 'BT41I - Biotecnologia aplicada a alimentos - PG'),
(18, 'BT41A - Redação Científica - PG'),
(18, 'EQ41L - Métodos analíticos - PG'),
(18, 'BT41I - Biotecnologia aplicada a alimentos - DV'),
(18, 'BT41Q - Métodos espectrofotométricos aplicados à biotecnologia - DV'),
(18, 'BT41J - Planejamento e otimização de experimentos - DV'),
(18, 'BT41S - Métodos em micologia - DV'),
(18, 'BT41E - Obtenção de bioprodutos a partir de resíduos agroindustriais - PG'),
(18, 'BT41E - Obtenção de bioprodutos a partir de resíduos agroindustriais - DV'),
(21, 'EQ41A - Métodos Matemáticos'),
(21, 'EQ41A - Fenômenos de Transporte'),
(21, 'EQ 41C - Termodinâmica'),
(21, 'EQ41D - Engenharia das Reações Químicas'),
(21, 'EQ41J - Tópicos Especiais em Desenvolvimento de Processos IV'),
(21, 'EQ41E - Modelagem e Simulação de Processos'),
(21, 'EQ41F - Planejamento Estatístico De Experimentos'),
(21, 'EQ41M - Técnicas Experimentais Para Caracterização De Materiais'),
(21, 'EQ41L - Métodos Instrumentais'),
(21, 'EQ41P - Métodos Convencionais E Alternativos Para O Tratamento De Efluentes'),
(21, 'EQ41H - Tópicos Especiais em Engenharia Química II – Processos Difusivos'),
(21, 'EL41I - Tópicos Especiais em Engenharia Química III'),
(21, 'EL41J - Tópicos Especiais em Engenharia Química IV'),
(21, 'EQ41O - Catálise Heterogênea'),
(21, 'EQ41R - Tópicos em Dinâmica Não Linear'),
(21, 'EQ41S - Dinâmica Não Linear e Caos'),
(21, 'EQ41T - Reações Oscilantes'),
(21, 'EQ41X-Conversão Térmica dos Sólidos'),
(21, 'EQ41U - Metodologia da Pesquisa Científica'),
(21, 'EQ41N - Produção e Caracterização de Biocombustíveis');


INSERT INTO `usuarios` (`nome`, `email`, `senha`, `tipo`) VALUES 
('Admin', 'admin@dirppg.pg', '$2y$12$eIz8wzg7XpEYeQE/ssL05u/SoUyrk5izpJwrmy5zpF43Yh5qazWWa', 'admin');
