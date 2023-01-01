UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "gwitman@yahoo.com" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PROPIETARY_EMAIL";## CORREO DEL PROPIETARIO


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "154" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_DEFAULT_TYPE_PRICE";## Tipo de precio por defecto


UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "PRO00000131" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXP_PROVIDER_DEFAULT";## Proveedor por defecto
	
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_AUTOAPPLY_CASH";## Auto aplicar las facturas de contados 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Dolar" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_CURRENCY_NAME_EXTERNAL";## MONEXA EXTRAJERA 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "CORE_TEMPORAL003" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_TEMPORAL003";## Varible para realiar calculos 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "CORE_TEMPORAL002" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_TEMPORAL002";## Varible para realiar calculos 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "1" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_TEMPORAL001";## Varible para realiar calculos 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "12ef04e7197c001b66920797fac63c46" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PAYMENT_PRODUCCION_CLAVE";## Clave para realizar el pago en ambiente de produccion 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "7be685eb39ed6cac49de29bd36f4665e" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PAYMENT_PRODUCCION_USUARIO";## Usuario para realizar el pago en ambiente de produccion 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "56720c930f874d4011ff7f3e2a86eddb" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PAYMENT_PRUEBA_CLAVE";## Clave para realizar el pago en ambiente de prueba 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "35f6110eb79c3640a9bc35f876fe05f6" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PAYMENT_PRUEBA_USUARIO";## Usuario para realizar el pago en ambiente de prueba 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PAYMENT_SENDBOX";## API DE PAGO DE PRUEBA 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "0.01" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_BY_INVOICE";## PRECIO POR FACTURA , ES LO que se le resgta al balane del core, por cada factura realizada dentro del sistema, siempre y cuando el tipo de pago sea:	CONSUMIBLE
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "MENSUALIDAD" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_TIPO_PLAN";## TIPO DE PAGO:  CONSUMIBLE O MENSUALIDAD 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "293" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_ITEM_PROVIDER_DEFAULT";## Proveedor por defecto para los productos. 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_QUANTITY_ZERO";## Permite facturar productos, aunque las existencias esten en 0 unidades 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_box_canceldocument/viewRegisterFormatoPaginaTicket" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "BOX_CANCELDOCUMENT_URL_PRINTER";## URL PARA LA IMPRESION DE CANCELACION 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_box_sharecapital/viewRegisterFormatoPaginaTicket" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "BOX_SHARECAPITAL_URL_PRINTER";## URL PARA LA IMPRESION DE ABONOS AL CAPITAL 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_box_share/viewRegisterFormatoPaginaTicket" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "BOX_SHARE_URL_PRINTER";## URL PARA LA IMPRESION DE ABONOS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_invoice_billing/viewRegisterFormatoPaginaTicket" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_URL_PRINTER";## URLs PARA LA IMPRESION DE FACTURA 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "284" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_MUNICIPIO_DEFAULT";## Municipio por defecto 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "190" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_FRECUENCIA_PAY_DEFAULT";## CXC_FRECUENCIA_PAY_DEFAULT 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "193" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_TYPE_AMORTIZATION";## CXC_TYPE_AMORTIZATION 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "0" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_BALANCE";## Saldo recargado para usar el sistema, estes saldo se consume a medida que se crean facturas. cuando el plan es PREPAGO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "true" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_UPDATEPRICE_ONLINE";## ACTUALIZAR EL PRECIO DURANTE LA FACTURACION 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "12" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_DEFAULT_PRICELIST";## LISTA DE PRECIO POR DEFECTO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "8712-5827" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PROPIETARY_PHONE";## TELEFONO DEL PROPIETARIO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = ";" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CSV_SPLIT";## CARACTERE SEPARADOR DE CVS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "2022-12-31" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_LICENCES_EXPIRED";## Fecha de expiracion de la licencia 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "10" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_SLEEP";## Dormida despues de pasado el tiempo de espera de la licencia en segundo 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "app_invoice_billing/viewPrinterDirect" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_DIRECT_URL";## Urls para imprimir el recibo directamente en la impresora, mejor dicho imprimir la factura 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BUTTOM_PRINTER_FIDLOCAL_PAYMENT_AND_AMORTIZACION";## Mostrar Calendario de Pago , al momento de imprimir una factura
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "false" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_DIRECT";## Imprimir directo en la impresora 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "ULTIMO COSTO" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_TYPE_COST";## Tipos de Costo: ULTIMO COSTO; PROMEDIO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "X007" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_VERSION";## Versions del sistema 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "[01-01-01-03]" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_RAZON_BANCO_AVANZ";## Resumen de cuenta AVANZ 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "[01-01-02-03] + [01-01-01-02]" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_RAZON_BANCO_BANPRO";## Resument de Cuenta BANPRO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "([01-01-01-01]+[01-01-02-04])" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_RAZON_BANCO_BAC";## Resument de Saldo en Banco BAC 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "211" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_DEPARTAMENTO_DEFAULT";## Departamento por defecto 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "42" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_PAIS_DEFAULT";## Pais por defecto 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "00002" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_ITEM_WAREHOUSE_DEFAULT";## Bodega por defecto 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "https://servicios.bcn.gob.ni/Tc_Servicio/ServicioTC.asmx?WSDL" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_ACCOUNTING_WSDL_DER";## Urls para consultar el tipo de cambio 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "b77a5c561934e089" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CXC_WSDL_SIN_RIESGO_UPLOAD_CODIGO";## Contraseña para subir la informacion a la sin riesgo identificacion del comercio 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "https://www.sinriesgos.com.ni/ServiceFacade/servicios.asmx?wsdl" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CXC_WSDL_SIN_RIESGO_UPLOAD";## Urls para cargar la informacio de los deudores a ala sin riesgo 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "flc_witman" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CXC_WSDL_SIN_RIESGO_USUARIO";## Usuario para conectarse a la sin riesgo 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "180389Gonzalez." 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CXC_WSDL_SIN_RIESGO_PASSWORD";## Passwords para conectarse a la sin riesgo 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "https://www.sinriesgos.com.ni/WS/WebService.asmx?WSDL" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CXC_WSDL_SIN_RIESGO";## Urls para consultar a la sin riesgo y para reportar a la sin riesgo 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "389" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_PROPIETARIO_DEL_CREDITO";## PROPIETARIO DEL CREDITO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "POS-80-Series USB" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_PRINTER_DIRECT_NAME_DEFAULT";## Nombre de la impresora por defecto 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "383" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_JOURNALTYPE_PROVISION";## ACCOUNTING_JOURNALTYPE_PROVISION 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "378" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_JOURNALTYPE_DIVIDENDO";## Cuenta para medir el pago de dividendo 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "1" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CREDIT_INTERES_MULTIPLO";## INTERES MULTIPLICADOR DE INTERES 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "93" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CREDIT_AMORTIZATION_PROVISIONED";## ESTADO PROVISIONADO DE LAS CUOTAS DE CREDITO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "92" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CREDIT_DOCUMENT_PROVISIONED";## ESTADO PROVISIONADO DEL DOCUMENTO DE CREDITO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "72" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_JOURNALTYPE_APORTECAPITAL";## tipo de comprobante aporte al capital 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "(04-03-02|04-03-03|04-03-04|04-03-05|04-03-06|04-03-07|04-03-08|05-01-03|05-01-04|05-01-05|06-01-01|06-01-02|06-01-03|06-01-04|06-01-05|06-01-06|06-02-01|06-02-02|06-02-03|06-02-04|06-02-05|06-02-06|06-03-01|06-03-02|06-03-03|06-03-04|06-03-05)" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_INGRESOS_EGRESOS";## ACCOUNTING_NUMBER_INGRESOS_EGRESOS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "01-01-01-01" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_CASH";## CUENTA DE BANCO O CAJA 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "1" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE";## Precio mensual de la licencia 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "((([04]-[05]-[06])/[01])*100)/12" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_RENTABILIDAD_MENSUAL";## RENTABILIDAD MENSUAL 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "(([04]-[05]-[06])/[01])*100" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_RENTABILIDAD_ANUAL";## RENTABILIDAD ANUAL 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "[04]-[05]-[06]" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_UTILIDAD_MENSUAL";## UTILIDAD MENSUAL 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "[04]-[05]-[06]" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_UTILIDAD_ANUAL";## UTILIDAD ANUAL 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "([02-02-02]/[03-02-01])*100" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_ENDEUDAMIENTO";## QUE TANTO ESTA ENDEUDADA LA EMPRESA 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "([01-01-01-01]/[02-02-02])*100" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_RF_RAZON_CIRCULANTE";## RAZÓN DEL CIRCULANTE 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "196" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CXC_AMERICANO";## TIPO DE AMORTIZACION AMERICANA 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Lic." 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PROPIETARY_TITLE";## Titulo del propietario de la empresa 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Multicentro las america, Managua Nicaragua" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PROPIETARY_ADDRESS";## la ciudad de Malpaisillo, de la policia nacional 3C.E y 1/2C.S  
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Witman Jose Gonzalez Rostran" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PROPIETARY_NAME";## Witman José González Rostran 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "281-180389-0004R" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PROPIETARY_ID";## CEDULA DEL PROPIETARIO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "0.4" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_EXCHANGE_SALE";## GANANCIA POR VENTA DE Dolares
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "0.40" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_EXCHANGE_PURCHASE";## ganancia por compra de dolares 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "83" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "SHARE_DOCUMENT_ANULADO";## ESTADO DEL DOCUMENTO DE CREDITO ANULADO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "8712-5827" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_PHONE";## TELEFONO DE LA FACTURACION 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "82" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "SHARE_DOCUMENT_CANCEL";## ESTADO DE DOCUMENTO DE CREDITO CANCELADO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "81" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "SHARE_CANCEL";## ABONO DE CREDITO CANCELADO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "22,24" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_CREDIT";## ID DE LOS CAUSALES DE CREDITO DE LA FACTURACION. 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "20" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_TRANSACTION_REVERSION_TO_BILLING";## TRANSACCIÓN DE REVERSIÓN PARA LA FACTURA 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "CLI00000000" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_CLIENTDEFAULT";## Cliente por defecto al realizar una factura
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "67" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_CANCEL";## FACTURAS EN ESTADO CANCELADAS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "68" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVOICE_BILLING_ANULADAS";## FACTURAS EN ESTADOS ANULADAS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "281-180389-0004R" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_COMPANY_IDENTIFIER";## RUC 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "153" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_AREA_ND";## Catalogo para el Área no Determinada 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "15" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "INVENTORY_TRANSFEROUTPUT_RELATION_TRANSFERINPUT";## La relación entre la transferencia por salida (16) y su transacción automática es con la entrada por transferencia (15) 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "36" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_JOURNALENTRY_WORKFLOWSTAGE_FINISH";## Ultimo estado de los Comprobantes 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "74" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_JOURNALTYPE_CLOSED";## VALOR DEL TIPO DE COMPROBANTE DE CIERRE: catalogItemID : 74 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "^(04|05|06|07)" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_ACCOUNTTYPE_RESULT";## LAS CUENTAS DE RESULTADOS SON LAS QUE EMPIEZAN CON ESTA NOMENCLATURA 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "34" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED";## Parámetro para almacenar el estado de los ciclos cerrados  
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "32" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED";## Este parámetro identifica todos los periodos contables cerrados. 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "03" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_CAPITAL";## CUENTA DE CAPITAL 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "02" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_PASIVO";## CUENTA DE PASIVO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "01" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_ACTIVO";## CUENTA DE ACTIVO 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "05" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_COSTOS";## CUENTA DE COSTOS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "06" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_GASTOS";## CUENTA DE GASTOS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "04" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_INGRESO";## CUENTA DE INGRESOS 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "logo-micro-finanza.jpg" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_COMPANY_LOGO";## Logo de la empresa 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Cordoba" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_CURRENCY_NAME_REPORT";## Moneda de Reporte para Presentar los Estados Financieros 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "[04]-[05]-[06]" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_FORMULATE_OF_UTILITY";## FORMULA PARA LAS UTILIDADES DE LA EMPRESA 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "03-01-01" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_UTILITY_ACUMULATE";## ACCOUNTING_NUMBER_UTILITY_ACUMULATE 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "03-01-02" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_NUMBER_UTILITY_PERIOD";## ACCOUNTING_NUMBER_UTILITY_PERIOD 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "Cordoba" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "ACCOUNTING_CURRENCY_NAME_FUNCTION";## Símbolo de la Moneda Funcional 
				
				
UPDATE  tb_company_parameter,tb_parameter SET 
	tb_company_parameter.value = "500" 
WHERE 
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = "CORE_CUST_PRICE_MAX_USER";## MAXIMO NUMERO DE USUARIOS 


UPDATE tb_company SET 
	NAME = 'Farmacia Familiar' , address = 'Wester Union 3c al Este 1/2 al Norte' 
WHERE 
	companyID = 2; ##Actualizar el nombre de la compania