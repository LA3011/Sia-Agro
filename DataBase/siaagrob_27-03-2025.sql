--
-- PostgreSQL database dump
--

-- Dumped from database version 12.22 (Ubuntu 12.22-0ubuntu0.20.04.2)
-- Dumped by pg_dump version 12.22 (Ubuntu 12.22-0ubuntu0.20.04.2)

-- Started on 2025-03-28 10:16:14 -04

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 236 (class 1259 OID 16600)
-- Name: animalsTest; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."animalsTest" (
    id_test integer NOT NULL,
    status boolean NOT NULL
);


ALTER TABLE public."animalsTest" OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 16598)
-- Name: animalsTest_id_test_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."animalsTest_id_test_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."animalsTest_id_test_seq" OWNER TO postgres;

--
-- TOC entry 3206 (class 0 OID 0)
-- Dependencies: 235
-- Name: animalsTest_id_test_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."animalsTest_id_test_seq" OWNED BY public."animalsTest".id_test;


--
-- TOC entry 202 (class 1259 OID 16386)
-- Name: costo_fijo_id_fijo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.costo_fijo_id_fijo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.costo_fijo_id_fijo_seq OWNER TO postgres;

--
-- TOC entry 203 (class 1259 OID 16388)
-- Name: costo_variable_id_variable_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.costo_variable_id_variable_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.costo_variable_id_variable_seq OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 16390)
-- Name: cultivo_plaga; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cultivo_plaga (
    id_plaga integer NOT NULL,
    id integer,
    nombre_plaga character varying(255),
    fecha_ultima_deteccion timestamp(6) with time zone DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE public.cultivo_plaga OWNER TO postgres;

--
-- TOC entry 205 (class 1259 OID 16394)
-- Name: cultivos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cultivos (
    "ID" integer NOT NULL,
    nombre character varying(255),
    tipo character varying(255),
    espacio character varying(255),
    cosecha_estimada integer,
    fecha_aspercion date,
    nombre_producto character varying(255),
    dosis integer,
    tipo_aspercion character varying(255),
    tipo_fertilizante character varying(255),
    cantidad_fertilizante integer,
    observaciones character varying(255),
    fecha_siembra date,
    fecha_cosecha date,
    tipo_riego character varying(255),
    fecha_registro timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    fecha_fertilizacion date,
    id_espacio integer
);


ALTER TABLE public.cultivos OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 16401)
-- Name: cultivos_ID_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."cultivos_ID_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."cultivos_ID_seq" OWNER TO postgres;

--
-- TOC entry 3207 (class 0 OID 0)
-- Dependencies: 206
-- Name: cultivos_ID_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."cultivos_ID_seq" OWNED BY public.cultivos."ID";


--
-- TOC entry 207 (class 1259 OID 16403)
-- Name: dataSet; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public."dataSet" (
    "id_dataSet" integer NOT NULL,
    nombre character varying,
    definicion character varying,
    familia character varying,
    tratamiento character varying,
    amenaza boolean
);


ALTER TABLE public."dataSet" OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 16409)
-- Name: dataSet_id_dataSet_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."dataSet_id_dataSet_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."dataSet_id_dataSet_seq" OWNER TO postgres;

--
-- TOC entry 3208 (class 0 OID 0)
-- Dependencies: 208
-- Name: dataSet_id_dataSet_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."dataSet_id_dataSet_seq" OWNED BY public."dataSet"."id_dataSet";


--
-- TOC entry 209 (class 1259 OID 16411)
-- Name: datos_veterinarios_id_veterinario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.datos_veterinarios_id_veterinario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.datos_veterinarios_id_veterinario_seq OWNER TO postgres;

--
-- TOC entry 210 (class 1259 OID 16413)
-- Name: espacios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.espacios (
    "Id_espacios" integer NOT NULL,
    nombre_espacio character varying(255) NOT NULL,
    estatus character varying(255) NOT NULL,
    recursos_hidricos character varying(255) NOT NULL,
    historial_uso character varying(255) NOT NULL,
    observaciones character varying(255) NOT NULL,
    tipo_riego character varying(255) NOT NULL,
    "Habilitado" integer DEFAULT 1 NOT NULL,
    poligono_id numeric
);


ALTER TABLE public.espacios OWNER TO postgres;

--
-- TOC entry 211 (class 1259 OID 16420)
-- Name: espacios_Id_espacios_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."espacios_Id_espacios_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."espacios_Id_espacios_seq" OWNER TO postgres;

--
-- TOC entry 3209 (class 0 OID 0)
-- Dependencies: 211
-- Name: espacios_Id_espacios_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."espacios_Id_espacios_seq" OWNED BY public.espacios."Id_espacios";


--
-- TOC entry 212 (class 1259 OID 16422)
-- Name: modelo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.modelo (
    id_modelo integer NOT NULL,
    "pesosH5" bytea,
    "modelsH5" bytea,
    prediccion_py bytea,
    tiempo character varying(30)
);


ALTER TABLE public.modelo OWNER TO postgres;

--
-- TOC entry 213 (class 1259 OID 16428)
-- Name: modelo_id_modelo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.modelo_id_modelo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.modelo_id_modelo_seq OWNER TO postgres;

--
-- TOC entry 3210 (class 0 OID 0)
-- Dependencies: 213
-- Name: modelo_id_modelo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.modelo_id_modelo_seq OWNED BY public.modelo.id_modelo;


--
-- TOC entry 214 (class 1259 OID 16430)
-- Name: modulo_movil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.modulo_movil (
    "Id_Modulos" integer NOT NULL,
    "Id_Subprograma" integer NOT NULL,
    nombre_modulo character varying(255) NOT NULL
);


ALTER TABLE public.modulo_movil OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 16433)
-- Name: modulo_Id_Modulos_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."modulo_Id_Modulos_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."modulo_Id_Modulos_seq" OWNER TO postgres;

--
-- TOC entry 3211 (class 0 OID 0)
-- Dependencies: 215
-- Name: modulo_Id_Modulos_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."modulo_Id_Modulos_seq" OWNED BY public.modulo_movil."Id_Modulos";


--
-- TOC entry 216 (class 1259 OID 16435)
-- Name: notificaciones_movil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.notificaciones_movil (
    id_notificacion integer NOT NULL,
    etiqueta boolean,
    expansion boolean
);


ALTER TABLE public.notificaciones_movil OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 16438)
-- Name: perfil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.perfil (
    "Id_Perfil" integer NOT NULL,
    nombre_perfil character varying,
    estado character varying
);


ALTER TABLE public.perfil OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 16444)
-- Name: perfil_movil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.perfil_movil (
    "Id_Perfil" integer NOT NULL,
    nombre_perfil character varying(255) NOT NULL,
    estado character varying(255) NOT NULL,
    trial377 character(1)
);


ALTER TABLE public.perfil_movil OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16450)
-- Name: perfil_Id_Perfil_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."perfil_Id_Perfil_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."perfil_Id_Perfil_seq" OWNER TO postgres;

--
-- TOC entry 3212 (class 0 OID 0)
-- Dependencies: 219
-- Name: perfil_Id_Perfil_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."perfil_Id_Perfil_seq" OWNED BY public.perfil_movil."Id_Perfil";


