<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller
{
    public function index() {
        return view('dashboard.index');
    }

    public function pageNotAllowed($message)
    {
        $message = Crypt::decryptString($message);
        return view('cm.page_not_allowed',compact('message'));
    }
}
