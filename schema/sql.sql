/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
USE mysql;
DROP DATABASE IF EXISTS SPR;
CREATE DATABASE SPR;
USE SPR;

GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'production' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;

DROP TABLE IF EXISTS ClassificatedInitiatives;
CREATE TABLE `ClassificatedInitiatives` (
  `id` int(11) NOT NULL auto_increment,
  `Initiative` int(11) default NULL,
  `Classification` int(11) default NULL,
  `Author` int(11) default NULL,
  `Deleted` tinyint(1) NOT NULL,
  `addDate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=cp1251;

INSERT INTO ClassificatedInitiatives VALUES (1, 1, 7, NULL, b'0', NULL);
INSERT INTO ClassificatedInitiatives VALUES (9, 14, 1, NULL, b'0', '2009-05-07 20:39:43');
INSERT INTO ClassificatedInitiatives VALUES (3, 4, 7, NULL, b'0', NULL);
INSERT INTO ClassificatedInitiatives VALUES (4, 5, 7, 0, b'0', '0000-00-00 00:00:00');
INSERT INTO ClassificatedInitiatives VALUES (5, 4, 2037, 3, b'0', NULL);
INSERT INTO ClassificatedInitiatives VALUES (6, 21, 1, 0, b'0', '2009-05-07 03:46:01');
INSERT INTO ClassificatedInitiatives VALUES (7, 22, 1, 0, b'0', '2009-05-07 03:50:07');
INSERT INTO ClassificatedInitiatives VALUES (8, 21, 2, 3, b'0', '2009-05-07 17:22:37');
INSERT INTO ClassificatedInitiatives VALUES (10, 15, 1, NULL, b'0', '2009-05-07 20:39:59');
INSERT INTO ClassificatedInitiatives VALUES (11, 16, 1, NULL, b'0', '2009-05-07 20:40:11');
INSERT INTO ClassificatedInitiatives VALUES (12, 22, 7, 0, b'0', '2009-05-07 23:06:09');
INSERT INTO ClassificatedInitiatives VALUES (13, 8, 8, 0, b'0', '2009-05-07 23:07:05');
INSERT INTO ClassificatedInitiatives VALUES (14, 14, 5, 0, b'0', '2009-05-07 23:07:37');

DROP TABLE IF EXISTS Decisions;
CREATE TABLE `Decisions` (
  `id` int(11) NOT NULL auto_increment,
  `Initiative` int(11) default NULL,
  `votesPro` int(11) default NULL,
  `votesCon` int(11) default NULL,
  `addDate` datetime default NULL,
  `Deleted` tinyint(1) default NULL,
  `Author` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS Delegations;
CREATE TABLE `Delegations` (
  `id` int(11) NOT NULL auto_increment,
  `delegateFrom` int(11) default NULL,
  `delegateTo` int(11) default NULL,
  `delegateType` tinyint(4) default NULL,
  `Classification` int(11) default NULL,
  `Exclude` tinyint(1) default NULL,
  `addDate` datetime default NULL,
  `Deleted` tinyint(1) default NULL,
  `Author` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=cp1251;

INSERT INTO Delegations VALUES (1, 2, 3, 1, 1, b'0', NULL, b'0', 2);
INSERT INTO Delegations VALUES (5, 4, 2, 2, 1, b'0', '2009-05-03 21:56:47', b'0', 4);
INSERT INTO Delegations VALUES (3, 3, 2, 1, 7, b'0', NULL, b'0', 3);
INSERT INTO Delegations VALUES (4, 3, 1, 1, 1, b'0', NULL, b'0', 3);
INSERT INTO Delegations VALUES (6, 5, 2, 2, 4, b'1', '2009-05-03 21:58:08', b'0', 5);
INSERT INTO Delegations VALUES (7, 2, 3, 1, 2037, b'0', '2009-05-07 14:31:07', b'1', 0);
INSERT INTO Delegations VALUES (8, 2, 2, 1, 2, b'0', '2009-05-07 14:53:40', b'0', 0);
INSERT INTO Delegations VALUES (9, 2, 5, 1, 2037, b'1', '2009-05-07 16:07:28', b'1', 0);
INSERT INTO Delegations VALUES (10, 2, 4, 2, 4, b'0', '2009-05-07 16:18:26', b'1', 2);
INSERT INTO Delegations VALUES (11, 2, 4, 2, 4, b'1', '2009-05-07 16:54:53', b'0', 2);
INSERT INTO Delegations VALUES (12, 2, 3, 1, 2037, b'1', '2009-05-07 16:55:05', b'1', 2);
INSERT INTO Delegations VALUES (13, 2, 3, 2, 2037, b'1', '2009-05-07 16:57:47', b'1', 2);
INSERT INTO Delegations VALUES (14, 2, 3, 2, 3, b'1', '2009-05-07 16:58:13', b'0', 2);

DROP TABLE IF EXISTS Events;
CREATE TABLE `Events` (
  `id` int(11) NOT NULL auto_increment,
  `Title` char(255) default NULL,
  `Author` int(11) default NULL,
  `eventRating` int(10) default NULL,
  `authorRating` int(11) default NULL,
  `eventText` text,
  `eventDate` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `Author` (`Author`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=cp1251;

INSERT INTO Events VALUES (1, '������� �������', NULL, 1, NULL, '����� �������', '2009-04-09 00:00:00');

DROP TABLE IF EXISTS Initiatives;
CREATE TABLE `Initiatives` (
  `id` int(11) NOT NULL auto_increment,
  `Author` int(11) default NULL,
  `Title` char(255) default NULL,
  `Location` int(11) default NULL,
  `Description` text,
  `authorRating` int(11) default NULL,
  `initRating` int(11) default NULL,
  `addDate` datetime default NULL,
  `deadLine` datetime default NULL,
  `Voting` tinyint(1) default NULL,
  `Decision` tinyint(1) default NULL,
  `Closed` tinyint(1) default NULL,
  `Information` text,
  `Deleted` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=cp1251;

INSERT INTO Initiatives VALUES (3, 4, '����� ����������1!', 14, '��������2009-04-11T04:40:20+03:00', 0, 0, '0000-00-00 00:00:00', '2009-01-01 01:00:00', b'0', b'0', b'1', '', b'0');
INSERT INTO Initiatives VALUES (4, 4, '���� 4', 1, '��������', 0, 2, '2009-04-11 00:00:00', '2009-05-06 00:32:37', b'1', b'0', b'0', '', b'0');
INSERT INTO Initiatives VALUES (5, 3, '���� 5', 1, '�� ���� ����� ����� ���������� ������� �������� ����������. ����������� ��� ��������� ��������� �����, ����� ���������� ��� �����������, ��� ���������� ���� ��� ����������� ����� �������.', 0, 2, '2009-04-11 00:00:00', '2009-05-08 00:32:44', b'1', b'0', b'0', '', b'0');
INSERT INTO Initiatives VALUES (6, 2, '����� ����������!', 1, '�� ���� ����� ����� ���������� ������� �������� ����������. ����������� ��� ��������� ��������� �����, ����� ���������� ��� �����������, ��� ���������� ���� ��� ����������� ����� �������.', 0, 1, '2009-04-11 00:00:00', '2009-05-05 01:16:14', b'0', b'0', b'1', '����� ��� ����������\r\n\r\n��� ��������\r\n', b'0');
INSERT INTO Initiatives VALUES (7, 3, '����� ����������!', 2, '��������', 0, 0, '2009-04-11 00:00:00', '2010-01-02 00:00:00', b'0', b'0', b'0', '', b'0');
INSERT INTO Initiatives VALUES (8, 4, '����� ����������!', 1, '��������', 0, 0, '2009-04-11 00:00:00', '2009-01-01 00:00:00', b'0', b'0', b'0', '', b'0');
INSERT INTO Initiatives VALUES (9, 5, '', 0, '', 0, 0, '2009-04-11 04:11:07', '0000-00-00 00:00:00', b'0', b'0', b'1', '', b'0');
INSERT INTO Initiatives VALUES (10, 2, '', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', b'0', b'0', b'1', '', b'0');
INSERT INTO Initiatives VALUES (11, 3, '', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', b'0', b'0', b'1', '', b'0');
INSERT INTO Initiatives VALUES (12, 4, '', 0, '', 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', b'0', b'0', b'1', '', b'0');
INSERT INTO Initiatives VALUES (13, 5, '', 0, '', 0, 0, '2009-04-11 04:28:52', '0000-00-00 00:00:00', b'0', b'0', b'1', '', b'0');
INSERT INTO Initiatives VALUES (14, 2, '����� ����������1!', 5, '��������', 0, 0, '2009-04-11 04:30:12', '2009-01-01 00:00:00', b'0', b'0', b'0', '����� ��� ���������� ������\r\n\r\n��������', b'0');
INSERT INTO Initiatives VALUES (15, 3, '����� ����������1!', 7, '��������', 0, 0, '2009-04-11 04:37:10', '2009-01-01 00:00:00', b'0', b'0', b'0', '', b'0');
INSERT INTO Initiatives VALUES (16, 4, '����� ����������1!', 6, '��������', 0, 0, '2009-04-11 04:37:26', '2009-01-01 00:00:00', b'0', b'0', b'0', '', b'0');
INSERT INTO Initiatives VALUES (17, 5, '����� ����������1!', 8, '��������', 0, 0, '2009-04-11 04:38:00', '2009-01-01 00:00:00', b'0', b'0', b'1', '', b'0');
INSERT INTO Initiatives VALUES (18, 2, '����� ����������1!', 10, '��������', 0, 0, '2009-04-11 04:39:13', '2009-01-01 00:00:00', b'0', b'0', b'1', '', b'0');
INSERT INTO Initiatives VALUES (19, 3, '����� ����������1!', 16, '��������', 0, 0, '2009-04-11 04:39:58', '2009-01-01 00:00:00', b'0', b'0', b'1', '', b'0');
INSERT INTO Initiatives VALUES (20, 4, '����� ����������1!', 15, '��������', 0, 0, '2009-04-11 04:40:20', '2009-01-01 00:00:00', b'0', b'0', b'1', '', b'0');
INSERT INTO Initiatives VALUES (21, 0, '� ��� � ������ ���������� ��������� ����� ���������!', 1, '������ ��������', 1000, 2, '2009-05-07 03:46:01', '2009-05-07 03:46:01', b'0', b'0', b'0', '����������� ����� �������� �����.', b'0');
INSERT INTO Initiatives VALUES (22, 2, '� ��� � ������ ����������', 1, '����� ��������� ������\r\n', 1000, 2, '2009-05-07 03:50:07', '2009-05-07 03:50:07', b'0', b'0', b'0', '� ������ ��������� ����������', b'0');

DROP TABLE IF EXISTS Locations;
CREATE TABLE `Locations` (
  `id` int(11) NOT NULL auto_increment,
  `Title` char(255) default NULL,
  `Parent` int(11) default NULL,
  `Description` text,
  `left_key` int(11) NOT NULL default '0',
  `right_key` int(11) NOT NULL default '0',
  `level` int(11) NOT NULL default '0',
  `Author` int(11) default NULL,
  `addDate` datetime default NULL,
  `Deleted` tinyint(1) default NULL,
  `Path` char(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `left_key` (`left_key`,`right_key`,`level`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=cp1251;

INSERT INTO Locations VALUES (1, 'Global', NULL, 'Global - root of locations tree', 1, 30, 0, 1, NULL, b'0', NULL);
INSERT INTO Locations VALUES (3, '���� (Crimea)', 1, '������ ���������� ���������� ���� ������� ����� (All Crimean membrs)', 2, 29, 1, 1, '2009-04-23 00:32:46', b'0', '/1');
INSERT INTO Locations VALUES (4, '����������� (Simferopol)', 3, '����/����������� (Crimea/Simferopol)', 3, 4, 2, 1, '2009-04-23 00:32:46', b'0', '/1/3');
INSERT INTO Locations VALUES (5, '����������� (Sevastopol)', 3, '����/����������� (Crimea/Sevastopol)', 5, 14, 2, 1, '2009-04-23 00:32:46', b'0', '/1/3');
INSERT INTO Locations VALUES (6, '���� (Yalta)', 3, '����/���� (Crimea/Yalta)', 15, 16, 2, 1, '2009-04-23 00:32:46', b'0', '/1/3');
INSERT INTO Locations VALUES (7, '����������� (Simferopol)', 3, '����/����������� (Crimea/Simferopol)', 17, 28, 2, 1, '2009-04-23 00:32:46', b'0', '/1/3');
INSERT INTO Locations VALUES (8, '�������� �����', 7, '����/�����������/�������� �����', 18, 19, 3, 1, '2009-04-23 00:32:46', b'0', '/1/3/7');
INSERT INTO Locations VALUES (9, '��������������� �����', 7, '����/�����������/��������������� �����', 20, 21, 3, 1, '2009-04-23 00:32:46', b'0', '/1/3/7');
INSERT INTO Locations VALUES (10, '����������� �����', 7, '����/�����������/����������� �����', 22, 27, 3, 1, '2009-04-23 00:32:46', b'0', '/1/3/7');
INSERT INTO Locations VALUES (11, '������� ��.', 10, '����/�����������/����������� �����/������� ��.', 23, 24, 4, 1, '2009-04-23 00:32:46', b'0', '/1/3/7/10');
INSERT INTO Locations VALUES (12, '�������� ��.', 10, '����/�����������/����������� �����/�������� ��.', 25, 26, 4, 1, '2009-04-23 00:32:46', b'0', '/1/3/7/10');
INSERT INTO Locations VALUES (13, '����������� �����', 5, '����/�����������/����������� �����', 6, 7, 3, 1, '2009-04-23 00:36:13', b'0', '/1/3/5');
INSERT INTO Locations VALUES (14, '����������� �����', 5, '����/�����������/����������� �����', 8, 13, 3, 1, '2009-04-23 00:36:13', b'0', '/1/3/5');
INSERT INTO Locations VALUES (15, '������� ��.', 14, '����/�����������/����������� �����/������� ��.', 9, 10, 4, 1, '2009-04-23 00:36:13', b'0', '/1/3/5/14');
INSERT INTO Locations VALUES (16, '����������� ��.', 14, '����/�����������/����������� �����/����������� ��.', 11, 12, 4, 1, '2009-04-23 00:36:13', b'0', '/1/3/5/14');

DROP TABLE IF EXISTS Members;
CREATE TABLE `Members` (
  `id` int(11) NOT NULL auto_increment,
  `firstName` char(50) default NULL,
  `lastName` char(50) default NULL,
  `userName` char(50) default NULL,
  `password` char(50) default NULL,
  `memberLocation` int(11) default NULL,
  `LocationDate` datetime default NULL,
  `voteWeight` int(11) default NULL,
  `addDate` datetime default NULL,
  `Email` char(255) default NULL,
  `Blocked` tinyint(1) default NULL,
  `Deleted` tinyint(1) default NULL,
  `Author` int(11) default NULL,
  `Language` char(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=cp1251;

INSERT INTO Members VALUES (1, 'E-congress', 'system', 'System', '12345678909876543212qwsderfgtyhfdsswer', 14, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', '', b'0', b'0', 0, NULL);
INSERT INTO Members VALUES (3, 'Ivan', 'Ivanov', 'ivan', '123', 14, NULL, 1, NULL, NULL, b'0', b'0', 2, 'english');
INSERT INTO Members VALUES (5, 'Sergey', 'Menyschikov', 'SM', '123', 13, '2009-05-03 21:56:00', 1, '2009-05-03 21:56:03', NULL, b'0', b'0', 1, 'english');

DROP TABLE IF EXISTS Posts;
CREATE TABLE `Posts` (
  `id` int(11) NOT NULL auto_increment,
  `Topic` int(11) default NULL,
  `Author` int(11) default NULL,
  `addDate` datetime default NULL,
  `authorRating` int(11) default NULL,
  `postText` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS Relations;
CREATE TABLE `Relations` (
  `id` int(11) NOT NULL auto_increment,
  `tableFrom` char(255) default NULL,
  `fieldFrom` char(255) default NULL,
  `tableTo` char(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `TableFrom` (`tableFrom`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS SysLog;
CREATE TABLE `SysLog` (
  `id` int(11) NOT NULL auto_increment,
  `Author` int(11) default NULL,
  `Deleted` tinyint(1) default NULL,
  `addDate` datetime default NULL,
  `Error` char(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=cp1251;

INSERT INTO SysLog VALUES (1, 2, b'0', '2009-05-03 12:55:45', 'Econgress class: Member #2 is not in Initiative Location #');
INSERT INTO SysLog VALUES (2, 2, b'0', '2009-05-03 12:57:14', 'Econgress class: Member #2 is not in Initiative Location #1');
INSERT INTO SysLog VALUES (3, 2, b'0', '2009-05-03 12:59:51', 'Econgress class: Member #2 is not in Initiative Location #1');
INSERT INTO SysLog VALUES (4, 2, b'0', '2009-05-03 13:00:31', 'Econgress class: Member #2 is not in Initiative Location #1');
INSERT INTO SysLog VALUES (5, 2, b'0', '2009-05-03 13:03:04', 'Econgress class: Member #2 is not in Initiative Location #1');
INSERT INTO SysLog VALUES (6, 2, b'0', '2009-05-03 13:03:35', 'Econgress class: Member #2 is not in Initiative Location #1');
INSERT INTO SysLog VALUES (7, 2, b'0', '2009-05-03 13:03:42', 'Econgress class: Member #2 is not in Initiative Location #1');
INSERT INTO SysLog VALUES (8, 2, b'0', '2009-05-03 13:04:44', 'Econgress class: Member #2 is not in Initiative Location #1');
INSERT INTO SysLog VALUES (9, 2, b'0', '2009-05-03 13:13:20', 'Econgress class: Member #2 is not in Initiative Location #1');
INSERT INTO SysLog VALUES (10, 2, b'0', '2009-05-03 13:13:28', 'Econgress class: Member #2 is not in Initiative Location #1');
INSERT INTO SysLog VALUES (11, 2, b'0', '2009-05-03 13:14:36', 'Econgress class: Member #2 is not in Initiative Location #1');
INSERT INTO SysLog VALUES (12, 2, b'0', '2009-05-03 17:46:39', 'Econgress class: Wrong time to vote for Inittiative #4');
INSERT INTO SysLog VALUES (13, 2, b'0', '2009-05-03 17:46:52', 'Econgress class: Wrong time to vote for Inittiative #4');
INSERT INTO SysLog VALUES (14, 2, b'0', '2009-05-03 17:47:39', 'Econgress class: Wrong time to vote for Inittiative #4');
INSERT INTO SysLog VALUES (15, 2, b'0', '2009-05-03 17:47:47', 'Econgress class: Wrong time to vote for Inittiative #4');
INSERT INTO SysLog VALUES (16, 2, b'0', '2009-05-07 02:36:03', 'Econgress class: Inittiative # not found.');
INSERT INTO SysLog VALUES (17, 2, b'0', '2009-05-07 02:36:39', 'Econgress class: Inittiative # not found.');
INSERT INTO SysLog VALUES (18, 2, b'0', '2009-05-07 02:36:57', 'Econgress class: Inittiative # not found.');
INSERT INTO SysLog VALUES (19, 2, b'0', '2009-05-07 02:38:55', 'Econgress class: Inittiative # not found.');
INSERT INTO SysLog VALUES (20, 2, b'0', '2009-05-07 02:41:19', 'Econgress class: Inittiative # not found.');
INSERT INTO SysLog VALUES (21, 2, b'0', '2009-05-07 16:07:44', 'Econgress class: Delegation #9 can not be removed because created by another Author #0.');
INSERT INTO SysLog VALUES (22, 2, b'0', '2009-05-07 16:08:01', 'Econgress class: Delegation #9 can not be removed because created by another Author #0.');
INSERT INTO SysLog VALUES (23, 2, b'0', '2009-05-07 16:13:27', 'Econgress class: Delegation #9 can not be removed because created by another Author #0.');

DROP TABLE IF EXISTS SysMessages;
CREATE TABLE `SysMessages` (
  `id` int(11) NOT NULL auto_increment,
  `messTo` int(11) default NULL,
  `Message` char(255) default NULL,
  `Type` int(11) default NULL,
  `Author` int(11) default NULL,
  `addDate` datetime default NULL,
  `Deleted` tinyint(1) default NULL,
  PRIMARY KEY  (`id`),
  KEY `Type` (`Type`,`addDate`),
  KEY `messTo` (`messTo`)
) ENGINE=MyISAM AUTO_INCREMENT=325 DEFAULT CHARSET=cp1251;

INSERT INTO SysMessages VALUES (57, 3, '������������� ���������� �� ������������� #2', 0, 1, '2009-04-26 00:00:00', b'0');
INSERT INTO SysMessages VALUES (58, 2, '������������� ���������� �� ������������� #1', 0, 1, '2009-04-26 00:00:00', b'0');
INSERT INTO SysMessages VALUES (55, 3, 'Created new delegation to classify. Classification element #2 (������� �������������) delegate to Memeber #3 (Ivan Ivanov)', 0, 2, '2009-04-23 05:02:58', b'0');
INSERT INTO SysMessages VALUES (56, 2, '������������� ���������� �� ������������� #2', 0, 1, '2009-04-26 00:00:00', b'0');
INSERT INTO SysMessages VALUES (54, 2, 'Created new delegation to classify. Classification element #2 (������� �������������) delegate to Memeber #3 (Ivan Ivanov)', 0, 1, '2009-04-23 05:02:58', b'0');
INSERT INTO SysMessages VALUES (53, 2, 'You try to delegate repeated. Classification element #2 (������� �������������) delegate to Memeber #3 (Ivan Ivanov)', 0, 1, '2009-04-23 05:02:35', b'0');
INSERT INTO SysMessages VALUES (59, 2, '������������� ���������� �� ������������� #2', 0, 1, '2009-04-26 00:00:00', b'0');
INSERT INTO SysMessages VALUES (60, 3, '������������� ���������� �� ������������� #2', 0, 1, '2009-04-26 00:00:00', b'0');
INSERT INTO SysMessages VALUES (61, 2, '������������� ���������� �� ������������� #1', 0, 1, '2009-04-26 00:00:00', b'0');
INSERT INTO SysMessages VALUES (62, 2, 'Login', 0, 1, '2009-05-01 14:27:34', b'0');
INSERT INTO SysMessages VALUES (63, 2, 'Login', 0, 1, '2009-05-01 16:40:47', b'0');
INSERT INTO SysMessages VALUES (64, 2, 'Login', 0, 1, '2009-05-01 17:53:25', b'0');
INSERT INTO SysMessages VALUES (65, 2, 'Login', 0, 1, '2009-05-01 19:47:26', b'0');
INSERT INTO SysMessages VALUES (66, 2, 'Login', 0, 1, '2009-05-01 19:48:15', b'0');
INSERT INTO SysMessages VALUES (67, 2, 'Login', 0, 1, '2009-05-01 19:48:52', b'0');
INSERT INTO SysMessages VALUES (68, 2, 'Login', 0, 1, '2009-05-02 00:36:11', b'0');
INSERT INTO SysMessages VALUES (69, 2, 'Login', 0, 1, '2009-05-02 00:36:39', b'0');
INSERT INTO SysMessages VALUES (70, 2, 'Login', 0, 1, '2009-05-02 00:44:04', b'0');
INSERT INTO SysMessages VALUES (71, 2, 'Login', 0, 1, '2009-05-03 00:21:55', b'0');
INSERT INTO SysMessages VALUES (72, 2, 'Login', 0, 1, '2009-05-03 00:22:28', b'0');
INSERT INTO SysMessages VALUES (73, 2, 'Login', 0, 1, '2009-05-03 00:32:18', b'0');
INSERT INTO SysMessages VALUES (74, 2, 'Login', 0, 1, '2009-05-03 11:44:27', b'0');
INSERT INTO SysMessages VALUES (75, 2, 'Login', 0, 1, '2009-05-03 11:44:35', b'0');
INSERT INTO SysMessages VALUES (76, 2, 'Login', 0, 1, '2009-05-03 12:36:18', b'0');
INSERT INTO SysMessages VALUES (77, 2, 'Login', 0, 1, '2009-05-03 12:36:28', b'0');
INSERT INTO SysMessages VALUES (78, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 13:26:30', b'0');
INSERT INTO SysMessages VALUES (79, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 13:34:26', b'0');
INSERT INTO SysMessages VALUES (80, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 13:39:10', b'0');
INSERT INTO SysMessages VALUES (81, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 13:39:28', b'0');
INSERT INTO SysMessages VALUES (82, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:06:21', b'0');
INSERT INTO SysMessages VALUES (83, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:07:04', b'0');
INSERT INTO SysMessages VALUES (84, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:07:09', b'0');
INSERT INTO SysMessages VALUES (85, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:08:00', b'0');
INSERT INTO SysMessages VALUES (86, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:08:29', b'0');
INSERT INTO SysMessages VALUES (87, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:09:03', b'0');
INSERT INTO SysMessages VALUES (88, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:09:45', b'0');
INSERT INTO SysMessages VALUES (89, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:10:15', b'0');
INSERT INTO SysMessages VALUES (90, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:10:36', b'0');
INSERT INTO SysMessages VALUES (91, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:11:10', b'0');
INSERT INTO SysMessages VALUES (92, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:13:40', b'0');
INSERT INTO SysMessages VALUES (93, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:14:56', b'0');
INSERT INTO SysMessages VALUES (94, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:33:28', b'0');
INSERT INTO SysMessages VALUES (95, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:34:35', b'0');
INSERT INTO SysMessages VALUES (96, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:35:18', b'0');
INSERT INTO SysMessages VALUES (97, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 15:36:10', b'0');
INSERT INTO SysMessages VALUES (98, 2, 'Logout', 0, 1, '2009-05-03 15:45:28', b'0');
INSERT INTO SysMessages VALUES (99, 1, 'Login', 0, 1, '2009-05-03 15:45:29', b'0');
INSERT INTO SysMessages VALUES (100, 1, 'Logout', 0, 1, '2009-05-03 15:45:51', b'0');
INSERT INTO SysMessages VALUES (101, 1, 'Login', 0, 1, '2009-05-03 15:45:51', b'0');
INSERT INTO SysMessages VALUES (102, 1, 'Logout', 0, 1, '2009-05-03 15:48:13', b'0');
INSERT INTO SysMessages VALUES (103, 1, 'Login', 0, 1, '2009-05-03 15:48:13', b'0');
INSERT INTO SysMessages VALUES (104, 1, 'Logout', 0, 1, '2009-05-03 15:48:41', b'0');
INSERT INTO SysMessages VALUES (105, 3, 'Login', 0, 1, '2009-05-03 15:48:41', b'0');
INSERT INTO SysMessages VALUES (106, 3, 'Logout', 0, 1, '2009-05-03 15:48:51', b'0');
INSERT INTO SysMessages VALUES (107, 2, 'Login', 0, 1, '2009-05-03 15:48:51', b'0');
INSERT INTO SysMessages VALUES (108, 2, 'Logout', 0, 1, '2009-05-03 15:49:01', b'0');
INSERT INTO SysMessages VALUES (109, 3, 'Login', 0, 1, '2009-05-03 15:49:01', b'0');
INSERT INTO SysMessages VALUES (110, 3, 'Logout', 0, 1, '2009-05-03 15:52:04', b'0');
INSERT INTO SysMessages VALUES (111, 3, 'Login', 0, 1, '2009-05-03 15:52:04', b'0');
INSERT INTO SysMessages VALUES (112, 3, 'Logout', 0, 1, '2009-05-03 15:53:11', b'0');
INSERT INTO SysMessages VALUES (113, 3, 'Login', 0, 1, '2009-05-03 15:53:11', b'0');
INSERT INTO SysMessages VALUES (114, 3, 'Logout', 0, 1, '2009-05-03 15:54:36', b'0');
INSERT INTO SysMessages VALUES (115, 3, 'Login', 0, 1, '2009-05-03 15:54:36', b'0');
INSERT INTO SysMessages VALUES (116, 3, 'Logout', 0, 1, '2009-05-03 15:55:16', b'0');
INSERT INTO SysMessages VALUES (117, 3, 'Login', 0, 1, '2009-05-03 15:55:16', b'0');
INSERT INTO SysMessages VALUES (118, 3, 'Logout', 0, 1, '2009-05-03 15:55:38', b'0');
INSERT INTO SysMessages VALUES (119, 1, 'Login', 0, 1, '2009-05-03 15:55:38', b'0');
INSERT INTO SysMessages VALUES (120, 1, 'Logout', 0, 1, '2009-05-03 15:58:40', b'0');
INSERT INTO SysMessages VALUES (121, 1, 'Login', 0, 1, '2009-05-03 15:58:40', b'0');
INSERT INTO SysMessages VALUES (122, 1, 'Logout', 0, 1, '2009-05-03 16:01:43', b'0');
INSERT INTO SysMessages VALUES (123, 1, 'Login', 0, 1, '2009-05-03 16:01:43', b'0');
INSERT INTO SysMessages VALUES (124, 1, 'Logout', 0, 1, '2009-05-03 16:04:54', b'0');
INSERT INTO SysMessages VALUES (125, 1, 'Login', 0, 1, '2009-05-03 16:04:54', b'0');
INSERT INTO SysMessages VALUES (126, 1, 'Logout', 0, 1, '2009-05-03 16:07:42', b'0');
INSERT INTO SysMessages VALUES (127, 1, 'Login', 0, 1, '2009-05-03 16:07:42', b'0');
INSERT INTO SysMessages VALUES (128, 1, 'Logout', 0, 1, '2009-05-03 16:08:08', b'0');
INSERT INTO SysMessages VALUES (129, 1, 'Login', 0, 1, '2009-05-03 16:08:08', b'0');
INSERT INTO SysMessages VALUES (130, 1, 'Logout', 0, 1, '2009-05-03 16:41:25', b'0');
INSERT INTO SysMessages VALUES (131, 1, 'Login', 0, 1, '2009-05-03 16:41:25', b'0');
INSERT INTO SysMessages VALUES (132, 1, 'Logout', 0, 1, '2009-05-03 16:45:24', b'0');
INSERT INTO SysMessages VALUES (133, 1, 'Login', 0, 1, '2009-05-03 16:45:24', b'0');
INSERT INTO SysMessages VALUES (134, 1, 'Logout', 0, 1, '2009-05-03 16:48:32', b'0');
INSERT INTO SysMessages VALUES (135, 1, 'Login', 0, 1, '2009-05-03 16:48:32', b'0');
INSERT INTO SysMessages VALUES (136, 1, 'Logout', 0, 1, '2009-05-03 16:49:37', b'0');
INSERT INTO SysMessages VALUES (137, 1, 'Login', 0, 1, '2009-05-03 16:49:37', b'0');
INSERT INTO SysMessages VALUES (138, 1, 'Logout', 0, 1, '2009-05-03 16:56:04', b'0');
INSERT INTO SysMessages VALUES (139, 1, 'Login', 0, 1, '2009-05-03 16:56:04', b'0');
INSERT INTO SysMessages VALUES (140, 1, 'Logout', 0, 1, '2009-05-03 16:58:01', b'0');
INSERT INTO SysMessages VALUES (141, 1, 'Login', 0, 1, '2009-05-03 16:58:01', b'0');
INSERT INTO SysMessages VALUES (142, 1, 'Logout', 0, 1, '2009-05-03 16:59:13', b'0');
INSERT INTO SysMessages VALUES (143, 2, 'Login', 0, 1, '2009-05-03 16:59:13', b'0');
INSERT INTO SysMessages VALUES (144, 2, 'Logout', 0, 1, '2009-05-03 17:01:02', b'0');
INSERT INTO SysMessages VALUES (145, 2, 'Login', 0, 1, '2009-05-03 17:01:02', b'0');
INSERT INTO SysMessages VALUES (146, 2, 'Logout', 0, 1, '2009-05-03 17:01:04', b'0');
INSERT INTO SysMessages VALUES (147, 2, 'Login', 0, 1, '2009-05-03 17:01:04', b'0');
INSERT INTO SysMessages VALUES (148, 2, 'Logout', 0, 1, '2009-05-03 17:02:45', b'0');
INSERT INTO SysMessages VALUES (149, 2, 'Login', 0, 1, '2009-05-03 17:02:45', b'0');
INSERT INTO SysMessages VALUES (150, 2, 'Logout', 0, 1, '2009-05-03 17:03:32', b'0');
INSERT INTO SysMessages VALUES (151, 2, 'Login', 0, 1, '2009-05-03 17:03:32', b'0');
INSERT INTO SysMessages VALUES (152, 2, 'Logout', 0, 1, '2009-05-03 17:04:17', b'0');
INSERT INTO SysMessages VALUES (153, 2, 'Login', 0, 1, '2009-05-03 17:04:17', b'0');
INSERT INTO SysMessages VALUES (154, 2, 'Logout', 0, 1, '2009-05-03 17:04:55', b'0');
INSERT INTO SysMessages VALUES (155, 2, 'Login', 0, 1, '2009-05-03 17:04:55', b'0');
INSERT INTO SysMessages VALUES (156, 2, 'Logout', 0, 1, '2009-05-03 17:05:42', b'0');
INSERT INTO SysMessages VALUES (157, 2, 'Login', 0, 1, '2009-05-03 17:05:42', b'0');
INSERT INTO SysMessages VALUES (158, 2, 'Logout', 0, 1, '2009-05-03 17:36:28', b'0');
INSERT INTO SysMessages VALUES (159, 2, 'Login', 0, 1, '2009-05-03 17:36:28', b'0');
INSERT INTO SysMessages VALUES (160, 2, 'Logout', 0, 1, '2009-05-03 17:46:03', b'0');
INSERT INTO SysMessages VALUES (161, 2, 'Login', 0, 1, '2009-05-03 17:46:03', b'0');
INSERT INTO SysMessages VALUES (162, 2, 'Initiative #4 (����� ����������!) was voted.', 0, 1, '2009-05-03 17:57:11', b'0');
INSERT INTO SysMessages VALUES (163, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 17:57:43', b'0');
INSERT INTO SysMessages VALUES (164, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 17:57:58', b'0');
INSERT INTO SysMessages VALUES (165, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:00:37', b'0');
INSERT INTO SysMessages VALUES (166, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:00:49', b'0');
INSERT INTO SysMessages VALUES (167, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:03:13', b'0');
INSERT INTO SysMessages VALUES (168, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:03:21', b'0');
INSERT INTO SysMessages VALUES (169, 2, 'Initiative #4 (����� ����������!) was voted.', 0, 1, '2009-05-03 18:03:25', b'0');
INSERT INTO SysMessages VALUES (170, 2, 'Initiative #4 (����� ����������!) was voted.', 0, 1, '2009-05-03 18:06:09', b'0');
INSERT INTO SysMessages VALUES (171, 2, 'Initiative #4 (����� ����������!) was voted.', 0, 1, '2009-05-03 18:06:29', b'0');
INSERT INTO SysMessages VALUES (172, 2, 'Initiative #4 (����� ����������!) was voted.', 0, 1, '2009-05-03 18:06:37', b'0');
INSERT INTO SysMessages VALUES (173, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:06:49', b'0');
INSERT INTO SysMessages VALUES (174, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:06:58', b'0');
INSERT INTO SysMessages VALUES (175, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:07:53', b'0');
INSERT INTO SysMessages VALUES (176, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:08:39', b'0');
INSERT INTO SysMessages VALUES (177, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:09:33', b'0');
INSERT INTO SysMessages VALUES (178, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:09:39', b'0');
INSERT INTO SysMessages VALUES (179, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:10:41', b'0');
INSERT INTO SysMessages VALUES (180, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:16:49', b'0');
INSERT INTO SysMessages VALUES (181, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:17:27', b'0');
INSERT INTO SysMessages VALUES (182, 2, 'Initiative #5 (\'����� ����������!\') was voted.', 0, 1, '2009-05-03 18:17:31', b'0');
INSERT INTO SysMessages VALUES (183, 2, 'Initiative #4 (����� ����������!) was voted.', 0, 1, '2009-05-03 18:17:35', b'0');
INSERT INTO SysMessages VALUES (184, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 18:19:44', b'0');
INSERT INTO SysMessages VALUES (185, 2, 'Logout', 0, 1, '2009-05-03 18:59:10', b'0');
INSERT INTO SysMessages VALUES (186, 3, 'Login', 0, 1, '2009-05-03 18:59:10', b'0');
INSERT INTO SysMessages VALUES (187, 3, 'Initiative #5 (���� 5) was voted.', 0, 1, '2009-05-03 19:01:25', b'0');
INSERT INTO SysMessages VALUES (188, 3, 'Logout', 0, 1, '2009-05-03 19:03:57', b'0');
INSERT INTO SysMessages VALUES (189, 2, 'Login', 0, 1, '2009-05-03 19:03:57', b'0');
INSERT INTO SysMessages VALUES (190, 2, 'Initiative #5 (���� 5) was voted.', 0, 1, '2009-05-03 19:04:06', b'0');
INSERT INTO SysMessages VALUES (191, 2, 'Initiative #5 (���� 5) was voted.', 0, 1, '2009-05-03 19:04:11', b'0');
INSERT INTO SysMessages VALUES (192, 2, 'Initiative #5 (���� 5) was voted.', 0, 1, '2009-05-03 19:04:20', b'0');
INSERT INTO SysMessages VALUES (193, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 19:04:33', b'0');
INSERT INTO SysMessages VALUES (194, 2, 'Logout', 0, 1, '2009-05-03 19:04:41', b'0');
INSERT INTO SysMessages VALUES (195, 3, 'Login', 0, 1, '2009-05-03 19:04:41', b'0');
INSERT INTO SysMessages VALUES (196, 3, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-03 19:04:46', b'0');
INSERT INTO SysMessages VALUES (197, 3, 'Initiative #5 (���� 5) was voted.', 0, 1, '2009-05-03 19:21:51', b'0');
INSERT INTO SysMessages VALUES (198, 3, 'Logout', 0, 1, '2009-05-03 19:51:53', b'0');
INSERT INTO SysMessages VALUES (199, 2, 'Login', 0, 1, '2009-05-03 19:51:53', b'0');
INSERT INTO SysMessages VALUES (200, 2, 'Logout', 0, 1, '2009-05-03 19:59:20', b'0');
INSERT INTO SysMessages VALUES (201, 2, 'Login', 0, 1, '2009-05-03 19:59:20', b'0');
INSERT INTO SysMessages VALUES (202, 2, 'Logout', 0, 1, '2009-05-03 20:01:02', b'0');
INSERT INTO SysMessages VALUES (203, 2, 'Login', 0, 1, '2009-05-03 20:01:02', b'0');
INSERT INTO SysMessages VALUES (204, 2, 'Logout', 0, 1, '2009-05-03 20:01:19', b'0');
INSERT INTO SysMessages VALUES (205, 2, 'Login', 0, 1, '2009-05-03 20:01:19', b'0');
INSERT INTO SysMessages VALUES (206, 2, 'Logout', 0, 1, '2009-05-03 20:02:15', b'0');
INSERT INTO SysMessages VALUES (207, 2, 'Login', 0, 1, '2009-05-03 20:02:15', b'0');
INSERT INTO SysMessages VALUES (208, 2, 'Logout', 0, 1, '2009-05-03 20:07:03', b'0');
INSERT INTO SysMessages VALUES (209, 2, 'Login', 0, 1, '2009-05-03 20:07:03', b'0');
INSERT INTO SysMessages VALUES (210, 2, 'Logout', 0, 1, '2009-05-03 20:08:05', b'0');
INSERT INTO SysMessages VALUES (211, 2, 'Login', 0, 1, '2009-05-03 20:08:05', b'0');
INSERT INTO SysMessages VALUES (212, 2, 'Logout', 0, 1, '2009-05-03 20:22:30', b'0');
INSERT INTO SysMessages VALUES (213, 2, 'Login', 0, 1, '2009-05-03 20:22:30', b'0');
INSERT INTO SysMessages VALUES (214, 2, 'Logout', 0, 1, '2009-05-03 20:25:18', b'0');
INSERT INTO SysMessages VALUES (215, 2, 'Login', 0, 1, '2009-05-03 20:25:18', b'0');
INSERT INTO SysMessages VALUES (216, 2, 'Logout', 0, 1, '2009-05-03 20:28:10', b'0');
INSERT INTO SysMessages VALUES (217, 2, 'Login', 0, 1, '2009-05-03 20:28:10', b'0');
INSERT INTO SysMessages VALUES (218, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 20:28:19', b'0');
INSERT INTO SysMessages VALUES (219, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 20:29:21', b'0');
INSERT INTO SysMessages VALUES (220, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 20:32:49', b'0');
INSERT INTO SysMessages VALUES (221, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:07:35', b'0');
INSERT INTO SysMessages VALUES (222, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:14:03', b'0');
INSERT INTO SysMessages VALUES (223, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:14:38', b'0');
INSERT INTO SysMessages VALUES (224, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:15:19', b'0');
INSERT INTO SysMessages VALUES (225, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:18:44', b'0');
INSERT INTO SysMessages VALUES (226, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:21:04', b'0');
INSERT INTO SysMessages VALUES (227, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:21:47', b'0');
INSERT INTO SysMessages VALUES (228, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:23:22', b'0');
INSERT INTO SysMessages VALUES (229, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:23:53', b'0');
INSERT INTO SysMessages VALUES (230, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:23:57', b'0');
INSERT INTO SysMessages VALUES (231, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:24:21', b'0');
INSERT INTO SysMessages VALUES (232, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:41:10', b'0');
INSERT INTO SysMessages VALUES (233, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:41:53', b'0');
INSERT INTO SysMessages VALUES (234, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:42:24', b'0');
INSERT INTO SysMessages VALUES (235, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:42:41', b'0');
INSERT INTO SysMessages VALUES (236, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:42:59', b'0');
INSERT INTO SysMessages VALUES (237, 2, 'Logout', 0, 1, '2009-05-03 21:43:16', b'0');
INSERT INTO SysMessages VALUES (238, 2, 'Login', 0, 1, '2009-05-03 21:43:16', b'0');
INSERT INTO SysMessages VALUES (239, 2, 'Login', 0, 1, '2009-05-03 21:43:55', b'0');
INSERT INTO SysMessages VALUES (240, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:44:06', b'0');
INSERT INTO SysMessages VALUES (241, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 21:45:33', b'0');
INSERT INTO SysMessages VALUES (242, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-03 22:48:26', b'0');
INSERT INTO SysMessages VALUES (243, 2, 'Login', 0, 1, '2009-05-04 21:35:39', b'0');
INSERT INTO SysMessages VALUES (244, 2, 'Login', 0, 1, '2009-05-04 23:42:47', b'0');
INSERT INTO SysMessages VALUES (245, 2, 'Login', 0, 1, '2009-05-05 00:02:23', b'0');
INSERT INTO SysMessages VALUES (246, 2, 'Logout', 0, 1, '2009-05-05 00:03:51', b'0');
INSERT INTO SysMessages VALUES (247, 4, 'Login', 0, 1, '2009-05-05 00:03:51', b'0');
INSERT INTO SysMessages VALUES (248, 2, 'Login', 0, 1, '2009-05-05 01:01:25', b'0');
INSERT INTO SysMessages VALUES (249, 2, 'Login', 0, 1, '2009-05-05 01:16:25', b'0');
INSERT INTO SysMessages VALUES (250, 2, 'Logout', 0, 1, '2009-05-05 01:29:27', b'0');
INSERT INTO SysMessages VALUES (251, 2, 'Login', 0, 1, '2009-05-05 01:29:27', b'0');
INSERT INTO SysMessages VALUES (252, 2, 'Logout', 0, 1, '2009-05-05 01:29:33', b'0');
INSERT INTO SysMessages VALUES (253, 5, 'Login', 0, 1, '2009-05-05 01:29:33', b'0');
INSERT INTO SysMessages VALUES (254, 5, 'Logout', 0, 1, '2009-05-05 01:34:18', b'0');
INSERT INTO SysMessages VALUES (255, 2, 'Login', 0, 1, '2009-05-05 01:34:18', b'0');
INSERT INTO SysMessages VALUES (256, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-05 01:56:46', b'0');
INSERT INTO SysMessages VALUES (257, 2, 'Logout', 0, 1, '2009-05-05 01:57:31', b'0');
INSERT INTO SysMessages VALUES (258, 5, 'Login', 0, 1, '2009-05-05 01:57:31', b'0');
INSERT INTO SysMessages VALUES (259, 5, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-05 02:04:52', b'0');
INSERT INTO SysMessages VALUES (260, 2, 'Login', 0, 1, '2009-05-05 02:05:12', b'0');
INSERT INTO SysMessages VALUES (261, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-05 02:49:10', b'0');
INSERT INTO SysMessages VALUES (262, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-05 02:49:23', b'0');
INSERT INTO SysMessages VALUES (263, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-05 02:54:43', b'0');
INSERT INTO SysMessages VALUES (264, 2, 'Initiative #6 (����� ����������!) was signed.', 0, 1, '2009-05-05 03:30:54', b'0');
INSERT INTO SysMessages VALUES (265, 2, 'Login', 0, 1, '2009-05-05 03:38:42', b'0');
INSERT INTO SysMessages VALUES (266, 2, 'Login', 0, 1, '2009-05-05 19:52:56', b'0');
INSERT INTO SysMessages VALUES (267, 2, 'Logout', 0, 1, '2009-05-05 19:54:40', b'0');
INSERT INTO SysMessages VALUES (268, 4, 'Login', 0, 1, '2009-05-05 19:54:40', b'0');
INSERT INTO SysMessages VALUES (269, 4, 'Logout', 0, 1, '2009-05-05 20:04:46', b'0');
INSERT INTO SysMessages VALUES (270, 2, 'Login', 0, 1, '2009-05-05 20:04:46', b'0');
INSERT INTO SysMessages VALUES (271, 2, 'Login', 0, 1, '2009-05-05 22:51:42', b'0');
INSERT INTO SysMessages VALUES (272, 2, 'Login', 0, 1, '2009-05-06 01:29:42', b'0');
INSERT INTO SysMessages VALUES (273, 2, 'Login', 0, 1, '2009-05-06 01:29:50', b'0');
INSERT INTO SysMessages VALUES (274, 2, 'Logout', 0, 1, '2009-05-06 01:31:14', b'0');
INSERT INTO SysMessages VALUES (275, 5, 'Login', 0, 1, '2009-05-06 01:31:14', b'0');
INSERT INTO SysMessages VALUES (276, 5, 'Initiative #5 (���� 5) was voted.', 0, 1, '2009-05-06 01:31:33', b'0');
INSERT INTO SysMessages VALUES (277, 2, 'Login', 0, 1, '2009-05-06 17:24:58', b'0');
INSERT INTO SysMessages VALUES (278, 2, 'Login', 0, 1, '2009-05-06 22:15:24', b'0');
INSERT INTO SysMessages VALUES (279, 2, 'Initiative #5 (���� 5) was signed.', 0, 1, '2009-05-07 01:01:46', b'0');
INSERT INTO SysMessages VALUES (280, 2, 'Initiative #4 (���� 4) was signed.', 0, 1, '2009-05-07 01:02:52', b'0');
INSERT INTO SysMessages VALUES (281, 2, 'Initiative #5 (���� 5) was signed.', 0, 1, '2009-05-07 01:03:58', b'0');
INSERT INTO SysMessages VALUES (282, 2, 'Initiative #5 (���� 5) was signed.', 0, 1, '2009-05-07 01:04:07', b'0');
INSERT INTO SysMessages VALUES (283, 2, 'Initiative #5 (���� 5) was signed.', 0, 1, '2009-05-07 01:04:14', b'0');
INSERT INTO SysMessages VALUES (284, 2, 'Initiative #4 (���� 4) was signed.', 0, 1, '2009-05-07 01:04:20', b'0');
INSERT INTO SysMessages VALUES (285, 2, 'Initiative #4 (���� 4) was signed.', 0, 1, '2009-05-07 01:04:25', b'0');
INSERT INTO SysMessages VALUES (286, 2, 'Initiative #4 (���� 4) was voted.', 0, 1, '2009-05-07 01:35:14', b'0');
INSERT INTO SysMessages VALUES (287, 2, 'Logout', 0, 1, '2009-05-07 01:37:23', b'0');
INSERT INTO SysMessages VALUES (288, 3, 'Login', 0, 1, '2009-05-07 01:37:23', b'0');
INSERT INTO SysMessages VALUES (289, 3, 'Logout', 0, 1, '2009-05-07 01:38:46', b'0');
INSERT INTO SysMessages VALUES (290, 4, 'Login', 0, 1, '2009-05-07 01:38:46', b'0');
INSERT INTO SysMessages VALUES (291, 4, 'Logout', 0, 1, '2009-05-07 01:41:10', b'0');
INSERT INTO SysMessages VALUES (292, 2, 'Login', 0, 1, '2009-05-07 01:41:10', b'0');
INSERT INTO SysMessages VALUES (293, 2, 'Initiative #5 (���� 5) was voted.', 0, 1, '2009-05-07 01:48:10', b'0');
INSERT INTO SysMessages VALUES (294, 2, 'Additional information for initiative #6 (����� ����������!) have changed.', 0, 1, '2009-05-07 02:57:08', b'0');
INSERT INTO SysMessages VALUES (295, 2, 'Additional information for initiative #14 (����� ����������1!) have changed.', 0, 1, '2009-05-07 02:57:33', b'0');
INSERT INTO SysMessages VALUES (296, 2, 'New initiative #21 (� ��� � ������ ���������� ��������� ����� ���������!) was created.', 0, 1, '2009-05-07 03:46:02', b'0');
INSERT INTO SysMessages VALUES (297, 2, 'New initiative #22 (� ��� � ������ ����������) was created.', 0, 1, '2009-05-07 03:50:07', b'0');
INSERT INTO SysMessages VALUES (298, 2, 'Additional information for initiative #22 (� ��� � ������ ����������) have changed.', 0, 1, '2009-05-07 03:53:09', b'0');
INSERT INTO SysMessages VALUES (299, 2, 'Login', 0, 1, '2009-05-07 04:38:12', b'0');
INSERT INTO SysMessages VALUES (300, 2, 'New delegation to Member #3 (Ivan Ivanov (ivan)) by classification #2037 (����.����) NonExclude created.', 0, 1, '2009-05-07 14:31:07', b'0');
INSERT INTO SysMessages VALUES (301, 2, 'New delegation to Member #2 (Vladislav Kosilov (Wlad)) by classification #2 (������� �������������) NonExclude created.', 0, 1, '2009-05-07 14:53:40', b'0');
INSERT INTO SysMessages VALUES (302, 2, 'New delegation to Member #5 (Sergey Menyschikov (SM)) by classification #2037 (����.����) Exclude created.', 0, 1, '2009-05-07 16:07:28', b'0');
INSERT INTO SysMessages VALUES (303, 2, 'New delegation to Member # (  ()) by classification # () NonExclude created.', 0, 1, '2009-05-07 16:17:25', b'0');
INSERT INTO SysMessages VALUES (304, 2, 'New delegation to Member #4 (Irina Agapeeva (AID)) by classification #4 (�����) NonExclude created.', 0, 1, '2009-05-07 16:18:26', b'0');
INSERT INTO SysMessages VALUES (305, 2, 'New delegation to Member # (  ()) by classification # () NonExclude created.', 0, 1, '2009-05-07 16:54:53', b'0');
INSERT INTO SysMessages VALUES (306, 2, 'New delegation to Member #4 (Irina Agapeeva (AID)) by classification #4 (�����) Exclude created.', 0, 1, '2009-05-07 16:54:53', b'0');
INSERT INTO SysMessages VALUES (307, 2, 'Delegation to Member #4 (Irina Agapeeva (AID)) by classification #4 (�����) Exclude removed.', 0, 1, '2009-05-07 16:54:53', b'0');
INSERT INTO SysMessages VALUES (308, 2, 'New delegation to Member # (  ()) by classification # () NonExclude created.', 0, 1, '2009-05-07 16:55:05', b'0');
INSERT INTO SysMessages VALUES (309, 2, 'New delegation to Member #3 (Ivan Ivanov (ivan)) by classification #2037 (����.����) Exclude created.', 0, 1, '2009-05-07 16:55:05', b'0');
INSERT INTO SysMessages VALUES (310, 2, 'Delegation to Member #3 (Ivan Ivanov (ivan)) by classification #2037 (����.����) Exclude removed.', 0, 1, '2009-05-07 16:55:05', b'0');
INSERT INTO SysMessages VALUES (311, 2, 'New delegation to Member # (  ()) by classification # () NonExclude created.', 0, 1, '2009-05-07 16:57:47', b'0');
INSERT INTO SysMessages VALUES (312, 2, 'New delegation to Member #3 (Ivan Ivanov (ivan)) by classification #2037 (����.����) Exclude created.', 0, 1, '2009-05-07 16:57:47', b'0');
INSERT INTO SysMessages VALUES (313, 2, 'Delegation to Member #3 (Ivan Ivanov (ivan)) by classification #2037 (����.����) Exclude removed.', 0, 1, '2009-05-07 16:57:47', b'0');
INSERT INTO SysMessages VALUES (314, 2, 'New delegation to Member # (  ()) by classification # () NonExclude created.', 0, 1, '2009-05-07 16:58:13', b'0');
INSERT INTO SysMessages VALUES (315, 2, 'New delegation to Member #3 (Ivan Ivanov (ivan)) by classification #3 (������) Exclude created.', 0, 1, '2009-05-07 16:58:13', b'0');
INSERT INTO SysMessages VALUES (316, 2, 'Delegation to Member #3 (Ivan Ivanov (ivan)) by classification #3 (������) Exclude removed.', 0, 1, '2009-05-07 16:58:13', b'0');
INSERT INTO SysMessages VALUES (317, 2, 'Initiative #22 (� ��� � ������ ����������) was classified as #8 (�������).', 0, 1, '2009-05-07 23:02:22', b'0');
INSERT INTO SysMessages VALUES (318, 2, 'Initiative #22 (� ��� � ������ ����������) was classified as #7 (�������).', 0, 1, '2009-05-07 23:06:09', b'0');
INSERT INTO SysMessages VALUES (319, 2, 'Initiative #8 (����� ����������!) was classified as #8 (�������).', 0, 1, '2009-05-07 23:07:05', b'0');
INSERT INTO SysMessages VALUES (320, 2, 'Initiative #14 (����� ����������1!) was classified as #5 (������).', 0, 1, '2009-05-07 23:07:37', b'0');
INSERT INTO SysMessages VALUES (321, 2, '', 0, 1, '2009-05-07 23:49:56', b'0');
INSERT INTO SysMessages VALUES (322, 2, '', 0, 1, '2009-05-07 23:50:21', b'0');
INSERT INTO SysMessages VALUES (323, 2, 'Voting on Initiative #5 (���� 5) start already. Initiative can not be reclassify any more.', 0, 1, '2009-05-07 23:52:39', b'0');
INSERT INTO SysMessages VALUES (324, 2, 'Initiative #5 (���� 5) was reclassified as #7 (�������).', 0, 1, '2009-05-07 23:53:28', b'0');

DROP TABLE IF EXISTS Topics;
CREATE TABLE `Topics` (
  `id` int(11) NOT NULL auto_increment,
  `Author` int(11) default NULL,
  `topicRating` int(11) default NULL,
  `authorRating` int(11) default NULL,
  `TopicText` text,
  `addDate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


DROP TABLE IF EXISTS Voting;
CREATE TABLE `Voting` (
  `id` int(11) NOT NULL auto_increment,
  `Initiative` int(11) default NULL,
  `addDate` datetime default NULL,
  `deadLine` datetime default NULL,
  `votingRating` int(11) default NULL,
  `Deleted` tinyint(1) default NULL,
  `Author` int(11) default NULL,
  `Pro` int(11) default NULL,
  `Con` int(11) default NULL,
  `startDate` datetime default NULL,
  `voidVoting` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=cp1251;

INSERT INTO Voting VALUES (1, 4, '2009-05-06 05:00:00', '2009-05-07 22:48:35', -2, b'0', 1, 0, 2, '2009-05-06 05:00:00', b'0');
INSERT INTO Voting VALUES (2, 5, '2009-05-04 00:00:00', '2009-05-07 22:48:46', 5, b'0', 1, 5, 0, '2009-05-08 00:00:00', b'0');

DROP TABLE IF EXISTS classifications;
CREATE TABLE `classifications` (
  `id` int(11) NOT NULL auto_increment,
  `left_key` int(11) NOT NULL default '0',
  `right_key` int(11) NOT NULL default '0',
  `Classification` int(11) default NULL,
  `Parent` int(11) default NULL,
  `Title` char(255) default NULL,
  `Description` text,
  `Author` int(11) default NULL,
  `level` int(11) NOT NULL default '0',
  `addDate` datetime default NULL,
  `Rating` int(11) default NULL,
  `Information` varchar(4000) default NULL,
  `Path` varchar(800) default NULL,
  `Deleted` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `left_key` (`left_key`,`right_key`,`level`),
  FULLTEXT KEY `Path` (`Path`)
) ENGINE=MyISAM AUTO_INCREMENT=2038 DEFAULT CHARSET=cp1251;

INSERT INTO classifications VALUES (1, 1, 14, NULL, NULL, '��������� (�� �������� �� �������������)', NULL, NULL, 0, NULL, NULL, NULL, NULL, b'0');
INSERT INTO classifications VALUES (2, 2, 13, 2, 1, '������� �������������', '������ ������������� ����� �������� �� 6 ���������', 2, 1, '2009-04-23 03:10:10', 0, NULL, '/1', b'0');
INSERT INTO classifications VALUES (3, 3, 8, 2, 2, '������', '������� �������������/������', 2, 2, '2009-04-23 03:18:15', 0, NULL, '/1/2', b'0');
INSERT INTO classifications VALUES (4, 9, 12, 2, 2, '�����', '������� �������������/�����', 2, 2, '2009-04-23 03:24:21', 0, NULL, '/1/2', b'0');
INSERT INTO classifications VALUES (5, 4, 5, 2, 3, '������', '������� �������������/������/������', 2, 3, '2009-04-23 03:29:02', 0, NULL, '/1/2/3', b'0');
INSERT INTO classifications VALUES (2036, 0, 0, 2036, 1, '������', '������ �������������', 3, 1, NULL, NULL, NULL, NULL, b'0');
INSERT INTO classifications VALUES (7, 10, 11, 2, 4, '�������', '������� �������������/�����/�������', 2, 3, '2009-04-23 03:36:34', 0, NULL, '/1/2/4', b'0');
INSERT INTO classifications VALUES (8, 6, 7, 2, 3, '�������', '������� �������������/������/�������', 2, 3, '2009-04-23 03:37:58', 0, NULL, '/1/2/3', b'0');
INSERT INTO classifications VALUES (2037, 0, 0, 2036, 2036, '����.����', NULL, 3, 2, NULL, NULL, NULL, NULL, b'0');

DROP TABLE IF EXISTS constants;
CREATE TABLE `constants` (
  `id` int(11) NOT NULL auto_increment,
  `Name` char(255) default NULL,
  `Value` varchar(1000) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=cp1251;

INSERT INTO constants VALUES (5, 'RatingTime', '10.00.0000 00:00:00');
INSERT INTO constants VALUES (6, 'MinRatingPercent', '1');
INSERT INTO constants VALUES (7, 'ClassificationTime', '01.00.0000 00:00:00');
INSERT INTO constants VALUES (8, 'MinVotingsMembersPercent', '50');
INSERT INTO constants VALUES (9, 'TimeBetweenReLocations', '00.01.0000 00:00:00');

DROP TABLE IF EXISTS delegationsToClassify;
CREATE TABLE `delegationsToClassify` (
  `id` int(11) NOT NULL auto_increment,
  `Deleted` tinyint(1) NOT NULL,
  `Classification` int(11) default NULL,
  `delegateTo` int(11) default NULL,
  `Author` int(11) default NULL,
  `addDate` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=cp1251;

INSERT INTO delegationsToClassify VALUES (5, b'0', 2, 5, 2, '2009-04-23 05:02:58');
INSERT INTO delegationsToClassify VALUES (6, b'0', 2, 2, 3, NULL);
INSERT INTO delegationsToClassify VALUES (7, b'0', 2036, 2, 3, NULL);

DROP TABLE IF EXISTS initiativesratinglist;
CREATE TABLE `initiativesratinglist` (
  `id` int(11) NOT NULL auto_increment,
  `Member` int(11) default NULL,
  `Initiative` int(11) default NULL,
  `declineByDelegant` tinyint(1) default NULL,
  `addDate` datetime default NULL,
  `delegateBackFrom` int(11) default NULL,
  `Classification` int(11) default NULL,
  `Deleted` tinyint(1) default NULL,
  `Author` int(11) default NULL,
  `level` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `MI` (`Member`,`Initiative`)
) ENGINE=MyISAM AUTO_INCREMENT=70 DEFAULT CHARSET=cp1251;

INSERT INTO initiativesratinglist VALUES (48, 3, 6, b'1', '2009-05-03 19:04:46', 3, NULL, b'0', 3, 1);
INSERT INTO initiativesratinglist VALUES (62, 2, 5, b'0', '2009-05-07 01:04:13', 2, NULL, b'0', 2, 1);
INSERT INTO initiativesratinglist VALUES (4, 3, 4, b'0', NULL, NULL, NULL, b'0', NULL, 1);
INSERT INTO initiativesratinglist VALUES (46, 1, 6, b'1', '2009-05-03 15:36:10', 2, NULL, b'0', 2, 2);
INSERT INTO initiativesratinglist VALUES (50, 5, 6, b'1', '2009-05-05 02:04:52', 5, NULL, b'0', 5, 1);
INSERT INTO initiativesratinglist VALUES (63, 3, 5, b'0', '2009-05-07 01:04:13', 2, NULL, b'0', 2, 2);
INSERT INTO initiativesratinglist VALUES (65, 2, 4, b'0', '2009-05-07 01:04:25', 2, NULL, b'0', 2, 1);
INSERT INTO initiativesratinglist VALUES (66, 2, 21, b'0', '2009-05-07 03:46:02', 2, NULL, b'0', 2, 1);
INSERT INTO initiativesratinglist VALUES (67, 3, 21, b'0', '2009-05-07 03:46:02', 2, NULL, b'0', 2, 2);
INSERT INTO initiativesratinglist VALUES (68, 2, 22, b'0', '2009-05-07 03:50:07', 2, NULL, b'0', 2, 1);
INSERT INTO initiativesratinglist VALUES (69, 3, 22, b'0', '2009-05-07 03:50:07', 2, NULL, b'0', 2, 2);

DROP TABLE IF EXISTS votes;
CREATE TABLE `votes` (
  `id` int(11) NOT NULL auto_increment,
  `Initiative` int(11) default NULL,
  `Member` int(11) default NULL,
  `addDate` datetime default NULL,
  `DelegateBackFrom` int(11) default NULL,
  `Classification` int(11) default NULL,
  `ProAndCon` tinyint(1) default NULL,
  `Deleted` tinyint(1) NOT NULL,
  `Author` int(11) default NULL,
  `level` int(11) default NULL,
  `Passive` tinyint(1) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `MI` (`Initiative`,`Member`)
) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=cp1251;

INSERT INTO votes VALUES (1, 1, 2, NULL, NULL, NULL, b'0', b'0', NULL, NULL, b'1');
INSERT INTO votes VALUES (4, 1, 3, NULL, NULL, NULL, b'1', b'0', NULL, NULL, b'0');
INSERT INTO votes VALUES (91, 4, 2, '2009-05-07 01:35:14', 2, 1, b'0', b'0', 2, 1, b'0');
INSERT INTO votes VALUES (45, 5, 8, '2009-05-03 19:21:51', 3, 1, b'1', b'0', 3, 1, b'0');
INSERT INTO votes VALUES (44, 5, 7, '2009-05-03 19:04:20', 2, 1, b'1', b'0', 2, 1, b'0');
INSERT INTO votes VALUES (92, 4, 3, '2009-05-07 01:35:14', 2, 7, b'0', b'0', 2, 2, b'0');
INSERT INTO votes VALUES (90, 5, 5, '2009-05-06 01:31:33', 5, 1, b'1', b'0', 5, 1, b'0');
INSERT INTO votes VALUES (93, 5, 2, '2009-05-07 01:48:10', 2, 1, b'1', b'0', 2, 1, b'0');
INSERT INTO votes VALUES (94, 5, 3, '2009-05-07 01:48:10', 2, 7, b'1', b'0', 2, 2, b'0');

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
