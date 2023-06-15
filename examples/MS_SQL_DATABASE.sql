
IF EXISTS(SELECT * FROM sys.tables WHERE SCHEMA_NAME(schema_id) LIKE 'dbo' AND name like 'dropdown_lookup_table')  
   DROP TABLE [dbo].[dropdown_lookup_table];  
CREATE TABLE master.dbo.dropdown_lookup_table (
	ID bigint NOT NULL,
	DEL bit NOT NULL,
	[TEXT] varchar(30) NULL
);
INSERT INTO master.dbo.dropdown_lookup_table (ID,DEL,[TEXT]) VALUES
	 (1,0,N'ONE'),
	 (2,0,N'TWO'),
	 (3,0,N'THREE'),
	 (4,0,N'FOUR'),
	 (5,0,N'FIVE'),
	 (6,0,N'SIX'),
	 (7,0,N'SEVEN'),
	 (8,0,N'EIGHT'),
	 (9,0,N'NINE');
------------------------------------------------------------------------------------
IF EXISTS(SELECT * FROM sys.tables WHERE SCHEMA_NAME(schema_id) LIKE 'dbo' AND name like 'dropdown_multi_lookup_table')  
   DROP TABLE [dbo].[dropdown_multi_lookup_table];  
CREATE TABLE master.dbo.dropdown_multi_lookup_table (
	ID bigint NOT NULL,
	DEL bit NOT NULL,
	[TEXT] varchar(30) NULL
);
INSERT INTO master.dbo.dropdown_multi_lookup_table (ID,DEL,[TEXT]) VALUES
	 (1,0,N'z&eacute;ro'),
	 (2,0,N'un'),
	 (3,0,N'deux'),
	 (4,0,N'trois'),
	 (5,0,N'quatre'),
	 (6,0,N'cinq'),
	 (7,0,N'six'),
	 (8,0,N'sept'),
	 (9,0,N'huit'),
	 (10,0,N'neuf'),
	 (11,0,N'dix'),
	 (12,0,N'onze'),
	 (13,0,N'douze'),
	 (14,0,N'treize'),
	 (15,0,N'quatorze'),
	 (16,0,N'quinze'),
	 (17,0,N'seize'),
	 (18,0,N'dix-sept'),
	 (19,0,N'dix-huit'),
	 (20,0,N'dix-neuf'),
	 (21,0,N'vingt');

------------------------------------------------------------------------------------
IF EXISTS(SELECT * FROM sys.tables WHERE SCHEMA_NAME(schema_id) LIKE 'dbo' AND name like 'ref_root_ref_dropdown_multi_table')  
   DROP TABLE [dbo].[ref_root_ref_dropdown_multi_table];  
CREATE TABLE master.dbo.ref_root_ref_dropdown_multi_table (
	ID bigint NOT NULL,
	DEL bit NOT NULL,
	REF_ROOT bigint NOT NULL,
	REF_DROPDOWN_MULTI bigint NOT NULL
);
INSERT INTO master.dbo.ref_root_ref_dropdown_multi_table (ID,DEL,REF_ROOT,REF_DROPDOWN_MULTI) VALUES
	 (1,0,1,1),
	 (2,0,1,2),
	 (3,0,1,3),
	 (4,0,1,4),
	 (5,0,1,5),
	 (6,0,2,6),
	 (7,0,2,7),
	 (8,0,2,8),
	 (9,0,3,9),
	 (10,0,3,10),
	 (11,0,4,11),
	 (12,0,5,12),
	 (13,0,5,13),
	 (14,0,6,14),
	 (15,0,6,15),
	 (16,0,6,16),
	 (17,0,7,17),
	 (18,0,7,18),
	 (19,0,7,19),
	 (20,0,7,20);
------------------------------------------------------------------------------------
IF EXISTS(SELECT * FROM sys.tables WHERE SCHEMA_NAME(schema_id) LIKE 'dbo' AND name like 'root_table')  
   DROP TABLE [dbo].[root_table];  