--
-- TOC entry 220 (class 1259 OID 16452)
-- Name: perfil_Id_Perfil_seq1; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."perfil_Id_Perfil_seq1"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."perfil_Id_Perfil_seq1" OWNER TO postgres;

--
-- TOC entry 3213 (class 0 OID 0)
-- Dependencies: 220
-- Name: perfil_Id_Perfil_seq1; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."perfil_Id_Perfil_seq1" OWNED BY public.perfil."Id_Perfil";


--
-- TOC entry 221 (class 1259 OID 16454)
-- Name: perfil_modulo_movil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.perfil_modulo_movil (
    "Id_Perfil" integer NOT NULL,
    "Id_Modulo" integer NOT NULL,
    trial380 character(1)
);


ALTER TABLE public.perfil_modulo_movil OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 16457)
-- Name: perfil_programa_movil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.perfil_programa_movil (
    "Id_Perfil" integer NOT NULL,
    "Id_Programa" integer NOT NULL,
    trial380 character(1)
);


ALTER TABLE public.perfil_programa_movil OWNER TO postgres;

--
-- TOC entry 223 (class 1259 OID 16460)
-- Name: perfil_subprograma_movil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.perfil_subprograma_movil (
    "Id_Perfil" integer NOT NULL,
    "Id_Subprograma" integer NOT NULL,
    trial383 character(1)
);


ALTER TABLE public.perfil_subprograma_movil OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 16463)
-- Name: plagas_id_plaga_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.plagas_id_plaga_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.plagas_id_plaga_seq OWNER TO postgres;

--
-- TOC entry 3214 (class 0 OID 0)
-- Dependencies: 224
-- Name: plagas_id_plaga_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.plagas_id_plaga_seq OWNED BY public.cultivo_plaga.id_plaga;


--
-- TOC entry 225 (class 1259 OID 16465)
-- Name: privilegios_movil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.privilegios_movil (
    id_privilegio integer NOT NULL,
    id_perfil integer NOT NULL,
    ver character varying(255) NOT NULL,
    editar character varying(255) NOT NULL,
    eliminar character varying(255) NOT NULL,
    imprimir character varying(255) NOT NULL,
    agregar character varying(255) NOT NULL,
    trial390 character(1)
);


ALTER TABLE public.privilegios_movil OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 16471)
-- Name: privilegios_id_privilegio_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.privilegios_id_privilegio_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.privilegios_id_privilegio_seq OWNER TO postgres;

--
-- TOC entry 3215 (class 0 OID 0)
-- Dependencies: 226
-- Name: privilegios_id_privilegio_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.privilegios_id_privilegio_seq OWNED BY public.privilegios_movil.id_privilegio;


--
-- TOC entry 227 (class 1259 OID 16473)
-- Name: programa_movil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.programa_movil (
    "Id_Programa" integer NOT NULL,
    nombre character varying(255) NOT NULL,
    trial393 character(1)
);


ALTER TABLE public.programa_movil OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 16476)
-- Name: programa_Id_Programa_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."programa_Id_Programa_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."programa_Id_Programa_seq" OWNER TO postgres;

--
-- TOC entry 3216 (class 0 OID 0)
-- Dependencies: 228
-- Name: programa_Id_Programa_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."programa_Id_Programa_seq" OWNED BY public.programa_movil."Id_Programa";


--
-- TOC entry 229 (class 1259 OID 16478)
-- Name: sub_programa_movil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sub_programa_movil (
    "Id_Subprograma" integer NOT NULL,
    "Id_ProgramaS" integer NOT NULL,
    nombre_subp character varying(255) NOT NULL,
    trial406 character(1)
);


ALTER TABLE public.sub_programa_movil OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 16481)
-- Name: sub_programa_Id_Subprograma_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."sub_programa_Id_Subprograma_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."sub_programa_Id_Subprograma_seq" OWNER TO postgres;

--
-- TOC entry 3217 (class 0 OID 0)
-- Dependencies: 230
-- Name: sub_programa_Id_Subprograma_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."sub_programa_Id_Subprograma_seq" OWNED BY public.sub_programa_movil."Id_Subprograma";


--
-- TOC entry 231 (class 1259 OID 16483)
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuarios (
    "Id_Usuario" integer NOT NULL,
    "Id_Perfilp" integer NOT NULL,
    "Usuario" character varying(255) NOT NULL,
    "Clave" character varying(255) NOT NULL,
    "Nombre" character varying(225) NOT NULL,
    "Apellido" character varying(400) NOT NULL,
    tipo_usuario character varying(255) NOT NULL,
    "Respuesta_1" character varying(255) NOT NULL,
    "Respuesta_2" character varying(255) NOT NULL,
    "Respuesta_3" character varying(255) NOT NULL,
    "Habilitado" integer NOT NULL,
    "Fecha" timestamp without time zone,
    "Id_Perfil_Movil" integer
);


ALTER TABLE public.usuarios OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 16489)
-- Name: usuarios_movil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.usuarios_movil (
    "Id_Usuario" integer NOT NULL,
    "Id_Perfilp" integer NOT NULL,
    "Usuario" character varying(255) NOT NULL,
    "Clave" character varying(255) NOT NULL,
    "Nombre" character varying(225) NOT NULL,
    "Apellido" character varying(400) NOT NULL,
    tipo_usuario character varying(255) NOT NULL,
    "Respuesta_1" character varying(255) NOT NULL,
    "Respuesta_2" character varying(255) NOT NULL,
    "Respuesta_3" character varying(255) NOT NULL,
    "Habilitado" integer DEFAULT 1 NOT NULL,
    "Fecha" timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL,
    trial413 character(1)
);


ALTER TABLE public.usuarios_movil OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 16497)
-- Name: usuarios_Id_Usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."usuarios_Id_Usuario_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."usuarios_Id_Usuario_seq" OWNER TO postgres;

--
-- TOC entry 3218 (class 0 OID 0)
-- Dependencies: 233
-- Name: usuarios_Id_Usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."usuarios_Id_Usuario_seq" OWNED BY public.usuarios_movil."Id_Usuario";


--
-- TOC entry 234 (class 1259 OID 16499)
-- Name: usuarios_Id_Usuario_seq1; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."usuarios_Id_Usuario_seq1"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."usuarios_Id_Usuario_seq1" OWNER TO postgres;

--
-- TOC entry 3219 (class 0 OID 0)
-- Dependencies: 234
-- Name: usuarios_Id_Usuario_seq1; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."usuarios_Id_Usuario_seq1" OWNED BY public.usuarios."Id_Usuario";


--
-- TOC entry 3000 (class 2604 OID 16603)
-- Name: animalsTest id_test; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."animalsTest" ALTER COLUMN id_test SET DEFAULT nextval('public."animalsTest_id_test_seq"'::regclass);


--
-- TOC entry 2983 (class 2604 OID 16501)
-- Name: cultivo_plaga id_plaga; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cultivo_plaga ALTER COLUMN id_plaga SET DEFAULT nextval('public.plagas_id_plaga_seq'::regclass);


--
-- TOC entry 2985 (class 2604 OID 16502)
-- Name: cultivos ID; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cultivos ALTER COLUMN "ID" SET DEFAULT nextval('public."cultivos_ID_seq"'::regclass);


