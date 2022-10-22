set @var_transactionNumberAbono 	:=  'SHR00005849';
set @var_transactionID 				:= 0;
set @var_transactionID				:= (select c.transactionMasterID from tb_transaction_master c where c.transactionNumber = @var_transactionNumberAbono);
set @var_documentID					:= (select c.componentItemID from tb_transaction_master_detail c where c.transactionMasterID = @var_transactionID);
set @var_IMPORTE						:= (	
													select 
														c.value
													from 
														tb_transaction_master_concept c
														inner join tb_transaction_concept l on 
															c.conceptID = l.conceptID 
													where
														c.transactionMasterID = @var_transactionID and 
														c.componentItemID = @var_documentID and 
														l.name = 'IMPORTE'
												);	
set @var_INTERES						:= (
													select 
														c.value
													from 
														tb_transaction_master_concept c
														inner join tb_transaction_concept l on 
															c.conceptID = l.conceptID 
													where
														c.transactionMasterID = @var_transactionID and 
														c.componentItemID = @var_documentID and 
														l.name = 'INTERES'
											);
set @var_TC								:= (
													select 
														c.value
													from 
														tb_transaction_master_concept c
														inner join tb_transaction_concept l on 
															c.conceptID = l.conceptID 
													where
														c.transactionMasterID = @var_transactionID and 
														c.componentItemID = @var_documentID and 
														l.name = 'GANANCIA X T/C'
											);

	
	
###ACTUALIZACIONES
update tb_transaction_master set amount = 0 where transactionMasterID = @var_transactionID;
update tb_transaction_master_detail set amount = 0 where transactionMasterID = @var_transactionID;
update tb_transaction_master_concept set value = 0 where transactionMasterID = @var_transactionID and  componentItemID = @var_documentID;	
update tb_customer_credit_document set balance = balance + @var_IMPORTE where customerCreditDocumentID = @var_documentID;

select 
	@var_IMPORTE,
	@var_INTERES,
	@var_TC, 
	round((@var_IMPORTE + @var_INTERES + @var_TC),2) as Total,
	c.reference1 as Factura,
	c.reference2 as SaldoAnterior,
	c.reference3 as IdAmortization,
	c.reference4 as NuevoSaldo,
	'78' CambiarEstadoAmortizationRegistrado,
	'77' CambiarEstadoDocumnentoRegistro
from 
	tb_transaction_master_detail c
where
	c.transactionMasterID = @var_transactionID;
	
select 
	*
from 
	tb_customer_credit_amoritization c 
where
	c.customerCreditDocumentID = @var_documentID 
order by 
	c.dateApply desc
limit 5;
	
select * from tb_customer_credit_document c where c.customerCreditDocumentID = @var_documentID ;