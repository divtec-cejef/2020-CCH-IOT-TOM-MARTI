CREATE TABLE room
(
    id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name varchar(5) not null
);

CREATE TABLE device
(
    id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name varchar(10) not null UNIQUE,
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

INSERT INTO room (name) VALUES ('B1-01');
INSERT INTO room (name) VALUES ('B1-02');
INSERT INTO room (name) VALUES ('B1-03');
INSERT INTO room (name) VALUES ('B1-04');
INSERT INTO room (name) VALUES ('B1-05');
INSERT INTO room (name) VALUES ('B1-06');
INSERT INTO room (name) VALUES ('B1-07');
INSERT INTO room (name) VALUES ('B1-08');
INSERT INTO room (name) VALUES ('B1-09');
INSERT INTO room (name) VALUES ('B1-10');
INSERT INTO room (name) VALUES ('B1-11');
INSERT INTO room (name) VALUES ('B1-12');