--
-- Create database
--
CREATE DATABASE IF NOT EXISTS board;
GRANT SELECT, INSERT, UPDATE, DELETE ON board.* TO root@localhost IDENTIFIED BY 'board_root';
FLUSH PRIVILEGES;
                    
--
-- Create tables
--
                    
USE board;
                    
CREATE TABLE IF NOT EXISTS thread (
id                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
title                   VARCHAR(255) NOT NULL,
created             	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id)
)ENGINE=InnoDB;
                    
CREATE TABLE IF NOT EXISTS comment (
id                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
thread_id               INT UNSIGNED NOT NULL,
username                VARCHAR(255) NOT NULL,
body                    TEXT NOT NULL,
created                 TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id),
INDEX (thread_id, created)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS user (
id                      INT UNSIGNED NOT NULL AUTO_INCREMENT,
firstname               VARCHAR(255) NOT NULL,
lastname                VARCHAR(255) NOT NULL,
email                   VARCHAR(255) NOT NULL,
username                VARCHAR(20) NOT NULL,
password                VARCHAR(20) NOT NULL,
PRIMARY KEY (id)   
)ENGINE=InnoDB;

