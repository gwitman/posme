UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'MENSUALIDAD'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_TIPO_PLAN';



UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '1'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PRICE';


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '0.01'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PRICE_BY_INVOICE';


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'true'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PAYMENT_SENDBOX';


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Dolar'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'ACCOUNTING_CURRENCY_NAME_EXTERNAL';


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Dolar'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'ACCOUNTING_CURRENCY_NAME_REPORT';

UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Dolar'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'ACCOUNTING_CURRENCY_NAME_FUNCTION';	


UPDATE tb_company SET NAME = 'posMe' , address = 'Managua, Nicaragua, Multicentro las america modulo A25' WHERE companyID = 2;


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Witman Jose Gonzalez Rostran'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_NAME';
		
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Managua, Nicaragua, Multicentro las america modulo A25'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_ADDRESS';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '2022-12-31'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_LICENSE_EXPIRED';
		
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '500'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_MAX_USER';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '8476-6457'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PHONE';
	
		
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '8476-6457'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_PHONE';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = ';'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_CSV_SPLIT';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'app_invoice_billing/viewRegisterFidLocal'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'INVOICE_URL_PRINTER';
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'app_box_share/viewRegister'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'BOX_SHARE_URL_PRINTER';
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'app_box_sharecapital/viewRegister'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'BOX_SHARECAPITAL_URL_PRINTER';
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'app_box_canceldocument/viewRegister'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'BOX_CANCELDOCUMENT_URL_PRINTER';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'flc_witman'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_CXC_WSDL_SIN_RIESGO_USUARIO';	
	

UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '121-180371-0004R'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_ID';
	
		
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '121-180371-0004R'
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
	tb_parameter.name = 'INVOICE_BUTTOM_PRINTER_FIDLOCAL_PAYMENT_AND_AMORTIZACION';	


	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '1'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CREDIT_INTERES_MULTIPLO';	


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'PROMEDIO'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'INVENTORY_TYPE_COST';	

	