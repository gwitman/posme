UPDATE tb_company SET NAME = 'Fid Local' , address = 'Frente a union fenosa' WHERE companyID = 2;


UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Witman Jose Gonzalez Rostran'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_NAME';
		
	
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = 'Frente a union fenosa'
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
	tb_company_parameter.value = 'app_invoice_billing/viewRegister'
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
	tb_company_parameter.value = '281-180389-0004R'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_PROPIETARY_ID';
	
		
UPDATE tb_company_parameter, tb_parameter  SET 
	tb_company_parameter.value = '281-180389-0004R'
WHERE
	tb_company_parameter.parameterID = tb_parameter.parameterID AND 
	tb_parameter.name = 'CORE_COMPANY_IDENTIFIER';