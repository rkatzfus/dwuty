
IF EXISTS(SELECT * FROM sys.tables WHERE SCHEMA_NAME(schema_id) LIKE 'dbo' AND name like 'dropdown_lookup_table')  
   DROP TABLE [dbo].[dropdown_lookup_table];  
CREATE TABLE [dbo].[dropdown_lookup_table](
	[ID] [bigint] IDENTITY(1,1) NOT NULL,
	[DEL] [bit] NOT NULL,
	[TEXT] [varchar](30) NULL,
 CONSTRAINT [PK_dropdown_lookup_table] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY];
ALTER TABLE [dbo].[dropdown_lookup_table] ADD  CONSTRAINT [DF_dropdown_lookup_table_DEL]  DEFAULT ((0)) FOR [DEL];
INSERT INTO master.dbo.dropdown_lookup_table (DEL,TEXT) VALUES
	 (0,'ONE'),
	 (0,'TWO'),
	 (0,'THREE'),
	 (0,'FOUR'),
	 (0,'FIVE'),
	 (0,'SIX'),
	 (0,'SEVEN'),
	 (0,'EIGHT'),
	 (0,'NINE');

------------------------------------------------------------------------------------
IF EXISTS(SELECT * FROM sys.tables WHERE SCHEMA_NAME(schema_id) LIKE 'dbo' AND name like 'dropdown_multi_lookup_table')  
   DROP TABLE [dbo].[dropdown_multi_lookup_table];  
CREATE TABLE [dbo].[dropdown_multi_lookup_table](
	[ID] [bigint] IDENTITY(1,1) NOT NULL,
	[DEL] [bit] NOT NULL,
	[TEXT] [varchar](30) NULL,
 CONSTRAINT [PK_dropdown_multi_lookup_table] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY];
ALTER TABLE [dbo].[dropdown_multi_lookup_table] ADD  CONSTRAINT [DF_dropdown_multi_lookup_table_DEL]  DEFAULT ((0)) FOR [DEL];
INSERT INTO master.dbo.dropdown_multi_lookup_table (DEL,TEXT) VALUES
	 (0,'z&eacute;ro'),
	 (0,'un'),
	 (0,'deux'),
	 (0,'trois'),
	 (0,'quatre'),
	 (0,'cinq'),
	 (0,'six'),
	 (0,'sept'),
	 (0,'huit'),
	 (0,'neuf'),
	 (0,'dix'),
	 (0,'onze'),
	 (0,'douze'),
	 (0,'treize'),
	 (0,'quatorze'),
	 (0,'quinze'),
	 (0,'seize'),
	 (0,'dix-sept'),
	 (0,'dix-huit'),
	 (0,'dix-neuf'),
	 (0,'vingt');

------------------------------------------------------------------------------------
IF EXISTS(SELECT * FROM sys.tables WHERE SCHEMA_NAME(schema_id) LIKE 'dbo' AND name like 'ref_root_ref_dropdown_multi_table')  
   DROP TABLE [dbo].[ref_root_ref_dropdown_multi_table];  
CREATE TABLE [dbo].[ref_root_ref_dropdown_multi_table](
	[ID] [bigint] IDENTITY(1,1) NOT NULL,
	[DEL] [bit] NOT NULL,
	[REF_ROOT] [bigint] NOT NULL,
	[REF_DROPDOWN_MULTI] [bigint] NOT NULL,
 CONSTRAINT [PK_ref_root_ref_dropdown_multi_table] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY];
ALTER TABLE [dbo].[ref_root_ref_dropdown_multi_table] ADD  CONSTRAINT [DF_ref_root_ref_dropdown_multi_table_DEL]  DEFAULT ((0)) FOR [DEL];
INSERT INTO master.dbo.ref_root_ref_dropdown_multi_table (DEL,REF_ROOT,REF_DROPDOWN_MULTI) VALUES
	 (0,1,1),
	 (0,1,2),
	 (0,1,3),
	 (0,1,4),
	 (0,1,5),
	 (0,2,6),
	 (0,2,7),
	 (0,2,8),
	 (0,3,9),
	 (0,3,10),
	 (0,4,11),
	 (0,5,12),
	 (0,5,13),
	 (0,6,14),
	 (0,6,15),
	 (0,6,16),
	 (0,7,17),
	 (0,7,18),
	 (0,7,19),
	 (0,7,20),
	 (0,26,1),
	 (0,25,1);
------------------------------------------------------------------------------------
IF EXISTS(SELECT * FROM sys.tables WHERE SCHEMA_NAME(schema_id) LIKE 'dbo' AND name like 'root_table')  
   DROP TABLE [dbo].[root_table];  
