drop table if exists dropdown_lookup_table;

drop table if exists dropdown_multi_lookup_table;

drop table if exists ref_root_ref_dropdown_multi_table;

drop table if exists root_table;

create table dropdown_lookup_table (
  ID mediumint not null auto_increment,
  DEL bit(1) not null default 0,
  ID_TEXT char(30) default null,
  primary key (ID)
);

create table dropdown_multi_lookup_table (
  ID mediumint not null auto_increment,
  DEL bit(1) not null default 0,
  ID_TEXT char(30) default null,
  primary key (ID)
);

create table ref_root_ref_dropdown_multi_table (
  ID mediumint not null auto_increment,
  DEL bit(1) not null default 0,
  REF_ROOT mediumint not null,
  REF_DROPDOWN_MULTI mediumint not null,
  primary key (ID)
);

create table root_table (
  ID mediumint not null auto_increment,
  DEL bit(1) not null default 0,
  TEXT_FIELD char(30) default null,
  CHECKBOX bit(1) not null default 0,
  REF_DROPDOWN mediumint default null,
  LINK varchar(2083) default null,
  LINK_BUTTON varchar(2083) default null,
  DATE_FIELD date default null,
  DATETIME_FIELD datetime default null,
  COLOR varchar(7) not null default '#ffffff',
  EMAIL varchar(70) default null,
  primary key (ID)
);

insert into
  dropdown_lookup_table (DEL, ID_TEXT)
values
  (0, 'ONE'),
  (0, 'TWO'),
  (0, 'THREE'),
  (0, 'FOUR'),
  (0, 'FIVE'),
  (0, 'SIX'),
  (0, 'SEVEN'),
  (0, 'EIGHT'),
  (0, 'NINE');

insert into
  dropdown_multi_lookup_table (DEL, ID_TEXT)
values
  (0, 'z&eacute;ro'),
  (0, 'un'),
  (0, 'deux'),
  (0, 'trois'),
  (0, 'quatre'),
  (0, 'cinq'),
  (0, 'six'),
  (0, 'sept'),
  (0, 'huit'),
  (0, 'neuf'),
  (0, 'dix'),
  (0, 'onze'),
  (0, 'douze'),
  (0, 'treize'),
  (0, 'quatorze'),
  (0, 'quinze'),
  (0, 'seize'),
  (0, 'dix-sept'),
  (0, 'dix-huit'),
  (0, 'dix-neuf'),
  (0, 'vingt');

insert into
  ref_root_ref_dropdown_multi_table (DEL, REF_ROOT, REF_DROPDOWN_MULTI)
values
  (0, 1, 1),
  (0, 1, 2),
  (0, 1, 3),
  (0, 1, 4),
  (0, 1, 5),
  (0, 2, 6),
  (0, 2, 7),
  (0, 2, 8),
  (0, 3, 9),
  (0, 3, 10),
  (0, 4, 11),
  (0, 5, 12),
  (0, 5, 13),
  (0, 6, 14),
  (0, 6, 15),
  (0, 6, 16),
  (0, 7, 17),
  (0, 7, 18),
  (0, 7, 19),
  (0, 7, 20);

insert into
  root_table (
    DEL,
    TEXT_FIELD,
    CHECKBOX,
    REF_DROPDOWN,
    LINK,
    LINK_BUTTON,
    DATE_FIELD,
    DATETIME_FIELD,
    COLOR,
    EMAIL
  )
