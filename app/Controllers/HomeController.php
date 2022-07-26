<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\View;
use App\App;
use App\Services\InvoiceService;
use App\Container;

class HomeController
{
    public function index(): View
    {
        (new Container())->get(InvoiceService::class)->process([ 'user' => 'Simone'], 25);

        return View::make('index');
    }
}
