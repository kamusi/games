CREATE DATABASE kamusi;

USE kamusi;

CREATE TABLE app (app_id VARCHAR(64), app_secret VARCHAR(64));

CREATE TABLE admin (Alias VARCHAR(64), Email VARCHAR(256));

CREATE TABLE wordnet (ID INT auto_increment, Word VARCHAR(64), PartOfSpeech VARCHAR(64), Definition VARCHAR(256), PRIMARY KEY(ID));

CREATE TABLE languages (ID INT auto_increment, LanguageName VARCHAR(64), PRIMARY KEY(ID));

CREATE TABLE users (UserID VARCHAR(64), Points INT DEFAULT 0, Rating INT DEFAULT 0, PositionMode1 INT DEFAULT 1, PositionMode2 INT DEFAULT 1, Notify INT DEFAULT 0, Mute INT DEFAULT 0, NumReports INT DEFAULT 0, Language INT, NotificationFrequency INT, PRIMARY KEY(UserID), FOREIGN KEY (Language) REFERENCES languages (ID));

CREATE TABLE rankedwords (ID INT, Word VARCHAR(64), PartOfSpeech VARCHAR(16), Rank INT, Consensus TINYINT(1) DEFAULT 0, PRIMARY KEY(ID));

CREATE TABLE definitions (ID INT auto_increment, GroupID INT, Definition VARCHAR(256), UserID VARCHAR(64), Votes INT DEFAULT 0, PRIMARY KEY(ID), FOREIGN KEY (UserID) REFERENCES users (UserID));

CREATE TABLE words (ID INT auto_increment, Word VARCHAR(64), PartOfSpeech VARCHAR(64), DefinitionID INT, PRIMARY KEY(ID));

CREATE TABLE translations (ID INT auto_increment, WordID INT, UserID VARCHAR(64), Translation VARCHAR(64), LanguageID INT, PRIMARY KEY(ID), FOREIGN KEY (WordID) REFERENCES words(ID), FOREIGN KEY (UserID) REFERENCES users(UserID), FOREIGN KEY (LanguageID) REFERENCES languages(ID));

CREATE TABLE pos (ID INT auto_increment, Code VARCHAR(64), Full VARCHAR(64), PRIMARY KEY (ID));

CREATE INDEX RankIndex ON rankedwords(Rank);

CREATE INDEX WordIndex ON rankedwords (Word);

CREATE INDEX WordIndex ON words (Word);