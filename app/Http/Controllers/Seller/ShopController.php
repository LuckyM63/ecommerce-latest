<?php

namespace App\Http\Controllers\Seller;


use App\CPU\PdfManager;
use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\Shop;
use App\Model\ShopPickupAddress;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class ShopController extends Controller
{
    public function view(Request $request)
    {
        $shop = Shop::where(['seller_id' => auth('seller')->id()])->first();
        if (isset($shop) == false) {
            DB::table('shops')->insert([
                'seller_id' => auth('seller')->id(),
                'name' => auth('seller')->user()->f_name,
                'address' => '',
                'contact' => auth('seller')->user()->phone,
                'image' => 'def.png',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $shop = Shop::where(['seller_id' => auth('seller')->id()])->first();
        }

        $minimum_order_amount = Helpers::get_business_settings('minimum_order_amount_status');
        $minimum_order_amount_by_seller = \App\CPU\Helpers::get_business_settings('minimum_order_amount_by_seller');
        $free_delivery_status = Helpers::get_business_settings('free_delivery_status');
        $free_delivery_responsibility = Helpers::get_business_settings('free_delivery_responsibility');

        if ($request->pagetype == 'order_settings' && (($minimum_order_amount && $minimum_order_amount_by_seller) || ($free_delivery_status && $free_delivery_responsibility == 'seller'))) {
            $seller = Seller::find($shop->seller_id);
            return view('seller-views.shop.order-settings', compact('seller'));
        }

        return view('seller-views.shop.shopInfo', compact('shop'));
    }

    public function edit($id)
    {
        $shop = Shop::where(['seller_id' =>  auth('seller')->id()])->first();
        return view('seller-views.shop.edit', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'banner'      => 'mimes:png,jpg,jpeg|max:2048',
            'image'       => 'mimes:png,jpg,jpeg|max:2048',

            // 008

            'gst_certificate' => 'mimes:pdf',
            'import_license' => 'mimes:pdf',
            'seller_license' => 'mimes:pdf',
            'ayush_license' => 'mimes:pdf',
            'factory_license' => 'mimes:pdf',
            'registration_certificate' => 'mimes:pdf',
            'iso_9001_certificate' => 'mimes:pdf',
            'international_license' => 'mimes:pdf',

            // 008
        ], [
            'banner.mimes'   => 'Banner image type jpg, jpeg or png',
            'banner.max'     => 'Banner Maximum size 2MB',
            'image.mimes'    => 'Image type jpg, jpeg or png',
            'image.max'      => 'Image Maximum size 2MB',
        ]);
        // Toastr::success('Request data: ' . json_encode($request->all()));
        $shop = Shop::find($id);
        if($request->name){
            $shop->name = $request->name;
        }
        
        if($request->address){
            $shop->address = $request->address;
        }
        if($request->house_no){
            $shop->house_no = $request->house_no;
        }
        if($request->street_no){
            $shop->street_no = $request->street_no;
        }
        if($request->city){
            $shop->city = $request->city;
        }
        if($request->state){
            $shop->state = $request->state;
        }
        if($request->country){
            $shop->country = $request->country;
        }
        if($request->pinCode){
            $shop->pinCode = $request->pinCode;
        }
        if($request->pickup_address){
            $shop->pickup_address = $request->pickup_address;
        }
        if($request->pickup_city){
            $shop->pickup_city = $request->pickup_city;
        }
        if($request->pickup_state){
            $shop->pickup_state = $request->pickup_state;
        }
        if($request->pickup_country){
            $shop->pickup_country = $request->pickup_country;
        }
        if($request->pickup_pinCode){
            $shop->pickup_pinCode = $request->pickup_pinCode;
        }
        if($request->contact){
            $shop->contact = $request->contact;
        }
        if ($request->image) {
            $shop->image = ImageManager::update('shop/', $shop->image, 'webp', $request->file('image'));
        }
        if ($request->banner) {
            $shop->banner = ImageManager::update('shop/banner/', $shop->banner, 'webp', $request->file('banner'));
        }
        if ($request->bottom_banner) {
            $shop->bottom_banner = ImageManager::update('shop/banner/', $shop->bottom_banner, 'webp', $request->file('bottom_banner'));
        }
        // offer Banner For Theme Fashion
        if ($request->offer_banner) {
            $shop->offer_banner = ImageManager::update('shop/banner/', $shop->offer_banner, 'webp', $request->file('offer_banner'));
        }

        // 008
        // Toastr::success('Request data: ' . json_encode($request->all()));
        if ($request->gst_certificate) {
           // Toastr::success(translate('gst_cert.'));
            $shop->gst_certificate = PdfManager::upload('shop/certificates/', $request->file('gst_certificate'));
        }
        else{
           // Toastr::success(translate('gst_cert. No'));
        }
        
        if ($request->import_license) {
            $shop->import_license = PdfManager::upload('shop/certificates/', $request->file('import_license'));
        }
        
        if ($request->file('seller_license')) {
            $shop->seller_license = PdfManager::upload('shop/certificates/', $request->file('seller_license'));
        }
        
        if ($request->file('ayush_license')) {
            $shop->ayush_license = PdfManager::upload('shop/certificates/', $request->file('ayush_license'));
        }
        
        if ($request->file('factory_license')) {
            $shop->factory_license = PdfManager::upload('shop/certificates/', $request->file('factory_license'));
        }
        
        if ($request->file('registration_certificate')) {
            $shop->registration_certificate = PdfManager::upload('shop/certificates/', $request->file('registration_certificate'));
        }
        
        if ($request->file('iso_9001_certificate')) {
            $shop->iso_certificate = PdfManager::upload('shop/certificates/', $request->file('iso_9001_certificate'));
        }
        
        if ($request->file('international_license')) {
            $shop->international_license = PdfManager::upload('shop/certificates/', $request->file('international_license'));
        }
        


        $shop->save();

        // Toastr::info(translate('Shop_updated_successfully'));
        // return redirect()->route('seller.shop.view');
// 008
        Toastr::success(translate('shop_updated_successfully'));
        return back();

        // 008
    }

    public function vacation_add(Request $request, $id)
    {
        $shop = Shop::find($id);
        $shop->vacation_status = $request->vacation_status == 'on' ? 1 : 0;
        $shop->vacation_start_date = $request->vacation_start_date;
        $shop->vacation_end_date = $request->vacation_end_date;
        $shop->vacation_note = $request->vacation_note;
        $shop->save();

        Toastr::success(translate('Vacation_mode_updated_successfully'));
        return redirect()->back();
    }

    public function temporary_close(Request $request)
    {
        $shop = Shop::find($request->id);

        $shop->temporary_close = $request->get('status', 0);
        $shop->save();

        return response()->json([
            'status' => true,
            'message' => $request->status ? translate("temporary_close_active_successfully") : translate("temporary_close_inactive_successfully"),
        ], 200);
    }

    public function order_settings(Request $request)
    {
        if ($request->has('minimum_order_amount')) {
            Seller::where('id', auth('seller')->id())->update([
                'minimum_order_amount' => BackEndHelper::currency_to_usd($request->minimum_order_amount),
            ]);
        }

        if ($request->has('free_delivery_over_amount')) {
            Seller::where('id', auth('seller')->id())->update([
                'free_delivery_status' => $request->free_delivery_status == 'on' ? 1 : 0,
            ]);
            Seller::where('id', auth('seller')->id())->update([
                'free_delivery_over_amount' => BackEndHelper::currency_to_usd($request->free_delivery_over_amount),
            ]);
        }

        Toastr::success(translate('updated_successfully'));
        return back();
    }

   

    public function create_new_pickup_address(Request $request)
    {
        // Validate the request data
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'pickup_address' =>'required',
            'house_no' => 'required',
            'street_no' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'pincode' => 'required',
        ]);
    
        // Start a database transaction
        DB::beginTransaction();
    
        try {
            // Create a new pickup address
            $pickupAddress = new ShopPickupAddress();
            $pickupAddress->shop_id = $request->shop_id;
            $pickupAddress->address = $request->pickup_address;
            $pickupAddress->house_no = $request->house_no;
            $pickupAddress->street_no = $request->street_no;
            $pickupAddress->city = $request->city;
            $pickupAddress->state = $request->state;
            $pickupAddress->country = $request->country;
            $pickupAddress->pincode = $request->pincode;
            $pickupAddress->save();
    
            // Update the default pickup address ID of the shop
            $shop = Shop::findOrFail($request->shop_id);
            $shop->default_pickup_address_id = $pickupAddress->id;
            $shop->save();
    
            // Commit the transaction
            DB::commit();
    
            // Return a success response and redirect back
            Toastr::success(translate('pickup_address_updated'));
            Toastr::success(translate('kindly update on shiprocket'));
            return Redirect::back()->with('success', 'Pickup address created successfully');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();
    
            // Return an error response
            return response()->json(['message' => 'Failed to create pickup address: ' . $e->getMessage()], 500);
        }
    }
    
}
