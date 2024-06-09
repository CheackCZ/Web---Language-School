create database jazykova_Skola;

use jazykova_skola;

-- Vytvoření tabulek
create table Student(
	id int primary key auto_increment,
    jmeno varchar(20),
    prijmeni varchar(20),
    uzivjm varchar(32) unique,
    email varchar(50) unique,
    heslo varchar(64),
    admin bit default false
);

create table Zapis(
	id int primary key auto_increment,
	student_id int not null,
	kurz_id int not null,
    foreign key(student_id) references Student(id),
	foreign key(kurz_id) references Kurz(id),
    datum_zapisu date
);

create table Kurz(
	id int primary key auto_increment,
    nazev varchar(20),
    kapacita int,
    datum_zacatku date,
    datum_konce date
);
ALTER table kurz MODIFY COLUMN nazev VARCHAR(50);

-- Vložení dat do tabulky Student
INSERT INTO Student (jmeno, prijmeni, uzivjm, email, heslo) VALUES
('Jan', 'Novák', 'jan.novak', 'jan.novak@example.com', 'heslo123'),
('Anna', 'Svobodová', 'anna.svobodova', 'anna.svobodova@example.com', 'heslo456'),
('Petr', 'Dvořák', 'petr.dvorak', 'petr.dvorak@example.com', 'heslo789');

INSERT INTO Student (jmeno, prijmeni, uzivjm, email, heslo, admin) VALUES
('Ondřej', 'Faltin', 'vondras', 'ondra.faltin@gmail.com', 'matys', true);

-- Vložení dat do tabulky Kurz
INSERT INTO Kurz (nazev, kapacita, datum_zacatku, datum_konce) VALUES
('CAE Preparation and Certification', 4, '2024-09-07', '2025-06-24'),
('French Classes (1 on 1)', 1, '2024-09-14', '2025-06-31'),
('Germany for Beginners', 8, '2024-09-24', '2025-06-12');

-- Vložení dat do tabulky Zapis
INSERT INTO Zapis (student_id, kurz_id, datum_zapisu) VALUES
(1, 1, '2024-05-15'),
(2, 2, '2024-08-20'),
(3, 3, '2024-06-25'),
(1, 2, '2024-09-01');