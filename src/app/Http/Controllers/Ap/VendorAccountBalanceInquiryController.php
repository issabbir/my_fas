<?php

namespace App\Http\Controllers\Ap;

use App\Contracts\Ap\ApLookupContract;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;

class VendorAccountBalanceInquiryController extends Controller
{
    protected $glCoaParam;
    protected $lookupManager;
    protected $flashMessageManager;

    /** @var ApLookupManager */
    private $apLookupManager;

    public function __construct(LookupManager $lookupManager, ApLookupContract $apLookupManager, FlashMessageManager $flashMessageManager)
    {
        $this->lookupManager = $lookupManager;
        $this->flashMessageManager = $flashMessageManager;
        $this->apLookupManager = $apLookupManager;
    }

    public function index()
    {

        return view('ap.vendor-account-balance-query.index', [
            'vendorType' => $this->apLookupManager->getVendorTypes(),
            'vendorCategory' => $this->apLookupManager->getVendorCategory(),
        ]);
    }
}
