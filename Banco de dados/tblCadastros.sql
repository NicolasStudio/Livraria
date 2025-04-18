create database cadastro
default character set utf8
default collate utf8_general_ci;

/* Criando tabela usuario*/
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    telefone VARCHAR(20),
    dtNasc DATE,
    cep VARCHAR(10),
    estado VARCHAR(45),
    cidade VARCHAR(45),
    bairro VARCHAR(45),
    rua VARCHAR(45),
    numero INT
) default charset = utf8;

desc usuarios;

/* Inserindo dados na tabela*/
INSERT INTO usuarios 
(nome, cpf, telefone, dtNasc, cep, estado, cidade, bairro, rua, numero)
VALUES
('Maria', '60606060660', '11044884488', '1997-01-02', '08940000','Minas Gerais', 'Monte Belo', 'Coqueiros', 'Rua dos Pão de Queijo', '597');

select * from  usuarios;

/*Incluindo uma coluna*/
ALTER TABLE usuarios
ADD COLUMN admin  BOOLEAN DEFAULT FALSE after nome;

select * from  usuarios;

/* Removendo uma coluna*/
ALTER TABLE usuarios
DROP COLUMN email;

select * from  produtos;

/*Incluindo uma coluna em um lugar especifico*/
ALTER TABLE usuarios
ADD COLUMN email VARCHAR(45) AFTER cpf;
/* Ou */
ADD COLUMN senha INT FIRST;

select * from  usuarios;

/* Renomeando a tablela */ 
ALTER TABLE usuario
RENAME TO usuarios;

/* -------- CRIANDO NOVA TABELA --------*/
CREATE TABLE IF NOT EXISTS login (
    idlogin INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
) DEFAULT CHARSET=utf8;

desc login;
select * from  login;

ALTER TABLE login
DROP COLUMN idlogin;

ALTER TABLE login
ADD PRIMARY KEY (idlogin);

INSERT INTO login 
( email, senha)
VALUES
('admin2@gmail.com', '1223');

/* Apagando Tabela */
DROP TABLE login;

/*Alterando dados na tabela */
/* mude a tabela login, na coluna senha insira '123', onde o idlogin for 0*/
update produtos 
set vendas = '5'
where idproduto = '14';

update usuarios 
set admin = ''
where id = '1';

select * from  produtos_vendidos;
select * from  produtos;
select * from  produtos_usuarios;
select * from  usuarios;
select * from  login;
select * from  historico_compras;
select * from  vendas;

/* apagando linhas das tabelas*/
DELETE FROM produtos_usuarios where idproduto ='1';

/* Ordenação decrescente, crescente não coloca o parametro desc*/ 
select * from login
order by email desc;

/*Adicionar coluna a tabela*/
ALTER TABLE produtos_vendidos ADD itens varchar(999);

/* Especificando a busca */
select * from login
where senha = '123'
order by email;

/* ou */

SELECT nome, cpf, dtNasc
FROM usuarios
WHERE id IN (1, 2);

/* *Combinando teste*/
select * from usuarios 
where numero > 100 and numero < 158;

/* usando o operador LIKE - Inicio - */
select * from usuarios
where nome Like 'a%';

/* usando o operador Wildcar - Fim -*/
select * from usuarios
where nome Like '%a';

/* usando o operador Wildcar - Em qualquer lugar -*/
select * from usuarios
where nome Like '%a%';

/* usando o operador Wildcar - Ele vai buscar todos que começam com AD e todos que tenham A no fim -*/
select * from usuarios
where nome Like 'ad%a%';

/*Buscando um sobrenome */
select * from usuarios
where nome Like '%Aprender%';

/*Como contar quantos registros tem*/
select count(*) from usuarios; 

/*Como contar quantos registros tem que possui o mesmo numero de casa menor que 200*/
select count(*) from usuarios where numero < 200; 

/* Metodo distict serve para distinguir cadastros*/
Select distinct nome from usuarios
order by numero <500;

/* Metodo agrupar cadastros*/
Select nome from usuarios
order by nome = '%a';


CREATE TABLE produtos (
    idproduto INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    autor VARCHAR(100),
    tema VARCHAR(100),
    preco DECIMAL(10,2),
    paginas INT,
    img VARCHAR(255), -- Neste exemplo, vamos armazenar apenas o caminho da imagem
    descricao TEXT
);

/* Relacionar a tabela de usuarios com produtos */
CREATE TABLE produtos_usuarios (
    idproduto INT,
    id_usuario INT,
    FOREIGN KEY (idproduto) REFERENCES produtos(idproduto),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    PRIMARY KEY (idproduto, id_usuario)
);

CREATE TABLE vendas (
    idvendas INT AUTO_INCREMENT PRIMARY KEY,
    numero_pedido VARCHAR(50) UNIQUE NOT NULL,
    valor_total DECIMAL(10,2),
    chave VARCHAR(100), -- Chave disponibilizada para o usuário
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

/* Relacionar a tabela de vendas com produtos */
CREATE TABLE produtos_vendidos (
    idvendas INT,
    idproduto INT,
    quantidade INT,
    itens varchar(999),
    FOREIGN KEY (idvendas) REFERENCES vendas(idvendas),
    FOREIGN KEY (idproduto) REFERENCES produtos(idproduto),
    PRIMARY KEY (idvendas, idproduto)
);

/* Relacionar a tabela de historico com compras */
CREATE TABLE historico_compras (
    idhistorico INT AUTO_INCREMENT PRIMARY KEY,
    numero_pedido VARCHAR(50) UNIQUE NOT NULL,
    id_usuario INT,
    idvendas INT,
    data_compra DATE,
    valor_total DECIMAL(10,2),
    chave VARCHAR(100),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (idvendas) REFERENCES vendas(idvendas)
);

UPDATE usuarios
SET admin = TRUE
WHERE id = 1;








