<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Model\offer;
use Illuminate\Support\Arr;



class showofferController extends Controller
{
    // public function showoffer(){
    //     return view('showoffer');
    // }

    public function showoffer(){

        $showoffers = offer::all()->sortByDesc('created_at')->toArray();
            //   dump($showoffers);

        return view("admin-views.business-settings.showoffer.view",compact('showoffers'));
    }

    public function updateStatus($id){




        return view("admin-views.business-settings.showoffer.view",compact('showoffers'));
    }
    

    public function addOffer(Request $request){

        $data = $request->validate([
         'offer_id' => 'required'
        ]);
 
 
 
        $offer = new offers();
        $offer['offerId'] = $data['offer_id'];
        $offer->save();
 
         return redirect()->route('admin.showoffer');
     }
     public function deleteOffer(){
        return view("admin-views.business-settings.showoffer.view",compact('showoffers'));
    }
 
}