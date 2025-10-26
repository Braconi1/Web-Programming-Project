CREATE DATABASE RevlonVotingDB;
USE RevlonVotingDB;

CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    jmbg CHAR(13) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE Parties (
    party_id INT AUTO_INCREMENT PRIMARY KEY,
    party_name VARCHAR(100) NOT NULL,
    logo VARCHAR(255)
);

CREATE TABLE Candidates (
    candidate_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    position VARCHAR(100),
    party_id INT,
    FOREIGN KEY (party_id) REFERENCES Parties(party_id)
);

CREATE TABLE Votes (
    vote_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    candidate_id INT,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (candidate_id) REFERENCES Candidates(candidate_id)
);

CREATE TABLE ContactMessages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT
);

CREATE TABLE Admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO Parties (party_name, logo)
VALUES 
('SDA', 'sda.jpg'),
('BPS', 'bps.png'),
('Narod i Pravda', 'narod_i_pravda.jpg'),
('SBiH', 'sbih.png');

INSERT INTO Candidates (full_name, position, party_id)
VALUES
('Amir Hadžić', 'Predsjednik', 1),
('Emina Kovačević', 'Zamjenik', 2),
('Jasmin Mehić', 'Predsjednik', 3),
('Lejla Selimović', 'Predsjednik', 4);

INSERT INTO Admins (username, password)
VALUES
('admin', 'admin123');
