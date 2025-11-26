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
senha_hash VARCHAR(100),
imagem_banner VARCHAR(255),
bio VARCHAR(255),
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
imagem_comunidade VARCHAR(255) NULL,
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
-- Tabela: POSTAGENS
-- ========================
CREATE TABLE postagens (
id_postagem INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
texto_postagem VARCHAR(255),
imagem_postagem VARCHAR(255),
id_categoria int,
FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
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
USE checkpoint_sa;

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
-- INSERIR USUÁRIOS
-- ========================
INSERT INTO usuarios (nome_usuario, data_nascimento, email_usuario, tipo_usuario, menor_idade, senha_hash, imagem_banner, bio, foto_perfil, ativo) VALUES
('Alice Silva', '1992-05-12', 'alice@gmail.com', 'padrao', 0, 'hash1', 'banner1.jpg', 'Bio Alice', 'perfil1.jpg', 1),
('Bruno Costa', '2003-08-20', 'bruno@gmail.com', 'padrao', 1, 'hash2', 'banner2.jpg', 'Bio Bruno', 'perfil2.jpg', 1),
('Carla Mendes', '1995-11-05', 'carla@gmail.com', 'padrao', 0, 'hash3', 'banner3.jpg', 'Bio Carla', 'perfil3.jpg', 1),
('Daniel Lima', '2008-01-18', 'daniel@gmail.com', 'padrao', 1, 'hash4', 'banner4.jpg', 'Bio Daniel', 'perfil4.jpg', 1),
('Eduardo Rocha', '1990-07-30', 'eduardo@gmail.com', 'admin', 0, 'hash5', 'banner5.jpg', 'Bio Eduardo', 'perfil5.jpg', 1),
('Fernanda Alves', '2001-09-14', 'fernanda@gmail.com', 'padrao', 1, 'hash6', 'banner6.jpg', 'Bio Fernanda', 'perfil6.jpg', 1),
('Gabriel Souza', '1998-03-22', 'gabriel@gmail.com', 'padrao', 0, 'hash7', 'banner7.jpg', 'Bio Gabriel', 'perfil7.jpg', 1),
('Helena Martins', '2010-12-02', 'helena@gmail.com', 'padrao', 1, 'hash8', 'banner8.jpg', 'Bio Helena', 'perfil8.jpg', 1),
('Igor Fernandes', '1993-06-17', 'igor@gmail.com', 'padrao', 0, 'hash9', 'banner9.jpg', 'Bio Igor', 'perfil9.jpg', 1),
('Juliana Pinto', '2005-04-11', 'juliana@gmail.com', 'padrao', 1, 'hash10', 'banner10.jpg', 'Bio Juliana', 'perfil10.jpg', 1),
('Lucas Almeida', '1996-02-28', 'lucas@gmail.com', 'admin', 0, 'hash11', 'banner11.jpg', 'Bio Lucas', 'perfil11.jpg', 1),
('Mariana Ribeiro', '2002-10-09', 'mariana@gmail.com', 'padrao', 1, 'hash12', 'banner12.jpg', 'Bio Mariana', 'perfil12.jpg', 1),
('Nicolas Barbosa', '1991-08-03', 'nicolas@gmail.com', 'padrao', 0, 'hash13', 'banner13.jpg', 'Bio Nicolas', 'perfil13.jpg', 1),
('Olivia Gomes', '2009-07-21', 'olivia@gmail.com', 'padrao', 1, 'hash14', 'banner14.jpg', 'Bio Olivia', 'perfil14.jpg', 1),
('Pedro Henrique', '1994-01-05', 'pedro@gmail.com', 'padrao', 0, 'hash15', 'banner15.jpg', 'Bio Pedro', 'perfil15.jpg', 1),
('Quintino Dias', '2012-03-14', 'quintino@gmail.com', 'padrao', 1, 'hash16', 'banner16.jpg', 'Bio Quintino', 'perfil16.jpg', 1),
('Rafaela Teixeira', '1997-05-27', 'rafaela@gmail.com', 'padrao', 0, 'hash17', 'banner17.jpg', 'Bio Rafaela', 'perfil17.jpg', 1),
('Samuel Cardoso', '2006-11-19', 'samuel@gmail.com', 'padrao', 1, 'hash18', 'banner18.jpg', 'Bio Samuel', 'perfil18.jpg', 1),
('Tatiane Souza', '1999-09-08', 'tatiane@gmail.com', 'padrao', 0, 'hash19', 'banner19.jpg', 'Bio Tatiane', 'perfil19.jpg', 1),
('Victor Hugo', '2004-12-25', 'victor@gmail.com', 'padrao', 1, 'hash20', 'banner20.jpg', 'Bio Victor', 'perfil20.jpg', 1);

-- ========================
-- INSERIR COMUNIDADES
-- ========================
INSERT INTO comunidades (nome_comunidade, imagem_comunidade, descricao_comunidade, maior_idade, id_categoria) VALUES
('Gamers Unidos', 'comunidade1.jpg', 'Comunidade de jogadores', 0, 1),
('Programadores BR', 'comunidade2.jpg', 'Programação e desenvolvimento', 0, 2),
('Fãs de Anime', 'comunidade3.jpg', 'Anime e cultura japonesa', 0, 3),
('Fitness & Saúde', 'comunidade4.jpg', 'Exercícios e dicas de saúde', 0, 4),
('Culinária Gourmet', 'comunidade5.jpg', 'Receitas e gastronomia', 0, 5),
('Música e Bandas', 'comunidade6.jpg', 'Fãs de música', 0, 6),
('Cinema e Séries', 'comunidade7.jpg', 'Discussão sobre filmes e séries', 0, 7),
('Literatura & Livros', 'comunidade8.jpg', 'Clube do livro', 0, 8),
('Fotografia', 'comunidade9.jpg', 'Fotografia amadora e profissional', 0, 9),
('Viagens pelo Mundo', 'comunidade10.jpg', 'Dicas de viagens', 0, 10),
('Tecnologia e Gadgets', 'comunidade11.jpg', 'Novidades em tecnologia', 0, 1),
('Arte Digital', 'comunidade12.jpg', 'Criação de arte digital', 0, 2),
('Esportes Radicais', 'comunidade13.jpg', 'Aventuras e esportes', 0, 3),
('Carros e Motocicletas', 'comunidade14.jpg', 'Veículos e motores', 0, 4),
('Pets & Animais', 'comunidade15.jpg', 'Amantes de pets', 0, 5),
('Jogos de Tabuleiro', 'comunidade16.jpg', 'Board games e RPG', 0, 6),
('Fotografia Noturna', 'comunidade17.jpg', 'Fotos à noite', 0, 7),
('Astrologia & Misticismo', 'comunidade18.jpg', 'Signos e astrologia', 0, 8),
('Cinema Clássico', 'comunidade19.jpg', 'Filmes antigos', 0, 9),
('Café & Confraternização', 'comunidade20.jpg', 'Pessoas que amam café', 0, 10);

-- ========================
-- ASSOCIAR USUÁRIOS ÀS COMUNIDADES
-- ========================
INSERT INTO usuarios_comunidades (id_usuario, id_comunidade) VALUES
(1,1),(2,1),(3,2),(4,2),(5,3),(6,3),(7,4),(8,4),(9,5),(10,5),
(11,6),(12,6),(13,7),(14,7),(15,8),(16,8),(17,9),(18,9),(19,10),(20,10);

-- ========================
-- INSERIR POSTAGENS
-- ========================
INSERT INTO postagens (texto_postagem, imagem_postagem, id_categoria, id_usuario) VALUES
('Post 1', 'post1.jpg', 1, 1),
('Post 2', 'post2.jpg', 2, 2),
('Post 3', 'post3.jpg', 3, 3),
('Post 4', 'post4.jpg', 4, 4),
('Post 5', 'post5.jpg', 5, 5),
('Post 6', 'post6.jpg', 6, 6),
('Post 7', 'post7.jpg', 7, 7),
('Post 8', 'post8.jpg', 8, 8),
('Post 9', 'post9.jpg', 9, 9),
('Post 10', 'post10.jpg', 10, 10),
('Post 11', 'post11.jpg', 1, 11),
('Post 12', 'post12.jpg', 2, 12),
('Post 13', 'post13.jpg', 3, 13),
('Post 14', 'post14.jpg', 4, 14),
('Post 15', 'post15.jpg', 5, 15),
('Post 16', 'post16.jpg', 6, 16),
('Post 17', 'post17.jpg', 7, 17),
('Post 18', 'post18.jpg', 8, 18),
('Post 19', 'post19.jpg', 9, 19),
('Post 20', 'post20.jpg', 10, 20);

-- ========================
-- INSERIR REAÇÕES
-- ========================
INSERT INTO reacoes (id_usuario, id_postagem, tipo_reacao) VALUES
(1,1,'like'),(2,1,'like'),(3,2,'like'),(4,2,'dislike'),(5,3,'like'),(6,3,'dislike'),
(7,4,'like'),(8,4,'dislike'),(9,5,'like'),(10,5,'dislike'),
(11,6,'like'),(12,6,'like'),(13,7,'dislike'),(14,7,'like'),(15,8,'like'),(16,8,'dislike'),
(17,9,'like'),(18,9,'dislike'),(19,10,'like'),(20,10,'like');

-- ========================
-- ASSOCIAR USUÁRIOS ÀS CATEGORIAS
-- ========================
INSERT INTO usuarios_categorias (id_usuario, id_categoria) VALUES
(1,1),(2,2),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9),(10,10),
(11,1),(12,2),(13,3),(14,4),(15,5),(16,6),(17,7),(18,8),(19,9),(20,10);

-- ========================
-- INSERIR SEGUIDORES
-- ========================
INSERT INTO seguidores (id_seguidor, id_seguindo) VALUES
(1,2),(2,3),(3,4),(4,5),(5,6),(6,7),(7,8),(8,9),(9,10),(10,1),
(11,12),(12,13),(13,14),(14,15),(15,16),(16,17),(17,18),(18,19),(19,20),(20,11);