--
-- TOC entry 2986 (class 2604 OID 16503)
-- Name: dataSet id_dataSet; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."dataSet" ALTER COLUMN "id_dataSet" SET DEFAULT nextval('public."dataSet_id_dataSet_seq"'::regclass);


--
-- TOC entry 2988 (class 2604 OID 16504)
-- Name: espacios Id_espacios; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.espacios ALTER COLUMN "Id_espacios" SET DEFAULT nextval('public."espacios_Id_espacios_seq"'::regclass);


--
-- TOC entry 2989 (class 2604 OID 16505)
-- Name: modelo id_modelo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.modelo ALTER COLUMN id_modelo SET DEFAULT nextval('public.modelo_id_modelo_seq'::regclass);


--
-- TOC entry 2990 (class 2604 OID 16506)
-- Name: modulo_movil Id_Modulos; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.modulo_movil ALTER COLUMN "Id_Modulos" SET DEFAULT nextval('public."modulo_Id_Modulos_seq"'::regclass);


--
-- TOC entry 2991 (class 2604 OID 16507)
-- Name: perfil Id_Perfil; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.perfil ALTER COLUMN "Id_Perfil" SET DEFAULT nextval('public."perfil_Id_Perfil_seq1"'::regclass);


--
-- TOC entry 2992 (class 2604 OID 16508)
-- Name: perfil_movil Id_Perfil; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.perfil_movil ALTER COLUMN "Id_Perfil" SET DEFAULT nextval('public."perfil_Id_Perfil_seq"'::regclass);


--
-- TOC entry 2993 (class 2604 OID 16509)
-- Name: privilegios_movil id_privilegio; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.privilegios_movil ALTER COLUMN id_privilegio SET DEFAULT nextval('public.privilegios_id_privilegio_seq'::regclass);


--
-- TOC entry 2994 (class 2604 OID 16510)
-- Name: programa_movil Id_Programa; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.programa_movil ALTER COLUMN "Id_Programa" SET DEFAULT nextval('public."programa_Id_Programa_seq"'::regclass);


--
-- TOC entry 2995 (class 2604 OID 16511)
-- Name: sub_programa_movil Id_Subprograma; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sub_programa_movil ALTER COLUMN "Id_Subprograma" SET DEFAULT nextval('public."sub_programa_Id_Subprograma_seq"'::regclass);


--
-- TOC entry 2996 (class 2604 OID 16512)
-- Name: usuarios Id_Usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN "Id_Usuario" SET DEFAULT nextval('public."usuarios_Id_Usuario_seq1"'::regclass);


--
-- TOC entry 2999 (class 2604 OID 16513)
-- Name: usuarios_movil Id_Usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios_movil ALTER COLUMN "Id_Usuario" SET DEFAULT nextval('public."usuarios_Id_Usuario_seq"'::regclass);


--
-- TOC entry 3200 (class 0 OID 16600)
-- Dependencies: 236
-- Data for Name: animalsTest; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."animalsTest" VALUES (1, true);
INSERT INTO public."animalsTest" VALUES (2, false);
INSERT INTO public."animalsTest" VALUES (3, false);


--
-- TOC entry 3168 (class 0 OID 16390)
-- Dependencies: 204
-- Data for Name: cultivo_plaga; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.cultivo_plaga VALUES (9, 14, 'Test 1-001', '2024-12-02 00:00:00-04');
INSERT INTO public.cultivo_plaga VALUES (10, 14, 'Test 1-002', '2024-12-02 20:32:38-04');
INSERT INTO public.cultivo_plaga VALUES (11, 14, 'Test 1-003', '2024-12-02 20:33:00-04');
INSERT INTO public.cultivo_plaga VALUES (12, 15, 'Test 2-002', '2024-12-02 20:34:21-04');
INSERT INTO public.cultivo_plaga VALUES (14, 15, 'Test 2-003', '2024-12-02 06:40:28-04');
INSERT INTO public.cultivo_plaga VALUES (16, 15, 'Test 2-004', '2024-12-02 07:25:46-04');
INSERT INTO public.cultivo_plaga VALUES (30, 15, 'Libre de Plagas', '2024-12-05 16:30:24-04');
INSERT INTO public.cultivo_plaga VALUES (31, 14, 'Test 004', '2025-02-21 17:28:10-04');
INSERT INTO public.cultivo_plaga VALUES (32, 14, 'Test 0004', '2025-03-21 22:03:01-04');
INSERT INTO public.cultivo_plaga VALUES (33, 14, 'Nada', '2025-03-27 12:01:09-04');
INSERT INTO public.cultivo_plaga VALUES (34, 15, 'Fresa', '2025-03-27 12:25:09-04');
INSERT INTO public.cultivo_plaga VALUES (35, 14, 'Lumpias', '2025-03-27 23:32:20-04');
INSERT INTO public.cultivo_plaga VALUES (36, 16, 'Agua', '2025-03-27 23:36:58-04');
INSERT INTO public.cultivo_plaga VALUES (37, 16, 'Aguacatess', '2025-03-27 23:37:26-04');
INSERT INTO public.cultivo_plaga VALUES (38, 19, 'Ajajja', '2025-03-27 23:38:37-04');
INSERT INTO public.cultivo_plaga VALUES (39, 23, 'Exitosos', '2025-03-27 23:42:20-04');
INSERT INTO public.cultivo_plaga VALUES (40, 23, 'Exitoso22', '2025-03-27 23:42:32-04');


--
-- TOC entry 3169 (class 0 OID 16394)
-- Dependencies: 205
-- Data for Name: cultivos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.cultivos VALUES (14, 'maiz', 'Hortaliza', 'poligono2', 200, '2024-11-15', 'fungicida', 200, 'movil', 'Orgánicos', 2, 'ninguna', '2024-11-14', '2025-07-10', 'Inundación', '2024-11-13 11:30:03.01716', '2024-11-07', 12);
INSERT INTO public.cultivos VALUES (15, 'fresa', 'Frutal', 'poligono1', 240, '2024-11-06', 'fungicida', 20, 'movil', 'Químicos', 1, 'ninguna', '2024-11-12', '2025-01-15', 'Goteo', '2024-11-13 11:31:59.945436', '2024-11-08', 13);
INSERT INTO public.cultivos VALUES (16, 'aguacate', 'Otro', 'poligono3', 47, '2024-11-12', 'fungicida', 30, 'movil', 'Minerales', 3, 'ninguna', '2024-11-12', '2025-01-11', 'Gravedad', '2024-11-13 11:34:54.640004', '2024-11-05', 14);
INSERT INTO public.cultivos VALUES (17, 'yuca', 'Hortaliza', 'poligono2', 180, '2024-11-01', 'fungicida', 33, 'movil', 'Biológicos', 1, 'ninguna', '2024-11-04', '2025-03-14', 'Aspersión', '2024-11-13 11:37:19.292203', '2024-11-06', 12);
INSERT INTO public.cultivos VALUES (18, 'Trigo', 'Cereal', 'poligono3', 120, '2024-11-14', 'fungicida', 30, 'movil', 'Orgánicos', 1, 'ninguna', '2024-11-11', '2025-03-19', 'Gravedad', '2024-11-13 11:39:33.544702', '2024-11-06', 14);
INSERT INTO public.cultivos VALUES (19, 'jamaica', 'Flor', 'poligono2', 140, '2024-11-12', 'fungicida', 200, 'movil', 'Orgánicos', 3, 'ninguna', '2024-11-01', '2025-01-09', 'Inundación', '2024-11-13 11:41:08.481224', '2024-11-06', 12);
INSERT INTO public.cultivos VALUES (20, 'auyama', 'Hortaliza', 'poligono1', 30, '2024-11-07', 'fungicida', 20, 'movil', 'Químicos', 5, 'ninguna', '2024-11-04', '2025-01-22', 'Surco', '2024-11-13 11:43:51.388083', '2024-10-30', 13);
INSERT INTO public.cultivos VALUES (22, 'xxx', 'xxx', 'poligono2', 0, '2024-11-15', 'xxx', 0, 'xxx', 'xxx', 0, 'xxx', '2024-11-15', '2024-11-15', 'xxx', '2024-11-15 00:00:00', '2024-11-15', 12);
INSERT INTO public.cultivos VALUES (23, 'xxx1', 'xxx1', 'poligono3', 1, '2024-11-15', 'xxx1', 0, 'xxx', 'xxx', 0, 'xxx', '2024-11-15', '2024-11-15', 'xxx', '2024-11-15 00:00:00', '2024-11-15', 12);


