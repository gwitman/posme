<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');
class core_web_accounting {
   
   /**********************Variables Estaticas********************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
	private $CI; 
	
	
   /**********************Funciones******************************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
   public function __construct(){		
        $this->CI = & get_instance(); 
   }
   //Ciclos
   function cycleIsCloseByID($companyID,$cycleID){
		$this->CI->load->model("Component_Cycle_Model");
		$this->CI->load->model("core/Parameter_Model");
		$this->CI->load->model("core/Company_Parameter_Model");
		
		
		$objParameter			= $this->CI->Parameter_Model->get_rowByName("ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED");		
		$objCompanyParameter	= $this->CI->Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objParameter->parameterID);				
		$objCycle				= $this->CI->Component_Cycle_Model->get_rowByCycleID($cycleID);
		
		if(!$objCycle)
		throw new Exception("NO EXISTE EL CICLO CONTABLE");
	
		if($objCycle->statusID == $objCompanyParameter->value)
			return true;
		else
			return false;
		
   }
   function cycleIsEmptyByID($companyID,$cycleID){
		
		$this->CI->load->model("Component_Cycle_Model");
		$objCycle		= $this->CI->Component_Cycle_Model->get_rowByCycleID($cycleID);
		$countJournal	= $this->CI->Component_Cycle_Model->countJournalInCycle($cycleID,$companyID);
		if($countJournal > 0 )
			return false;
		else
			return true;
		
   }
   function cycleIsCloseByDate($companyID,$dateOn){
		
		$this->CI->load->model("Component_Cycle_Model");
		$this->CI->load->model("core/Parameter_Model");
		$this->CI->load->model("core/Company_Parameter_Model");
		
		$objParameter			= $this->CI->Parameter_Model->get_rowByName("ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED");		
		$objCompanyParameter	= $this->CI->Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objParameter->parameterID);		
		$objCycle				= $this->CI->Component_Cycle_Model->get_rowByCompanyIDFecha($companyID,$dateOn);
		
		if(!$objCycle)
		throw new Exception("NO EXISTE EL CICLO CONTABLE");
	
		if($objCycle->statusID == $objCompanyParameter->value)
			return true;
		else
			return false;
		
   }
   function cycleIsEmptyByDate($companyID,$dateOn){	
		
		$this->CI->load->model("Component_Cycle_Model");
		$objCycle		= $this->CI->Component_Cycle_Model->get_rowByCompanyIDFecha($companyID,$dateOn);
		$countJournal	= $this->CI->Component_Cycle_Model->countJournalInCycle($objCycle->cycleID,$companyID);
		if($countJournal > 0 )
			return false;
		else
			return true;
		
   }
   //Periodos
   function periodIsCloseByID($companyID,$periodID){
		$this->CI->load->model("Component_Cycle_Model");
		$this->CI->load->model("Component_Period_Model");
		$this->CI->load->model("core/Parameter_Model");
		$this->CI->load->model("core/Company_Parameter_Model");
		
		
		$objParameter			= $this->CI->Parameter_Model->get_rowByName("ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED");		
		$objCompanyParameter	= $this->CI->Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objParameter->parameterID);				
		$objPeriod				= $this->CI->Component_Period_Model->get_rowByPK($periodID);
		
		if(!$objPeriod)
		throw new Exception("NO EXISTE EL PERIODO CONTABLE");
	
		if($objPeriod->statusID == $objCompanyParameter->value)
			return true;
		else
			return false;
		
   }
   function periodIsEmptyByID($companyID,$periodID){
		
		$this->CI->load->model("Component_Cycle_Model");
		$this->CI->load->model("Component_Period_Model");
		$objPeriod		= $this->CI->Component_Period_Model->get_rowByPK($periodID);
		$countJournal	= $this->CI->Component_Period_Model->countJournalInPeriod($periodID,$companyID);
		if($countJournal > 0 )
			return false;
		else
			return true;
		
   }
   function periodIsCloseByDate($companyID,$dateOn){
		
		$this->CI->load->model("Component_Cycle_Model");
		$this->CI->load->model("Component_Period_Model");
		$this->CI->load->model("core/Parameter_Model");
		$this->CI->load->model("core/Company_Parameter_Model");
		
		$objParameter			= $this->CI->Parameter_Model->get_rowByName("ACCOUNTING_PERIOD_WORKFLOWSTAGECLOSED");		
		$objCompanyParameter	= $this->CI->Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objParameter->parameterID);		
		$objPeriod				= $this->CI->Component_Period_Model->get_rowByCompanyIDFecha($companyID,$dateOn);
		
		if(!$objPeriod)
		throw new Exception("NO EXISTE EL PERIODO CONTABLE");
	
		if($objPeriod->statusID == $objCompanyParameter->value)
			return true;
		else
			return false;
		
   }
   function periodIsEmptyByDate($companyID,$dateOn){	
		
		$this->CI->load->model("Component_Cycle_Model");
		$this->CI->load->model("Component_Period_Model");
		$objPeriod		= $this->CI->Component_Period_Model->get_rowByCompanyIDFecha($companyID,$dateOn);
		$countJournal	= $this->CI->Component_Period_Model->countJournalInPeriod($objPeriod->componentPeriodID,$companyID);
		if($countJournal > 0 )
			return false;
		else
			return true;
		
   }
   //Procesos
   function mayorizateAccount ($companyID,$branchID,$loginID,$accountID,$componentPeriodID,$componentCycleID,$balance_,$debit_,$credit_){
		$this->CI->load->model("Account_Model");
		$this->CI->load->model("Accounting_Balance_Model");
		
		$parentAccountID_ 	= 0;
		$objAccount 		= $this->CI->Account_Model->get_rowByPK($companyID,$accountID);
		
		if ($objAccount->parentAccountID !== null)
		{
			$this->mayorizateAccount ($companyID,$branchID,$loginID,$objAccount->parentAccountID,$componentPeriodID,$componentCycleID,$balance_,$debit_,$credit_);
		}
		
		$this->CI->Accounting_Balance_Model->updateBalance($companyID,$componentPeriodID,$componentCycleID,$accountID,$balance_,$debit_,$credit_);
		
   }
   function mayorizateCycle($companyID,$branchID,$loginID,$componentPeriodID,$componentCycleID){
		$this->CI->load->model("Journal_Entry_Model");
		$this->CI->load->model("Journal_Entry_Detail_Model");
		$this->CI->load->model("Accounting_Balance_Model");
		$this->CI->load->model("Component_Cycle_Model");
		$this->CI->load->library("core_web_parameter");
		
		
		$journalTypeClosed 			= 0;
		$minAccountID 				= 0;
		$maxAccountID 				= 0;
		$debit_ 					= 0;
		$credit_ 					= 0;
		$balance_ 					= 0;
		$componentAccountID 		= 4;
		$workflowStageCycleClosed_ 	= 0;
		
		//Obtener el ciclo
		$objCycle					= $this->CI->Component_Cycle_Model->get_rowByPK($componentPeriodID,$componentCycleID);
		
		//Obtener el estado cerrado de los ciclos
		$workflowStageCycleClosed_	= $this->CI->core_web_parameter->getParameter("ACCOUNTING_CYCLE_WORKFLOWSTAGECLOSED",$companyID)->value;		
		
		if($objCycle->statusID ==  $workflowStageCycleClosed_)
			return 1;				
		
		//Obtener el comprobante de Cierre	
		$journalTypeClosed 			= $this->CI->core_web_parameter->getParameter("ACCOUNTING_JOURNALTYPE_CLOSED",$companyID)->value;	
		
		//Limpiar la tabla Temporal
		$this->CI->Accounting_Balance_Model->deleteJournalEntryDetailSummary($companyID,$branchID,$loginID);
		
		//Obtener los comprobantes resumidos
		$this->CI->Accounting_Balance_Model->setJournalSummary($companyID,$branchID,$loginID,$componentCycleID,$journalTypeClosed);
		
		//Ingresar las cuentas en la tabla balance
		$this->CI->Accounting_Balance_Model->setAccountBalance($companyID,$branchID,$loginID,$componentCycleID,$componentPeriodID,$componentAccountID);
		
		//Mayorizar Cuentas
		$this->CI->Accounting_Balance_Model->clearCycle($companyID,$componentPeriodID,$componentCycleID);
		$minAccountID = $this->CI->Accounting_Balance_Model->getMinAccount($companyID,$branchID,$loginID);
		$maxAccountID = $this->CI->Accounting_Balance_Model->getMaxAccount($companyID,$branchID,$loginID);
		
		while (($minAccountID <= $maxAccountID) and ($minAccountID !== null)) {
			$objAccountBalance	= $this->CI->Accounting_Balance_Model->getInfoAccount($companyID,$branchID,$loginID,$minAccountID);
			$debit_ 			= $objAccountBalance->debit;
			$credit_ 			= $objAccountBalance->credit;
			
			$this->mayorizateAccount(
				$companyID,
				$branchID,
				$loginID,
				$minAccountID,
				$componentPeriodID,
				$componentCycleID,
				$balance_,
				$debit_,
				$credit_
			);
			
			$minAccountID 		= $this->CI->Accounting_Balance_Model->getMinAccountBy($companyID,$branchID,$loginID,$minAccountID);
		}
		
		
		
		//Limpiar la tabla Temporal
		$this->CI->Accounting_Balance_Model->deleteJournalEntryDetailSummary($companyID,$branchID,$loginID);
		
		return 1;
   }
}
?>