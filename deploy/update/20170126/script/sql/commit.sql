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

CREATE TABLE public.business_process
(
  id SERIAL PRIMARY KEY NOT NULL,
  code CHAR(100) NOT NULL,
  insert_date TIMESTAMPTZ DEFAULT NOW(),
  update_date TIMESTAMPTZ DEFAULT NOW(),
  is_hidden INTEGER DEFAULT 0,
  discription TEXT
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
INSERT INTO business_process (code, discription) VALUES ('user_profile_view','просмотр профиль');
INSERT INTO business_process (code, discription) VALUES ('user_logon','Вход');
INSERT INTO business_process (code, discription) VALUES ('user_logout','Выход');
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

CREATE TABLE public.business_object
(
  id SERIAL PRIMARY KEY NOT NULL,
  code CHAR(100) NOT NULL,
  insert_date TIMESTAMPTZ DEFAULT NOW(),
  update_date TIMESTAMPTZ DEFAULT NOW(),
  is_hidden INTEGER DEFAULT 0,
  discription TEXT
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

CREATE TABLE public.business_role
(
  id SERIAL PRIMARY KEY NOT NULL,
  code CHAR(100) NOT NULL,
  insert_date TIMESTAMPTZ DEFAULT NOW(),
  update_date TIMESTAMPTZ DEFAULT NOW(),
  is_hidden INTEGER DEFAULT 0,
  discription TEXT
);
CREATE INDEX ix_business_role_is_hidden_code ON public.business_role (is_hidden ASC, code ASC);
CREATE UNIQUE INDEX ux_business_role_code ON public.business_role (code);
INSERT INTO business_role (code,discription) VALUES ('guest','Гость');
INSERT INTO business_role (code,discription) VALUES ('user','Пользователь');
INSERT INTO business_role (code,discription) VALUES ('company','Компания');
INSERT INTO business_role (code,discription) VALUES ('operator','Оператор');
INSERT INTO business_role (code,discription) VALUES ('redactor','Редактор');
INSERT INTO business_role (code,discription) VALUES ('administrator','Администратор');

CREATE TABLE public.business_object_business_process
(
  id SERIAL PRIMARY KEY NOT NULL,
  business_process_id INTEGER NOT NULL,
  business_object_id INTEGER NOT NULL,
  CONSTRAINT "fk_business_object_business_process_business_process_id" FOREIGN KEY (business_process_id) REFERENCES public.business_process (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "fk_business_object_business_process_business_object_id" FOREIGN KEY (business_object_id) REFERENCES public.business_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
CREATE UNIQUE INDEX ux_business_object_business_process_business_process_id_business_object_id ON public.business_object_business_process (business_process_id,business_object_id);
INSERT INTO business_object_business_process (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='account' AND bp.code IN ('mode_user','mode_company','mode_operator','mode_redactor','mode_administrator','edit_permission','user_registration','user_profile_edit','user_profile_view','user_logon','user_logout','password_reset','edit_password');
INSERT INTO business_object_business_process (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='review' AND bp.code IN ('add_review');
INSERT INTO business_object_business_process (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='catalog' AND bp.code IN ('catalog_search','structure_view','structure_add','structure_edit_user_data','structure_edit_system_data','rubric_search','rubric_view','rubric_add_common','rubric_add_self','rubric_edit_user_data','rubric_edit_system_data','rubric_edit_self_data');
INSERT INTO business_object_business_process (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='formula' AND bp.code IN ('calculate_formula');
INSERT INTO business_object_business_process (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='goods' AND bp.code IN ('add_order_goods','add_order_shipping');
INSERT INTO business_object_business_process (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='comment' AND bp.code IN ('comment_view','comment_add');
INSERT INTO business_object_business_process (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='message' AND bp.code IN ('message_add','message_view');
INSERT INTO business_object_business_process (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='favorite' AND bp.code IN ('add_favorite');
INSERT INTO business_object_business_process (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='like' AND bp.code IN ('add_like');
INSERT INTO business_object_business_process (business_object_id,business_process_id)
  SELECT bo.id,bp.id
  FROM business_object as bo,business_process as bp
  WHERE bo.code='ad' AND bp.code IN ('add_ad');

CREATE TABLE public.business_role_business_privilege
(
  id SERIAL PRIMARY KEY NOT NULL,
  business_object_business_process_id INTEGER NOT NULL,
  business_role_id INTEGER NOT NULL,
  CONSTRAINT "fk_business_role_business_privilege_business_object_business_process_id" FOREIGN KEY (business_object_business_process_id) REFERENCES public.business_object_business_process (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "fk_business_role_business_privilege_business_role_id" FOREIGN KEY (business_role_id) REFERENCES public.business_role (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
CREATE UNIQUE INDEX ux_business_role_business_privilege_business_object_business_process_id_business_role_id ON public.business_role_business_privilege (business_object_business_process_id,business_role_id);
INSERT INTO business_role_business_privilege (business_role_id, business_object_business_process_id)
  SELECT r.id,p.id FROM business_role as r,business_object_business_process as p,business_process as bp,business_object as bo
  WHERE
    bp.id = p.business_process_id AND
    bo.id = p.business_object_id AND
    r.code = 'guest' AND
    (
      (bo.code = 'account' AND bp.code in ('user_registration','user_logon','password_reset','mode_user')) OR
      (bo.code = 'catalog' AND bp.code in ('catalog_search')) OR
      (bo.code = 'formula' AND bp.code in ('calculate_formula')) OR
      (bo.code = 'goods' AND bp.code in ('add_order_goods','add_order_shipping')) OR
      (bo.code = 'comment' AND bp.code in ('comment_view'))
    );

INSERT INTO business_role_business_privilege (business_role_id, business_object_business_process_id)
  SELECT r.id,p.id FROM business_role as r,business_object_business_process as p,business_process as bp,business_object as bo
  WHERE
    bp.id = p.business_process_id AND
    bo.id = p.business_object_id AND
    r.code = 'user' AND
    (
      (bo.code = 'review' AND bp.code in ('add_review')) OR
      (bo.code = 'account' AND bp.code in ('user_registration','user_logon','user_logout','password_reset','edit_password','user_profile_edit','user_profile_view','mode_user','mode_company','mode_operator','mode_redactor','mode_administrator')) OR
      (bo.code = 'catalog' AND bp.code in ('catalog_search','structure_view','rubric_search','rubric_view','rubric_add_self','rubric_edit_user_data')) OR
      (bo.code = 'goods' AND bp.code in ('add_order_goods','add_order_shipping')) OR
      (bo.code = 'comment' AND bp.code in ('comment_view','comment_add')) OR
      (bo.code = 'formula' AND bp.code in ('calculate_formula')) OR
      (bo.code = 'message' AND bp.code in ('message_add','message_view')) OR
      (bo.code = 'favorite' AND bp.code in ('add_favorite')) OR
      (bo.code = 'like' AND bp.code in ('add_like')) OR
      (bo.code = 'ad' AND bp.code in ('add_ad'))
    );

INSERT INTO business_role_business_privilege (business_role_id, business_object_business_process_id)
  SELECT r.id,p.id FROM business_role as r,business_object_business_process as p,business_process as bp,business_object as bo
  WHERE
    bp.id = p.business_process_id AND
    bo.id = p.business_object_id AND
    r.code = 'company' AND
    (
      (bo.code = 'review' AND bp.code in ('add_review')) OR
      (bo.code = 'account' AND bp.code in ('user_logout','user_profile_view','mode_user','mode_company','mode_operator','mode_redactor','mode_administrator')) OR
      (bo.code = 'catalog' AND bp.code in ('catalog_search','structure_view','rubric_edit_user_data','rubric_search','rubric_view','structure_edit_system_data','rubric_edit_self_data')) OR
      (bo.code = 'goods' AND bp.code in ('add_order_goods','add_order_shipping')) OR
      (bo.code = 'comment' AND bp.code in ('comment_view','comment_add')) OR
      (bo.code = 'formula' AND bp.code in ('calculate_formula')) OR
      (bo.code = 'message' AND bp.code in ('message_add','message_view')) OR
      (bo.code = 'favorite' AND bp.code in ('add_favorite')) OR
      (bo.code = 'like' AND bp.code in ('add_like')) OR
      (bo.code = 'ad' AND bp.code in ('add_ad'))
    );

INSERT INTO business_role_business_privilege (business_role_id, business_object_business_process_id)
  SELECT r.id,p.id FROM business_role as r,business_object_business_process as p,business_process as bp,business_object as bo
  WHERE
    bp.id = p.business_process_id AND
    bo.id = p.business_object_id AND
    r.code = 'operator' AND
    (
      (bo.code = 'review' AND bp.code in ('add_review')) OR
      (bo.code = 'account' AND bp.code in ('user_logout','user_profile_view','mode_user','mode_company','mode_operator','mode_redactor','mode_administrator')) OR
      (bo.code = 'catalog' AND bp.code in ('catalog_search','structure_view','rubric_search','rubric_add_common','structure_edit_system_data')) OR
      (bo.code = 'comment' AND bp.code in ('comment_view')) OR
      (bo.code = 'message' AND bp.code in ('message_add','message_view')) OR
      (bo.code = 'favorite' AND bp.code in ('add_favorite')) OR
      (bo.code = 'like' AND bp.code in ('add_like'))
    );

INSERT INTO business_role_business_privilege (business_role_id, business_object_business_process_id)
  SELECT r.id,p.id FROM business_role as r,business_object_business_process as p,business_process as bp,business_object as bo
  WHERE
    bp.id = p.business_process_id AND
    bo.id = p.business_object_id AND
    r.code = 'redactor' AND
    (
      (bo.code = 'review' AND bp.code in ('add_review')) OR
      (bo.code = 'account' AND bp.code in ('user_logout','user_profile_view','mode_user','mode_company','mode_operator','mode_redactor','mode_administrator')) OR
      (bo.code = 'catalog' AND bp.code in ('structure_view','structure_add','structure_edit_user_data','structure_edit_system_data')) OR
      (bo.code = 'comment' AND bp.code in ('comment_view')) OR
      (bo.code = 'message' AND bp.code in ('message_add','message_view')) OR
      (bo.code = 'favorite' AND bp.code in ('add_favorite')) OR
      (bo.code = 'like' AND bp.code in ('add_like'))
    );

INSERT INTO business_role_business_privilege (business_role_id, business_object_business_process_id)
  SELECT r.id,p.id FROM business_role as r,business_object_business_process as p,business_process as bp,business_object as bo
  WHERE
    bp.id = p.business_process_id AND
    bo.id = p.business_object_id AND
    r.code = 'administrator' AND
    (
      (bo.code = 'review' AND bp.code in ('add_review')) OR
      (bo.code = 'account' AND bp.code in ('user_logout','user_profile_view','mode_user','mode_company','mode_operator','mode_redactor','mode_administrator','edit_permission')) OR
      (bo.code = 'message' AND bp.code in ('message_add','message_view')) OR
      (bo.code = 'favorite' AND bp.code in ('add_favorite')) OR
      (bo.code = 'like' AND bp.code in ('add_like'))
    );

CREATE TABLE public.account_role
(
  id SERIAL PRIMARY KEY NOT NULL,
  business_role_id INTEGER NOT NULL,
  account_id INTEGER NOT NULL,
  CONSTRAINT "fk_account_role_business_role_id" FOREIGN KEY (business_role_id) REFERENCES public.business_role (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "fk_account_role_account_id" FOREIGN KEY (account_id) REFERENCES public.account (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
CREATE UNIQUE INDEX ux_account_role_business_role_id_account_id ON public.account_role (business_role_id,account_id);
INSERT INTO account_role (business_role_id,account_id) VALUES (1,1);

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

CREATE TABLE public.profile
(
  id BIGSERIAL PRIMARY KEY NOT NULL,
  is_hidden integer DEFAULT 0,
  insert_date TIMESTAMPTZ DEFAULT NOW(),
  code CHAR(100),
  name VARCHAR(4000),
  description TEXT,
  city VARCHAR(4000),
  country VARCHAR(4000),
  update_date TIMESTAMPTZ DEFAULT NOW()
);
CREATE UNIQUE INDEX ux_account_code ON public.profile (code);
CREATE UNIQUE INDEX ux_account_name ON public.profile (name);
CREATE INDEX ix_profile_is_hidden_id ON public.profile (is_hidden ASC, id ASC);
INSERT INTO public.profile (code,name,description) VALUES ('guest','Гость','Человек, который мало чего может');

CREATE TABLE public.account_profile
(
  id SERIAL PRIMARY KEY NOT NULL,
  account_id INTEGER NOT NULL,
  profile_id INTEGER NOT NULL,
  CONSTRAINT "fk_account_profile_account_id" FOREIGN KEY (account_id) REFERENCES public.account (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "fk_account_profile_profile_id" FOREIGN KEY (profile_id) REFERENCES public.profile (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
CREATE UNIQUE INDEX ux_account_profile_account_id_profile_id ON public.account_profile (account_id,profile_id);
INSERT INTO account_profile (account_id, profile_id) VALUES (1,1);