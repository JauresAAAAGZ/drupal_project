-- Database: first_dupral

-- DROP DATABASE IF EXISTS first_dupral;

CREATE DATABASE first_dupral
    WITH
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'French_France.1252'
    LC_CTYPE = 'French_France.1252'
    LOCALE_PROVIDER = 'libc'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1
    IS_TEMPLATE = False;

ALTER DATABASE first_dupral
    SET bytea_output TO 'escape';