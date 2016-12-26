create table user(
id int primary key auto_increment,
username varchar(30) not null,
password varchar(30) not null,
role varchar(30),
roleAction varchar(30)
);
insert into user(username,password,role,roleAction) values("hxl","hxl123456","管理员","管理权限");