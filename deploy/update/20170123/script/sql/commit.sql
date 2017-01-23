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
INSERT INTO business_process (code, discription) VALUES ('mode_user','режим пользователь');
INSERT INTO business_process (code, discription) VALUES ('mode_company','режим компания');
INSERT INTO business_process (code, discription) VALUES ('mode_operator','режим оператор');
INSERT INTO business_process (code, discription) VALUES ('mode_redactor','режим редактор');
INSERT INTO business_process (code, discription) VALUES ('mode_administrator','режим администратор');
INSERT INTO business_process (code, discription) VALUES ('add_review','добавить отзыв');
INSERT INTO business_process (code, discription) VALUES ('edit_permission','изменение разрешений для пользователей');
INSERT INTO business_process (code, discription) VALUES ('user_registration','регистрация');
INSERT INTO business_process (code, discription) VALUES ('user_profile_edit','изменить профиль');
INSERT INTO business_process (code, discription) VALUES ('user_logon','Вход');
INSERT INTO business_process (code, discription) VALUES ('password_reset','Восстановить пароль');
INSERT INTO business_process (code, discription) VALUES ('edit_password','Изменить пароль');
INSERT INTO business_process (code, discription) VALUES ('catalog_search','Поиск в каталоге');
INSERT INTO business_process (code, discription) VALUES ('structure_view','Просмотр структуры');
INSERT INTO business_process (code, discription) VALUES ('structure_add','Добавление и удаление элементов структуры');
INSERT INTO business_process (code, discription) VALUES ('structure_edit_user_data','Изменение пользовательских свойств для элементов структуры');
INSERT INTO business_process (code, discription) VALUES ('structure_edit_system_data','изменение системных свойств элементов структуры');
INSERT INTO business_process (code, discription) VALUES ('rubric_search','поиск в рубрике');
INSERT INTO business_process (code, discription) VALUES ('rubric_view','просмотр данных');
INSERT INTO business_process (code, discription) VALUES ('rubric_add_common','добавление и удаление элементов рубрики');
INSERT INTO business_process (code, discription) VALUES ('rubric_add_self','добавление и удаление соотнесённых элементов рубрики');
INSERT INTO business_process (code, discription) VALUES ('rubric_edit_user_data','изменение пользовательских свойств для элементов рубрики');
INSERT INTO business_process (code, discription) VALUES ('rubric_edit_system_data','изменение системных свойств элементов структуры');
INSERT INTO business_process (code, discription) VALUES ('rubric_edit_self_data','изменение пользовательских свойств Компании');
INSERT INTO business_process (code, discription) VALUES ('calculate_formula','вычислить формулу');
INSERT INTO business_process (code, discription) VALUES ('add_order_goods','добавить заявку на товар');
INSERT INTO business_process (code, discription) VALUES ('add_order_shipping','добавить заявку на доставку');
INSERT INTO business_process (code, discription) VALUES ('comment_view','просмотр комментариев');
INSERT INTO business_process (code, discription) VALUES ('comment_add','добавить комментарий');
INSERT INTO business_process (code, discription) VALUES ('message_add','добавить сообщение');
INSERT INTO business_process (code, discription) VALUES ('message_view','просматривать сообщения');
INSERT INTO business_process (code, discription) VALUES ('add_favorite','добавить в избранное');
INSERT INTO business_process (code, discription) VALUES ('add_like','добавить лайк');
INSERT INTO business_process (code, discription) VALUES ('add_ad','добавить объявление');

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
INSERT INTO business_object (code, discription) VALUES ('review','отзыв');
INSERT INTO business_object (code, discription) VALUES ('account','аккаунт');
INSERT INTO business_object (code, discription) VALUES ('catalog','каталог');
INSERT INTO business_object (code, discription) VALUES ('formula','формула');
INSERT INTO business_object (code, discription) VALUES ('goods','товары');
INSERT INTO business_object (code, discription) VALUES ('comment','комментарии');
INSERT INTO business_object (code, discription) VALUES ('message','сообщения');
INSERT INTO business_object (code, discription) VALUES ('favorite','избранное');
INSERT INTO business_object (code, discription) VALUES ('like','лайки');
INSERT INTO business_object (code, discription) VALUES ('ad','объявления');

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
INSERT INTO role (code,discription) VALUES ('user','Пользователь');
INSERT INTO role (code,discription) VALUES ('company','Компания');
INSERT INTO role (code,discription) VALUES ('operator','Оператор');
INSERT INTO role (code,discription) VALUES ('redactor','Редактор');
INSERT INTO role (code,discription) VALUES ('administrator','Администратор');

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
INSERT INTO privilege (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='account' AND bp.code IN ('mode_user','mode_company','mode_operator','mode_redactor','mode_administrator','edit_permission','user_registration','user_profile_edit','user_logon','password_reset','edit_password');
INSERT INTO privilege (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='review' AND bp.code IN ('add_review');
INSERT INTO privilege (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='catalog' AND bp.code IN ('catalog_search','structure_view','structure_add','structure_edit_user_data','structure_edit_system_data','rubric_search','rubric_view','rubric_add_common','rubric_add_self','rubric_edit_user_data','rubric_edit_system_data','rubric_edit_self_data');
INSERT INTO privilege (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='formula' AND bp.code IN ('calculate_formula');
INSERT INTO privilege (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='goods' AND bp.code IN ('add_order_goods','add_order_shipping');
INSERT INTO privilege (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='comment' AND bp.code IN ('comment_view','comment_add');
INSERT INTO privilege (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='message' AND bp.code IN ('message_add','message_view');
INSERT INTO privilege (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='favorite' AND bp.code IN ('add_favorite');
INSERT INTO privilege (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='like' AND bp.code IN ('add_like');
INSERT INTO privilege (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='ad' AND bp.code IN ('add_ad');

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