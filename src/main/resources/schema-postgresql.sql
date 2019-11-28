-------------
--- TYPES ---
-------------

CREATE DOMAIN slugid AS VARCHAR CHECK (VALUE ~ '^[a-zA-Z0-9_-]{22}$');

-------------------------
--- TRIGGER FUNCTIONS ---
-------------------------

CREATE OR REPLACE FUNCTION set_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = now()::timestamp;
    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

CREATE OR REPLACE FUNCTION set_timestamps()
RETURNS TRIGGER AS $$
BEGIN
    NEW.created_at = now()::timestamp;
    NEW.updated_at = now()::timestamp;
    RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

----------------------
--- TABLES - AUTHOR --
----------------------

CREATE TABLE IF NOT EXISTS author (
    author_id  SLUGID PRIMARY KEY,
    bio        TEXT,
    created_at TIMESTAMP,
    name       VARCHAR(255) UNIQUE,
    slug       VARCHAR(255) UNIQUE,
    updated_at TIMESTAMP
);

CREATE TRIGGER trig_author_insert BEFORE INSERT ON author FOR EACH ROW EXECUTE PROCEDURE set_timestamps();
CREATE TRIGGER trig_author_update BEFORE UPDATE ON author FOR EACH ROW EXECUTE PROCEDURE set_updated_at();

COMMENT ON COLUMN author.author_id  IS 'URL-safe Base64-encoded UUID for the author.';
COMMENT ON COLUMN author.bio        IS 'Bio of the author.';
COMMENT ON COLUMN author.created_at IS 'Timestamp when the author was created.';
COMMENT ON COLUMN author.name       IS 'Well, the name of the author ...';
COMMENT ON COLUMN author.slug       IS 'Unique SEO-friendly string representation of the author name for use in URLs';
COMMENT ON COLUMN author.updated_at IS 'Timestamp when the author was updated.';

-------------------
--- TABLES - TAG --
-------------------

CREATE TABLE IF NOT EXISTS tag (
    created_at      TIMESTAMP,
    tag_id          SLUGID PRIMARY KEY,
    updated_at      TIMESTAMP,
    value           VARCHAR UNIQUE
);

CREATE TRIGGER trig_tag_insert BEFORE INSERT ON tag FOR EACH ROW EXECUTE PROCEDURE set_timestamps();
CREATE TRIGGER trig_tag_update BEFORE UPDATE ON tag FOR EACH ROW EXECUTE PROCEDURE set_updated_at();

COMMENT ON COLUMN tag.created_at IS 'Timestamp when the tag was created.';
COMMENT ON COLUMN tag.tag_id     IS 'URL-safe Base64-encoded UUID for a tag.';
COMMENT ON COLUMN tag.updated_at IS 'Timestamp when the tag was updated.';
COMMENT ON COLUMN tag.value      IS 'The actual tag.';

-----------------------------
--- TABLES - QUOTE_SOURCE ---
-----------------------------

CREATE TABLE IF NOT EXISTS quote_source (
    created_at      TIMESTAMP,
    filename        VARCHAR(2083),
    quote_source_id SLUGID PRIMARY KEY,
    remarks         TEXT,
    updated_at      TIMESTAMP,
    url             VARCHAR(2083)
);

CREATE TRIGGER trig_quote_source_insert BEFORE INSERT ON quote_source FOR EACH ROW EXECUTE PROCEDURE set_timestamps();
CREATE TRIGGER trig_quote_source_update BEFORE UPDATE ON quote_source FOR EACH ROW EXECUTE PROCEDURE set_updated_at();

COMMENT ON COLUMN quote_source.created_at      IS 'Timestamp when the quote source was created.';
COMMENT ON COLUMN quote_source.filename        IS 'Url of an optional filename.';
COMMENT ON COLUMN quote_source.quote_source_id IS 'URL-safe Base64-encoded UUID for a quote source.';
COMMENT ON COLUMN quote_source.updated_at      IS 'Timestamp when the quote source was updated.';
COMMENT ON COLUMN quote_source.url             IS 'An external url.';
COMMENT ON COLUMN quote_source.remarks         IS 'Additional remarks of the source.';

----------------------
--- TABLES - QUOTE ---
----------------------

CREATE TABLE IF NOT EXISTS quote (
    appeared_at     TIMESTAMP,
    author_id       SLUGID REFERENCES author (author_id) ON DELETE SET NULL,
    created_at      TIMESTAMP,
    quote_id        SLUGID PRIMARY KEY,
    quote_source_id SLUGID REFERENCES quote_source (quote_source_id) ON DELETE SET NULL,
    updated_at      TIMESTAMP,
    value           TEXT UNIQUE
);

CREATE TRIGGER trig_quote_insert BEFORE INSERT ON quote FOR EACH ROW EXECUTE PROCEDURE set_timestamps();
CREATE TRIGGER trig_quote_update BEFORE UPDATE ON quote FOR EACH ROW EXECUTE PROCEDURE set_updated_at();

COMMENT ON COLUMN quote.appeared_at     IS 'Timestamp when the quote has been appeared for the first time.';
COMMENT ON COLUMN quote.author_id       IS 'The author id of the quote.';
COMMENT ON COLUMN quote.created_at      IS 'Timestamp when the quote was created.';
COMMENT ON COLUMN quote.quote_id        IS 'URL-safe Base64-encoded UUID for a quote.';
COMMENT ON COLUMN quote.quote_source_id IS 'The source id of the quote.';
COMMENT ON COLUMN quote.updated_at      IS 'Timestamp when the quote was updated.';
COMMENT ON COLUMN quote.value           IS 'The actual quote.';

-------------------------
--- TABLES - TAG_QUOTE --
-------------------------

CREATE TABLE IF NOT EXISTS quote_tag (
    quote_id SLUGID REFERENCES quote (quote_id) ON DELETE CASCADE,
    tag_id   SLUGID REFERENCES tag (tag_id) ON DELETE CASCADE
);
