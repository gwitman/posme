@echo off
cls 
echo 1/4 Antes de Empezar cualquier operacion, eliminar BD
echo Password:Ax.#123manGonzalez
echo BD:fidlocal_erp_inversionista
echo Ejecutar la siguiente intruccion: 
echo DROP DATABASE fidlocal_erp_inversionista;
C:\xampp\mysql\bin\mysql -h fidlocal.com -u fidlocal -p

@ECHO. 
@ECHO.
cls  
echo 2/4 Antes de Empezar cualquier operacion, crear BD
echo Password:Ax.#123manGonzalez
echo BD:fidlocal_erp_inversionista
echo Ejecutar la siguiente intruccion: 
echo CREATE DATABASE fidlocal_erp_inversionista;
C:\xampp\mysql\bin\mysql -h fidlocal.com -u fidlocal -p

@ECHO. 
@ECHO. 
cls 
echo 3/4 Realizando Backup de la base de datos 
echo nssystem_erp_fidlocal_produccion Local
echo Escribir la siguiente Contrase;a: Ax.#123manGonzalez
C:\xampp\mysql\bin\mysqldump.exe -h localhost -u fidlocal -p --routines nssystem_erp_fidlocal_produccion > nssystem_erp_fidlocal_produccion.sql

@ECHO.
@ECHO.
cls  
echo 4/4 Realizando Restore de la base de datos del servidor 
echo fidlocal_erp_inversionista
echo Escribir la siguiente Contrase;a: Ax.#123manGonzalez
C:\xampp\mysql\bin\mysql -h fidlocal.com -u fidlocal -p fidlocal_erp_inversionista < nssystem_erp_fidlocal_produccion.sql

@ECHO. 
@ECHO. 
echo Proceso Finalizado Soucess..