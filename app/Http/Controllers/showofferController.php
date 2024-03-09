<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;



class PaymentController extends Controller
{
    public function showoffer(){
        return view('showoffer');
    }
}