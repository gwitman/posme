#USE nssystem_puntoventa_produccion;

#insert into tb_role(companyID,branchID,name,description,isAdmin,createdOn,urlDefault,isActive,createdBy ) values
#(2,2,'Contabilidad','Contabilidad',0,now(),'core_dashboards',1,0);

#usuario:e.serrano@negociosplus.net
#password:e.serrano@negociosplus.net
#configurar permisos basicos

insert into tb_user_permission (companyID,branchID,elementID,roleID,selected,inserted,deleted,edited) values 
(2,2,58,41,  0 /*selected*/,0 /*inserted*/, 0 /*deleted*/, 0 /*edited*/ ),
(2,2,56,41,  0 /*selected*/,0 /*inserted*/, 0 /*deleted*/, 0 /*edited*/ ),
(2,2,55,41,  0 /*selected*/,0 /*inserted*/, 0 /*deleted*/, 0 /*edited*/ ),
(2,2,57,41,  0 /*selected*/,0 /*inserted*/, 0 /*deleted*/, 0 /*edited*/ ),
(2,2,53,41,  0 /*selected*/,0 /*inserted*/, 0 /*deleted*/, 0 /*edited*/ ),
(2,2,34,41,  0 /*selected*/,0 /*inserted*/, 0 /*deleted*/, 0 /*edited*/ ),
(2,2,33,41,  0 /*selected*/,0 /*inserted*/, 0 /*deleted*/, 0 /*edited*/ ),
(2,2,29,41,  0 /*selected*/,0 /*inserted*/, 0 /*deleted*/, 0 /*edited*/ ),
(2,2,32,41,  0 /*selected*/,0 /*inserted*/, 0 /*deleted*/, 0 /*edited*/ ),
(2,2,0,41,  0 /*selected*/,0 /*inserted*/, 0 /*deleted*/, 0 /*edited*/ );

#configurar como minimo una autorizacion
insert into tb_role_autorization (companyID,componentAutorizationID,roleID,branchID) values 
(2,6,41,2);