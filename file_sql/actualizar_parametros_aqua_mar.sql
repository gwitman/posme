UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'MENSUALIDAD'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_TIPO_PLAN';


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '75'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PRICE';


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '0.0'
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

UPDATE tb_company SET NAME = 'Aqua Mar' , address = 'Wester Union 3c al Este 1/2 al Norte' WHERE companyID = 2;


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Jennyfer Ester Salmeron Perez'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_NAME';
		
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Wester Union 3c al Este 1/2 al Norte'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_ADDRESS';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '2023-10-24'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_LICENSE_EXPIRED';
		
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '500'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_MAX_USER';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '8888-8888'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PHONE';
	
		
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '8888-8888'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_PHONE';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = ','
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_CSV_SPLIT';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'app_invoice_billing/viewRegisterVariedadesCarlosLuis'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'INVOICE_URL_PRINTER';
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'app_box_share/viewRegisterVariedadesCarlosLuis'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'BOX_SHARE_URL_PRINTER';
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'app_box_sharecapital/viewRegisterVariedadesCarlosLuis'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'BOX_SHARECAPITAL_URL_PRINTER';
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'app_box_canceldocument/viewRegisterVariedadesCarlosLuis'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'BOX_CANCELDOCUMENT_URL_PRINTER';
	
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'variedades_aqua_mar'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_CXC_WSDL_SIN_RIESGO_USUARIO';	
	

UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '291-080000-00000'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_ID';
	
		
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '291-080000-00000'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_COMPANY_IDENTIFIER';
	


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'false'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'INVOICE_BUTTOM_PRINTER_FIDLOCAL_PAYMENT';


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'false'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'INVOICE_BUTTOM_PRINTER_FIDLOCAL_PAYMENT_AND_AMORTIZACION';	