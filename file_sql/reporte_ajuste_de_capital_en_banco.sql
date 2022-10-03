select 
	round(sum(tx.balanceNIO),2) as total
from
	(
		select 
			nat.firstName,
			if(cc.currencyID = 2 , cc.balance / cc.exchangeRate , cc.balance) as balanceNIO
		from 
			tb_customer_credit_document cc
			inner join tb_workflow_stage ws on 
				cc.statusID = ws.workflowStageID 
			inner join tb_naturales nat on 
				cc.entityID = nat.entityID 
		where
			cc.companyID = 2 and 
			cc.isActive = 1 and 
			ws.vinculable = 1
	) as tx 
	