CREATE TABLE [dbo].[root_table](
	[ID] [bigint] IDENTITY(1,1) NOT NULL,
	[DEL] [bit] NOT NULL,
	[TEXT] [varchar](30) NULL,
	[CHECKBOX] [bit] NOT NULL,
	[REF_DROPDOWN] [bigint] NULL,
	[LINK] [varchar](2083) NULL,
	[LINK_BUTTON] [varchar](2083) NULL,
	[DATE] [date] NULL,
	[DATETIME] [datetime] NULL,
	[COLOR] [varchar](7) NULL,
	[EMAIL] [varchar](70) NULL,
 CONSTRAINT [PK_root_table] PRIMARY KEY CLUSTERED 
(
	[ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY];
ALTER TABLE [dbo].[root_table] ADD  CONSTRAINT [DF_root_table_CHECKBOX]  DEFAULT ((0)) FOR [CHECKBOX];
INSERT INTO master.dbo.root_table (DEL,TEXT,CHECKBOX,REF_DROPDOWN,LINK,LINK_BUTTON,DATE,DATETIME,COLOR,EMAIL) VALUES
	 (0,'ALPHA',0,1,'https://stackoverflow.com/questions/219569/best-database-field-type-for-a-url','https://stackoverflow.com/questions/219569/best-database-field-type-for-a-url','2022-06-17','2022-06-17 00:00:00.0','#ff0000','info@dwuty.de '),
	 (0,'BRAVO',0,2,'https://packagist.org/packages/datatableswebutility/dwuty','https://packagist.org/packages/datatableswebutility/dwuty','2022-06-23','2022-06-23 12:57:36.0','#00ff1e','abuse@dwuty.de'),
	 (0,'CHARLIE',0,3,'http://datatableswebutility.com/','http://datatableswebutility.com/','2022-06-29','2022-06-30 01:55:12.0','#4f6392','postmaster@dwuty.de'),
	 (0,'DELTA',0,4,'http://datatableswebutility.de','http://datatableswebutility.de','2022-07-05','2022-07-06 14:52:48.0','#ffffff','security@dwuty.de'),
	 (0,'ECHO',1,5,'http://datatableswebutility.net','http://datatableswebutility.net','2022-07-11','2022-07-13 03:50:24.0','#ffffff','info@datatableswebutility.de'),
	 (0,'FOXTROT',0,6,'http://dwuty.com','http://dwuty.com','2022-07-17','2022-07-19 16:48:00.0','#ffffff','abuse@datatableswebutility.de'),
	 (0,'GOLF',0,7,'http://dwuty.de','http://dwuty.de','2022-07-23','2022-07-26 05:45:36.0','#ffffff','postmaster@datatableswebutility.de'),
	 (0,'HOTEL',0,8,'http://dwuty.net','http://dwuty.net','2022-07-29','2022-08-01 18:43:12.0','#ffffff','security@datatableswebutility.de'),
	 (0,'INDIA',0,9,NULL,NULL,'2022-08-04','2022-08-08 07:40:48.0','#ffffff',NULL),
	 (0,'JULIETT',1,8,NULL,NULL,'2022-08-10','2022-08-14 20:38:24.0','#ffffff',NULL),
	 (0,'KILO',1,7,NULL,NULL,'2022-08-16','2022-08-21 09:36:00.0','#ffffff',NULL),
	 (0,'LIMA',0,6,NULL,NULL,'2022-08-22','2022-08-27 22:33:36.0','#ffffff',NULL),
	 (0,'MIKE',1,5,NULL,NULL,'2022-08-28','2022-09-03 11:31:12.0','#ffffff',NULL),
	 (0,'NOVEMBER',0,4,NULL,NULL,'2022-09-03','2022-09-10 00:28:48.0','#ffffff',NULL),
	 (0,'OSCAR',0,3,NULL,NULL,'2022-09-09','2022-09-16 13:26:24.0','#ffffff',NULL),
	 (0,'PAPA',0,2,NULL,NULL,'2022-09-15','2022-09-23 02:24:00.0','#ffffff',NULL),
	 (0,'QUEBEC',0,1,NULL,NULL,'2022-09-23','2022-09-29 15:21:36.0','#ffffff',NULL),
	 (0,'ROMEO',0,NULL,NULL,NULL,NULL,NULL,'#ffffff',NULL),
	 (0,'SIERRA',0,NULL,NULL,NULL,NULL,NULL,'#ffffff',NULL),
	 (0,'TANGO',0,NULL,NULL,NULL,NULL,NULL,'#ffffff',NULL),
	 (0,'UNIFORM',0,NULL,NULL,NULL,NULL,NULL,'#ffffff',NULL),
	 (0,'VICTOR',0,NULL,NULL,NULL,NULL,NULL,'#ffffff',NULL),
	 (0,'WHISKEY',0,NULL,NULL,NULL,NULL,NULL,'#ffffff',NULL),
	 (0,'XRAY',0,NULL,NULL,NULL,NULL,NULL,'#ffffff',NULL),
	 (0,'YANKEE',0,NULL,NULL,NULL,NULL,NULL,'#ffffff',NULL),
	 (0,'ZULU',0,NULL,NULL,NULL,NULL,NULL,'#ffffff',NULL);

