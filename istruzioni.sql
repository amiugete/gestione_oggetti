


-- util_go.sys_history definition

-- Drop table

-- DROP TABLE util_go.sys_history;

CREATE TABLE util_go.sys_history (
	id serial4 NOT NULL, -- identificativo univoco
	"type" varchar NOT NULL,
	"action" varchar NOT NULL, -- azione effettuata
	description varchar NULL, -- descrizione azione
	datetime timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, -- timestamp azione
	id_user int4 NOT NULL, -- utente che ha effettuato l'azione
	id_piazzola numeric(11) NULL,
	id_intervento numeric(11) NULL,
	id_elemento numeric(11) NULL,
	id_odl numeric(11) NULL, -- id Utenza PaP
	CONSTRAINT sys_history_pkey PRIMARY KEY (id)
);
COMMENT ON TABLE util_go.sys_history IS 'storia delle azioni di modifica effettuate';

-- Column comments

COMMENT ON COLUMN util_go.sys_history.id IS 'identificativo univoco';
COMMENT ON COLUMN util_go.sys_history."action" IS 'azione effettuata';
COMMENT ON COLUMN util_go.sys_history.description IS 'descrizione azione';
COMMENT ON COLUMN util_go.sys_history.datetime IS 'timestamp azione';
COMMENT ON COLUMN util_go.sys_history.id_user IS 'utente che ha effettuato l''azione';

-- Permissions

ALTER TABLE util_go.sys_history OWNER TO postgres;
GRANT ALL ON TABLE util_go.sys_history TO postgres;
GRANT ALL ON TABLE util_go.sys_history TO gisamiu;
GRANT ALL ON TABLE util_go.sys_history TO manutenzione;