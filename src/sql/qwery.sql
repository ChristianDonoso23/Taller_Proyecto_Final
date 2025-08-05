CREATE DATABASE project_db
    DEFAULT CHARACTER SET = 'utf8mb4';
CREATE TABLE author(  
    id int AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    orcid VARCHAR(20) NOT NULL,
    affiliation VARCHAR(50) NOT NULL
);

CREATE TABLE publication(
    id int AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(100) NOT NULL,
    publication_date DATE NOT NULL,
    author_id int NOT NULL,
    type ENUM('book', 'article') NOT NULL,
    FOREIGN KEY (author_id) REFERENCES author(id)
        ON DELETE CASCADE
);

CREATE TABLE book(
    publication_id int AUTO_INCREMENT PRIMARY KEY,
    ISBN VARCHAR(20) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    edition int NOT NULL,
    FOREIGN KEY (publication_id) REFERENCES publication(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE article(
    publication_id int AUTO_INCREMENT PRIMARY KEY,
    doi VARCHAR(20) NOT NULL,
    abstract VARCHAR(300) NOT NULL,
    keywords VARCHAR(50) NOT NULL,
    indexaTion VARCHAR(20) NOT NULL,
    magazine VARCHAR(50) NOT NULL,
    area VARCHAR(50) NOT NULL,
    FOREIGN KEY (publication_id) REFERENCES publication(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Insertar nuevos autores
INSERT INTO author (first_name, last_name, username, email, password, orcid, affiliation)
VALUES 
('Lucía', 'Valverde', 'lvalverde', 'lucia.valverde@ute.edu.ec', 'luciaPass123', '0000-0006-1111-2222', 'UTE'),
('Marco', 'Santacruz', 'msantacruz', 'marco.santacruz@epn.edu.ec', 'marcoClave456', '0000-0007-3333-4444', 'EPN'),
('Daniela', 'Quishpe', 'dquishpe', 'daniela.quishpe@yachay.edu.ec', 'daniela789', '0000-0008-5555-6666', 'Yachay Tech'),
('Esteban', 'Cevallos', 'escevallos', 'esteban.cevallos@espol.edu.ec', 'estebanPass', '0000-0009-7777-8888', 'ESPOL'),
('Natalia', 'Mera', 'nmera', 'natalia.mera@ucuenca.edu.ec', 'nataliaClave', '0000-0010-9999-0000', 'U Cuenca');


-- Insertar nuevas publicaciones
INSERT INTO publication (title, description, publication_date, author_id, type)
VALUES
('Cloud Computing Basics', 'Fundamentos del cómputo en la nube.', '2024-01-12', 1, 'book'),
('Machine Learning Trends', 'Nuevas tendencias en aprendizaje automático.', '2024-02-08', 2, 'article'),
('DevOps Culture', 'Transformación digital con DevOps.', '2024-03-15', 3, 'book'),
('Blockchain Aplicado', 'Uso práctico de blockchain en logística.', '2024-04-10', 4, 'article'),
('Python para Científicos', 'Aplicaciones científicas con Python.', '2024-05-25', 5, 'book'),
('IA Ética y Sociedad', 'Impacto ético de la inteligencia artificial.', '2024-06-18', 1, 'article'),
('Redes Neuronales Convolucionales', 'Teoría y práctica de CNNs.', '2024-07-20', 2, 'book'),
('Computación Cuántica', 'Introducción a los qubits y puertas cuánticas.', '2024-08-30', 3, 'article'),
('Big Data en Salud', 'Análisis de datos clínicos con Big Data.', '2024-09-22', 4, 'book'),
('Ciberseguridad Moderna', 'Amenazas actuales y medidas defensivas.', '2024-10-14', 5, 'article'),
('Visualización de Datos', 'Herramientas modernas para análisis visual.', '2024-11-10', 1, 'book'),
('Técnicas de NLP', 'Procesamiento de lenguaje natural avanzado.', '2024-12-05', 2, 'article'),
('Automatización Industrial', 'Robots, sensores y sistemas inteligentes.', '2025-01-19', 3, 'book'),
('Realidad Aumentada en Educación', 'Uso de RA en el aula.', '2025-02-14', 4, 'article'),
('Ingeniería de Prompting', 'Optimización de prompts para LLMs.', '2025-03-09', 5, 'book'),
('Diseño de Algoritmos Genéticos', 'Algoritmos evolutivos aplicados.', '2025-04-04', 1, 'article'),
('Sistemas Embebidos', 'Diseño de sistemas integrados.', '2025-05-30', 2, 'book'),
('Movilidad Inteligente', 'Tecnologías en transporte urbano.', '2025-06-25', 3, 'article'),
('Sistemas Distribuidos', 'Arquitecturas distribuidas modernas.', '2025-07-15', 4, 'book'),
('Análisis Predictivo', 'Modelos para predecir comportamiento.', '2025-08-10', 5, 'article');

INSERT INTO article (publication_id, doi, abstract, keywords, indexaTion, magazine, area)
VALUES
(2, '10.5555/abcd.2024.01', 'Estudio de tendencias emergentes en ML.', 'ML, IA, algoritmos', 'Scopus', 'AI Journal', 'Inteligencia Artificial'),
(4, '10.5555/abcd.2024.02', 'Aplicaciones reales de blockchain.', 'Blockchain, logística', 'Web of Science', 'Blockchain Review', 'Tecnología'),
(6, '10.5555/abcd.2024.03', 'Análisis ético de la IA moderna.', 'Ética, IA, Sociedad', 'Scopus', 'Ethics & AI', 'Humanidades Digitales'),
(8, '10.5555/abcd.2024.04', 'Introducción práctica a la computación cuántica.', 'Qubit, Quantum', 'Web of Science', 'Quantum Tech', 'Física Computacional'),
(10, '10.5555/abcd.2024.05', 'Panorama actual de ciberamenazas.', 'Ciberseguridad, hacking', 'Scopus', 'Cyber Defense', 'Seguridad Informática'),
(12, '10.5555/abcd.2024.06', 'Modelos de NLP aplicados a textos clínicos.', 'NLP, Machine Learning', 'Web of Science', 'LanguageTech', 'Procesamiento de Lenguaje'),
(14, '10.5555/abcd.2024.07', 'Aplicación educativa de RA.', 'RA, Educación', 'Scopus', 'AR Education', 'Educación Digital'),
(16, '10.5555/abcd.2024.08', 'Optimización de interacción con LLMs.', 'Prompting, Chatbots', 'Web of Science', 'Prompt Design', 'IA Conversacional'),
(18, '10.5555/abcd.2024.09', 'Tecnología para ciudades inteligentes.', 'Smart Mobility', 'Scopus', 'Urban Mobility', 'Transporte'),
(20, '10.5555/abcd.2024.10', 'Uso de analítica para predicción.', 'Big Data, Análisis Predictivo', 'Web of Science', 'Predictive Models', 'Analítica Avanzada');

INSERT INTO book (publication_id, isbn, gender, edition)
VALUES
(1, '978-9-87-654321-1', 'Tecnología', 1),
(3, '978-8-76-543210-2', 'Transformación Digital', 1),
(5, '978-7-65-432109-3', 'Ciencia', 2),
(7, '978-6-54-321098-4', 'Inteligencia Artificial', 1),
(9, '978-5-43-210987-5', 'Salud', 1),
(11, '978-4-32-109876-6', 'Visualización', 1),
(13, '978-3-21-098765-7', 'Automatización', 1),
(15, '978-2-10-987654-8', 'IA Conversacional', 1),
(17, '978-1-09-876543-9', 'Sistemas Embebidos', 1),
(19, '978-0-98-765432-0', 'Distribuidos', 1);

DELIMITER $$
CREATE OR REPLACE PROCEDURE sp_book_list()
BEGIN
    SELECT 
        b.`ISBN`,
        b.`gender`,
        b.`edition`,
        b.`publication_id`,
        p.id AS pub_id,
        p.`title`,
        p.`description`,      -- <<<< AÑADIDO
        p.`publication_date`,
        p.`type`,
        p.author_id,
        a.id AS author_id,    -- <<<< CAMBIADO
        a.first_name,
        a.last_name,
        a.username,
        a.email,
        a.password,
        a.orcid,
        a.affiliation
    FROM book b
    JOIN publication p ON b.publication_id = p.id
    JOIN author a ON p.author_id = a.id
    ORDER BY p.publication_date DESC;
END$$


CREATE OR REPLACE PROCEDURE sp_find_book(IN P_id INT)
BEGIN
    SELECT 
        b.`ISBN`,
        b.`gender`,
        b.`edition`,
        b.`publication_id`,
        p.id AS pub_id,
        p.`title`,
        p.`description`,      -- <<<< AÑADIDO
        p.`publication_date`,
        p.`type`,
        p.author_id,
        a.id AS author_id,    -- <<<< CAMBIADO
        a.first_name,
        a.last_name,
        a.username,
        a.email,
        a.password,
        a.orcid,
        a.affiliation
    FROM book b
    JOIN publication p ON b.publication_id = p.id
    JOIN author a ON p.author_id = a.id
    WHERE b.publication_id = P_id
    ORDER BY p.publication_date DESC;
END$$



CREATE OR REPLACE PROCEDURE sp_create_book(
    IN p_title                  VARCHAR(255),
    IN p_description            TEXT,
    IN p_publication_date       DATE,
    IN p_author_id              INT,
    IN p_isbn                   VARCHAR(20),
    IN p_gender                 VARCHAR(20),
    IN p_edition                INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;
    START TRANSACTION;
    INSERT INTO publication (title, description, publication_date, author_id, type)
    VALUES (p_title, p_description, p_publication_date, p_author_id, 'book');
    SET @new_pub_id := LAST_INSERT_ID();
    INSERT INTO book (publication_id, ISBN, gender, edition)
    VALUES (@new_pub_id, p_isbn, p_gender, p_edition);
    COMMIT;
    SELECT @new_pub_id AS pub_id;
END$$
--CREAR LA FUNCION ACTUALIZAR UN LIBRO
CREATE OR REPLACE PROCEDURE sp_update_book(
    IN p_publication_id         INT,
    IN p_title                  VARCHAR(255),
    IN p_description            TEXT,
    IN p_publication_date       DATE,
    IN p_author_id              INT,
    IN p_isbn                   VARCHAR(20),
    IN p_gender                 VARCHAR(20),
    IN p_edition                INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;
    START TRANSACTION;
    UPDATE publication
    SET title = p_title,
        description = p_description,
        publication_date = p_publication_date,
        author_id = p_author_id
    WHERE id = p_publication_id;
    UPDATE book
    SET ISBN = p_isbn,
        gender = p_gender,
        edition = p_edition
    WHERE publication_id = p_publication_id;
    COMMIT;
END$$
-- FUNCION PARA ELIMINAR UN LIBRO
CREATE OR REPLACE PROCEDURE sp_delete_book(IN P_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;
    START TRANSACTION;
    DELETE FROM book WHERE publication_id = P_id;
    DELETE FROM publication WHERE id = P_id;
    COMMIT;
    SELECT 1 AS OK;
END$$

-- LISTAR TODOS LOS ARTÍCULOS
CREATE OR REPLACE PROCEDURE sp_article_list()
BEGIN
    SELECT 
        a.publication_id,
        a.doi,
        a.abstract,
        a.keywords,
        a.indexaTion,
        a.magazine,
        a.area,
        p.title,
        p.description,
        p.publication_date,
        p.type,
        p.author_id,
        au.first_name,
        au.last_name
    FROM article a
    JOIN publication p ON a.publication_id = p.id
    JOIN author au ON p.author_id = au.id
    ORDER BY p.publication_date DESC;
END$$

-- BUSCAR UN ARTÍCULO POR ID
CREATE OR REPLACE PROCEDURE sp_find_article(IN P_id INT)
BEGIN
    SELECT 
        a.publication_id,
        a.doi,
        a.abstract,
        a.keywords,
        a.indexaTion,
        a.magazine,
        a.area,
        p.title,
        p.description,
        p.publication_date,
        p.type,
        p.author_id,
        au.first_name,
        au.last_name
    FROM article a
    JOIN publication p ON a.publication_id = p.id
    JOIN author au ON p.author_id = au.id
    WHERE a.publication_id = P_id;
END$$

-- CREAR UN NUEVO ARTÍCULO
CREATE OR REPLACE PROCEDURE sp_create_article(
    IN p_title            VARCHAR(255),
    IN p_description      TEXT,
    IN p_publication_date DATE,
    IN p_author_id        INT,
    IN p_doi              VARCHAR(20),
    IN p_abstract         VARCHAR(300),
    IN p_keywords         VARCHAR(50),
    IN p_indexation       VARCHAR(20),
    IN p_magazine         VARCHAR(50),
    IN p_area             VARCHAR(50)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    INSERT INTO publication (title, description, publication_date, author_id, type)
    VALUES (p_title, p_description, p_publication_date, p_author_id, 'article');

    SET @new_pub_id := LAST_INSERT_ID();

    INSERT INTO article (
        publication_id, doi, abstract, keywords, indexaTion, magazine, area
    )
    VALUES (
        @new_pub_id, p_doi, p_abstract, p_keywords, p_indexation, p_magazine, p_area
    );

    COMMIT;

    SELECT @new_pub_id AS pub_id;
END$$

-- ACTUALIZAR UN ARTÍCULO
CREATE OR REPLACE PROCEDURE sp_update_article(
    IN p_publication_id   INT,
    IN p_title            VARCHAR(255),
    IN p_description      TEXT,
    IN p_publication_date DATE,
    IN p_author_id        INT,
    IN p_doi              VARCHAR(20),
    IN p_abstract         VARCHAR(300),
    IN p_keywords         VARCHAR(50),
    IN p_indexation       VARCHAR(20),
    IN p_magazine         VARCHAR(50),
    IN p_area             VARCHAR(50)
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    UPDATE publication
    SET title = p_title,
        description = p_description,
        publication_date = p_publication_date,
        author_id = p_author_id
    WHERE id = p_publication_id;

    UPDATE article
    SET doi = p_doi,
        abstract = p_abstract,
        keywords = p_keywords,
        indexaTion = p_indexation,
        magazine = p_magazine,
        area = p_area
    WHERE publication_id = p_publication_id;

    COMMIT;
END$$

-- ELIMINAR UN ARTÍCULO
CREATE OR REPLACE PROCEDURE sp_delete_article(IN P_id INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    DELETE FROM article WHERE publication_id = P_id;
    DELETE FROM publication WHERE id = P_id;

    COMMIT;

    SELECT 1 AS OK;
END$$

DELIMITER ;