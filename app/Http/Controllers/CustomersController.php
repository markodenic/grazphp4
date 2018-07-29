<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

/**
 * Class CustomersController
 * @package App\Http\Controllers
 */
class CustomersController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $customers = Customer::with('company')
            ->withLastAction()
            ->orderByField($request->get('order', 'name'))
            ->paginate();
        return view('customers', ['customers' => $customers]);
    }
}