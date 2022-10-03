USE nssystem_erp_fidlocal_produccion;

select 'Procesando...Contabilidad' as processsx;
delete from tb_journal_entry_detail;
delete from tb_journal_entry;
delete from tb_accounting_balance;


select 'Procesando...Clientes' as processsx;
delete from tb_customer_credit_amoritization;
delete from tb_customer_credit_document;
delete from tb_customer_credit_line where entityID not in ( 13,309);
delete from tb_customer_credit where entityID not in ( 13,309);
delete from tb_customer where entityID not in ( 13,309);


select 'Procesando...Transacciones' as processsx;
delete from tb_transaction_master_info;
delete from tb_transaction_master_purchase;
delete from tb_transaction_master_detail_credit;
delete from tb_transaction_master_concept;
delete from tb_transaction_master_detail;
delete from tb_transaction_master;


#delete from tb_fixed_assent;
#delete from tb_employee;
#delete from tb_provider; 

select 'Procesando...Productos' as processsx;
delete from tb_item_data_sheet_detail;
delete from tb_item_data_sheet;
delete from tb_kardex;
delete from tb_item_warehouse;
delete from tb_price;
delete from tb_provider_item;
delete from tb_item where itemID not in (4,5);



#DELETE FROM tb_catalog_item where catalogID = 10 and catalogItemID NOT IN ( 70,73,74);
#DELETE FROM tb_center_cost where classID <> 1;
#DELETE FROM tb_accounting_cycle where companyID = 2 and componentID = 4 ;
#DELETE FROM tb_accounting_period where companyID = 2 and componentID = 4 ;
select 'Procesando...Cuentas' as processsx;
DELETE FROM tb_account;