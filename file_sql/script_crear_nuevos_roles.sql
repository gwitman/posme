set @nameCompany := 'AQUA_MAR';
set @nameCompanyOrigen := 'VARIEDADES_CARLOS_LUIS';

#insertar roles
insert into tb_role(
	companyID,branchID,`name`,description,isAdmin,createdOn,urlDefault,createdBy,isActive
) 
select 	
	companyID,branchID,
	REPLACE(cc.`name`,@nameCompanyOrigen,@nameCompany) as `name`,description,
	createdOn,'core_dashboards' as urlDefault,0 as isAdmin,createdBy ,isActive
from 
	tb_role cc 
where
	cc.`name` like concat(@nameCompanyOrigen,'@%'); 



#insertar permisos del rol admin
set @roleAdmin := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@ADMINISTRADOR') );
insert into tb_user_permission(elementID,companyID,branchID,roleID,selected,inserted,deleted,edited)
select 
	e.elementID,e.companyID,e.branchID,@roleAdmin roleID,e.selected,e.inserted,e.deleted,e.edited
from 
	tb_user_permission e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@ADMINISTRADOR');


set @roleAdmin := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@ADMINISTRADOR') );
insert into tb_role_autorization(companyID,componentAutorizationID,roleID,branchID)
select 
	 e.companyID,
	 componentAutorizationID,
	 @roleAdmin,
	 e.branchID
from 
	tb_role_autorization e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@ADMINISTRADOR');


#insertar permisos del rol facturador
set @roleFacturador := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@FACTURADOR') );
insert into tb_user_permission(elementID,companyID,branchID,roleID,selected,inserted,deleted,edited)
select 
	e.elementID,e.companyID,e.branchID,@roleFacturador roleID,e.selected,e.inserted,e.deleted,e.edited
from 
	tb_user_permission e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@FACTURADOR');

set @roleFacturador := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@FACTURADOR') );
insert into tb_role_autorization(companyID,componentAutorizationID,roleID,branchID)
select 
	 e.companyID,
	 componentAutorizationID,
	 @roleFacturador,
	 e.branchID
from 
	tb_role_autorization e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@FACTURADOR');

#crear el rol de supervisor
set @roleSupervisor := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@SUPERVISOR') );
insert into tb_user_permission(elementID,companyID,branchID,roleID,selected,inserted,deleted,edited)
select 
	e.elementID,e.companyID,e.branchID,@roleSupervisor roleID,e.selected,e.inserted,e.deleted,e.edited
from 
	tb_user_permission e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@SUPERVISOR');


set @roleSupervisor := (select u.roleID from tb_role u where u.`name` = CONCAT(@nameCompany,'@SUPERVISOR') );
insert into tb_role_autorization(companyID,componentAutorizationID,roleID,branchID)
select 
	 e.companyID,
	 componentAutorizationID,
	 @roleSupervisor,
	 e.branchID
from 
	tb_role_autorization e
	inner join tb_role r on 
		e.roleID = r.roleID 
where
	r.`name` = CONCAT(@nameCompanyOrigen,'@SUPERVISOR');