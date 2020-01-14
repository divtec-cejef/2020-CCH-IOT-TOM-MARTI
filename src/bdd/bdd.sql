CREATE TABLE room
(
    id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name varchar(5) not null
);

CREATE TABLE device
(
    id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name varchar(5) not null,
	id_room int(11) not null,
    FOREIGN KEY (id_room) REFERENCES room(id)
);

CREATE TABLE measure
(
    id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    time varchar(254) not null,
    temperature varchar(254) not null,
    humidity varchar(254) not null,
	id_device int(11) not null,
    FOREIGN KEY (id_device) REFERENCES device(id)
);