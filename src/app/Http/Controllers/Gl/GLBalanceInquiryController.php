<?php

namespace App\Http\Controllers\Gl;

use App\Contracts\Ap\ApLookupContract;
use App\Entities\Gl\GlCoaParams;
use App\Http\Controllers\Controller;
use App\Managers\Ap\ApLookupManager;
use App\Managers\FlashMessageManager;
use App\Managers\LookupManager;

class GLBalanceInquiryController extends Controller
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
        $this->glCoaParam = new GlCoaParams();
    }

    public function index()
    {
        return view('gl.gl-balance-query.index', [
            'accountType' => $this->glCoaParam->get(),
        ]);
    }
}
