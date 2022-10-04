CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `fidlocal`@`localhost` 
    SQL SECURITY DEFINER
VIEW `vw_sin_riesgo_reporte_creditos_to_systema` AS
    SELECT 
        `i`.`companyID` AS `companyID`,
        `i`.`TIPO DE ENTIDAD` AS `TIPO_DE_ENTIDAD`,
        `i`.`NUMERO CORRELATIVO` AS `NUMERO_CORRELATIVO`,
        `i`.`FECHA DE REPORTE` AS `FECHA_DE_REPORTE`,
        `i`.`DEPARTAMENTO` AS `DEPARTAMENTO`,
        `i`.`NUMERO DE CEDULA O RUC` AS `NUMERO_DE_CEDULA_O_RUC`,
        `i`.`NOMBRE DE PERSONA` AS `NOMBRE_DE_PERSONA`,
        `i`.`TIPO DE CREDITO` AS `TIPO_DE_CREDITO`,
        `i`.`FECHA DE DESEMBOLSO` AS `FECHA_DE_DESEMBOLSO`,
        `i`.`TIPO DE OBLIGACION` AS `TIPO_DE_OBLIGACION`,
        `i`.`MONTO AUTORIZADO` AS `MONTO_AUTORIZADO`,
        `i`.`PLAZO` AS `PLAZO`,
        `i`.`FRECUENCIA DE PAGO` AS `FRECUENCIA_DE_PAGO`,
        `i`.`SALDO DEUDA` AS `SALDO_DEUDA`,
        `i`.`ESTADO` AS `ESTADO`,
        `i`.`MONTO VENCIDO` AS `MONTO_VENCIDO`,
        `i`.`ANTIGUEDAD DE MORA` AS `ANTIGUEDAD_DE_MORA`,
        `i`.`TIPO DE GARANTIA` AS `TIPO_DE_GARANTIA`,
        `i`.`FORMA DE RECUPERACION` AS `FORMA_DE_RECUPERACION`,
        `i`.`NUMERO DE CREDITO` AS `NUMERO_DE_CREDITO`,
        `i`.`VALOR DE LA CUOTA` AS `VALOR_DE_LA_CUOTA`
    FROM
        `vw_sin_riesgo_reporte_creditos` `i`