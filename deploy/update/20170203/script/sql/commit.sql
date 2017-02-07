DROP TABLE public.account CASCADE;

CREATE TABLE public.account
(
  id            SERIAL PRIMARY KEY NOT NULL,
  login         VARCHAR(4000),
  password_hash VARCHAR(4000),
  email         VARCHAR(4000),
  activity_date TIMESTAMPTZ DEFAULT NOW(),
  insert_date   TIMESTAMPTZ DEFAULT NOW(),
  is_hidden     INTEGER     DEFAULT 0
);
CREATE UNIQUE INDEX ux_account_login ON public.account (login);
CREATE UNIQUE INDEX ux_account_email ON public.account (email);
CREATE INDEX ix_account_is_hidden_id ON public.account (is_hidden ASC, id ASC);
INSERT INTO public.account (login) VALUES ('guest');

DROP TABLE public.business_process CASCADE;

CREATE TABLE public.business_process
(
  id          SERIAL PRIMARY KEY NOT NULL,
  code        VARCHAR(4000)      NOT NULL,
  insert_date TIMESTAMPTZ DEFAULT NOW(),
  update_date TIMESTAMPTZ DEFAULT NOW(),
  is_hidden   INTEGER     DEFAULT 0,
  discription VARCHAR(4000)
);
CREATE INDEX ix_business_process_is_hidden_code ON public.business_process (is_hidden ASC, code ASC);
CREATE UNIQUE INDEX ux_business_process_code ON public.business_process (code);
INSERT INTO business_process (code, discription) VALUES ('mode_user', 'режим пользователь');
INSERT INTO business_process (code, discription) VALUES ('mode_company', 'режим компания');
INSERT INTO business_process (code, discription) VALUES ('mode_operator', 'режим оператор');
INSERT INTO business_process (code, discription) VALUES ('mode_redactor', 'режим редактор');
INSERT INTO business_process (code, discription) VALUES ('mode_administrator', 'режим администратор');
INSERT INTO business_process (code, discription) VALUES ('add_review', 'добавить отзыв');
INSERT INTO business_process (code, discription) VALUES ('edit_permission', 'изменение разрешений для пользователей');
INSERT INTO business_process (code, discription) VALUES ('user_registration', 'регистрация');
INSERT INTO business_process (code, discription) VALUES ('user_profile_edit', 'изменить профиль');
INSERT INTO business_process (code, discription) VALUES ('user_profile_view', 'просмотр профиль');
INSERT INTO business_process (code, discription) VALUES ('user_logon', 'Вход');
INSERT INTO business_process (code, discription) VALUES ('user_logout', 'Выход');
INSERT INTO business_process (code, discription) VALUES ('password_reset', 'Восстановить пароль');
INSERT INTO business_process (code, discription) VALUES ('edit_password', 'Изменить пароль');
INSERT INTO business_process (code, discription) VALUES ('catalog_search', 'Поиск в каталоге');
INSERT INTO business_process (code, discription) VALUES ('structure_view', 'Просмотр структуры');
INSERT INTO business_process (code, discription) VALUES ('structure_add', 'Добавление и удаление элементов структуры');
INSERT INTO business_process (code, discription)
VALUES ('structure_edit_user_data', 'Изменение пользовательских свойств для элементов структуры');
INSERT INTO business_process (code, discription)
VALUES ('structure_edit_system_data', 'изменение системных свойств элементов структуры');
INSERT INTO business_process (code, discription) VALUES ('rubric_search', 'поиск в рубрике');
INSERT INTO business_process (code, discription) VALUES ('rubric_view', 'просмотр данных');
INSERT INTO business_process (code, discription)
VALUES ('rubric_add_common', 'добавление и удаление элементов рубрики');
INSERT INTO business_process (code, discription)
VALUES ('rubric_add_self', 'добавление и удаление соотнесённых элементов рубрики');
INSERT INTO business_process (code, discription)
VALUES ('rubric_edit_user_data', 'изменение пользовательских свойств для элементов рубрики');
INSERT INTO business_process (code, discription)
VALUES ('rubric_edit_system_data', 'изменение системных свойств элементов структуры');
INSERT INTO business_process (code, discription)
VALUES ('rubric_edit_self_data', 'изменение пользовательских свойств Компании');
INSERT INTO business_process (code, discription) VALUES ('calculate_formula', 'вычислить формулу');
INSERT INTO business_process (code, discription) VALUES ('add_order_goods', 'добавить заявку на товар');
INSERT INTO business_process (code, discription) VALUES ('add_order_shipping', 'добавить заявку на доставку');
INSERT INTO business_process (code, discription) VALUES ('comment_view', 'просмотр комментариев');
INSERT INTO business_process (code, discription) VALUES ('comment_add', 'добавить комментарий');
INSERT INTO business_process (code, discription) VALUES ('message_add', 'добавить сообщение');
INSERT INTO business_process (code, discription) VALUES ('message_view', 'просматривать сообщения');
INSERT INTO business_process (code, discription) VALUES ('add_favorite', 'добавить в избранное');
INSERT INTO business_process (code, discription) VALUES ('add_like', 'добавить лайк');
INSERT INTO business_process (code, discription) VALUES ('add_ad', 'добавить объявление');

DROP TABLE public.business_object CASCADE;

CREATE TABLE public.business_object
(
  id          SERIAL PRIMARY KEY NOT NULL,
  code        VARCHAR(4000)      NOT NULL,
  insert_date TIMESTAMPTZ DEFAULT NOW(),
  update_date TIMESTAMPTZ DEFAULT NOW(),
  is_hidden   INTEGER     DEFAULT 0,
  discription VARCHAR(4000)
);
CREATE INDEX ix_business_object_is_hidden_code ON public.business_object (is_hidden ASC, code ASC);
CREATE UNIQUE INDEX ux_business_object_code ON public.business_object (code);
INSERT INTO business_object (code, discription) VALUES ('review', 'отзыв');
INSERT INTO business_object (code, discription) VALUES ('account', 'аккаунт');
INSERT INTO business_object (code, discription) VALUES ('catalog', 'каталог');
INSERT INTO business_object (code, discription) VALUES ('formula', 'формула');
INSERT INTO business_object (code, discription) VALUES ('goods', 'товары');
INSERT INTO business_object (code, discription) VALUES ('comment', 'комментарии');
INSERT INTO business_object (code, discription) VALUES ('message', 'сообщения');
INSERT INTO business_object (code, discription) VALUES ('favorite', 'избранное');
INSERT INTO business_object (code, discription) VALUES ('like', 'лайки');
INSERT INTO business_object (code, discription) VALUES ('ad', 'объявления');

