select 
	proveedor.firstName as proveedor,
    cliente.firstName as cliente,
    i.documentNumber as desembolso,
   (select max(lp.balanceStart) from tb_customer_credit_amoritization lp where lp.customerCreditDocumentID = i.customerCreditDocumentID) as capital_inicial_invertido,
    i.balance as saldo_de_capital_invertido,
   (select min(lp.dateApply) from tb_customer_credit_amoritization lp where lp.customerCreditDocumentID = i.customerCreditDocumentID) as primera_fecha_pago ,
   ci.name as periodo
from 
	tb_customer_credit_document i 
    inner join tb_naturales proveedor on 
		proveedor.entityID = i.providerIDCredit
	inner join tb_naturales cliente on 
		cliente.entityID = i.entityID 
	inner join tb_catalog_item ci on 
		ci.catalogItemID = i.periodPay 
where
	proveedor.entityID = 528  
order by 
	i.customerCreditDocumentID 