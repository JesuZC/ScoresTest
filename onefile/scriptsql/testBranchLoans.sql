CREATE DATABASE IF NOT EXISTS testPHP;
CREATE TABLE IF NOT EXISTS testPHP.branch(
	id int(6) NOT NULL,
	country varchar(2),
	state varchar(2),
	PRIMARY KEY(id)
);
CREATE TABLE IF NOT EXISTS testPHP.loan (
	id text,
	branch_id int,
	value float,
	is_active ENUM('0','1'),
	FOREIGN KEY(branch_id) REFERENCES branch(id)
	ON DELETE CASCADE ON UPDATE CASCADE
);
INSERT INTO testPHP.branch VALUES(1,'EU','CA'),(2,'MX','GR'),(3,'AK','CM'),(4,'EU','FL'),(5,'ES','GL');
INSERT INTO testPHP.loan VALUES('investment PS',1,120536.23,'0'),('expense PS',1,65879.99,'1'),('profitableness Sapp',2,27654.32,'1'),('expense Sapp',2,57654.32,'1'),('profitableness RyB',5,207540.02,'1'),('investment RyB',5,27654.32,'0');

SELECT br.country,br.state,lo.value FROM testPHP.branch br INNER JOIN testPHP.loan lo ON br.id = lo.branch_id WHERE lo.is_active = '0';