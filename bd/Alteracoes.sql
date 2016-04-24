-- ALTERA��ES PARA QUE O M�TODO ExcluirPerfil FUNCIONE


-- Cria��o de um usuario an�nimo
INSERT INTO BoaIniciativa.Usuario (cpf,sexo,datanascimento,foto,email,senha,nome,classificacao,cep,estado,bairro,cidade,logradouro,numero,complemento,bloqueado,databloqueio, latitude, longitude) Values ('12345678910','M','09-07-1986','default.jpg','teste1@teste.com',md5('12345678'),'Zilmar Souza',2,'44002-488','Bahia','Muchila','Feira de Santana','Avenida Eduardo Fr�es da Mota','361',null,false,null, -12.2317073, -38.9564264);

--Depois de excluir as colunuas doadorCPF, idCampanha da tabela doa��o e codCampanha da tabela Agradecimento elas foram readicionadas da seguinte forma:

-- A coluna doadorCPF na tabela Doacao tem como default o usuario anonimo e � delete set default
ALTER TABLE ONLY BoaIniciativa.Doacao ADD doadorCPF character varying(15) DEFAULT 12345678910 NOT NULL constraint FK_doadorCPF references BoaIniciativa.Usuario(CPF) on delete set default 

-- A coluna idCampanha na tabela Doacao � delete cascade
ALTER TABLE ONLY BoaIniciativa.Doacao ADD idCampanha integer NOT NULL constraint FK_idCampanha references BoaIniciativa.Campanha(idCampanha) on delete cascade 

-- A coluna codCampanha na tabela Agradecimento � delete set null
ALTER TABLE ONLY BoaIniciativa.Agradecimento ADD codCampanha integer  constraint FK_codCampanha references BoaIniciativa.Campanha(idCampanha) on delete set null 

-- NOTA: Como a chave primaria da tabela Campanha � gerada pelo banco eu n�o coloque um id defaul nas tabelas que tem essa chave como chave estrangeira.
