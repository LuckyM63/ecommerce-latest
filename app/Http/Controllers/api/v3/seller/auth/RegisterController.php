<?php

namespace App\Http\Controllers\api\v3\seller\auth;

use App\CPU\ImageManager;
use App\CPU\PdfManager;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Validator;
use function App\CPU\translate;

use Illuminate\Support\Facades\Log;


class RegisterController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'         => 'required|unique:sellers',
            'shop_address'  => 'required',
            'f_name'        => 'required',
            'l_name'        => 'required',
            'shop_name'     => 'required',
            'phone'         => 'required',
            'password'      => 'required|min:8',
            'image'         => 'required|mimes: jpg,jpeg,png,gif',
            'logo'          => 'required|mimes: jpg,jpeg,png,gif',
            'banner'        => 'required|mimes: jpg,jpeg,png,gif',
            'bottom_banner' => 'mimes: jpg,jpeg,png,gif',

             // 008

             //'gst_certificate' => 'mimes:pdf',
             'import_license' => 'mimes:pdf',
             'seller_license' => 'mimes:pdf',
             'ayush_license' => 'mimes:pdf',
             'factory_license' => 'mimes:pdf',
             'registration_certificate' => 'mimes:pdf',
             'iso_9001_certificate' => 'mimes:pdf',
             'international_license' => 'mimes:pdf',
            //  'business_pan' => '',
             // 008
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => Helpers::error_processor($validator)], 403);
        }

        DB::beginTransaction();
        try {
            $seller = new Seller();
            $seller->f_name = $request->f_name;
            $seller->l_name = $request->l_name;
            $seller->phone = $request->phone;
            $seller->email = $request->email;
            $seller->image = ImageManager::upload('seller/', 'webp', $request->file('image'));
            $seller->password = bcrypt($request->password);
            $seller->status =  $request->status == 'approved'?'approved': "pending";
            $seller->save();

            $shop = new Shop();
            $shop->seller_id = $seller->id;
            $shop->name = $request->shop_name;
            $shop->address = $request->shop_address;
            $shop->contact = $request->phone;
            $shop->image = ImageManager::upload('shop/', 'webp', $request->file('logo'));
            $shop->banner = ImageManager::upload('shop/banner/', 'webp', $request->file('banner'));
            $shop->bottom_banner = ImageManager::upload('shop/banner/', 'webp', $request->file('bottom_banner'));

            //008
           // $shop->bottom_banner = ImageManager::upload('shop/banner/', 'webp', $request->file('bottom_banner'));
           $shop->business_pan = $request->business_PAN_No; 
           $shop->gst_certificate = PdfManager::upload('shop/certificates/', $request->file('gst_certificate'));
            $shop->import_license = PdfManager::upload('shop/certificates/', $request->file('import_license'));
            $shop->seller_license = PdfManager::upload('shop/certificates/', $request->file('seller_license'));
            $shop->ayush_license = PdfManager::upload('shop/certificates/', $request->file('ayush_license'));
            $shop->factory_license = PdfManager::upload('shop/certificates/', $request->file('factory_license'));
            $shop->registration_certificate = PdfManager::upload('shop/certificates/', $request->file('registration_certificate'));
            $shop->iso_certificate = PdfManager::upload('shop/certificates/', $request->file('iso_9001_certificate'));
            $shop->international_license = PdfManager::upload('shop/certificates/', $request->file('international_license'));
            //008



            $shop->save();

            DB::table('seller_wallets')->insert([
                'seller_id' => $seller['id'],
                'withdrawn' => 0,
                'commission_given' => 0,
                'total_earning' => 0,
                'pending_withdraw' => 0,
                'delivery_charge_earned' => 0,
                'collected_cash' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

             // Log the request data
        Log::info('Request Data: ' . json_encode($request->all()));
         // Log the files received
    Log::info('Files Received: ' . json_encode($_FILES));


            DB::commit();
            return response()->json(['message' => translate('Shop apply successfully!')], 200);

        } 
        
        // catch (\Exception $e) {
        //     DB::rollback();
        //     return response()->json(['message' => translate('Shop apply fail!')], 403);
        // }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => translate('Shop apply fail!', 'en'), 'error' => $e->getMessage()], 403);
        }

    }
}
