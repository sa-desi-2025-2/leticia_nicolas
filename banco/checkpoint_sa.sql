-- ========================
-- Criação do banco
-- ========================
CREATE DATABASE IF NOT EXISTS checkpoint_sa;
USE checkpoint_sa;

-- ========================
-- Tabela: USUÁRIOS
-- ========================
CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_usuario VARCHAR(100),
    data_nascimento DATE,
    email_usuario VARCHAR(200),
    tipo_usuario ENUM('padrao','admin') DEFAULT 'padrao',
    menor_idade TINYINT,
    senha_usuario VARCHAR(100),
    foto_perfil VARCHAR(255),
    ativo TINYINT(1) DEFAULT 1
);

-- ========================
-- Tabela: CATEGORIAS
-- ========================
CREATE TABLE categorias (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_categoria VARCHAR(45)
);

-- ========================
-- Tabela: COMUNIDADES
-- ========================
CREATE TABLE comunidades (
    id_comunidade INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_comunidade VARCHAR(45),
    descricao_comunidade VARCHAR(255),
    maior_idade TINYINT,
    id_categoria INT,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

-- ========================
-- Tabela: USUÁRIOS_COMUNIDADES
-- ========================
CREATE TABLE usuarios_comunidades (
    id_user_com INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    id_comunidade INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_comunidade) REFERENCES comunidades(id_comunidade)
);

-- ========================
-- Tabela: POSTAGENS (SEM relação com comunidades)
-- ========================
CREATE TABLE postagens (
    id_postagem INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    texto_postagem VARCHAR(255),
    imagem_postagem VARCHAR(255),
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
);

-- ========================
-- Tabela: REAÇÕES
-- ========================
CREATE TABLE reacoes (
    id_reacao INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    id_postagem INT,
    tipo_reacao ENUM('like','dislike'),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_postagem) REFERENCES postagens(id_postagem)
);

-- ========================
-- Tabela: USUÁRIOS_CATEGORIAS
-- ========================
CREATE TABLE usuarios_categorias (
    id_user_cat INT PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT,
    id_categoria INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

-- ========================
-- Tabela: SEGUIDORES
-- ========================
CREATE TABLE seguidores (
    id_seguidor INT,
    id_seguindo INT,
    PRIMARY KEY (id_seguidor, id_seguindo),
    FOREIGN KEY (id_seguidor) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_seguindo) REFERENCES usuarios(id_usuario)
);

-- ========================
-- Inserir USUÁRIOS
-- ========================
INSERT INTO usuarios (nome_usuario, data_nascimento, email_usuario, tipo_usuario, menor_idade, senha_usuario, foto_perfil, ativo) VALUES
('Alice Silva', '2005-03-12', 'alice@email.com', 'padrao', 1, 'senha123', 'alice.jpg', 1),
('Bruno Costa', '1998-07-22', 'bruno@email.com', 'admin', 0, 'senha123', 'bruno.jpg', 1),
('Carla Mendes', '2010-01-15', 'carla@email.com', 'padrao', 1, 'senha123', 'carla.jpg', 0),
('Daniel Souza', '1995-09-05', 'daniel@email.com', 'padrao', 0, 'senha123', 'daniel.jpg', 1),
('Eduardo Lima', '2003-12-30', 'eduardo@email.com', 'padrao', 1, 'senha123', 'eduardo.jpg', 1),
('Fernanda Rocha', '1990-06-18', 'fernanda@email.com', 'admin', 0, 'senha123', 'fernanda.jpg', 0),
('Gabriel Pinto', '2002-11-02', 'gabriel@email.com', 'padrao', 0, 'senha123', 'gabriel.jpg', 1),
('Helena Martins', '2008-08-20', 'helena@email.com', 'padrao', 1, 'senha123', 'helena.jpg', 1),
('Igor Alves', '1999-05-10', 'igor@email.com', 'padrao', 0, 'senha123', 'igor.jpg', 0),
('Juliana Ferreira', '2001-02-28', 'juliana@email.com', 'padrao', 0, 'senha123', 'juliana.jpg', 1);

-- ========================
-- Inserir CATEGORIAS
-- ========================
INSERT INTO categorias (nome_categoria) VALUES
('Ação'),
('Aventura'),
('RPG'),
('FPS'),
('Estratégia'),
('Simulação'),
('Esportes'),
('Terror'),
('Puzzle'),
('MOBA');

-- ========================
-- Inserir COMUNIDADES
-- ========================
INSERT INTO comunidades (nome_comunidade, descricao_comunidade, maior_idade, id_categoria) VALUES
('Gamers de Ação', 'Comunidade de jogos de ação', 0, 1),
('Aventuras Virtuais', 'Explorando mundos de aventura', 0, 2),
('RPG Lovers', 'Para fãs de RPG', 0, 3),
('FPS Masters', 'Jogos de tiro em primeira pessoa', 0, 4),
('Estratégia Total', 'Discussões sobre jogos de estratégia', 0, 5),
('Simulação Real', 'Simuladores e realismo', 0, 6),
('Futebol Mania', 'Jogos de esportes e futebol', 0, 7),
('Terror Night', 'Para fãs de jogos de terror', 1, 8),
('Puzzle Club', 'Resolvendo puzzles juntos', 0, 9),
('MOBA Heroes', 'Jogos MOBA e competições', 0, 10);

-- ========================
-- Inserir USUÁRIOS_COMUNIDADES
-- ========================
INSERT INTO usuarios_comunidades (id_usuario, id_comunidade) VALUES
(1, 1),
(2, 1),
(1, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(2, 3),
(4, 1),
(5, 2),
(7, 3);

-- ========================
-- Inserir POSTAGENS
-- ========================
INSERT INTO postagens (texto_postagem, imagem_postagem, id_usuario) VALUES
('Adorei esse jogo de ação!', 'acao1.jpg', 1),
('Alguém jogou essa aventura nova?', 'aventura1.jpg', 2),
('RPG é minha paixão!', 'rpg1.jpg', 3),
('FPS competitivo é demais', 'fps1.jpg', 4),
('Dicas de estratégia aqui', 'estrategia1.jpg', 5),
('Simuladores são muito realistas', 'simulacao1.jpg', 6),
('Quem quer jogar futebol online?', 'esportes1.jpg', 7),
('Jogo de terror de arrepiar!', 'terror1.jpg', 8),
('Puzzle resolvido com sucesso', 'puzzle1.jpg', 9),
('MOBA é emocionante!', 'moba1.jpg', 10);

-- ========================
-- Inserir REAÇÕES
-- ========================
INSERT INTO reacoes (id_usuario, id_postagem, tipo_reacao) VALUES
(1, 1, 'like'),
(2, 1, 'like'),
(3, 2, 'dislike'),
(4, 3, 'like'),
(5, 4, 'dislike'),
(6, 5, 'like'),
(7, 6, 'like'),
(8, 7, 'dislike'),
(9, 8, 'like'),
(10, 9, 'like');

-- ========================
-- Inserir USUÁRIOS_CATEGORIAS
-- ========================
INSERT INTO usuarios_categorias (id_usuario, id_categoria) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 3),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

-- ========================
-- Inserir SEGUIDORES
-- ========================
INSERT INTO seguidores (id_seguidor, id_seguindo) VALUES
(1, 2),
(2, 1),
(3, 4),
(4, 3),
(5, 6),
(6, 5),
(7, 8),
(8, 7),
(9, 10),
(10, 9);
