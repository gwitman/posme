select 
	cust.entityID as clienteID,
	cust.customerNumber as clienteNumero,
	concat(nat.firstName ,' ' , nat.lastName) as cliente,
	rl.employeeID as colaboradorID,
	ex.employeNumber as colaboradorNumero,
	nat2.firstName as colaborador
from 
	tb_naturales nat 
	inner join tb_customer cust on 
		nat.entityID = cust.entityID 
	left join tb_relationship rl on 
		nat.entityID = rl.customerID  
	left join tb_employee ex on 
		rl.employeeID = ex.entityID 
	left join tb_naturales nat2 on 
		ex.entityID = nat2.entityID 
where
	nat.companyID = 2 and 
	nat.isActive = 1 and 
	cust.isActive = 1
order by 
	nat2.firstName asc ,concat(nat.firstName ,' ' , nat.lastName) asc 