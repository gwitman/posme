CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER 
VIEW `vw_sin_riesgo_reporte_clientes` AS 
    SELECT 
        DATE_FORMAT(NOW(), '%d/%m/%Y') AS `FECHA REPORTE`,
        REPLACE(`cus`.`identification`, '-', '') AS `IDENTIFICACION`,
        'N' AS `TIPO DE PERSONA`,
        'NICARAGUENSE' AS `NACIONALIDAD`,
        (CASE
            WHEN (`sexo`.`display` = 'FEMENINO') THEN 'F'
            ELSE 'M'
        END) AS `SEXO`,
        DATE_FORMAT(`cus`.`birthDate`, '%d/%m/%Y') AS `FECHA DE NACIMIENTO`,
        'SOL' AS `ESTADO CIVIL`,
        `cus`.`address` AS `DIRECCION`,
        '08' AS `DEPARTAMENTO`,
        '84' AS `MUNICIPIO`,
        `cus`.`address` AS `DIRECCION DE TRABAJO`,
        '08' AS `DEPARTAMENTO DE TRABAJO`,
        '84' AS `MUNICIPIO DE TRABAJO`,
        IF(ISNULL((SELECT 
                            `ph`.`number`
                        FROM
                            `tb_entity_phone` `ph`
                        WHERE
                            ((`ph`.`entityID` = `nat`.`entityID`)
                                AND (`ph`.`isPrimary` = 1)))),
            '',
            (SELECT 
                    `ph`.`number`
                FROM
                    `tb_entity_phone` `ph`
                WHERE
                    ((`ph`.`entityID` = `nat`.`entityID`)
                        AND (`ph`.`isPrimary` = 1)))) AS `TELEFONO DOMICILIAR`,
        IF(ISNULL((SELECT 
                            `ph`.`number`
                        FROM
                            `tb_entity_phone` `ph`
                        WHERE
                            ((`ph`.`entityID` = `nat`.`entityID`)
                                AND (`ph`.`isPrimary` = 1)))),
            '',
            (SELECT 
                    `ph`.`number`
                FROM
                    `tb_entity_phone` `ph`
                WHERE
                    ((`ph`.`entityID` = `nat`.`entityID`)
                        AND (`ph`.`isPrimary` = 1)))) AS `TELEFONO TRABAJO`,
        IF(ISNULL((SELECT 
                            `ph`.`number`
                        FROM
                            `tb_entity_phone` `ph`
                        WHERE
                            ((`ph`.`entityID` = `nat`.`entityID`)
                                AND (`ph`.`isPrimary` = 1)))),
            '',
            (SELECT 
                    `ph`.`number`
                FROM
                    `tb_entity_phone` `ph`
                WHERE
                    ((`ph`.`entityID` = `nat`.`entityID`)
                        AND (`ph`.`isPrimary` = 1)))) AS `CELULAR`,
        '' AS `CORREO ELECTRONICO`,
        'COMERCIANTE' AS `OCUPACION`,
        'PULPERIA' AS `ACTIVIDAD ECONOMICA`,
        'DETALLE' AS `SECTOR`
    FROM
        ((`tb_naturales` `nat`
        JOIN `tb_customer` `cus` ON ((`nat`.`entityID` = `cus`.`entityID`)))
        JOIN `tb_catalog_item` `sexo` ON ((`cus`.`sexoID` = `sexo`.`catalogItemID`)))
    WHERE
        (`nat`.`isActive` = 1)