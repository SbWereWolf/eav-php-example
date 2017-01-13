DROP TABLE public.account CASCADE;

CREATE TABLE public.account
(
  id SERIAL PRIMARY KEY NOT NULL,
  login VARCHAR(4000),
  password_hash VARCHAR(4000),
  email VARCHAR(4000),
  activity_date TIMESTAMPTZ,
  insert_date TIMESTAMPTZ
);
CREATE UNIQUE INDEX UX_account_login ON public.account (login);
CREATE UNIQUE INDEX UX_account_email ON public.account (email);
INSERT INTO public.account (login,insert_date) VALUES ('guest',now());

DROP TABLE public.business_role CASCADE;

CREATE TABLE public.business_role
(
  id SERIAL PRIMARY KEY NOT NULL,
  code INTEGER NOT NULL,
  name VARCHAR(4000) NOT NULL,
  discription VARCHAR(4000)
);
CREATE UNIQUE INDEX UX_business_role_name ON public.business_role (name);
CREATE UNIQUE INDEX UX_business_role_code ON public.business_role (code);
INSERT INTO business_role (name,code,discription) VALUES ('guest',1,'Гость');

DROP TABLE public.user_business_role CASCADE;

CREATE TABLE public.user_business_role
(
  id serial,
  account_id integer NOT NULL,
  business_role_id integer NOT NULL,
  CONSTRAINT "FX_user_business_role_business_role_id" FOREIGN KEY (business_role_id) REFERENCES public.business_role (code) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "FX_user_business_role_account_id" FOREIGN KEY (account_id) REFERENCES public.account (id) ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
OIDS = FALSE
)
;
ALTER TABLE public.user_business_role
  OWNER TO assay_manager;

CREATE UNIQUE INDEX UX_user_business_role_business_role_id_account_id ON public.user_business_role (business_role_id,account_id);
INSERT INTO public.user_business_role (account_id, business_role_id) VALUES (1,1);

DROP TABLE public.business_role_rule CASCADE;

CREATE TABLE public.business_role_rule
(
  id serial,
  business_role_id integer NOT NULL,
  process VARCHAR(4000) NOT NULL,
  object VARCHAR(4000) NOT NULL,
  CONSTRAINT "FX_business_role_rule_business_role_id" FOREIGN KEY (business_role_id) REFERENCES public.business_role (id) ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
OIDS = FALSE
)
;
ALTER TABLE public.business_role_rule
  OWNER TO assay_manager;

CREATE UNIQUE INDEX UX_business_role_id_process_object ON public.business_role_rule (business_role_id, process, object);
INSERT INTO public.business_role_rule (business_role_id, process, object) VALUES (1,'open_session','session');

DROP TABLE public.session CASCADE;

CREATE TABLE public.session
(
  id serial,
  key VARCHAR(4000) NOT NULL,
  user_id INTEGER NOT NULL,
  insert_date TIMESTAMPTZ,
  CONSTRAINT "FX_session_account_id" FOREIGN KEY (user_id) REFERENCES public.account (id) ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
OIDS = FALSE
)
;
ALTER TABLE public.session
  OWNER TO assay_manager;