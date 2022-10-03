select 
	cl.firstName,
	cl.documentNumber,
	cl.Moneda,
	cl.customerCreditDocumentID,
	cl.Desembolso,
	cl.Balance,
	cl.capitalPagado1,
	cl.capitalPagado2,
	cl.capitalPagado1 + cl.capitalPagado2 as capitalPagadoTotal,
	(cl.Desembolso - cl.capitalPagado1 - cl.capitalPagado2 ) as BalanceNuevo,
	cl.Balance - (cl.Desembolso - cl.capitalPagado1 - cl.capitalPagado2 ) as Dif
from 
	(
		select 
			nat.firstName,
			c.documentNumber,
			cur.name as Moneda,
			c.customerCreditDocumentID,
			c.amount as Desembolso,
			c.balance as Balance,
			(
				select 
					SUM(
						CASE 
							WHEN cx.remaining = 0 THEN 
								cx.capital
							WHEN cx.remaining <> cx.`share` and cx.remaining >= cx.interest then 
								cx.`share` - cx.remaining
							WHEN cx.remaining <> cx.`share` and cx.remaining < cx.interest then 
								cx.capital 
							WHEN cx.statusID = 81 /*cuota cancelada*/ then 
								cx.capital 
							ELSE
								0
						END 
					) 
				from 
					tb_customer_credit_amoritization cx 
				where
					cx.customerCreditDocumentID = c.customerCreditDocumentID			
			) as capitalPagado1,
			(
				select 
						IFNULL(SUM(
							CASE 
								WHEN cx4.exchangeRate < 1 then 	
									cx4.value * cx4.exchangeRate /*cuando es dolares, el concepto hay que convertilo a dolares*/
								ELSE 
									cx4.value /*cuando es cordoba*/
							END 
						),0)
				from 
					tb_transaction_master cx2 
					inner join tb_transaction_master_detail cx3 	on 
						cx2.transactionMasterID = cx3.transactionMasterID 
					inner join tb_transaction_master_concept cx4 on 
						cx3.transactionMasterID = cx4.transactionMasterID 
						and cx3.componentItemID = cx4.componentItemID 
				where 
					cx2.transactionID in (- 1 /*,24*/ /*cancelar factura*/ , 25 /*abono al capital*/) 
					and cx4.conceptID in (37,39) /*concepto de impote*/
					and cx3.reference1 = c.documentNumber 
			) capitalPagado2 
		from 
			tb_customer_credit_document c
			inner join tb_currency cur on 
				c.currencyID = cur.currencyID 
			inner join tb_naturales nat on 
				c.entityID = nat.entityID 
		where
			c.isActive = 1 and 
			c.currencyID IN (1,2) /*cordoba,dolares*/ and 
			c.statusID in (77,82) /*registrado,cancelado*/ and 
			c.entityID not in (309,315)  
	) as cl
where
	cl.Balance - (cl.Desembolso - cl.capitalPagado1 - cl.capitalPagado2 ) not between -0.2 and 0.2
	and (cl.Desembolso - cl.capitalPagado1 - cl.capitalPagado2 ) not between -0.2 and 0.2 /*todos hay que corregir pero me voy enformar en estos*/
	