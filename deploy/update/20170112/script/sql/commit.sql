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
  id integer NOT NULL DEFAULT nextval('account_id_seq'::regclass),
  login character varying COLLATE pg_catalog."default" NOT NULL,
  password_hash character varying COLLATE pg_catalog."default" NOT NULL,
  email character varying COLLATE pg_catalog."default" NOT NULL,
  activity_date timestamp with time zone,
  insert_date timestamp with time zone,
  CONSTRAINT account_pkey PRIMARY KEY (id)
)
WITH (
OIDS = FALSE
)
TABLESPACE pg_default;

ALTER TABLE public.account
  OWNER to assay_manager;

DROP INDEX public.account_email_uindex;

CREATE UNIQUE INDEX account_email_uindex
  ON public.account USING btree
  (email COLLATE pg_catalog."default")
TABLESPACE pg_default;

DROP INDEX public.account_login_uindex;

CREATE UNIQUE INDEX account_login_uindex
  ON public.account USING btree
  (login COLLATE pg_catalog."default")
TABLESPACE pg_default;