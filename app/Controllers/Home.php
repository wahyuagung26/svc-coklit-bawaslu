<?php

namespace App\Controllers;

use Config\Services;

class Home extends BaseController
{
    public function index()
    {
        Services::toolbar()->respond();

        // return view('welcome_message');
    }
}
