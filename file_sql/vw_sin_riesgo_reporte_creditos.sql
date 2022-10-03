SELECT 
		  `cc`.`companyID` AS `companyID`,
        `cc`.`customerCreditDocumentID` AS `customerCreditDocumentID`,
        `cc`.`entityID` AS `entityID`,
        '03' AS `TIPO DE ENTIDAD`,
        '552' AS `NUMERO CORRELATIVO`,
        DATE_FORMAT(NOW(), '%d/%m/%Y') AS `FECHA DE REPORTE`,
        '08' AS `DEPARTAMENTO`,
        REPLACE(`c`.`identification`, '-', '') AS `NUMERO DE CEDULA O RUC`,
        CONCAT(`nat`.`firstName`, ' ', `nat`.`lastName`) AS `NOMBRE DE PERSONA`,
        RIGHT(CONCAT('0000', `tipocredito`.`sequence`),
            2) AS `TIPO DE CREDITO`,
        DATE_FORMAT(`cc`.`dateOn`, '%d/%m/%Y') AS `FECHA DE DESEMBOLSO`,
        RIGHT(CONCAT('0000', `obli`.`sequence`),2) AS `TIPO DE OBLIGACION`,
        ROUND((FN_CALCULATE_EXCHANGE_RATE(2,
                        CAST(NOW() AS DATE),
                        `cc`.`currencyID`,
                        1,
                        `cc`.`amount`) * `p`.`ratioDesembolso`),
                2) AS `MONTO AUTORIZADO`,
        IF((ROUND(((CASE
                        WHEN (`cc`.`periodPay` = 190) THEN (`cc`.`term` * 30)
                        WHEN (`cc`.`periodPay` = 188) THEN (`cc`.`term` * 7)
                        WHEN (`cc`.`periodPay` = 189) THEN (`cc`.`term` * 14)
                        ELSE 0
                    END) / 30),
                    0) = 0),
            1,
            ROUND(((CASE
                        WHEN (`cc`.`periodPay` = 190) THEN (`cc`.`term` * 30)
                        WHEN (`cc`.`periodPay` = 188) THEN (`cc`.`term` * 7)
                        WHEN (`cc`.`periodPay` = 189) THEN (`cc`.`term` * 14)
                        ELSE 0
                    END) / 30),
                    0)) AS `PLAZO`,
        (CASE
            WHEN (`cc`.`periodPay` = 190) THEN '05'
            WHEN (`cc`.`periodPay` = 188) THEN '07'
            WHEN (`cc`.`periodPay` = 189) THEN '06'
            ELSE 0
        END) AS `FRECUENCIA DE PAGO`,
        (CASE
            WHEN (`cc`.`statusID` = 82) THEN 0
            ELSE ROUND((FN_CALCULATE_EXCHANGE_RATE(2,
                            CAST(NOW() AS DATE),
                            `cc`.`currencyID`,
                            1,
                            `cc`.`balance`) * `p`.`ratioBalance`),
                    2)
        END) AS `SALDO DEUDA`,
        (CASE
        		WHEN `estadosinriesgo`.`sequence` = 1 /*ESTADO DE CREDITO:ACTIVO*/ THEN   
				   CASE      			
		            WHEN
		                ((`ws`.`workflowStageID` NOT IN (93 , 92, 82))
		                    AND (CAST(NOW() AS DATE) > (SELECT 
		                        MAX(`xl`.`dateApply`)
		                    FROM
		                        `tb_customer_credit_amoritization` `xl`
		                    WHERE
		                        (`xl`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`))))
		            THEN
		                '02'
		            WHEN
		                ((`ws`.`workflowStageID` NOT IN (93 , 92, 82))
		                    AND (CAST(NOW() AS DATE) > (SELECT 
		                        MIN(`xl`.`dateApply`)
		                    FROM
		                        `tb_customer_credit_amoritization` `xl`
		                    WHERE
		                        ((`xl`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)
		                            AND (`xl`.`remaining` > 0)))))
		            THEN
		                '02'
		            WHEN (`ws`.`workflowStageID` = 83) THEN 'N/D'
		            WHEN (`ws`.`workflowStageID` = 92) THEN '08'
		            WHEN (`ws`.`workflowStageID` = 82) THEN '03'
		            WHEN (`ws`.`workflowStageID` = 77) THEN '01'
		            ELSE RIGHT(CONCAT('0000', `estadosinriesgo`.`sequence`),2)
	            END 
	         ELSE 
	         	RIGHT(CONCAT('0000', `estadosinriesgo`.`sequence`),2)
        END) AS `ESTADO`,
        ROUND(((SELECT 
                        IFNULL(ROUND((CASE
                                                WHEN
                                                    (`cc`.`typeAmortization` = 196)
                                                THEN
                                                    AVG(FN_CALCULATE_EXCHANGE_RATE(2,
                                                            CAST(NOW() AS DATE),
                                                            `cc`.`currencyID`,
                                                            1,
                                                            `cx`.`balanceStart`))
                                                ELSE SUM(FN_CALCULATE_EXCHANGE_RATE(2,
                                                        CAST(NOW() AS DATE),
                                                        `cc`.`currencyID`,
                                                        1,
                                                        `cx`.`capital`))
                                            END),
                                            2),
                                    0)
                    FROM
                        `tb_customer_credit_amoritization` `cx`
                    WHERE
                        ((`cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)
                            AND (`cx`.`isActive` = 1)
                            AND (`cx`.`remaining` > 0)
                            AND (`cx`.`statusID` = 78)
                            AND (`cx`.`dateApply` < CAST(NOW() AS DATE)))) * `p`.`ratioBalanceExpired`),
                2) AS `MONTO VENCIDO`,
        (SELECT 
                IFNULL((TO_DAYS(NOW()) - TO_DAYS(MIN(`cx`.`dateApply`))),
                            0)
            FROM
                `tb_customer_credit_amoritization` `cx`
            WHERE
                ((`cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)
                    AND (`cx`.`isActive` = 1)
                    AND (`cx`.`remaining` > 0)
                    AND (`cx`.`statusID` = 78)
                    AND (`cx`.`dateApply` < CAST(NOW() AS DATE)))) AS `ANTIGUEDAD DE MORA`,
        RIGHT(CONCAT('0000', `tipogarantia`.`sequence`),2) AS `TIPO DE GARANTIA`,
        (CASE
        		WHEN `recuperacion`.`sequence` = 1 THEN
        			CASE 
		            WHEN (`ws`.`workflowStageID` = 83) THEN '01'
		            WHEN (`ws`.`workflowStageID` = 92) THEN '08'
		            WHEN (`ws`.`workflowStageID` = 82) THEN '01'
		            WHEN
		                ((`ws`.`workflowStageID` = 77)
		                    AND ((SELECT 
		                        IFNULL((TO_DAYS(NOW()) - TO_DAYS(MIN(`cx`.`dateApply`))),
		                                    0)
		                    FROM
		                        `tb_customer_credit_amoritization` `cx`
		                    WHERE
		                        ((`cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)
		                            AND (`cx`.`isActive` = 1)
		                            AND (`cx`.`remaining` > 0)
		                            AND (`cx`.`statusID` = 78)
		                            AND (`cx`.`dateApply` < CAST(NOW() AS DATE)))) BETWEEN 30 AND 59))
		            THEN
		                '03'
		            WHEN
		                ((`ws`.`workflowStageID` = 77)
		                    AND ((SELECT 
		                        IFNULL((TO_DAYS(NOW()) - TO_DAYS(MIN(`cx`.`dateApply`))),
		                                    0)
		                    FROM
		                        `tb_customer_credit_amoritization` `cx`
		                    WHERE
		                        ((`cx`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`)
		                            AND (`cx`.`isActive` = 1)
		                            AND (`cx`.`remaining` > 0)
		                            AND (`cx`.`statusID` = 78)
		                            AND (`cx`.`dateApply` < CAST(NOW() AS DATE)))) > 60))
		            THEN
		                '04'
		            WHEN (`ws`.`workflowStageID` = 77) THEN '01'
		            ELSE RIGHT(CONCAT('0000', `recuperacion`.`sequence`),2)
		         END 
		      ELSE 
		      	RIGHT(CONCAT('0000', `recuperacion`.`sequence`),2)
        END) AS `FORMA DE RECUPERACION`,
        `cc`.`documentNumber` AS `NUMERO DE CREDITO`,
        ROUND(((CASE
                    WHEN
                        (`ci`.`catalogItemID` = 196)
                    THEN
                        (CASE
                            WHEN
                                (`cc`.`periodPay` = 190)
                            THEN
                                ((FN_CALCULATE_EXCHANGE_RATE(2,
                                        CAST(NOW() AS DATE),
                                        `cc`.`currencyID`,
                                        1,
                                        `cc`.`balance`) * ((((`cc`.`interes` / 12) * `cc`.`term`) / 100) + 1)) / `cc`.`term`)
                            WHEN
                                (`cc`.`periodPay` = 188)
                            THEN
                                ((FN_CALCULATE_EXCHANGE_RATE(2,
                                        CAST(NOW() AS DATE),
                                        `cc`.`currencyID`,
                                        1,
                                        `cc`.`balance`) * ((((`cc`.`interes` / 52) * `cc`.`term`) / 100) + 0)) / `cc`.`term`)
                            ELSE 0
                        END)
                    ELSE (SELECT 
                            AVG(FN_CALCULATE_EXCHANGE_RATE(2,
                                        CAST(NOW() AS DATE),
                                        `cc`.`currencyID`,
                                        1,
                                        `cp`.`share`))
                        FROM
                            `tb_customer_credit_amoritization` `cp`
                        WHERE
                            (`cp`.`customerCreditDocumentID` = `cc`.`customerCreditDocumentID`))
                END) * `p`.`ratioShare`),
                2) AS `VALOR DE LA CUOTA`
    FROM
        ((((((((((((`tb_customer_credit_document` `cc`
        JOIN `tb_currency` `cur` ON ((`cc`.`currencyID` = `cur`.`currencyID`)))
        JOIN `tb_workflow_stage` `ws` ON ((`cc`.`statusID` = `ws`.`workflowStageID`)))
        JOIN `tb_catalog_item` `ci` ON ((`cc`.`typeAmortization` = `ci`.`catalogItemID`)))
        JOIN `tb_customer_credit_document_entity_related` `p` ON ((`cc`.`customerCreditDocumentID` = `p`.`customerCreditDocumentID`)))
        JOIN `tb_catalog_item` `obli` ON ((`obli`.`catalogItemID` = `p`.`type`)))
        JOIN `tb_catalog_item` `tipocredito` ON ((`tipocredito`.`catalogItemID` = `p`.`typeCredit`)))
        JOIN `tb_catalog_item` `tipogarantia` ON ((`tipogarantia`.`catalogItemID` = `p`.`typeGarantia`)))
        JOIN `tb_catalog_item` `frepago` ON ((`frepago`.`catalogItemID` = `cc`.`periodPay`)))
        JOIN `tb_catalog_item` `recuperacion` ON ((`recuperacion`.`catalogItemID` = `p`.`typeRecuperation`)))
        JOIN `tb_catalog_item` `estadosinriesgo` ON ((`estadosinriesgo`.`catalogItemID` = `p`.`statusCredit`)))
        JOIN `tb_naturales` `nat` ON ((`p`.`entityID` = `nat`.`entityID`)))
        JOIN `tb_customer` `c` ON ((`nat`.`entityID` = `c`.`entityID`)))
    WHERE
        ((`cc`.`isActive` = 1)
            AND (`cc`.`entityID` <> 309)
            AND (REPLACE(`c`.`identification`, '-', '') NOT IN (
				'0000000000000B', 
				'0000000000000A',
            '0000000000000C',
            '0000000000000P',
            '0000000000000K',
            '2811803890004R',
            '2912906610000G',
            '2911206850000P',
            '0000000000000T'))
            AND (`ws`.`workflowStageID` <> 83))
    ORDER BY CONCAT(`nat`.`firstName`, ' ', `nat`.`lastName`) 