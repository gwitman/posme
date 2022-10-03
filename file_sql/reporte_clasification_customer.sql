#Reporte de calidad de cartera
CREATE TEMPORARY TABLE tb_customer_credit_tmp_clasification_customer  
select 
	c.entityID,
	current_date() as dateHiatory,
	cc.documentNumber,
	cc.statusID as statusDocument, 
	round(if(cc.currencyID = 1 /*cordoba*/, cc.balance / cc.exchangeRate,cc.balance  ),2) as balanceDocumentDolares, 
	cca.creditAmortizationID,	
	round(if(cc.currencyID = 1 /*cordoba*/, cca.capital / cc.exchangeRate,cca.capital  ),2) as capitalShareDolares, 
	round(if(cc.currencyID = 1 /*cordoba*/, cca.interest / cc.exchangeRate,cca.interest  ),2) as interesShareDolares, 
	cca.statusID as statusShare,
	if(
		cca.statusID = 78 /*registrado*/ and datediff(cca.dateApply,current_date()) < 0 /*cuostas pasadas*/, 
		abs(datediff(cca.dateApply,current_date())) /*calculo de dias*/,
		cca.dayDelay 
	) as diasAtraso  
from 
	tb_customer_credit_document cc 
	inner join tb_customer c on 
		cc.entityID = c.entityID 
	inner join tb_naturales nat on 
		c.entityID = nat.entityID 
	inner join tb_customer_credit_amoritization  cca on 
		cc.customerCreditDocumentID = cca.customerCreditDocumentID
WHERE
	c.isActive = 1 
	and cc.isActive = 1 
	and nat.isActive = 1 
	and cca.isActive = 1
	and cc.statusID in (77 /*registrado*/,82 /*cancelado*/) 	; 

#Limpiar Datos
delete from tb_customer_credit_clasification where dateHistory = current_date();

#Ingresar Cliente
insert into tb_customer_credit_clasification(entityID,dateHistory) 
SELECT entityID,dateHiatory FROM tb_customer_credit_tmp_clasification_customer c group by entityID,dateHiatory; 

#Actualizar todas las cuotas que el cliente se ha atrasado para pagar
#Actualizar todo el capital que el cliente se ha atrasado, sumado en cada cuota atrasada aunque este a la fecha ya este pagado
#Actualizar todo el interes que el cliente se ha atrasado, sumado en cada cuota atrasada aunque este a la fecha ya este pagado
#Actualizar cual es la cuota se ha tardado mas en pagar el cliente y en cuantos dias
update 
tb_customer_credit_clasification,
(
SELECT 
	c.entityID,c.dateHiatory,
	count(*) as numberShareLate,
	sum(c.capitalShareDolares) as capitalShareDolares,
	sum(c.interesShareDolares) as interesShareDolares,
	max(c.diasAtraso) as diasAtraso
FROM 
	tb_customer_credit_tmp_clasification_customer c 
where 
	c.diasAtraso > 8 
group by 
	entityID,dateHiatory) pk 
set 
	tb_customer_credit_clasification.numberShareLate = pk.numberShareLate ,
	tb_customer_credit_clasification.amountCapitalLate = pk.capitalShareDolares,
	tb_customer_credit_clasification.amountInterestLate = pk.interesShareDolares,
	tb_customer_credit_clasification.maxDayMora = pk.diasAtraso 
where
	tb_customer_credit_clasification.entityID = pk.entityID and 
	tb_customer_credit_clasification.dateHistory = pk.dateHiatory;


#Actualizar cuantos creditos estan abiertos
#Actualizar cuanto es el monto saldo de los creditos que estan abierto 
update 
tb_customer_credit_clasification,
(
select 
	TLM.entityID,TLM.dateHiatory,	
	count(TLM.documentNumber) as documentosAbiertos,
	sum(TLM.balanceDocumentDolares) AS balanceDocumentDolares
FROM 
	(
		SELECT 
			distinct 
			c.entityID,c.dateHiatory,documentNumber,balanceDocumentDolares
		FROM 
			tb_customer_credit_tmp_clasification_customer c 
		where 
			statusDocument  = 77 
	) TLM
group by 
	TLM.entityID,TLM.dateHiatory
) pk 
set 
	tb_customer_credit_clasification.numberCreditAbiertos = pk.documentosAbiertos,
	tb_customer_credit_clasification.amountCapitalAbierto = pk.balanceDocumentDolares 
where
	tb_customer_credit_clasification.entityID = pk.entityID and 
	tb_customer_credit_clasification.dateHistory = pk.dateHiatory;
	

#Actualizar cuantos creditos estan cancelados
#Actualizar cuanto es el monto saldo de los creditos que estan cancelados 
update 
tb_customer_credit_clasification,
(
select 
	TLM.entityID,TLM.dateHiatory,	
	count(TLM.documentNumber) as documentosCancelado,
	sum(TLM.balanceDocumentDolares) AS balanceDocumentDolares
FROM 
	(
		SELECT 
			distinct 
			c.entityID,c.dateHiatory,documentNumber,balanceDocumentDolares
		FROM 
			tb_customer_credit_tmp_clasification_customer c 
		where 
			statusDocument  = 82  
	) TLM
group by 
	TLM.entityID,TLM.dateHiatory
) pk 
set 
	tb_customer_credit_clasification.numberCreditCancelados = pk.documentosCancelado ,
	tb_customer_credit_clasification.amountCapitalCancelado = pk.balanceDocumentDolares 
where
	tb_customer_credit_clasification.entityID = pk.entityID and 
	tb_customer_credit_clasification.dateHistory = pk.dateHiatory;
	
drop table tb_customer_credit_tmp_clasification_customer;  

select 
    cus.customerNumber,
	 nat.firstName,
	 #tm.clasificationID,
	 #tm.entityID,
	 #tm.dateHistory,
	 tm.numberShareLate as numberShareLate8Day,
	 tm.amountCapitalLate as amountCapitalLate8Day,
	 tm.amountInterestLate as amountInterestLate8Day,
	 tm.maxDayMora,
	 tm.numberCreditAbiertos,
	 tm.amountCapitalAbierto,
	 tm.numberCreditCancelados,
	 case 
	 	when tm.maxDayMora > 17 then 
	 		'MALO'
	 	when tm.numberShareLate > 5 then 
	 		'MALO'
	 	else
	 		'BUENO' 
    end as clasification
from 
	tb_customer_credit_clasification tm
	inner join tb_naturales nat on 
		tm.entityID = nat.entityID  
	inner join tb_customer cus on 
		nat.entityID = cus.entityID 
where
	tm.dateHistory = current_date() 
order by 
	clasification 