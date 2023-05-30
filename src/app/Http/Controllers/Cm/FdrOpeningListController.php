<?php


namespace App\Http\Controllers\Cm;


use App\Contracts\LookupContract;
use App\Enums\Ap\ApFunType;
use App\Http\Controllers\Controller;

class FdrOpeningListController extends Controller
{
    private $lookupManager;

    public function __construct(LookupContract $lookupManager)
    {
        $this->lookupManager = $lookupManager;

    }
    public function index($id=null)
    {
        $investmentTypes = $this->lookupManager->getLFdrInvestmentType();
        $periodTypes = $this->lookupManager->getInvestmentPeriodTypes();
        $investmentStatus = $this->lookupManager->getFdrInvestmentStatus();

        $fiscalYear = $this->lookupManager->getCurrentFinancialYear();
        $department = $this->lookupManager->getDeptCostCenter();
        $billSecs = $this->lookupManager->getBillSections(ApFunType::AP_INVOICE_BILL_ENTRY);
        //$billRegs = $this->lookupManager->getBillRegisterOnFunction(ApFunType::AP_INVOICE_BILL_ENTRY);

        return view('cm.fdr-opening-list.index',compact('investmentStatus','periodTypes','investmentTypes','fiscalYear','department','billSecs'));

    }
}
