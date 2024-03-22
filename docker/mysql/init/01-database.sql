-- create databases
CREATE DATABASE IF NOT EXISTS `poc_testing`;
CREATE DATABASE IF NOT EXISTS `poc_system`;

-- create rd user and grant rights
CREATE USER 'rd'@'%' IDENTIFIED BY 'rdrd';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, REFERENCES, INDEX, ALTER ON `poc_system`.* TO 'rd'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, REFERENCES, INDEX, ALTER ON `poc_testing`.* TO 'rd'@'%';