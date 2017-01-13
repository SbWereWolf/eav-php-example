CREATE DATABASE assay_catalog
WITH
OWNER = postgres
ENCODING = 'UTF8'
LC_COLLATE = 'Russian_Russia.1251'
LC_CTYPE = 'Russian_Russia.1251'
CONNECTION LIMIT = -1;

CREATE USER assay_manager WITH
  LOGIN
  NOSUPERUSER
  NOCREATEDB
  NOCREATEROLE
  INHERIT
  REPLICATION
  CONNECTION LIMIT -1
  PASSWORD 'df1funi';

ALTER DATABASE assay_catalog OWNER TO assay_manager;

DROP TABLE public.account;

CREATE TABLE public.account
(
  id SERIAL PRIMARY KEY NOT NULL,
  login VARCHAR,
  password_hash VARCHAR,
  email VARCHAR,
  activity_date TIMESTAMPTZ,
  insert_date TIMESTAMPTZ
);

CREATE UNIQUE INDEX account_login_uindex ON public.account (login);
CREATE UNIQUE INDEX account_email_uindex ON public.account (email);

DROP TABLE public.role;

CREATE TABLE public.role
(
  id SERIAL PRIMARY KEY NOT NULL,
  role_name VARCHAR NOT NULL
);
CREATE UNIQUE INDEX role_role_name_uindex ON public.role (role_name);

DROP TABLE public.user_role;

CREATE TABLE public.user_role
(
  id SERIAL PRIMARY KEY NOT NULL,
  user_id INT NOT NULL,
  user_role_id INT NOT NULL
);
CREATE UNIQUE INDEX role_role_user_uindex ON public.user_role (user_id, user_role_id);