CREATE TABLE master.dbo.root_table (
	ID bigint NOT NULL,
	DEL bit NOT NULL,
	[TEXT] varchar(30) NULL,
	CHECKBOX bit NOT NULL,
	REF_DROPDOWN bigint NULL,
	LINK varchar(2083) NULL,
	LINK_BUTTON varchar(2083) NULL,
	[DATE] date NULL,
	[DATETIME] datetime NULL,
	COLOR varchar(7) NULL,
	EMAIL varchar(70)NULL
);
INSERT INTO master.dbo.root_table (ID,DEL,[TEXT],CHECKBOX,REF_DROPDOWN,LINK,LINK_BUTTON,[DATE],[DATETIME],COLOR,EMAIL) VALUES
	 (1,0,N'ALPHA',0,1,N'https://stackoverflow.com/questions/219569/best-database-field-type-for-a-url',N'https://stackoverflow.com/questions/219569/best-database-field-type-for-a-url','2022-06-17','2022-06-17 00:00:00.0',N'#ff0000',N'info@dwuty.de '),
	 (2,0,N'BRAVO',0,2,N'https://packagist.org/packages/datatableswebutility/dwuty',N'https://packagist.org/packages/datatableswebutility/dwuty','2022-06-23','2022-06-23 12:57:36.0',N'#00ff1e',N'abuse@dwuty.de'),
	 (3,0,N'CHARLIE',0,3,N'http://datatableswebutility.com/',N'http://datatableswebutility.com/','2022-06-29','2022-06-30 01:55:12.0',N'#4f6392',N'postmaster@dwuty.de'),
	 (4,0,N'DELTA',0,4,N'http://datatableswebutility.de',N'http://datatableswebutility.de','2022-07-05','2022-07-06 14:52:48.0',N'#ffffff',N'security@dwuty.de'),
	 (5,0,N'ECHO',1,5,N'http://datatableswebutility.net',N'http://datatableswebutility.net','2022-07-11','2022-07-13 03:50:24.0',N'#ffffff',N'info@datatableswebutility.de'),
	 (6,0,N'FOXTROT',0,6,N'http://dwuty.com',N'http://dwuty.com','2022-07-17','2022-07-19 16:48:00.0',N'#ffffff',N'abuse@datatableswebutility.de'),
	 (7,0,N'GOLF',0,7,N'http://dwuty.de',N'http://dwuty.de','2022-07-23','2022-07-26 05:45:36.0',N'#ffffff',N'postmaster@datatableswebutility.de'),
	 (8,0,N'HOTEL',0,8,N'http://dwuty.net',N'http://dwuty.net','2022-07-29','2022-08-01 18:43:12.0',N'#ffffff',N'security@datatableswebutility.de'),
	 (9,0,N'INDIA',0,9,NULL,NULL,'2022-08-04','2022-08-08 07:40:48.0',N'#ffffff',NULL),
	 (10,0,N'JULIETT',1,8,NULL,NULL,'2022-08-10','2022-08-14 20:38:24.0',N'#ffffff',NULL),
	 (11,0,N'KILO',1,7,NULL,NULL,'2022-08-16','2022-08-21 09:36:00.0',N'#ffffff',NULL),
	 (12,0,N'LIMA',0,6,NULL,NULL,'2022-08-22','2022-08-27 22:33:36.0',N'#ffffff',NULL),
	 (13,0,N'MIKE',1,5,NULL,NULL,'2022-08-28','2022-09-03 11:31:12.0',N'#ffffff',NULL),
	 (14,0,N'NOVEMBER',0,4,NULL,NULL,'2022-09-03','2022-09-10 00:28:48.0',N'#ffffff',NULL),
	 (15,0,N'OSCAR',0,3,NULL,NULL,'2022-09-09','2022-09-16 13:26:24.0',N'#ffffff',NULL),
	 (16,0,N'PAPA',0,2,NULL,NULL,'2022-09-15','2022-09-23 02:24:00.0',N'#ffffff',NULL),
	 (17,0,N'QUEBEC',0,1,NULL,NULL,'2022-09-23','2022-09-29 15:21:36.0',N'#ffffff',NULL),
	 (18,0,N'ROMEO',0,NULL,NULL,NULL,NULL,NULL,N'#ffffff',NULL),
	 (19,0,N'SIERRA',0,NULL,NULL,NULL,NULL,NULL,N'#ffffff',NULL),
	 (20,0,N'TANGO',0,NULL,NULL,NULL,NULL,NULL,N'#ffffff',NULL),
	 (21,0,N'UNIFORM',0,NULL,NULL,NULL,NULL,NULL,N'#ffffff',NULL),
	 (22,0,N'VICTOR',0,NULL,NULL,NULL,NULL,NULL,N'#ffffff',NULL),
	 (23,0,N'WHISKEY',0,NULL,NULL,NULL,NULL,NULL,N'#ffffff',NULL),
	 (24,0,N'XRAY',0,NULL,NULL,NULL,NULL,NULL,N'#ffffff',NULL),
	 (25,0,N'YANKEE',0,NULL,NULL,NULL,NULL,NULL,N'#ffffff',NULL),
	 (26,0,N'ZULU',0,NULL,NULL,NULL,NULL,NULL,N'#ffffff',NULL);

