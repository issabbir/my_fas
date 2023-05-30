<?php

namespace App\Http\Controllers\Ar;

use App\Contracts\Ap\ApLookupContract;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\Ar\ArLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;

class CustomerAccountBalanceInquiryController extends Controller
{
    protected $glCoaParam;
    protected $lookupManager;
    protected $flashMessageManager;
    private $arLookupManager;

    /** @var ApLookupManager */
    private $apLookupManager;

    public function __construct(ArLookupManager $arLookupManager,LookupManager $lookupManager, ApLookupContract $apLookupManager, FlashMessageManager $flashMessageManager)
    {
        $this->lookupManager = $lookupManager;
        $this->flashMessageManager = $flashMessageManager;
        $this->apLookupManager = $apLookupManager;
        $this->arLookupManager = $arLookupManager;
    }

    public function index()
    {

        return view('ar.customer-account-balance-query.index', [
            'vendorType' => $this->apLookupManager->getVendorTypes(),
            'vendorCategory' => $this->apLookupManager->getVendorCategory(),
            'customerCategory' => $this->arLookupManager->findCustomerCategory()
        ]);
    }
}
