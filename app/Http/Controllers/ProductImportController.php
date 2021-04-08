<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductImportRequest as Request;
use App\Managers\ProductImportManager;

class ProductImportController extends Controller
{
    public function import(Request $request, ProductImportManager $manager)
    {

    }
}