DROP TABLE public.role CASCADE;

CREATE TABLE public.role
(
  id          SERIAL PRIMARY KEY NOT NULL,
  code        VARCHAR(4000)      NOT NULL,
  name VARCHAR(4000),
  discription TEXT,
  insert_date TIMESTAMPTZ DEFAULT NOW(),
  update_date TIMESTAMPTZ DEFAULT NOW(),
  is_hidden   INTEGER     DEFAULT 0

);
CREATE INDEX ix_role_is_hidden_code ON public.role (is_hidden ASC, code ASC);
CREATE UNIQUE INDEX ux_role_code ON public.role (code);
INSERT INTO role (code, discription) VALUES ('guest', 'Гость');
INSERT INTO role (code, discription) VALUES ('user', 'Пользователь');
INSERT INTO role (code, discription) VALUES ('company', 'Компания');
INSERT INTO role (code, discription) VALUES ('operator', 'Оператор');
INSERT INTO role (code, discription) VALUES ('redactor', 'Редактор');
INSERT INTO role (code, discription) VALUES ('administrator', 'Администратор');

DROP TABLE public.privilege CASCADE;

CREATE TABLE public.privilege
(
  id                  SERIAL PRIMARY KEY NOT NULL,
  business_process_id INTEGER            NOT NULL,
  business_object_id  INTEGER            NOT NULL,
  CONSTRAINT "fk_privilege_business_process_id" FOREIGN KEY (business_process_id) REFERENCES public.business_process (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "fk_privilege_business_object_id" FOREIGN KEY (business_object_id) REFERENCES public.business_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
CREATE UNIQUE INDEX ux_privilege_business_process_id_business_object_id ON public.privilege (business_process_id, business_object_id);
INSERT INTO privilege (business_object_id, business_process_id)
  SELECT
    bo.id,
    bp.id
  FROM business_object AS bo, business_process AS bp
  WHERE bo.code = 'account' AND bp.code IN
                                ('mode_user', 'mode_company', 'mode_operator', 'mode_redactor', 'mode_administrator', 'edit_permission', 'user_registration', 'user_profile_edit', 'user_profile_view', 'user_logon', 'user_logout', 'password_reset', 'edit_password');
INSERT INTO privilege (business_object_id, business_process_id)
  SELECT
    bo.id,
    bp.id
  FROM business_object AS bo, business_process AS bp
  WHERE bo.code = 'review' AND bp.code IN ('add_review');
INSERT INTO privilege (business_object_id, business_process_id)
  SELECT
    bo.id,
    bp.id
  FROM business_object AS bo, business_process AS bp
  WHERE bo.code = 'catalog' AND bp.code IN
                                ('catalog_search', 'structure_view', 'structure_add', 'structure_edit_user_data', 'structure_edit_system_data', 'rubric_search', 'rubric_view', 'rubric_add_common', 'rubric_add_self', 'rubric_edit_user_data', 'rubric_edit_system_data', 'rubric_edit_self_data');
INSERT INTO privilege (business_object_id, business_process_id)
  SELECT
    bo.id,
    bp.id
  FROM business_object AS bo, business_process AS bp
  WHERE bo.code = 'formula' AND bp.code IN ('calculate_formula');
INSERT INTO privilege (business_object_id, business_process_id)
  SELECT
    bo.id,
    bp.id
  FROM business_object AS bo, business_process AS bp
  WHERE bo.code = 'goods' AND bp.code IN ('add_order_goods', 'add_order_shipping');
INSERT INTO privilege (business_object_id, business_process_id)
  SELECT
    bo.id,
    bp.id
  FROM business_object AS bo, business_process AS bp
  WHERE bo.code = 'comment' AND bp.code IN ('comment_view', 'comment_add');
INSERT INTO privilege (business_object_id, business_process_id)
  SELECT
    bo.id,
    bp.id
  FROM business_object AS bo, business_process AS bp
  WHERE bo.code = 'message' AND bp.code IN ('message_add', 'message_view');
INSERT INTO privilege (business_object_id, business_process_id)
  SELECT
    bo.id,
    bp.id
  FROM business_object AS bo, business_process AS bp
  WHERE bo.code = 'favorite' AND bp.code IN ('add_favorite');
INSERT INTO privilege (business_object_id, business_process_id)
  SELECT
    bo.id,
    bp.id
  FROM business_object AS bo, business_process AS bp
  WHERE bo.code = 'like' AND bp.code IN ('add_like');
INSERT INTO privilege (business_object_id, business_process_id)
  SELECT
    bo.id,
    bp.id
  FROM business_object AS bo, business_process AS bp
  WHERE bo.code = 'ad' AND bp.code IN ('add_ad');

DROP TABLE public.role_detail CASCADE;

CREATE TABLE public.role_detail
(
  id           SERIAL PRIMARY KEY NOT NULL,
  privilege_id INTEGER            NOT NULL,
  role_id      INTEGER            NOT NULL,
  CONSTRAINT "fk_role_detail_privilege_id" FOREIGN KEY (privilege_id) REFERENCES public.privilege (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "fk_role_detail_role_id" FOREIGN KEY (role_id) REFERENCES public.role (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
CREATE UNIQUE INDEX ux_role_detail_privilege_id_role_id ON public.role_detail (privilege_id, role_id);
INSERT INTO role_detail (role_id, privilege_id)
  SELECT
    r.id,
    p.id
  FROM role AS r, privilege AS p, business_process AS bp, business_object AS bo
  WHERE
    bp.id = p.business_process_id AND
    bo.id = p.business_object_id AND
    r.code = 'guest' AND
    (
      (bo.code = 'account' AND bp.code IN ('user_registration', 'user_logon', 'password_reset', 'mode_user')) OR
      (bo.code = 'catalog' AND bp.code IN ('catalog_search')) OR
      (bo.code = 'formula' AND bp.code IN ('calculate_formula')) OR
      (bo.code = 'goods' AND bp.code IN ('add_order_goods', 'add_order_shipping')) OR
      (bo.code = 'comment' AND bp.code IN ('comment_view'))
    );

INSERT INTO role_detail (role_id, privilege_id)
  SELECT
    r.id,
    p.id
  FROM role AS r, privilege AS p, business_process AS bp, business_object AS bo
  WHERE
    bp.id = p.business_process_id AND
    bo.id = p.business_object_id AND
    r.code = 'user' AND
    (
      (bo.code = 'review' AND bp.code IN ('add_review')) OR
      (bo.code = 'account' AND bp.code IN
                               ('user_registration', 'user_logon', 'user_logout', 'password_reset', 'edit_password', 'user_profile_edit', 'user_profile_view', 'mode_user', 'mode_company', 'mode_operator', 'mode_redactor', 'mode_administrator'))
      OR
      (bo.code = 'catalog' AND bp.code IN
                               ('catalog_search', 'structure_view', 'rubric_search', 'rubric_view', 'rubric_add_self', 'rubric_edit_user_data'))
      OR
      (bo.code = 'goods' AND bp.code IN ('add_order_goods', 'add_order_shipping')) OR
      (bo.code = 'comment' AND bp.code IN ('comment_view', 'comment_add')) OR
      (bo.code = 'formula' AND bp.code IN ('calculate_formula')) OR
      (bo.code = 'message' AND bp.code IN ('message_add', 'message_view')) OR
      (bo.code = 'favorite' AND bp.code IN ('add_favorite')) OR
      (bo.code = 'like' AND bp.code IN ('add_like')) OR
      (bo.code = 'ad' AND bp.code IN ('add_ad'))
    );

INSERT INTO role_detail (role_id, privilege_id)
  SELECT
    r.id,
    p.id
  FROM role AS r, privilege AS p, business_process AS bp, business_object AS bo
  WHERE
    bp.id = p.business_process_id AND
    bo.id = p.business_object_id AND
    r.code = 'company' AND
    (
      (bo.code = 'review' AND bp.code IN ('add_review')) OR
      (bo.code = 'account' AND bp.code IN
                               ('user_logout', 'user_profile_view', 'mode_user', 'mode_company', 'mode_operator', 'mode_redactor', 'mode_administrator'))
      OR
      (bo.code = 'catalog' AND bp.code IN
                               ('catalog_search', 'structure_view', 'rubric_edit_user_data', 'rubric_search', 'rubric_view', 'structure_edit_system_data', 'rubric_edit_self_data'))
      OR
      (bo.code = 'goods' AND bp.code IN ('add_order_goods', 'add_order_shipping')) OR
      (bo.code = 'comment' AND bp.code IN ('comment_view', 'comment_add')) OR
      (bo.code = 'formula' AND bp.code IN ('calculate_formula')) OR
      (bo.code = 'message' AND bp.code IN ('message_add', 'message_view')) OR
      (bo.code = 'favorite' AND bp.code IN ('add_favorite')) OR
      (bo.code = 'like' AND bp.code IN ('add_like')) OR
      (bo.code = 'ad' AND bp.code IN ('add_ad'))
    );

INSERT INTO role_detail (role_id, privilege_id)
  SELECT
    r.id,
    p.id
  FROM role AS r, privilege AS p, business_process AS bp, business_object AS bo
  WHERE
    bp.id = p.business_process_id AND
    bo.id = p.business_object_id AND
    r.code = 'operator' AND
    (
      (bo.code = 'review' AND bp.code IN ('add_review')) OR
      (bo.code = 'account' AND bp.code IN
                               ('user_logout', 'user_profile_view', 'mode_user', 'mode_company', 'mode_operator', 'mode_redactor', 'mode_administrator'))
      OR
      (bo.code = 'catalog' AND bp.code IN
                               ('catalog_search', 'structure_view', 'rubric_search', 'rubric_add_common', 'structure_edit_system_data'))
      OR
      (bo.code = 'comment' AND bp.code IN ('comment_view')) OR
      (bo.code = 'message' AND bp.code IN ('message_add', 'message_view')) OR
      (bo.code = 'favorite' AND bp.code IN ('add_favorite')) OR
      (bo.code = 'like' AND bp.code IN ('add_like'))
    );

INSERT INTO role_detail (role_id, privilege_id)
  SELECT
    r.id,
    p.id
  FROM role AS r, privilege AS p, business_process AS bp, business_object AS bo
  WHERE
    bp.id = p.business_process_id AND
    bo.id = p.business_object_id AND
    r.code = 'redactor' AND
    (
      (bo.code = 'review' AND bp.code IN ('add_review')) OR
      (bo.code = 'account' AND bp.code IN
                               ('user_logout', 'user_profile_view', 'mode_user', 'mode_company', 'mode_operator', 'mode_redactor', 'mode_administrator'))
      OR
      (bo.code = 'catalog' AND
       bp.code IN ('structure_view', 'structure_add', 'structure_edit_user_data', 'structure_edit_system_data')) OR
      (bo.code = 'comment' AND bp.code IN ('comment_view')) OR
      (bo.code = 'message' AND bp.code IN ('message_add', 'message_view')) OR
      (bo.code = 'favorite' AND bp.code IN ('add_favorite')) OR
      (bo.code = 'like' AND bp.code IN ('add_like'))
    );

INSERT INTO role_detail (role_id, privilege_id)
  SELECT
    r.id,
    p.id
  FROM role AS r, privilege AS p, business_process AS bp, business_object AS bo
  WHERE
    bp.id = p.business_process_id AND
    bo.id = p.business_object_id AND
    r.code = 'administrator' AND
    (
      (bo.code = 'review' AND bp.code IN ('add_review')) OR
      (bo.code = 'account' AND bp.code IN
                               ('user_logout', 'user_profile_view', 'mode_user', 'mode_company', 'mode_operator', 'mode_redactor', 'mode_administrator', 'edit_permission'))
      OR
      (bo.code = 'message' AND bp.code IN ('message_add', 'message_view')) OR
      (bo.code = 'favorite' AND bp.code IN ('add_favorite')) OR
      (bo.code = 'like' AND bp.code IN ('add_like'))
    );

DROP TABLE public.account_role CASCADE;

CREATE TABLE public.account_role
(
  id         SERIAL PRIMARY KEY NOT NULL,
  role_id    INTEGER            NOT NULL,
  account_id INTEGER            NOT NULL,
  CONSTRAINT "fk_account_role_role_id" FOREIGN KEY (role_id) REFERENCES public.role (id) ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT "fk_account_role_account_id" FOREIGN KEY (account_id) REFERENCES public.account (id) ON UPDATE NO ACTION ON DELETE NO ACTION
);
CREATE UNIQUE INDEX ux_account_role_role_id_account_id ON public.account_role (role_id, account_id);
INSERT INTO account_role (role_id, account_id) VALUES (1, 1);

DROP TABLE public.session CASCADE;

CREATE TABLE public.session
(
  id          SERIAL,
  key         VARCHAR(4000),
  account_id  INTEGER,
  is_hidden   INTEGER     DEFAULT 0,
  insert_date TIMESTAMPTZ DEFAULT NOW(),
  update_date TIMESTAMPTZ DEFAULT NOW(),
  CONSTRAINT "fk_session_account_id" FOREIGN KEY (account_id) REFERENCES public.account (id) ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
OIDS = FALSE
);
ALTER TABLE public.session
  OWNER TO assay_manager;
CREATE INDEX ix_session_is_hidden_id ON public.session (is_hidden ASC, id ASC);

CREATE TABLE structure
(
  id           SERIAL PRIMARY KEY NOT NULL,
  structure_id INTEGER,
  code         CHAR(100),
  name         VARCHAR(4000),
  description  TEXT,
  insert_date  TIMESTAMP WITH TIME ZONE DEFAULT now(),
  is_hidden    INTEGER                  DEFAULT 0,
  CONSTRAINT fk_structure_structure_id FOREIGN KEY (structure_id) REFERENCES structure (id)
);
COMMENT ON COLUMN structure.id IS 'идентификатор записи';
COMMENT ON COLUMN structure.structure_id IS 'ссылка на родительский элемент';
COMMENT ON COLUMN structure.code IS 'код элемента ( узла древовидной структуры )';
COMMENT ON COLUMN structure.name IS 'имя элемента';
COMMENT ON COLUMN structure.description IS 'описание элемента';
COMMENT ON COLUMN structure.insert_date IS 'дата добавления записи';
COMMENT ON COLUMN structure.is_hidden IS 'флаг "запись является скрытой"';
CREATE UNIQUE INDEX ux_structure_code ON structure (code);
CREATE INDEX ix_structure_is_hidden_code ON structure (is_hidden, code);
CREATE INDEX ix_structure_is_hidden_id ON structure (is_hidden, code);

CREATE TABLE rubric
(
  id          SERIAL PRIMARY KEY NOT NULL,
  code        CHAR(100),
  name        VARCHAR(4000),
  description TEXT,
  insert_date TIMESTAMP WITH TIME ZONE DEFAULT now(),
  is_hidden   INTEGER                  DEFAULT 0
);
COMMENT ON COLUMN rubric.id IS 'идентификатор рубрики';
COMMENT ON COLUMN rubric.code IS 'код записи';
COMMENT ON COLUMN rubric.name IS 'наимнование';
COMMENT ON COLUMN rubric.description IS 'описание';
COMMENT ON COLUMN rubric.insert_date IS 'дата добавления записи';
COMMENT ON COLUMN rubric.is_hidden IS 'является скрытым';
CREATE UNIQUE INDEX ux_rubric_code ON rubric (code);
CREATE INDEX ix_rubric_is_hidden_id ON rubric (is_hidden, id);
CREATE INDEX ix_rubric_is_hidden_code ON rubric (is_hidden, code);

CREATE TABLE rubric_structure
(
  id           SERIAL PRIMARY KEY NOT NULL,
  rubric_id    INTEGER            NOT NULL,
  structure_id INTEGER            NOT NULL,
  CONSTRAINT fk_rubric_structure_rubric_id FOREIGN KEY (rubric_id) REFERENCES rubric (id),
  CONSTRAINT fk_rubric_structure_structure_id FOREIGN KEY (structure_id) REFERENCES structure (id)
);
COMMENT ON COLUMN rubric_structure.id IS 'идентификатор';
COMMENT ON COLUMN rubric_structure.rubric_id IS 'рубрика';
COMMENT ON COLUMN rubric_structure.structure_id IS 'элемент структуры';
CREATE UNIQUE INDEX ux_rubric_structure_rubric_id_structure_id ON rubric_structure (rubric_id, structure_id);

CREATE TABLE data_type
(
  id          SERIAL PRIMARY KEY NOT NULL,
  code        CHAR(100),
  name        VARCHAR(4000),
  description TEXT,
  insert_date TIMESTAMP WITH TIME ZONE DEFAULT now(),
  is_hidden   INTEGER                  DEFAULT 0
);
COMMENT ON COLUMN data_type.id IS 'идентификатор типа данных';
COMMENT ON COLUMN data_type.code IS 'код типа данных';
COMMENT ON COLUMN data_type.name IS 'имя';
COMMENT ON COLUMN data_type.description IS 'описание';
COMMENT ON COLUMN data_type.insert_date IS 'дата добавления записи';
COMMENT ON COLUMN data_type.is_hidden IS 'флаг "является скрытым"';
CREATE UNIQUE INDEX ux_data_type_code ON data_type (code);
CREATE INDEX is_data_type_is_hidden_id ON data_type (is_hidden, id);
CREATE INDEX is_data_type_is_hidden_code ON data_type (is_hidden, code);

CREATE TABLE search_type
(
  id          SERIAL PRIMARY KEY NOT NULL,
  code        CHAR(100),
  name        VARCHAR(4000),
  description TEXT,
  insert_date TIMESTAMP WITH TIME ZONE DEFAULT now(),
  is_hidden   INTEGER                  DEFAULT 0
);
COMMENT ON COLUMN search_type.id IS 'идентификатор типа поиска';
COMMENT ON COLUMN search_type.code IS 'код';
COMMENT ON COLUMN search_type.name IS 'имя';
COMMENT ON COLUMN search_type.description IS 'описание';
COMMENT ON COLUMN search_type.insert_date IS 'дата добавления записи';
COMMENT ON COLUMN search_type.is_hidden IS 'флаг "является скрытым"';
CREATE UNIQUE INDEX ux_search_type_code ON search_type (code);
CREATE INDEX ix_search_type_is_hidden_id ON search_type (is_hidden, id);
CREATE INDEX ix_search_type_is_hidden_code ON search_type (is_hidden, code);

CREATE TABLE type_edit
(
  id          SERIAL PRIMARY KEY NOT NULL,
  code        CHAR(100),
  name        VARCHAR(4000),
  description TEXT,
  insert_date TIMESTAMP WITH TIME ZONE DEFAULT now(),
  is_hidden   INTEGER                  DEFAULT 0
);
COMMENT ON COLUMN type_edit.id IS 'идентификатор типа редактирования';
COMMENT ON COLUMN type_edit.code IS 'код';
COMMENT ON COLUMN type_edit.name IS 'имя';
COMMENT ON COLUMN type_edit.description IS 'описание';
COMMENT ON COLUMN type_edit.insert_date IS 'дата добавления записи';
COMMENT ON COLUMN type_edit.is_hidden IS 'флаг "является скрытым"';
CREATE UNIQUE INDEX ux_type_edit_code ON type_edit (code);
CREATE INDEX ix_type_edit_is_hidden_id ON type_edit (is_hidden, id);
CREATE INDEX ix_type_edit_is_hidden_code ON type_edit (is_hidden, code);

CREATE TABLE information_domain
(
  id             SERIAL PRIMARY KEY NOT NULL,
  code           CHAR(100),
  name           VARCHAR(4000),
  description    TEXT,
  insert_date    TIMESTAMP WITH TIME ZONE DEFAULT now(),
  is_hidden      INTEGER                  DEFAULT 0,
  type_edit_id   INTEGER,
  search_type_id INTEGER,
  data_type_id   INTEGER,
  CONSTRAINT fk_information_domain_type_edit_id FOREIGN KEY (type_edit_id) REFERENCES type_edit (id),
  CONSTRAINT fk_information_domain_search_type_id FOREIGN KEY (search_type_id) REFERENCES search_type (id),
  CONSTRAINT fk_information_domain_data_type_id FOREIGN KEY (data_type_id) REFERENCES data_type (id)
);
COMMENT ON COLUMN information_domain.id IS 'идентификатор информационного домена';
COMMENT ON COLUMN information_domain.code IS 'код домена';
COMMENT ON COLUMN information_domain.name IS 'имя домена';
COMMENT ON COLUMN information_domain.description IS 'описание домена';
COMMENT ON COLUMN information_domain.insert_date IS 'дата добавления записи';
COMMENT ON COLUMN information_domain.is_hidden IS 'флаг "является скрытым"';
COMMENT ON COLUMN information_domain.type_edit_id IS 'тип способа редактирования';
COMMENT ON COLUMN information_domain.search_type_id IS 'тип способа поиска';
COMMENT ON COLUMN information_domain.data_type_id IS 'тип данных';
CREATE UNIQUE INDEX ux_information_domain_code ON information_domain (code);
CREATE UNIQUE INDEX ux_information_domain_data_type_id_search_type_id_type_edit_id ON information_domain (data_type_id, search_type_id, type_edit_id);
CREATE INDEX ix_information_domain_is_hidden_id ON information_domain (is_hidden, id);
CREATE INDEX ix_information_domain_is_hidden_code ON information_domain (is_hidden, code);

CREATE TABLE information_property
(
  id          SERIAL PRIMARY KEY NOT NULL,
  code        CHAR(100),
  name        VARCHAR(4000),
  description TEXT,
  is_hidden   INTEGER                  DEFAULT 0,
  insert_date TIMESTAMP WITH TIME ZONE DEFAULT now()
);
COMMENT ON COLUMN information_property.id IS 'идентификатор свойства информации';
COMMENT ON COLUMN information_property.name IS 'имя';
COMMENT ON COLUMN information_property.description IS 'описание';
COMMENT ON COLUMN information_property.code IS 'код';
COMMENT ON COLUMN information_property.is_hidden IS 'флаг "является скрытым"';
COMMENT ON COLUMN information_property.insert_date IS 'дата добавления записи';
CREATE UNIQUE INDEX ux_information_property_code ON information_property (code);
CREATE INDEX ix_information_property_is_hidden_id ON information_property (is_hidden, id);
CREATE INDEX ix_information_property_is_hidden_code ON information_property (is_hidden, code);

CREATE TABLE information_property_information_domain
(
  id                      SERIAL PRIMARY KEY NOT NULL,
  information_domain_id   INTEGER,
  information_property_id INTEGER,
  CONSTRAINT fk_information_property_information_domain_information_domain_i FOREIGN KEY (information_domain_id) REFERENCES information_domain (id),
  CONSTRAINT fk_information_property_information_domain_information_property FOREIGN KEY (information_property_id) REFERENCES information_property (id)
);
COMMENT ON COLUMN information_property_information_domain.id IS 'идентификатор';
COMMENT ON COLUMN information_property_information_domain.information_domain_id IS 'информационный домен';
COMMENT ON COLUMN information_property_information_domain.information_property_id IS 'информационное свойство';
CREATE UNIQUE INDEX ux_information_property_id_information_domain_id ON information_property_information_domain (information_domain_id, information_property_id);

CREATE TABLE rubric_information_property
(
  id                      SERIAL PRIMARY KEY NOT NULL,
  rubric_id               INTEGER            NOT NULL,
  information_property_id INTEGER            NOT NULL,
  CONSTRAINT fk_rubric_information_property_rubric_id FOREIGN KEY (rubric_id) REFERENCES rubric (id),
  CONSTRAINT fk_rubric_information_property_information_property_id FOREIGN KEY (information_property_id) REFERENCES information_property (id)
);
COMMENT ON COLUMN rubric_information_property.id IS 'идентификатор';
COMMENT ON COLUMN rubric_information_property.rubric_id IS 'рубирка';
COMMENT ON COLUMN rubric_information_property.information_property_id IS 'свойство информации';
CREATE UNIQUE INDEX "ux_rubric_Information_property_rubric_id_information_property_i" ON rubric_information_property (information_property_id, rubric_id);

CREATE TABLE rubric_position
(
  rubric_id   INTEGER            NOT NULL,
  id          SERIAL PRIMARY KEY NOT NULL,
  code        CHAR(100),
  name        VARCHAR(4000),
  description TEXT,
  is_hidden   INTEGER                  DEFAULT 0,
  insert_date TIMESTAMP WITH TIME ZONE DEFAULT now(),
  CONSTRAINT fk_rubric_position_rubric_id FOREIGN KEY (rubric_id) REFERENCES rubric (id)
);
COMMENT ON COLUMN rubric_position.rubric_id IS 'ссылка на рубрику';
COMMENT ON COLUMN rubric_position.id IS 'идентификатор';
COMMENT ON COLUMN rubric_position.code IS 'код';
COMMENT ON COLUMN rubric_position.name IS 'имя';
COMMENT ON COLUMN rubric_position.description IS 'описание';
COMMENT ON COLUMN rubric_position.is_hidden IS 'флаг "является скрытым"';
COMMENT ON COLUMN rubric_position.insert_date IS 'дата добавления записи';
CREATE UNIQUE INDEX ux_rubric_position_code ON rubric_position (code);
CREATE INDEX ix_rubric_position_is_hidden_id ON rubric_position (is_hidden, id);
CREATE INDEX ix_rubric_position_is_hidden_code ON rubric_position (is_hidden, code);

CREATE TABLE property_content
(
  rubric_position_id      INTEGER            NOT NULL,
  id                      SERIAL PRIMARY KEY NOT NULL,
  information_property_id INTEGER            NOT NULL,
  content                 VARCHAR(4000),
  is_hidden               INTEGER                  DEFAULT 0,
  insert_date             TIMESTAMP WITH TIME ZONE DEFAULT now(),
  CONSTRAINT fk_property_content_rubric_position_id FOREIGN KEY (rubric_position_id) REFERENCES rubric_position (id),
  CONSTRAINT fk_property_content_information_property_id FOREIGN KEY (information_property_id) REFERENCES information_property (id)
);
COMMENT ON COLUMN property_content.rubric_position_id IS 'ссылка на позицию рубрики';
COMMENT ON COLUMN property_content.id IS 'идентификатор';
COMMENT ON COLUMN property_content.information_property_id IS 'ссылка на свойсвто';
COMMENT ON COLUMN property_content.content IS 'содержание ( значение ) свойсвта';
COMMENT ON COLUMN property_content.is_hidden IS 'флаг "является скрытым"';
COMMENT ON COLUMN property_content.insert_date IS 'дата добавления записи';
CREATE UNIQUE INDEX ux_property_content_rubric_position_id_information_property_id ON property_content (rubric_position_id, information_property_id);
CREATE INDEX ix_property_content_is_hidden_id ON property_content (is_hidden, id);

CREATE TABLE redactor
(
  id          SERIAL PRIMARY KEY NOT NULL,
  code        CHAR(100),
  name        VARCHAR(4000),
  description TEXT,
  is_hidden   INTEGER                  DEFAULT 0,
  insert_date TIMESTAMP WITH TIME ZONE DEFAULT now()
);
COMMENT ON COLUMN redactor.id IS 'идентификатор редактра';
COMMENT ON COLUMN redactor.code IS 'код';
COMMENT ON COLUMN redactor.name IS 'имя';
COMMENT ON COLUMN redactor.description IS 'описание';
COMMENT ON COLUMN redactor.is_hidden IS 'флаг "является скрытым"';
COMMENT ON COLUMN redactor.insert_date IS 'дата добавления записи';
CREATE UNIQUE INDEX ux_redactor_code ON redactor (code);
CREATE INDEX ix_redactor_is_hidden_id ON redactor (is_hidden, id);
CREATE INDEX ix_redactor_is_hidden_code ON redactor (is_hidden, code);

CREATE TABLE additional_value
(
  property_content_id INTEGER            NOT NULL,
  id                  SERIAL PRIMARY KEY NOT NULL,
  redactor_id         INTEGER            NOT NULL,
  value               VARCHAR(4000),
  is_hidden           INTEGER                  DEFAULT 0,
  insert_date         TIMESTAMP WITH TIME ZONE DEFAULT now(),
  CONSTRAINT fk_additional_value_property_content_id FOREIGN KEY (property_content_id) REFERENCES property_content (id),
  CONSTRAINT fk_additional_value_redactor_id FOREIGN KEY (redactor_id) REFERENCES redactor (id)
);
COMMENT ON COLUMN additional_value.property_content_id IS 'содержание свойства';
CREATE INDEX ix_additional_value_is_hidden_id ON additional_value (is_hidden, id);
CREATE INDEX ix_additional_value_is_hidden_redactor ON additional_value (is_hidden, redactor_id);

CREATE TABLE string_value
(
  additional_value_id INTEGER NOT NULL,
  id SERIAL PRIMARY KEY NOT NULL,
  string VARCHAR(4000),
  is_hidden INTEGER DEFAULT 0,
  insert_date TIMESTAMP WITH TIME ZONE DEFAULT now(),
  CONSTRAINT fk_string_value_additional_value_id FOREIGN KEY (additional_value_id) REFERENCES additional_value (id)
);
COMMENT ON COLUMN string_value.additional_value_id IS 'ссылка на запись допольнительного значения';
COMMENT ON COLUMN string_value.id IS 'идентификатор';
COMMENT ON COLUMN string_value.string IS 'строковое дополнительное значение';
COMMENT ON COLUMN string_value.is_hidden IS 'флаг "является скрытым"';
COMMENT ON COLUMN string_value.insert_date IS 'дата добавления записи';
CREATE INDEX ix_string_value_is_hidden_id ON string_value (is_hidden, id);

CREATE TABLE digital_value
(
  additional_value_id INTEGER NOT NULL,
  id SERIAL PRIMARY KEY NOT NULL,
  digital DOUBLE PRECISION,
  is_hidden INTEGER DEFAULT 0,
  insert_date TIMESTAMP WITH TIME ZONE DEFAULT now(),
  CONSTRAINT fk_digital_value_additional_value_id FOREIGN KEY (additional_value_id) REFERENCES additional_value (id)
);
COMMENT ON COLUMN digital_value.additional_value_id IS 'ссылка на запись допольнительного значения';
COMMENT ON COLUMN digital_value.id IS 'идентификатор';
COMMENT ON COLUMN digital_value.digital IS 'числовое дополнительное значение';
COMMENT ON COLUMN digital_value.is_hidden IS 'флаг "является скрытым"';
COMMENT ON COLUMN digital_value.insert_date IS 'дата добавления записи';
CREATE INDEX ix_digital_value_is_hidden_id ON digital_value (is_hidden, id);

CREATE TABLE string_content
(
  property_content_id INTEGER NOT NULL,
  id SERIAL PRIMARY KEY NOT NULL,
  string VARCHAR(4000),
  is_hidden INTEGER DEFAULT 0,
  insert_date TIMESTAMP WITH TIME ZONE DEFAULT now(),
  CONSTRAINT fk_string_content_property_content_id FOREIGN KEY (property_content_id) REFERENCES property_content (id)
);
COMMENT ON COLUMN string_content.property_content_id IS 'ссылка на запись допольнительного значения';
COMMENT ON COLUMN string_content.id IS 'идентификатор';
COMMENT ON COLUMN string_content.string IS 'строковое дополнительное значение';
COMMENT ON COLUMN string_content.is_hidden IS 'флаг "является скрытым"';
COMMENT ON COLUMN string_content.insert_date IS 'дата добавления записи';
CREATE INDEX ix_string_content_is_hidden_id ON string_content (is_hidden, id);

CREATE TABLE digital_content
(
  property_content_id INTEGER NOT NULL,
  id SERIAL PRIMARY KEY NOT NULL,
  digital DOUBLE PRECISION,
  is_hidden INTEGER DEFAULT 0,
  insert_date TIMESTAMP WITH TIME ZONE DEFAULT now(),
  CONSTRAINT fk_digital_content_property_content_id FOREIGN KEY (property_content_id) REFERENCES property_content (id)
);
COMMENT ON COLUMN digital_content.property_content_id IS 'ссылка на запись допольнительного значения';
COMMENT ON COLUMN digital_content.id IS 'идентификатор';
COMMENT ON COLUMN digital_content.digital IS 'числовое дополнительное значение';
COMMENT ON COLUMN digital_content.is_hidden IS 'флаг "является скрытым"';
COMMENT ON COLUMN digital_content.insert_date IS 'дата добавления записи';
CREATE INDEX ix_digital_content_is_hidden_id ON digital_content (is_hidden, id);


INSERT INTO public.data_type (code, name, description) VALUES ('DIGITAL', 'числовой тип', 'числовой тип');
INSERT INTO public.data_type (code, name, description) VALUES ('STRING', 'символьный тип', 'символьный тип');

INSERT INTO public.search_type (code, name, description)
VALUES ('UNDEFINED', 'значение не определено', 'значение не определено');
INSERT INTO public.search_type (code, name, description) VALUES ('LIKE', 'поиск подобия', 'поиск подобия');
INSERT INTO public.search_type (code, name, description) VALUES ('BETWEEN', 'поиск в диапазоне', 'поиск в диапазоне');
INSERT INTO public.search_type (code, name, description)
VALUES ('ENUMERATION', 'поиск перечисления', 'поиск перечисления');

INSERT INTO public.type_edit (code, name, description)
VALUES ('UNDEFINED', 'значение не определено', 'значение не определено');
INSERT INTO public.type_edit (code, name, description)
VALUES ('USER', 'пользовательское свойство', 'пользовательское свойство');
INSERT INTO public.type_edit (code, name, description) VALUES ('SYSTEM', 'системное свойство', 'системное свойство');
INSERT INTO public.type_edit (code, name, description) VALUES ('COMPANY', 'свойство компании', 'свойство компании');

INSERT INTO public.information_domain (code, name, description, type_edit_id, search_type_id, data_type_id)
  SELECT
    'SYSTEM_LIKE',
    'Системный вхождение',
    'Системный вхождение',
    (SELECT TE.id
     FROM type_edit AS TE
     WHERE TE.CODE = 'USER'),
    (SELECT ST.id
     FROM search_type AS ST
     WHERE ST.CODE = 'LIKE'),
    (SELECT DT.id
     FROM data_type AS DT
     WHERE DT.CODE = 'STRING');

INSERT INTO public.information_domain (code, name, description, type_edit_id, search_type_id, data_type_id)
  SELECT
    'USER_LIKE',
    'Пользовательский вхождение',
    'Пользовательский вхождение',
    (SELECT TE.id
     FROM type_edit AS TE
     WHERE TE.CODE = 'SYSTEM'),
    (SELECT ST.id
     FROM search_type AS ST
     WHERE ST.CODE = 'LIKE'),
    (SELECT DT.id
     FROM data_type AS DT
     WHERE DT.CODE = 'STRING');

INSERT INTO public.information_domain (code, name, description, type_edit_id, search_type_id, data_type_id)
  SELECT
    'COMPANY_LIKE',
    'Компании вхождение',
    'Компании вхождение',
    (SELECT TE.id
     FROM type_edit AS TE
     WHERE TE.CODE = 'COMPANY'),
    (SELECT ST.id
     FROM search_type AS ST
     WHERE ST.CODE = 'LIKE'),
    (SELECT DT.id
     FROM data_type AS DT
     WHERE DT.CODE = 'STRING');
INSERT INTO public.information_domain (code, name, description, type_edit_id, search_type_id, data_type_id)
  SELECT
    'SYSTEM_STRING_ENUMERATION',
    'Системный строковое перечисление',
    'Системный строковое перечисление',
    (SELECT TE.id
     FROM type_edit AS TE
     WHERE TE.CODE = 'SYSTEM'),
    (SELECT ST.id
     FROM search_type AS ST
     WHERE ST.CODE = 'ENUMERATION'),
    (SELECT DT.id
     FROM data_type AS DT
     WHERE DT.CODE = 'STRING');

INSERT INTO public.information_domain (code, name, description, type_edit_id, search_type_id, data_type_id)
  SELECT
    'USER_STRING_ENUMERATION',
    'Пользовательский строковое перечисление',
    'Пользовательский строковое перечисление',
    (SELECT TE.id
     FROM type_edit AS TE
     WHERE TE.CODE = 'USER'),
    (SELECT ST.id
     FROM search_type AS ST
     WHERE ST.CODE = 'ENUMERATION'),
    (SELECT DT.id
     FROM data_type AS DT
     WHERE DT.CODE = 'STRING');
INSERT INTO public.information_domain (code, name, description, type_edit_id, search_type_id, data_type_id)
  SELECT
    'COMPANY_STRING_ENUMERATION',
    'Компании строковое перечисление',
    'Компании строковое перечисление',
    (SELECT TE.id
     FROM type_edit AS TE
     WHERE TE.CODE = 'COMPANY'),
    (SELECT ST.id
     FROM search_type AS ST
     WHERE ST.CODE = 'ENUMERATION'),
    (SELECT DT.id
     FROM data_type AS DT
     WHERE DT.CODE = 'STRING');

INSERT INTO public.information_domain (code, name, description, type_edit_id, search_type_id, data_type_id)
  SELECT
    'SYSTEM_DIGITAL_ENUMERATION',
    'Системный числовое перечисление',
    'Системный числовое перечисление',
    (SELECT TE.id
     FROM type_edit AS TE
     WHERE TE.CODE = 'SYSTEM'),
    (SELECT ST.id
     FROM search_type AS ST
     WHERE ST.CODE = 'ENUMERATION'),
    (SELECT DT.id
     FROM data_type AS DT
     WHERE DT.CODE = 'DIGITAL');

INSERT INTO public.information_domain (code, name, description, type_edit_id, search_type_id, data_type_id)
  SELECT
    'USER_DIGITAL_ENUMERATION',
    'Пользовательский числовое перечисление',
    'Пользовательский числовое перечисление',
    (SELECT TE.id
     FROM type_edit AS TE
     WHERE TE.CODE = 'USER'),
    (SELECT ST.id
     FROM search_type AS ST
     WHERE ST.CODE = 'ENUMERATION'),
    (SELECT DT.id
     FROM data_type AS DT
     WHERE DT.CODE = 'DIGITAL');
INSERT INTO public.information_domain (code, name, description, type_edit_id, search_type_id, data_type_id)
  SELECT
    'COMPANY_DIGITAL_ENUMERATION',
    'Компании числовое перечисление',
    'Компании числовое перечисление',
    (SELECT TE.id
     FROM type_edit AS TE
     WHERE TE.CODE = 'COMPANY'),
    (SELECT ST.id
     FROM search_type AS ST
     WHERE ST.CODE = 'ENUMERATION'),
    (SELECT DT.id
     FROM data_type AS DT
     WHERE DT.CODE = 'DIGITAL');


INSERT INTO public.information_domain (code, name, description, type_edit_id, search_type_id, data_type_id)
  SELECT
    'SYSTEM_BETWEEN_DIGITAL',
    'Системные диапазон',
    'Системные диапазон',
    (SELECT TE.id
     FROM type_edit AS TE
     WHERE TE.CODE = 'SYSTEM'),
    (SELECT ST.id
     FROM search_type AS ST
     WHERE ST.CODE = 'BETWEEN'),
    (SELECT DT.id
     FROM data_type AS DT
     WHERE DT.CODE = 'DIGITAL');
INSERT INTO public.information_domain (code, name, description, type_edit_id, search_type_id, data_type_id)
  SELECT
    'USER_BETWEEN_DIGITAL',
    'Пользовательский диапазон',
    'Пользовательский диапазон',
    (SELECT TE.id
     FROM type_edit AS TE
     WHERE TE.CODE = 'USER'),
    (SELECT ST.id
     FROM search_type AS ST
     WHERE ST.CODE = 'BETWEEN'),
    (SELECT DT.id
     FROM data_type AS DT
     WHERE DT.CODE = 'DIGITAL');
INSERT INTO public.information_domain (code, name, description, type_edit_id, search_type_id, data_type_id)
  SELECT
    'COMPANY_BETWEEN_DIGITAL',
    'Диапазон компании',
    'Диапазон компании',
    (SELECT TE.id
     FROM type_edit AS TE
     WHERE TE.CODE = 'COMPANY'),
    (SELECT ST.id
     FROM search_type AS ST
     WHERE ST.CODE = 'BETWEEN'),
    (SELECT DT.id
     FROM data_type AS DT
     WHERE DT.CODE = 'DIGITAL');

INSERT INTO public.rubric (code, name, description)
VALUES ('TRANSPORTATION', 'цены на грузоперевозки', 'Настройки цен на грузоперевозки');
INSERT INTO public.rubric (code, name, description)
VALUES ('GOODS_PRICING', 'цены на поставку', 'Настройки цен на поставку товаров и услуг');

INSERT INTO public.information_property (name, description, code)
VALUES ('Цена за км', 'Цена за километр', 'TRANSPORTATION_PRICE_KM');
INSERT INTO public.information_property (name, description, code)
VALUES ('Цена за тонну', 'Цена за тонну', 'TRANSPORTATION_PRICE_TON');
INSERT INTO public.information_property (name, description, code)
VALUES ('Цена', 'Цена за единицу товара', 'GOODS_PRICE');
INSERT INTO public.information_property (name, description, code)
VALUES ('единицы', 'единицы измерения товара', 'GOODS_UNITS_OF_MEASURE');

INSERT INTO public.information_property_information_domain (information_domain_id, information_property_id)
  SELECT
    (SELECT id
     FROM information_property
     WHERE code = 'TRANSPORTATION_PRICE_KM'),
    (SELECT id
     FROM information_domain
     WHERE code = 'USER_BETWEEN_DIGITAL');
INSERT INTO public.information_property_information_domain (information_domain_id, information_property_id)
  SELECT
    (SELECT id
     FROM information_property
     WHERE code = 'TRANSPORTATION_PRICE_TON'),
    (SELECT id
     FROM information_domain
     WHERE code = 'USER_BETWEEN_DIGITAL');
INSERT
INTO public.information_property_information_domain (information_domain_id, information_property_id)
  SELECT
    (SELECT id
     FROM information_property
     WHERE code = 'GOODS_PRICE'),
    (SELECT id
     FROM information_domain
     WHERE code = 'USER_BETWEEN_DIGITAL');
INSERT INTO public.information_property_information_domain (information_domain_id, information_property_id)
  SELECT
    (SELECT id
     FROM information_property
     WHERE code = 'GOODS_UNITS_OF_MEASURE'),
    (SELECT id
     FROM information_domain
     WHERE code = 'USER_STRING_ENUMERATION');
INSERT INTO public.rubric_information_property (rubric_id, information_property_id)
  SELECT
    (SELECT id
     FROM rubric
     WHERE code = 'TRANSPORTATION'),
    (SELECT id
     FROM information_property
     WHERE code = 'TRANSPORTATION_PRICE_KM');
INSERT INTO public.rubric_information_property (rubric_id, information_property_id)
  SELECT
    (SELECT id
     FROM rubric
     WHERE code = 'TRANSPORTATION'),
    (SELECT id
     FROM information_property
     WHERE code =
           'TRANSPORTATION_PRICE_TON');
INSERT INTO public.rubric_information_property (rubric_id, information_property_id)
  SELECT
    (SELECT id
     FROM rubric
     WHERE code = 'GOODS_PRICING'),
    (SELECT id
     FROM information_property
     WHERE code =
           'GOODS_PRICE');
INSERT INTO public.rubric_information_property (rubric_id, information_property_id)
  SELECT
    (SELECT id
     FROM rubric
     WHERE code = 'GOODS_PRICING'),
    (SELECT id
     FROM information_property
     WHERE code =
           'GOODS_UNITS_OF_MEASURE');
