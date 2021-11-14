CREATE DATABASE login_system;

CREATE TABLE login_system.users (
   id int(11) NOT NULL AUTO_INCREMENT,
   username varchar(255) CHARACTER SET utf8 NOT NULL UNIQUE,
   email varchar(255) CHARACTER SET utf8 NOT NULL UNIQUE,
   Password text CHARACTER SET utf8 NOT NULL,
   created_at timestamp
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;