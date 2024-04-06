<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class categoryseoController extends Controller
{
    //
    public function showoffer(){

        $showoffers = offer::all()->sortByDesc('created_at')->toArray();
            //   dump($showoffers);

        return view("admin-views.business-settings.showoffer.view",compact('showoffers'));
    }

    
    
    public function updateStatus($id){

             
        $showid = offer::find($id);

        if($showid){
            if($showid->status){
                $showid->status= 0;
            }else{

                $showid->status=1;
            }

           
        }

        $showid->save();
                // return back();
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
     public function deleteOffer($id){
        $deletid = offer::find($id);

        $deletid = save();
        return view("admin-views.business-settings.showoffer.view",compact('showoffers'));
    }
}
