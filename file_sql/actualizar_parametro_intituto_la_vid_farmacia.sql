UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'MENSUALIDAD'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_TIPO_PLAN';



UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '80'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PRICE';


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '0.01'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PRICE_BY_INVOICE';


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'false'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PAYMENT_SENDBOX';


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Dolar'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'ACCOUNTING_CURRENCY_NAME_EXTERNAL';


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Cordoba'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'ACCOUNTING_CURRENCY_NAME_REPORT';

UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Cordoba'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'ACCOUNTING_CURRENCY_NAME_FUNCTION';

	


UPDATE tb_company SET NAME = 'Instituto la VID Farm' , address = 'Chinandega, San Agustin 1c al sur' WHERE companyID = 2;


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Antonio Jose Delgado Melendez'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_NAME';
		
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Chinandega, San Agustin 1c al sur'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_ADDRESS';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '2023-11-01'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_LICENSE_EXPIRED';
		
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '500'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_MAX_USER';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'TIGO: 8963-3737 | CLARO: 8902-3679'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PHONE';
	
		
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'TIGO: 8963-3737 | CLARO: 8902-3679'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_PHONE';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = ';'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_CSV_SPLIT';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'app_invoice_billing/viewRegisterInstitutoLaVid'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'INVOICE_URL_PRINTER';
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'app_box_share/viewRegisterInstitutoLaVid'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'BOX_SHARE_URL_PRINTER';
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'app_box_sharecapital/viewRegisterInstitutoLaVid'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'BOX_SHARECAPITAL_URL_PRINTER';
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'app_box_canceldocument/viewRegisterInstitutoLaVid'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'BOX_CANCELDOCUMENT_URL_PRINTER';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'flc_intituto_l'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_CXC_WSDL_SIN_RIESGO_USUARIO';	
	

UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '081-040573-0000D'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_ID';
	
		
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '081-040573-0000D'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_COMPANY_IDENTIFIER';


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '00002'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'INVENTORY_ITEM_WAREHOUSE_DEFAULT';



UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'false'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'INVOICE_BUTTOM_PRINTER_FIDLOCAL_PAYMENT';
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '1'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CREDIT_INTERES_MULTIPLO';	



UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'false'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'INVOICE_BILLING_PRINTER_DIRECT';	

UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'false'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'INVOICE_BUTTOM_PRINTER_FIDLOCAL_PAYMENT_AND_AMORTIZACION';	
