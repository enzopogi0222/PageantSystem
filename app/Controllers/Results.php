<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Results extends BaseController
{
    public function index()
    {
        return view ('admin/results');
    }
}