--
-- TOC entry 3171 (class 0 OID 16403)
-- Dependencies: 207
-- Data for Name: dataSet; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public."dataSet" VALUES (37, 'Necrosis Apical', 'La necrosis apical del mango (NAM) es una enfermedad bacteriana causada por la bacteria Pseudomonas syringae pv. syringae. Esta enfermedad afecta principalmente a los árboles de mango en climas mediterráneos y subtropicales.

- Síntomas
Manchas necróticas: Aparición de manchas necróticas en las yemas vegetativas y florales. Estas manchas pueden extenderse hacia el tallo y las hojas.

Exudado: En algunas ocasiones, se pueden observar gotas de exudado blanco lechoso en las yemas o panículas florales, que más tarde oscurecen y toman aspecto de resina.

Secado y muerte: Las yemas afectadas se secan y mueren, lo que puede llevar a la muerte de la rama o incluso del árbol en casos extremos', 'La necrosis apical del mango es causada por la bacteria Pseudomonas syringae pv. syringae, que pertenece a la familia Pseudomonadaceae', 'Tratamiento de la Enfermedad:

- Eliminación de partes afectadas: Cortar y eliminar las partes del árbol que presenten síntomas de la enfermedad, como las yemas y ramas afectadas.

- Aplicación de bactericidas:
Tratar las plantas afectadas con bactericidas específicos para controlar la bacteria', true);
INSERT INTO public."dataSet" VALUES (33, 'Hoja de Mango', 'El mango (Mangifera indica) es un árbol frutal tropical originario del sur de Asia, concretamente de la región entre India y Birmania', 'Este árbol pertenece a la familia de las Anacardiáceas', 'No Aplica', false);
INSERT INTO public."dataSet" VALUES (95, 'Candida Guillermondii', 'Candida guilliermondii no es una planta. Es una levadura, un tipo de hongo unicelular. Aunque el término "Candida" puede sonar similar a nombres de plantas, se refiere específicamente a un grupo de levaduras que pueden estar presentes en el medio ambiente, en alimentos, y también pueden causar infecciones en seres humanos y animales.

Las levaduras, incluyendo Candida guilliermondii, son organismos importantes tanto en aplicaciones industriales (como la fermentación) como en investigaciones médicas y biotecnológicas. También tienen aplicaciones en el control biológico en la agricultura, como mencioné antes, pero no son plantas.', 'Candida guilliermondii pertenece a la familia Debaryomycetaceae. Esta familia incluye varias especies de levaduras del género Candida, que son importantes tanto en contextos médicos debido a su capacidad para causar infecciones, como en aplicaciones industriales y biotecnológicas.', 'El tratamiento para Candida guilliermondii generalmente implica el uso de medicamentos antifúngicos. Aquí hay algunas opciones comunes:

Fluconazol: Un medicamento antifúngico comúnmente utilizado, aunque algunas cepas de Candida guilliermondii pueden mostrar resistencia.

Anfotericina B: Este medicamento es efectivo contra muchas cepas de Candida, incluyendo Candida guilliermondii.

Caso de resistencia: En casos donde hay resistencia a los tratamientos anteriores, se pueden considerar otros antifúngicos como el anidulafungina.

Es importante que el tratamiento sea supervisado por un profesional de la salud para asegurar su efectividad y evitar complicaciones.', true);
INSERT INTO public."dataSet" VALUES (88, 'Hongo de Suelo', 'los hongos del suelo pueden afectar a la planta Zamioculcas zamiifolia (también conocida como Zamioculca). Aquí tienes una explicación más detallada:

Pudrición de raíces: Los hongos del suelo pueden infectar las raíces de la planta, causando que se pudran y se deterioren. Esto afecta la capacidad de la planta para absorber agua y nutrientes.

Hojas caídas y amarillentas: A medida que las raíces se pudren, la planta no puede mantenerse saludable, lo que lleva a que las hojas se vuelvan amarillas y caigan.

Muerte de la planta: Si la infección no se trata, la planta puede llegar a morir debido a la falta de nutrientes y agua adecuados.

Condiciones favorables: Los hongos prosperan en suelos húmedos y mal drenados, lo que crea un ambiente ideal para su crecimiento.

Mancha oscura: Esta es una enfermedad fúngica que causa manchas oscuras e irregulares en las hojas y tallos de la planta. La enfermedad se propaga en condiciones de alta humedad y puede ser altamente infecciosa.', 'Los hongos del suelo pertenecen a varias familias dentro del reino Fungi. Los principales grupos de hongos del suelo incluyen:

Ascomicetos (Ascomycota): Esta es la familia más grande del reino Fungi y contiene muchas especies que desempeñan un papel importante en la descomposición de materia orgánica.

Basidiomicetos (Basidiomycota): Estos hongos también son importantes descomponedores y pueden formar relaciones simbióticas con las raíces de las plantas (micorrizas).

Zigomicetos (Zygomycota): Estos hongos son conocidos por su capacidad para descomponer materia orgánica y pueden encontrarse en suelos ricos en materia orgánica.

Quitridiomicetos (Chytridiomycota): Aunque muchos de estos hongos son acuáticos, algunos pueden encontrarse en suelos húmedos y desempeñan un papel en la descomposición de materia orgánica.', 'Para tratar los hongos del suelo que afectan a tu Zamioculcas, puedes seguir estos pasos:

Mejorar el Drenaje del Suelo: Asegúrate de que el suelo tenga un buen drenaje para evitar el encharcamiento, que favorece el crecimiento de hongos. Puedes modificar la mezcla para macetas para aumentar la aireación y el drenaje1.

Ajustar los Hábitos de Riego: Reduce la frecuencia de riego y asegúrate de que el suelo se seque entre sesiones de riego. Esto ayuda a prevenir condiciones húmedas que favorecen los hongos2.

Aplicar Fungicidas: Utiliza un fungicida adecuado para controlar la propagación del hongo. Sigue las instrucciones del fabricante para aplicarlo correctamente2.

Mejorar la Ventilación: Aumenta la circulación de aire alrededor de la planta para reducir la retención de humedad. Esto puede ayudar a desalentar el crecimiento fúngico2.

Eliminar las Hojas Afectadas: Retira cualquier hoja afectada para evitar que el hongo se propague a otras partes de la planta.

Es importante actuar rápidamente para controlar la infestación y minimizar el daño a tu planta.', true);
INSERT INTO public."dataSet" VALUES (92, 'Hoja de Tuatua', 'La hoja de tuatua, también conocida como Jatropha gossypiifolia o tua tua, es una planta herbácea silvestre originaria de Latinoamérica. Esta planta es conocida por sus múltiples propiedades medicinales.

Características de la Hoja de Tuatua
Hojas: Son grandes, de color verde oscuro y brillante, y pueden tener un tono violeta en algunas ocasiones.

Tallo: Es cilíndrico, muy ramificado y puede alcanzar hasta 2.5 metros de altura.

Flores: Son de color purpúreo y se agrupan en inflorescencias llamativas.

Frutos: Son pequeñas cápsulas triloculares que van oscureciéndose a medida que maduran.

Usos y Beneficios
Antibiótico Natural: Las hojas y el látex del tallo se utilizan para tratar infecciones cutáneas, bucales y oculares.

Cicatrizante: El látex del tallo se usa para preparar infusiones que actúan como cicatrizantes y restauradores de tejidos.

Diurético y Purgante: Se utiliza para tratar problemas urinarios y como purgante.

Antinflamatorio: Ayuda a reducir la inflamación en diversas partes del cuerpo.

Es importante tener en cuenta que, aunque la tua tua tiene muchas propiedades beneficiosas, también puede ser tóxica si se consume en grandes cantidades', 'La Jatropha gossypiifolia, comúnmente conocida como tua tua, pertenece a la familia Euphorbiaceae. Esta familia incluye muchas plantas que son conocidas por sus propiedades medicinales y también por sus toxinas, por lo que se deben manejar con cuidado. Algunos otros miembros notables de esta familia incluyen la poinsettia (Euphorbia pulcherrima) y el árbol de ricino (Ricinus communis).', 'No Aplica', false);
INSERT INTO public."dataSet" VALUES (94, 'Tithonia Diversifolia', 'Tithonia diversifolia, también conocida como girasol mexicano, girasol japonés o acahual. Es originaria de México y Centroamérica, pero se ha naturalizado en muchas regiones tropicales y subtropicales del mundo debido a su crecimiento vigoroso y su capacidad para adaptarse a diferentes condiciones ambientales.

Características de Tithonia diversifolia
Hojas: Son grandes, lobuladas y de color verde oscuro. Las hojas pueden tener entre 10 a 40 cm de largo.

Flores: Produce flores grandes y llamativas de color amarillo anaranjado, similares a las de los girasoles. Las flores atraen a polinizadores como abejas y mariposas.

Altura: Puede crecer entre 2 y 3 metros de altura, formando arbustos densos.

Tallo: Los tallos son gruesos, leñosos y a menudo ramificados.', 'Tithonia diversifolia, conocida comúnmente como girasol mexicano, pertenece a la familia Asteraceae. Esta familia, también conocida como la familia de las compuestas, incluye una amplia variedad de plantas que son populares tanto por su valor ornamental como por sus propiedades medicinales y ecológicas. Algunos miembros notables de esta familia incluyen el girasol (Helianthus annuus), la margarita (Bellis perennis) y el diente de león (Taraxacum officinale).', 'No Aplica', false);
INSERT INTO public."dataSet" VALUES (89, 'Zamioculca', 'La Zamioculca (Zamioculcas zamiifolia), también conocida comúnmente como "planta ZZ" o "planta de la eternidad", es una planta tropical nativa de África oriental, específicamente de regiones como Kenia y Tanzania. Es muy apreciada como planta de interior debido a su resistencia y fácil mantenimiento.

Características de la Zamioculca
Hojas: Tiene hojas pinnadas, brillantes y de color verde oscuro que crecen en pecíolos gruesos.

Tamaño: Puede alcanzar una altura de entre 45 cm a 60 cm, aunque en condiciones ideales puede crecer más.

Rizomas: Crece a partir de rizomas gruesos y subterráneos que almacenan agua, lo que permite a la planta sobrevivir en condiciones de sequía.

Floración: Las flores son raras y poco vistosas, generalmente ocultas entre las hojas.', 'La Zamioculcas zamiifolia, comúnmente conocida como Zamioculca o planta ZZ, pertenece a la familia Araceae. Esta familia, también conocida como la familia de los aroides, incluye muchas otras plantas ornamentales populares como los anturios, las alocasia y las dieffenbachias.', 'No Aplica', false);
INSERT INTO public."dataSet" VALUES (93, 'Roya', 'La roya es una enfermedad fúngica que puede afectar a muchas plantas, incluyendo la tua tua (Jatropha gossypiifolia). Esta enfermedad se caracteriza por la aparición de manchas de color amarillo, naranja o marrón en las hojas, tallos y frutos de la planta. Las manchas suelen estar acompañadas por un polvo de esporas característico del hongo que causa la roya.

Síntomas de la Roya en la Planta Tuatua
Manchas en las hojas: Manchas de color amarillo, naranja o marrón.

Pústulas polvorientas: Pequeñas protuberancias polvorientas en el envés de las hojas.

Decoloración y marchitamiento: Las hojas pueden decolorarse y marchitarse.

Caída prematura de las hojas: En casos severos, las hojas pueden caer antes de tiempo', 'La plaga de roya, que afecta a la planta tuatua, es causada por diversos hongos que pertenecen a diferentes familias fúngicas. En general, las royas son causadas por hongos de las familias Pucciniaceae y Uropyxidaceae. Estos hongos son altamente especializados y pueden afectar a una amplia variedad de plantas, incluidos cultivos importantes.', 'Para tratar la plaga de roya en plantas como la tuatua, puedes seguir estos pasos:

Eliminación de Plantas Afectadas: Retira y destruye las hojas infectadas para reducir la cantidad de esporas en el ambiente.

Fungicidas: Aplica fungicidas específicos para roya, como aquellos que contienen azufre o cobre. Es importante seguir las instrucciones del fabricante para la aplicación adecuada.

Rotación de Cultivos: Evita plantar especies susceptibles en el mismo lugar de cultivo durante varios años para reducir la acumulación de esporas en el suelo.

Mejora de la Circulación de Aire: Asegúrate de que las plantas tengan suficiente espacio para una buena circulación de aire, lo cual ayuda a reducir la humedad y la propagación de hongos.

Riego Adecuado: Evita el riego por aspersión y opta por sistemas de riego que mantengan las hojas secas, como el riego por goteo.

Estos métodos pueden ayudar a controlar y prevenir la roya en las plantas de tuatua.', true);
INSERT INTO public."dataSet" VALUES (86, 'Phyllosticta psidiicola', 'Phyllosticta psidiicola es un hongo patógeno. Este hongo causa manchas rojizas en las hojas y frutos de la guayaba, lo que puede disminuir su valor comercial2. Las manchas suelen ser visibles como áreas hundidas de diversos tamaños en los frutos y hojas', 'Phyllosticta psidiicola pertenece a la familia Botryosphaeriaceae. Esta familia incluye muchos hongos que son patógenos de plantas y pueden causar diversas enfermedades, como manchas foliares, cancro y pudriciones.', 'Para tratar la infección por Phyllosticta psidiicola en las plantas de guayaba, puedes seguir estos pasos:

Control Cultural: Mantén el área de cultivo limpia eliminando restos de plantas infectadas y desechos de cultivo. Esto ayuda a reducir la cantidad de esporas en el suelo.

Riego Adecuado: Evita el riego excesivo y asegúrate de que el suelo tenga un buen drenaje para evitar condiciones de humedad que favorecen el crecimiento del hongo.

Uso de Fungicidas: Aplica fungicidas adecuados, como aquellos que contienen hexaconazol (0.1%), propiconazol (0.1%) o carbendazim + mancozeb. Pulveriza las plantas cuando se observe la enfermedad por primera vez y repite la aplicación dos veces con un intervalo de 20 días.

Manejo de pH del Suelo: Aumenta el pH del suelo aplicando cal, lo que puede ayudar a reducir la incidencia de la enfermedad.

Control Biológico: Si está disponible, utiliza métodos de control biológico para reducir la población del hongo.', true);
INSERT INTO public."dataSet" VALUES (111, 'Hoja de Guayaba', 'La hoja de guayaba proviene del árbol de guayabo (Psidium guajava), que es originario de regiones tropicales. Estas hojas tienen una gran importancia tanto en la medicina tradicional como en la cocina. Suelen ser de color verde oscuro, alargadas y aromáticas.

En términos medicinales, las hojas de guayaba son conocidas por sus propiedades antioxidantes, antiinflamatorias y antimicrobianas. Tradicionalmente, se han utilizado para tratar problemas digestivos, como la diarrea, y para reducir el azúcar en la sangre en personas con diabetes. También se emplean en infusiones para aliviar dolores menstruales y como enjuague bucal natural para combatir infecciones o mejorar la salud bucal.', '(Psidium guajava), y por ende sus hojas, pertenece a la familia botánica Myrtaceae, conocida comúnmente como la familia del mirto. Esta familia incluye una gran variedad de plantas, muchas de las cuales son aromáticas y tienen aplicaciones medicinales, como el eucalipto y el clavo', 'No Aplica', false);
INSERT INTO public."dataSet" VALUES (117, 'retsy', 'jkjk', 'jkjkj', 'No Aplica', false);
INSERT INTO public."dataSet" VALUES (118, 'testh', 'hjh', 'jhjhj', 'hjhj', true);
INSERT INTO public."dataSet" VALUES (113, 'random', 'N/A', 'N/A', 'No Aplica', false);


--
-- TOC entry 3174 (class 0 OID 16413)
-- Dependencies: 210
-- Data for Name: espacios; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.espacios VALUES (12, 'poligono2', 'Activo', 'pozo', 'ninguno', 'ninguna', 'Goteo', 1, 29);
INSERT INTO public.espacios VALUES (13, 'poligono1', 'Activo', 'tanque de agua', 'ninguno', 'ninguna', 'Aspercion', 1, 28);
INSERT INTO public.espacios VALUES (14, 'poligono3', 'Activo', 'tanque', 'fresa', 'ninguna', 'Inundacion', 1, 31);


--
-- TOC entry 3176 (class 0 OID 16422)
-- Dependencies: 212
-- Data for Name: modelo; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.modelo VALUES (11, NULL, NULL, NULL, '03:33:48');
INSERT INTO public.modelo VALUES (12, NULL, NULL, NULL, '03:37:03');
INSERT INTO public.modelo VALUES (13, NULL, NULL, NULL, '00:10:43');
INSERT INTO public.modelo VALUES (14, NULL, NULL, NULL, '13:10:41');
INSERT INTO public.modelo VALUES (15, NULL, NULL, NULL, '00:02:39');
INSERT INTO public.modelo VALUES (16, NULL, NULL, NULL, '00:00:00');
INSERT INTO public.modelo VALUES (17, NULL, NULL, NULL, '00:02:27');
INSERT INTO public.modelo VALUES (18, NULL, NULL, NULL, '00:02:10');
INSERT INTO public.modelo VALUES (19, NULL, NULL, NULL, '00:02:05');
INSERT INTO public.modelo VALUES (20, NULL, NULL, NULL, '00:02:10');
INSERT INTO public.modelo VALUES (21, NULL, NULL, NULL, '00:00:08');
INSERT INTO public.modelo VALUES (22, NULL, NULL, NULL, '00:00:05');
INSERT INTO public.modelo VALUES (23, NULL, NULL, NULL, '00:00:05');
INSERT INTO public.modelo VALUES (24, NULL, NULL, NULL, '00:00:08');
INSERT INTO public.modelo VALUES (25, NULL, NULL, NULL, '00:00:05');
INSERT INTO public.modelo VALUES (26, NULL, NULL, NULL, '00:00:08');
INSERT INTO public.modelo VALUES (27, NULL, NULL, NULL, '00:00:05');
INSERT INTO public.modelo VALUES (28, NULL, NULL, NULL, '00:00:25');
INSERT INTO public.modelo VALUES (29, NULL, NULL, NULL, '00:00:08');
INSERT INTO public.modelo VALUES (30, NULL, NULL, NULL, '00:00:42');
INSERT INTO public.modelo VALUES (31, NULL, NULL, NULL, '00:00:39');
INSERT INTO public.modelo VALUES (32, NULL, NULL, NULL, '00:00:41');


--
-- TOC entry 3178 (class 0 OID 16430)
-- Dependencies: 214
-- Data for Name: modulo_movil; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3180 (class 0 OID 16435)
-- Dependencies: 216
-- Data for Name: notificaciones_movil; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.notificaciones_movil VALUES (1, true, false);


--
-- TOC entry 3181 (class 0 OID 16438)
-- Dependencies: 217
-- Data for Name: perfil; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.perfil VALUES (1, 'ADMINISTRADOR', 'Activo');
INSERT INTO public.perfil VALUES (163, 'OPERADOR', 'Activo');
INSERT INTO public.perfil VALUES (164, 'INSPECTOR', 'Activo');
INSERT INTO public.perfil VALUES (165, 'PERFILII', 'Activo');
INSERT INTO public.perfil VALUES (5, 'test', 'activo');


--
-- TOC entry 3185 (class 0 OID 16454)
-- Dependencies: 221
-- Data for Name: perfil_modulo_movil; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- TOC entry 3182 (class 0 OID 16444)
-- Dependencies: 218
-- Data for Name: perfil_movil; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.perfil_movil VALUES (16, 'Administrador Movil', 'activo', NULL);
INSERT INTO public.perfil_movil VALUES (34, 'Supervisor Movil', 'activo', NULL);
INSERT INTO public.perfil_movil VALUES (33, 'Operador Movil', 'activo', NULL);


--
-- TOC entry 3186 (class 0 OID 16457)
-- Dependencies: 222
-- Data for Name: perfil_programa_movil; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.perfil_programa_movil VALUES (16, 2, NULL);
INSERT INTO public.perfil_programa_movil VALUES (16, 3, NULL);
INSERT INTO public.perfil_programa_movil VALUES (16, 4, NULL);
INSERT INTO public.perfil_programa_movil VALUES (16, 5, NULL);
INSERT INTO public.perfil_programa_movil VALUES (16, 5, NULL);
INSERT INTO public.perfil_programa_movil VALUES (16, 1, NULL);
INSERT INTO public.perfil_programa_movil VALUES (34, 4, NULL);
INSERT INTO public.perfil_programa_movil VALUES (34, 5, NULL);
INSERT INTO public.perfil_programa_movil VALUES (34, 5, NULL);
INSERT INTO public.perfil_programa_movil VALUES (34, 1, NULL);
INSERT INTO public.perfil_programa_movil VALUES (33, 2, NULL);
INSERT INTO public.perfil_programa_movil VALUES (33, 5, NULL);
INSERT INTO public.perfil_programa_movil VALUES (33, 5, NULL);
INSERT INTO public.perfil_programa_movil VALUES (33, 1, NULL);


--
-- TOC entry 3187 (class 0 OID 16460)
-- Dependencies: 223
-- Data for Name: perfil_subprograma_movil; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.perfil_subprograma_movil VALUES (16, 1, NULL);
INSERT INTO public.perfil_subprograma_movil VALUES (16, 2, NULL);
INSERT INTO public.perfil_subprograma_movil VALUES (16, 3, NULL);
INSERT INTO public.perfil_subprograma_movil VALUES (34, 1, NULL);
INSERT INTO public.perfil_subprograma_movil VALUES (34, 2, NULL);
INSERT INTO public.perfil_subprograma_movil VALUES (34, 3, NULL);
INSERT INTO public.perfil_subprograma_movil VALUES (33, 1, NULL);
INSERT INTO public.perfil_subprograma_movil VALUES (33, 3, NULL);


--
-- TOC entry 3189 (class 0 OID 16465)
-- Dependencies: 225
-- Data for Name: privilegios_movil; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.privilegios_movil VALUES (15, 16, 'true', 'true', 'true', 'true', 'true', NULL);
INSERT INTO public.privilegios_movil VALUES (33, 34, 'true', 'false', 'false', 'true', 'false', NULL);
INSERT INTO public.privilegios_movil VALUES (32, 33, 'true', 'false', 'false', 'false', 'false', NULL);


--
-- TOC entry 3191 (class 0 OID 16473)
-- Dependencies: 227
-- Data for Name: programa_movil; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.programa_movil VALUES (1, 'Inicio', NULL);
INSERT INTO public.programa_movil VALUES (2, 'Animales', NULL);
INSERT INTO public.programa_movil VALUES (3, 'Cultivos', NULL);
INSERT INTO public.programa_movil VALUES (4, 'Usuarios', NULL);
INSERT INTO public.programa_movil VALUES (6, 'Configuracion', NULL);
INSERT INTO public.programa_movil VALUES (5, 'IA', NULL);


--
-- TOC entry 3193 (class 0 OID 16478)
-- Dependencies: 229
-- Data for Name: sub_programa_movil; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.sub_programa_movil VALUES (1, 5, 'Detector', NULL);
INSERT INTO public.sub_programa_movil VALUES (2, 5, 'Modelos', NULL);
INSERT INTO public.sub_programa_movil VALUES (3, 6, 'Conexion', NULL);


--
-- TOC entry 3195 (class 0 OID 16483)
-- Dependencies: 231
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.usuarios VALUES (1, 1, 'ADMINISTRADOR', '$2y$10$sVZfQflNKGsygthGDr4X4u3fsu3aGMlIkcdZFsHic5.9dD.MzqJze', 'ADMINISTRADOR', 'ADMINISTRADOR', 'prueba', 'ADMINISTRADOR', 'ADMINISTRADOR', 'ADMINISTRADOR', 1, '2024-01-29 15:06:10', 16);
INSERT INTO public.usuarios VALUES (74, 164, 'INSPECTOR', '$2y$10$3wcvicJGEH.NRmWHSu9w0OrnuMJ6fhRq0qXUM7x94I9l1ndMVo83G', 'INSPECTOR', 'INSPECTOR', 'INSPECTOR', 'INSPECTOR', 'INSPECTOR', 'INSPECTOR', 1, '2024-03-31 22:12:20', 34);
INSERT INTO public.usuarios VALUES (73, 163, 'OPERADOR', '$2y$10$eSTYkHU1OsS8JSP7Rd98TuGG1S71STapbGK6EXrrWfBNB3gK2dfMq', 'OPERADOR', 'OPERADOR', 'OPERADOR', 'OPERADOR', 'OPERADOR', 'OPERADOR', 1, '2024-03-31 22:11:57', 33);


--
-- TOC entry 3196 (class 0 OID 16489)
-- Dependencies: 232
-- Data for Name: usuarios_movil; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.usuarios_movil VALUES (5, 16, 'Ddaniel', '123456', 'Daniel', 'Molnar', 'admin', 'res1', 'res2', 'res3', 1, '2002-12-17 00:00:00', NULL);


--
-- TOC entry 3220 (class 0 OID 0)
-- Dependencies: 235
-- Name: animalsTest_id_test_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."animalsTest_id_test_seq"', 1, false);


--
-- TOC entry 3221 (class 0 OID 0)
-- Dependencies: 202
-- Name: costo_fijo_id_fijo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.costo_fijo_id_fijo_seq', 1, false);


--
-- TOC entry 3222 (class 0 OID 0)
-- Dependencies: 203
-- Name: costo_variable_id_variable_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.costo_variable_id_variable_seq', 1, false);


--
-- TOC entry 3223 (class 0 OID 0)
-- Dependencies: 206
-- Name: cultivos_ID_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."cultivos_ID_seq"', 20, true);


--
-- TOC entry 3224 (class 0 OID 0)
-- Dependencies: 208
-- Name: dataSet_id_dataSet_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."dataSet_id_dataSet_seq"', 122, true);


--
-- TOC entry 3225 (class 0 OID 0)
-- Dependencies: 209
-- Name: datos_veterinarios_id_veterinario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.datos_veterinarios_id_veterinario_seq', 1, false);


--
-- TOC entry 3226 (class 0 OID 0)
-- Dependencies: 211
-- Name: espacios_Id_espacios_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."espacios_Id_espacios_seq"', 14, true);


--
-- TOC entry 3227 (class 0 OID 0)
-- Dependencies: 213
-- Name: modelo_id_modelo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.modelo_id_modelo_seq', 32, true);


--
-- TOC entry 3228 (class 0 OID 0)
-- Dependencies: 215
-- Name: modulo_Id_Modulos_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."modulo_Id_Modulos_seq"', 1, false);


--
-- TOC entry 3229 (class 0 OID 0)
-- Dependencies: 219
-- Name: perfil_Id_Perfil_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."perfil_Id_Perfil_seq"', 130, true);


--
-- TOC entry 3230 (class 0 OID 0)
-- Dependencies: 220
-- Name: perfil_Id_Perfil_seq1; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."perfil_Id_Perfil_seq1"', 5, true);


--
-- TOC entry 3231 (class 0 OID 0)
-- Dependencies: 224
-- Name: plagas_id_plaga_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.plagas_id_plaga_seq', 40, true);


--
-- TOC entry 3232 (class 0 OID 0)
-- Dependencies: 226
-- Name: privilegios_id_privilegio_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.privilegios_id_privilegio_seq', 130, true);


--
-- TOC entry 3233 (class 0 OID 0)
-- Dependencies: 228
-- Name: programa_Id_Programa_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."programa_Id_Programa_seq"', 6, true);


--
-- TOC entry 3234 (class 0 OID 0)
-- Dependencies: 230
-- Name: sub_programa_Id_Subprograma_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."sub_programa_Id_Subprograma_seq"', 5, true);


--
-- TOC entry 3235 (class 0 OID 0)
-- Dependencies: 233
-- Name: usuarios_Id_Usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."usuarios_Id_Usuario_seq"', 8, true);


--
-- TOC entry 3236 (class 0 OID 0)
-- Dependencies: 234
-- Name: usuarios_Id_Usuario_seq1; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."usuarios_Id_Usuario_seq1"', 9, true);


--
-- TOC entry 3028 (class 2606 OID 16608)
-- Name: animalsTest animalsTest_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."animalsTest"
    ADD CONSTRAINT "animalsTest_pkey" PRIMARY KEY (id_test);


--
-- TOC entry 3004 (class 2606 OID 16517)
-- Name: cultivos cultivos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cultivos
    ADD CONSTRAINT cultivos_pkey PRIMARY KEY ("ID");


--
-- TOC entry 3006 (class 2606 OID 16519)
-- Name: dataSet dataSet_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public."dataSet"
    ADD CONSTRAINT "dataSet_pkey" PRIMARY KEY ("id_dataSet");


--
-- TOC entry 3008 (class 2606 OID 16521)
-- Name: modelo modelo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.modelo
    ADD CONSTRAINT modelo_pkey PRIMARY KEY (id_modelo);


--
-- TOC entry 3012 (class 2606 OID 16523)
-- Name: notificaciones_movil notificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones_movil
    ADD CONSTRAINT notificaciones_pkey PRIMARY KEY (id_notificacion);


--
-- TOC entry 3014 (class 2606 OID 16525)
-- Name: perfil perfil_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.perfil
    ADD CONSTRAINT perfil_pkey PRIMARY KEY ("Id_Perfil");


--
-- TOC entry 3010 (class 2606 OID 16527)
-- Name: modulo_movil pk_modulo; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.modulo_movil
    ADD CONSTRAINT pk_modulo PRIMARY KEY ("Id_Modulos");


--
-- TOC entry 3016 (class 2606 OID 16529)
-- Name: perfil_movil pk_perfil; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.perfil_movil
    ADD CONSTRAINT pk_perfil PRIMARY KEY ("Id_Perfil");


--
-- TOC entry 3018 (class 2606 OID 16531)
-- Name: privilegios_movil pk_privilegios; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.privilegios_movil
    ADD CONSTRAINT pk_privilegios PRIMARY KEY (id_privilegio);


--
-- TOC entry 3020 (class 2606 OID 16533)
-- Name: programa_movil pk_programa; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.programa_movil
    ADD CONSTRAINT pk_programa PRIMARY KEY ("Id_Programa");


--
-- TOC entry 3022 (class 2606 OID 16535)
-- Name: sub_programa_movil pk_sub_programa; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sub_programa_movil
    ADD CONSTRAINT pk_sub_programa PRIMARY KEY ("Id_Subprograma");


--
-- TOC entry 3026 (class 2606 OID 16537)
-- Name: usuarios_movil pk_usuarios; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios_movil
    ADD CONSTRAINT pk_usuarios PRIMARY KEY ("Id_Usuario");


--
-- TOC entry 3002 (class 2606 OID 16539)
-- Name: cultivo_plaga plagas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cultivo_plaga
    ADD CONSTRAINT plagas_pkey PRIMARY KEY (id_plaga);


--
-- TOC entry 3024 (class 2606 OID 16541)
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY ("Id_Usuario");


--
-- TOC entry 3029 (class 2606 OID 16542)
-- Name: cultivo_plaga cult_plaga; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cultivo_plaga
    ADD CONSTRAINT cult_plaga FOREIGN KEY (id) REFERENCES public.cultivos("ID") NOT VALID;


--
-- TOC entry 3030 (class 2606 OID 16547)
-- Name: modulo_movil fk_modulo_sub_programa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.modulo_movil
    ADD CONSTRAINT fk_modulo_sub_programa FOREIGN KEY ("Id_Subprograma") REFERENCES public.sub_programa_movil("Id_Subprograma");


--
-- TOC entry 3031 (class 2606 OID 16552)
-- Name: perfil_modulo_movil fk_perfil_modulo_modulo; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.perfil_modulo_movil
    ADD CONSTRAINT fk_perfil_modulo_modulo FOREIGN KEY ("Id_Modulo") REFERENCES public.modulo_movil("Id_Modulos");


--
-- TOC entry 3032 (class 2606 OID 16557)
-- Name: perfil_modulo_movil fk_perfil_modulo_perfil; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.perfil_modulo_movil
    ADD CONSTRAINT fk_perfil_modulo_perfil FOREIGN KEY ("Id_Perfil") REFERENCES public.perfil_movil("Id_Perfil");


--
-- TOC entry 3033 (class 2606 OID 16562)
-- Name: perfil_programa_movil fk_perfil_programa_perfil; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.perfil_programa_movil
    ADD CONSTRAINT fk_perfil_programa_perfil FOREIGN KEY ("Id_Perfil") REFERENCES public.perfil_movil("Id_Perfil");


--
-- TOC entry 3034 (class 2606 OID 16567)
-- Name: perfil_programa_movil fk_perfil_programa_programa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.perfil_programa_movil
    ADD CONSTRAINT fk_perfil_programa_programa FOREIGN KEY ("Id_Programa") REFERENCES public.programa_movil("Id_Programa");


--
-- TOC entry 3035 (class 2606 OID 16572)
-- Name: perfil_subprograma_movil fk_perfil_subprograma_perfil; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.perfil_subprograma_movil
    ADD CONSTRAINT fk_perfil_subprograma_perfil FOREIGN KEY ("Id_Perfil") REFERENCES public.perfil_movil("Id_Perfil");


--
-- TOC entry 3036 (class 2606 OID 16577)
-- Name: perfil_subprograma_movil fk_perfil_subprograma_sub_programa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.perfil_subprograma_movil
    ADD CONSTRAINT fk_perfil_subprograma_sub_programa FOREIGN KEY ("Id_Subprograma") REFERENCES public.sub_programa_movil("Id_Subprograma") NOT VALID;


--
-- TOC entry 3037 (class 2606 OID 16582)
-- Name: privilegios_movil fk_privilegios_perfil; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.privilegios_movil
    ADD CONSTRAINT fk_privilegios_perfil FOREIGN KEY (id_perfil) REFERENCES public.perfil_movil("Id_Perfil");


--
-- TOC entry 3039 (class 2606 OID 16587)
-- Name: usuarios_movil fk_usuarios_perfil; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.usuarios_movil
    ADD CONSTRAINT fk_usuarios_perfil FOREIGN KEY ("Id_Perfilp") REFERENCES public.perfil_movil("Id_Perfil");


--
-- TOC entry 3038 (class 2606 OID 16592)
-- Name: sub_programa_movil pk_subprograma_programa; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sub_programa_movil
    ADD CONSTRAINT pk_subprograma_programa FOREIGN KEY ("Id_ProgramaS") REFERENCES public.programa_movil("Id_Programa") NOT VALID;


-- Completed on 2025-03-28 10:16:18 -04

--
-- PostgreSQL database dump complete
--


