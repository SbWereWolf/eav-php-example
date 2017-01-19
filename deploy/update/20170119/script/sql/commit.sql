DROP TABLE public.account CASCADE;

CREATE TABLE public.account
(
  id SERIAL PRIMARY KEY NOT NULL,
  login VARCHAR(4000),
  password_hash VARCHAR(4000),
  email VARCHAR(4000),
  activity_date TIMESTAMPTZ,
  insert_date TIMESTAMPTZ,
  is_hidden INTEGER
);
CREATE UNIQUE INDEX UX_account_login ON public.account (login);
CREATE UNIQUE INDEX UX_account_email ON public.account (email);
INSERT INTO public.account (login,insert_date,activity_date,is_hidden) VALUES ('guest',now(),now(),0);

DROP TABLE public.business_process CASCADE;

CREATE TABLE public.business_process
(
  id SERIAL PRIMARY KEY NOT NULL,
  code VARCHAR(4000) NOT NULL,
  insert_date TIMESTAMPTZ,
  activity_date TIMESTAMPTZ,
  is_hidden INTEGER,
  discription VARCHAR(4000)
);

CREATE UNIQUE INDEX UX_business_process_code ON public.business_process (code);
INSERT INTO business_process (code,discription,insert_date,activity_date,is_hidden) VALUES ('user_registration','Зарегистрироваться',now(),now(),'0');

DROP TABLE public.business_object CASCADE;

CREATE TABLE public.business_object
(
  id SERIAL PRIMARY KEY NOT NULL,
  code VARCHAR(4000) NOT NULL,
  insert_date TIMESTAMPTZ,
  activity_date TIMESTAMPTZ,
  is_hidden INTEGER,
  discription VARCHAR(4000)
);

CREATE UNIQUE INDEX UX_business_object_code ON public.business_object (code);
INSERT INTO business_object (code,discription,insert_date,activity_date,is_hidden) VALUES ('account','Аккаунт',now(),now(),'0');

DROP TABLE public.role CASCADE;

CREATE TABLE public.role
(
  id SERIAL PRIMARY KEY NOT NULL,
  code VARCHAR(4000) NOT NULL,
  insert_date TIMESTAMPTZ,
  activity_date TIMESTAMPTZ,
  is_hidden INTEGER,
  discription VARCHAR(4000)
);

CREATE UNIQUE INDEX UX_role_code ON public.role (code);
INSERT INTO role (code,discription,insert_date,activity_date,is_hidden) VALUES ('guest','Гость',now(),now(),'0');

DROP TABLE public.privilege CASCADE;

CREATE TABLE public.privilege
(
  id SERIAL PRIMARY KEY NOT NULL,
  business_process_id INTEGER NOT NULL,
  business_object_id INTEGER NOT NULL,
  CONSTRAINT "FX_privilege_business_process_id" FOREIGN KEY (business_process_id) REFERENCES public.business_process (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "FX_privilege_business_object_id" FOREIGN KEY (business_object_id) REFERENCES public.business_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
CREATE UNIQUE INDEX UX_privilege_business_process_id_business_object_id ON public.privilege (business_process_id,business_object_id);
INSERT INTO privilege (business_process_id,business_object_id) VALUES (1,1);

DROP TABLE public.role_detail CASCADE;

CREATE TABLE public.role_detail
(
  id SERIAL PRIMARY KEY NOT NULL,
  privilege_id INTEGER NOT NULL,
  role_id INTEGER NOT NULL,
  CONSTRAINT "FX_role_detail_privilege_id" FOREIGN KEY (privilege_id) REFERENCES public.privilege (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "FX_role_detail_role_id" FOREIGN KEY (role_id) REFERENCES public.role (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
CREATE UNIQUE INDEX UX_role_detail_privilege_id_role_id ON public.role_detail (privilege_id,role_id);
INSERT INTO role_detail (privilege_id,role_id) VALUES (1,1);

DROP TABLE public.account_role CASCADE;

CREATE TABLE public.account_role
(
  id SERIAL PRIMARY KEY NOT NULL,
  role_id INTEGER NOT NULL,
  account_id INTEGER NOT NULL,
  CONSTRAINT "FX_account_role_role_id" FOREIGN KEY (role_id) REFERENCES public.account_role (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "FX_account_role_account_id" FOREIGN KEY (account_id) REFERENCES public.account (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
CREATE UNIQUE INDEX UX_account_role_role_id_account_id ON public.account_role (role_id,account_id);
INSERT INTO account_role (role_id,account_id) VALUES (1,1);

DROP TABLE public.session CASCADE;

CREATE TABLE public.session
(
  id serial,
  key VARCHAR(4000),
  account_id INTEGER,
  is_hidden INTEGER,
  insert_date TIMESTAMPTZ,
  activity_date TIMESTAMPTZ,
  CONSTRAINT "FX_session_account_id" FOREIGN KEY (account_id) REFERENCES public.account (id) ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
OIDS = FALSE
)
;
ALTER TABLE public.session
  OWNER TO assay_manager;