values
  (
    0,
    'ALPHA',
    0,
    1,
    'https://stackoverflow.com/questions/219569/best-database-field-type-for-a-url',
    'https://stackoverflow.com/questions/219569/best-database-field-type-for-a-url',
    '2022-06-17',
    '2022-06-17 00:00:00',
    '#ff0000',
    'info@dwuty.de '
  ),
  (
    0,
    'BRAVO',
    0,
    2,
    'https://packagist.org/packages/datatableswebutility/dwuty',
    'https://packagist.org/packages/datatableswebutility/dwuty',
    '2022-06-23',
    '2022-06-23 12:57:36',
    '#00ff1e',
    'abuse@dwuty.de'
  ),
  (
    0,
    'CHARLIE',
    0,
    3,
    'http://datatableswebutility.com/',
    'http://datatableswebutility.com/',
    '2022-06-29',
    '2022-06-30 01:55:12',
    '#4f6392',
    'postmaster@dwuty.de'
  ),
  (
    0,
    'DELTA',
    0,
    4,
    'http://datatableswebutility.de',
    'http://datatableswebutility.de',
    '2022-07-05',
    '2022-07-06 14:52:48',
    '#ffffff',
    'security@dwuty.de'
  ),
  (
    0,
    'ECHO',
    1,
    5,
    'http://datatableswebutility.net',
    'http://datatableswebutility.net',
    '2022-07-11',
    '2022-07-13 03:50:24',
    '#ffffff',
    'info@datatableswebutility.de'
  ),
  (
    0,
    'FOXTROT',
    0,
    6,
    'http://dwuty.com',
    'http://dwuty.com',
    '2022-07-17',
    '2022-07-19 16:48:00',
    '#ffffff',
    'abuse@datatableswebutility.de'
  ),
  (
    0,
    'GOLF',
    0,
    7,
    'http://dwuty.de',
    'http://dwuty.de',
    '2022-07-23',
    '2022-07-26 05:45:36',
    '#ffffff',
    'postmaster@datatableswebutility.de'
  ),
  (
    0,
    'HOTEL',
    0,
    8,
    'http://dwuty.net',
    'http://dwuty.net',
    '2022-07-29',
    '2022-08-01 18:43:12',
    '#ffffff',
    'security@datatableswebutility.de'
  ),
  (
    0,
    'INDIA',
    0,
    9,
    null,
    null,
    '2022-08-04',
    '2022-08-08 07:40:48',
    '#ffffff',
    null
  ),
  (
    0,
    'JULIETT',
    1,
    8,
    null,
    null,
    '2022-08-10',
    '2022-08-14 20:38:24',
    '#ffffff',
    null
  ),
  (
    0,
    'KILO',
    1,
    7,
    null,
    null,
    '2022-08-16',
    '2022-08-21 09:36:00',
    '#ffffff',
    null
  ),
  (
    0,
    'LIMA',
    0,
    6,
    null,
    null,
    '2022-08-22',
    '2022-08-27 22:33:36',
    '#ffffff',
    null
  ),
  (
    0,
    'MIKE',
    1,
    5,
    null,
    null,
    '2022-08-28',
    '2022-09-03 11:31:12',
    '#ffffff',
    null
  ),
  (
    0,
    'NOVEMBER',
    0,
    4,
    null,
    null,
    '2022-09-03',
    '2022-09-10 00:28:48',
    '#ffffff',
    null
  ),
  (
    0,
    'OSCAR',
    0,
    3,
    null,
    null,
    '2022-09-09',
    '2022-09-16 13:26:24',
    '#ffffff',
    null
  ),
  (
    0,
    'PAPA',
    0,
    2,
    null,
    null,
    '2022-09-15',
    '2022-09-23 02:24:00',
    '#ffffff',
    null
  ),
  (
    0,
    'QUEBEC',
    0,
    1,
    null,
    null,
    '2022-09-23',
    '2022-09-29 15:21:36',
    '#ffffff',
    null
  ),
  (
    0,
    'ROMEO',
    0,
    null,
    null,
    null,
    null,
    null,
    '#ffffff',
    null
  ),
  (
    0,
    'SIERRA',
    0,
    null,
    null,
    null,
    null,
    null,
    '#ffffff',
    null
  ),
  (
    0,
    'TANGO',
    0,
    null,
    null,
    null,
    null,
    null,
    '#ffffff',
    null
  ),
  (
    0,
    'UNIFORM',
    0,
    null,
    null,
    null,
    null,
    null,
    '#ffffff',
    null
  ),
  (
    0,
    'VICTOR',
    0,
    null,
    null,
    null,
    null,
    null,
    '#ffffff',
    null
  ),
  (
    0,
    'WHISKEY',
    0,
    null,
    null,
    null,
    null,
    null,
    '#ffffff',
    null
  ),
  (
    0,
    'XRAY',
    0,
    null,
    null,
    null,
    null,
    null,
    '#ffffff',
    null
  ),
  (
    0,
    'YANKEE',
    0,
    null,
    null,
    null,
    null,
    null,
    '#ffffff',
    null
  ),
  (
    0,
    'ZULU',
    0,
    null,
    null,
    null,
    null,
    null,
    '#ffffff',
    null
  );