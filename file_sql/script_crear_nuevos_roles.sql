set @nameCompany        := 'VARIEDADES_MONDRAGON';
set @domainDestino 			:= CONCAT('@',LOWER( @nameCompany));


set @nameCompanyOrigen  := 'VARIEDADES_CARLOS_LUIS';
set @domainOrigen 		:=  CONCAT('@',LOWER( @nameCompanyOrigen));
set @userAdministrador 	:=  'administrador';
set @userFacturador 	:=  'facturador';
set @userSupervisor     :=  'supervisor'; 


#INSERTAR ROLES
insert into tb_role(
	companyID,branchID,`name`,description,isAdmin,createdOn,urlDefault,createdBy,isActive
) 
select 	
	companyID,branchID,
	REPLACE(cc.`name`,@nameCompanyOrigen,@nameCompany) as `name`,description,0 as isAdmin,
	createdOn,'core_dashboards' as urlDefault,createdBy ,isActive
from 
	tb_role cc 
where
	cc.`name` like concat(@nameCompanyOrigen,'@%'); 



#CONFIGURAR ADMINISTRADOR
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


#CONFIGURAR RACTURADOR
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


#CONFIGURAR SUPERVISOR
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
	
	
	

#INSERTAR USUARIOS
insert into tb_user (companyID,branchID,nickname,`password`,createdOn,isActive,email,createdBy,employeeID)
select 
	companyID,
	branchID,
	REPLACE(c.email,@domainOrigen,@domainDestino)  as nickname,
	`password`,
	createdOn,
	isActive,
	REPLACE(c.email,@domainOrigen,@domainDestino) as email,
	createdBy,
	0 as employeeID 
from 
	tb_user c 
where 
	c.email = CONCAT(@userAdministrador,@domainOrigen) or 
	c.email = CONCAT(@userFacturador,@domainOrigen) or 
	c.email = CONCAT(@userSupervisor,@domainOrigen);
	
#ASOCIAR ROLES A LOS USUARIOS
insert into tb_membership (branchID,companyID,roleID,userID)
select 
    m.branchID,
	m.companyID,
	urd.roleID,
	dd.userID
from 
	tb_membership m 
	inner join tb_user u on m.userID = u.userID  	
	inner join (
			select 
				x.userID,
				replace(x.email ,@domainDestino,'') as email
			from 
				tb_user x 
			where 
				x.email = CONCAT(@userAdministrador,@domainDestino)  or 
				x.email = CONCAT(@userFacturador,@domainDestino)  or 
				x.email = CONCAT(@userSupervisor,@domainDestino)  	
	) as dd on dd.email = replace(u.email ,@domainOrigen,'')
	
	inner join tb_role ur on 	
		ur.roleID = m.roleID 
		
	inner join (
			select 
				z.roleID,
				replace(z.name ,replace(@nameCompany,'@',''),'') as nombrez
			from 
				tb_role z
			where 
				z.name = CONCAT(replace(@domainDestino,'@',''),'@',@userAdministrador)  or 
				z.name = CONCAT(replace(@domainDestino,'@',''),'@',@userFacturador)  or 
				z.name = CONCAT(replace(@domainDestino,'@',''),'@',@userSupervisor)  	
	) as urd on urd.nombrez  = replace( ur.name , replace(@nameCompanyOrigen,'@',''),'')
		
where		
	u.email = CONCAT(@userAdministrador,@domainOrigen)  or 
	u.email = CONCAT(@userFacturador,@domainOrigen)  or 
	u.email = CONCAT(@userSupervisor,@domainOrigen) ;
	
	



#INSERTAR TAG
insert into tb_user_tag (tagID,companyID,branchID,userID)
select 
	m.tagID,
  m.companyID,
	m.branchID,
	dd.userID	
from 
	tb_user_tag m 
	inner join tb_user u on m.userID = u.userID  	
	inner join (
			select 
				x.userID,
				replace(x.email ,@domainDestino,'') as email
			from 
				tb_user x 
			where 
				x.email = CONCAT(@userAdministrador,@domainDestino)  or 
				x.email = CONCAT(@userFacturador,@domainDestino)  or 
				x.email = CONCAT(@userSupervisor,@domainDestino)  	
	) as dd on dd.email = replace(u.email ,@domainOrigen,'')
where		
	u.email = CONCAT(@userAdministrador,@domainOrigen)  or 
	u.email = CONCAT(@userFacturador,@domainOrigen)  or 
	u.email = CONCAT(@userSupervisor,@domainOrigen) ;


#INSERTAR BODEGAS 
insert into tb_user_warehouse (companyID,branchID,userID,warehouseID)
select 
  m.companyID,
	m.branchID,
	dd.userID,
	m.warehouseID	
from 
	tb_user_warehouse m 
	inner join tb_user u on m.userID = u.userID  	
	inner join (
			select 
				x.userID,
				replace(x.email ,@domainDestino,'') as email
			from 
				tb_user x 
			where 
				x.email = CONCAT(@userAdministrador,@domainDestino)  or 
				x.email = CONCAT(@userFacturador,@domainDestino)  or 
				x.email = CONCAT(@userSupervisor,@domainDestino)  	
	) as dd on dd.email = replace(u.email ,@domainOrigen,'')
where		
	u.email = CONCAT(@userAdministrador,@domainOrigen)  or 
	u.email = CONCAT(@userFacturador,@domainOrigen)  or 
	u.email = CONCAT(@userSupervisor,@domainOrigen) ;
