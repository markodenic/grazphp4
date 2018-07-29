<?php

namespace App\Http\Controllers;

use App\Customer;

/**
 * Class CustomersController
 * @package App\Http\Controllers
 */
class CustomersController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $customers = Customer::orderByName()->paginate();
        return view('customers', ['customers' => $customers]);
    }
}