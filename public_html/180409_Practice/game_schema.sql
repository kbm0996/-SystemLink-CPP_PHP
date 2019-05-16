CREATE SCHEMA `game_schema` DEFAULT CHARACTER SET utf8;

USE game_schema;

CREATE TABLE `account` (
	`accountno` BIGINT primary key not null auto_increment, 
	`id` VARCHAR(45) unique not null, 
	`password` VARCHAR(64) not null, 
	`nickname` VARCHAR(45) unique not null
);

CREATE TABLE `login` (
	`accountno` BIGINT primary key not null, 
	`time` BIGINT not null, 
	`ip` VARCHAR(45) not null, 
	`count` BIGINT not null
);

CREATE TABLE `session` (
	`accountno` BIGINT primary key not null, 
	`sessionkey` VARCHAR(45) unique not null, 
	`regtime` BIGINT not null
);

CREATE TABLE `score` (
	`accountno` BIGINT primary key not null, 
	`score` BIGINT not null
);

