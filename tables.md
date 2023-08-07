
-- Table: public.product_images

-- DROP TABLE IF EXISTS public.product_images;


CREATE TABLE IF NOT EXISTS public.product_images
(
    id integer NOT NULL DEFAULT nextval('product_images_id_seq'::regclass),
    product_id integer,
    width integer DEFAULT 100,
    height integer,
    image text COLLATE pg_catalog."default",
    CONSTRAINT product_images_pkey PRIMARY KEY (id),
    CONSTRAINT product_images_product_id_fkey FOREIGN KEY (product_id)
        REFERENCES public.products (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.product_images
    OWNER to postgres;




-- Table: public.products

-- DROP TABLE IF EXISTS public.products;


CREATE TABLE IF NOT EXISTS public.products
(
    id integer NOT NULL DEFAULT nextval('products_id_seq'::regclass),
    price numeric(10,2) NOT NULL,
    title character varying(100) COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT products_pkey PRIMARY KEY (id)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.products
    OWNER to postgres;


-- Table: public.users

-- DROP TABLE IF EXISTS public.users;


CREATE TABLE IF NOT EXISTS public.users
(
    id integer NOT NULL DEFAULT nextval('users_id_seq'::regclass),
    username character varying(50) COLLATE pg_catalog."default" NOT NULL,
    password character varying(255) COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT users_pkey PRIMARY KEY (id),
    CONSTRAINT users_username_key UNIQUE (username)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.users
    OWNER to postgres;