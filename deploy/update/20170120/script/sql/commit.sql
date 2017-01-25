DROP TABLE public.account CASCADE;

CREATE TABLE public.account
(
  id SERIAL PRIMARY KEY NOT NULL,
  login VARCHAR(4000),
  password_hash VARCHAR(4000),
  email VARCHAR(4000),
  activity_date TIMESTAMPTZ DEFAULT NOW(),
  insert_date TIMESTAMPTZ DEFAULT NOW(),
  is_hidden INTEGER DEFAULT 0
);
CREATE UNIQUE INDEX ux_account_login ON public.account (login);
CREATE UNIQUE INDEX ux_account_email ON public.account (email);
CREATE INDEX ix_account_is_hidden_id ON public.account (is_hidden ASC, id ASC);
INSERT INTO public.account (login) VALUES ('guest');

DROP TABLE public.business_process CASCADE;

CREATE TABLE public.business_process
(
  id SERIAL PRIMARY KEY NOT NULL,
  code VARCHAR(4000) NOT NULL,
  insert_date TIMESTAMPTZ DEFAULT NOW(),
  update_date TIMESTAMPTZ DEFAULT NOW(),
  is_hidden INTEGER DEFAULT 0,
  discription VARCHAR(4000)
);
CREATE INDEX ix_business_process_is_hidden_code ON public.business_process (is_hidden ASC, code ASC);
CREATE UNIQUE INDEX ux_business_process_code ON public.business_process (code);
INSERT INTO business_process (code,discription) VALUES ('user_registration','Зарегистрироваться');

DROP TABLE public.business_object CASCADE;

CREATE TABLE public.business_object
(
  id SERIAL PRIMARY KEY NOT NULL,
  code VARCHAR(4000) NOT NULL,
  insert_date TIMESTAMPTZ DEFAULT NOW(),
  update_date TIMESTAMPTZ DEFAULT NOW(),
  is_hidden INTEGER DEFAULT 0,
  discription VARCHAR(4000)
);
CREATE INDEX ix_business_object_is_hidden_code ON public.business_object (is_hidden ASC, code ASC);
CREATE UNIQUE INDEX ux_business_object_code ON public.business_object (code);
INSERT INTO business_object (code,discription) VALUES ('account','Аккаунт');

DROP TABLE public.role CASCADE;

CREATE TABLE public.role
(
  id SERIAL PRIMARY KEY NOT NULL,
  code VARCHAR(4000) NOT NULL,
  insert_date TIMESTAMPTZ DEFAULT NOW(),
  update_date TIMESTAMPTZ DEFAULT NOW(),
  is_hidden INTEGER DEFAULT 0,
  discription VARCHAR(4000)
);
CREATE INDEX ix_role_is_hidden_code ON public.role (is_hidden ASC, code ASC);
CREATE UNIQUE INDEX ux_role_code ON public.role (code);
INSERT INTO role (code,discription) VALUES ('guest','Гость');

DROP TABLE public.privilege CASCADE;

CREATE TABLE public.privilege
(
  id SERIAL PRIMARY KEY NOT NULL,
  business_process_id INTEGER NOT NULL,
  business_object_id INTEGER NOT NULL,
  CONSTRAINT "fk_privilege_business_process_id" FOREIGN KEY (business_process_id) REFERENCES public.business_process (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "fk_privilege_business_object_id" FOREIGN KEY (business_object_id) REFERENCES public.business_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
CREATE UNIQUE INDEX ux_privilege_business_process_id_business_object_id ON public.privilege (business_process_id,business_object_id);
INSERT INTO privilege (business_process_id,business_object_id) VALUES (1,1);

DROP TABLE public.role_detail CASCADE;

CREATE TABLE public.role_detail
(
  id SERIAL PRIMARY KEY NOT NULL,
  privilege_id INTEGER NOT NULL,
  role_id INTEGER NOT NULL,
  CONSTRAINT "fk_role_detail_privilege_id" FOREIGN KEY (privilege_id) REFERENCES public.privilege (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "fk_role_detail_role_id" FOREIGN KEY (role_id) REFERENCES public.role (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
CREATE UNIQUE INDEX ux_role_detail_privilege_id_role_id ON public.role_detail (privilege_id,role_id);
INSERT INTO role_detail (privilege_id,role_id) VALUES (1,1);

DROP TABLE public.account_role CASCADE;

CREATE TABLE public.account_role
(
  id SERIAL PRIMARY KEY NOT NULL,
  role_id INTEGER NOT NULL,
  account_id INTEGER NOT NULL,
  CONSTRAINT "fk_account_role_role_id" FOREIGN KEY (role_id) REFERENCES public.role (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "fk_account_role_account_id" FOREIGN KEY (account_id) REFERENCES public.account (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
CREATE UNIQUE INDEX ux_account_role_role_id_account_id ON public.account_role (role_id,account_id);
INSERT INTO account_role (role_id,account_id) VALUES (1,1);

DROP TABLE public.session CASCADE;

CREATE TABLE public.session
(
  id serial,
  key VARCHAR(4000),
  account_id INTEGER,
  is_hidden INTEGER DEFAULT 0,
  insert_date TIMESTAMPTZ DEFAULT NOW(),
  update_date TIMESTAMPTZ DEFAULT NOW(),
  CONSTRAINT "fk_session_account_id" FOREIGN KEY (account_id) REFERENCES public.account (id) ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
OIDS = FALSE
)
;
ALTER TABLE public.session
  OWNER TO assay_manager;
CREATE INDEX ix_session_is_hidden_id ON public.session (is_hidden ASC, id ASC);