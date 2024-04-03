<?php

namespace App\Http\Controllers\Seller\Auth;

use App\CPU\ImageManager;
use App\CPU\PdfManager;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\Shop;
use App\Model\ShopPickupAddress;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CPU\Helpers;
use Illuminate\Support\Facades\Session;
use function App\CPU\translate;


class RegisterController extends Controller
{
    public function create()
    {
        $business_mode = Helpers::get_business_settings('business_mode');
        $seller_registration = Helpers::get_business_settings('seller_registration');
        if ((isset($business_mode) && $business_mode == 'single') || (isset($seller_registration) && $seller_registration == 0)) {
            Toastr::warning(translate('access_denied!!'));
            return redirect('/');
        }
        return view(VIEW_FILE_NAMES['seller_registration']);
    }

    public function store(Request $request)

    {

        $request->validate(
            [
                'image'         => 'required|mimes: jpg,jpeg,png,gif',
                'logo'          => 'required|mimes: jpg,jpeg,png,gif',
                'banner'        => 'required|mimes: jpg,jpeg,png,gif',
                'bottom_banner' => 'mimes: jpg,jpeg,png,gif',
                // 008

                'gst_certificate' => 'mimes:pdf',
                'import_license' => 'mimes:pdf',
                'seller_license' => 'mimes:pdf',
                'ayush_license' => 'mimes:pdf',
                'factory_license' => 'mimes:pdf',
                'registration_certificate' => 'mimes:pdf',
                'iso_9001_certificate' => 'mimes:pdf',
                'international_license' => 'mimes:pdf',
                'business_pan' => '',
                // 008
                'email'         => 'required|unique:sellers',
                'shop_address'  => 'required',
                'shop_city'  => 'required',
                'shop_state'  => 'required',
                'shop_country'  => 'required',
                'shop_pincode'  => 'required',
                'f_name'        => 'required',
                'l_name'        => 'required',
                'shop_name'     => 'required',
                'phone'         => 'required',
                'password'      => 'required|min:8'
            ],
            [

                'image.required'  => translate('image_is_required') . '!',
                'logo.required'  => translate('logo_name_is_required') . '!',
                'banner.required'  => translate('banner_name_is_required') . '!',
                'bottom_banner.required'  => translate('bottom_banner_name_is_required') . '!',
                'shop_address.required'  => translate('shop_address_is_required') . '!',
                'shop_city.required'  => translate('shop_city_is_required') . '!',
                'shop_state.required'  => translate('shop_state_is_required') . '!',
                'shop_country.required'  => translate('shop_country_is_required') . '!',
                'shop_pincode.required'  => translate('shop_pincode_is_required') . '!',
            ]
        );

        if ($request['from_submit'] != 'admin') {
            //recaptcha validation
            $recaptcha = Helpers::get_business_settings('recaptcha');
            if (isset($recaptcha) && $recaptcha['status'] == 1) {
                try {
                    $request->validate([
                        'g-recaptcha-response' => [
                            function ($attribute, $value, $fail) {
                                $secret_key = Helpers::get_business_settings('recaptcha')['secret_key'];
                                $response = $value;
                                $url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response;
                                $response = \file_get_contents($url);
                                $response = json_decode($response);
                                if (!$response->success) {
                                    $fail(\App\CPU\translate('ReCAPTCHA Failed'));
                                }
                            },
                        ],
                    ]);
                } catch (\Exception $exception) {
                }
            } else {
                if (strtolower($request->default_recaptcha_id_seller_regi) != strtolower(Session('default_recaptcha_id_seller_regi'))) {
                    Session::forget('default_recaptcha_id_seller_regi');
                    return back()->withErrors(\App\CPU\translate('Captcha Failed'));
                }
            }
        }

        DB::transaction(function ($r) use ($request) {
            $seller = new Seller();
            $seller->f_name = $request->f_name;
            $seller->l_name = $request->l_name;
            $seller->phone = $request->phone;
            $seller->email = $request->email;
            $seller->image = ImageManager::upload('seller/', 'webp', $request->file('image'));
            $seller->password = bcrypt($request->password);
            $seller->status =  $request->status == 'approved' ? 'approved' : "pending";
            $seller->save();

            $shop = new Shop();
            $shop->seller_id = $seller->id;
            $shop->name = $request->shop_name;
            $shop->address = $request->shop_address;
            $shop->house_no = $request->house_no;
            $shop->street_no = $request->street_no;
            $shop->city = $request->shop_city;
            $shop->state = $request->shop_state;
            $shop->country = $request->shop_country;
            $shop->pincode = $request->shop_pincode;
            $shop->contact = $request->phone;
            $shop->save();

            // // Check if pickup address is provided, if yes, use pickup address, else use shop address
            // $pickupAddress = $request->pickup_address ?? $request->shop_address;
            // $pickupCity = $request->pickup_city ?? $request->shop_city;
            // $pickupState = $request->pickup_state ?? $request->shop_state;
            // $pickupCountry = $request->pickup_country ?? $request->shop_country;
            // $pickupPincode = $request->pickup_pincode ?? $request->shop_pincode;

            // $shop->pickup_address = $pickupAddress;
            // $shop->pickup_city = $pickupCity;
            // $shop->pickup_state = $pickupState;
            // $shop->pickup_country = $pickupCountry;
            // $shop->pickup_pincode = $pickupPincode;

            $pickupAddress = new ShopPickupAddress();
            $pickupAddress->shop_id = $shop->id;
            $pickupAddress->address = $request->pickup_address ?? $request->shop_address;
            $pickupAddress->house_no = $request->pickup_house_no ?? $request->house_no;
            $pickupAddress->street_no = $request->pickup_street_no ?? $request->street_no;
            $pickupAddress->city = $request->pickup_city ?? $request->shop_city;
            $pickupAddress->state = $request->pickup_state ?? $request->shop_state;
            $pickupAddress->country = $request->pickup_country ?? $request->shop_country;
            $pickupAddress->pincode = $request->pickup_pincode ?? $request->shop_pincode;
            $pickupAddress->save();

            // Update the default pickup address ID in the shops table
            $shop->default_pickup_address_id = $pickupAddress->id;
            $shop->save();
            // 008
            $shop->business_pan = $request->business_pan;
            // 008

            $shop->image = ImageManager::upload('shop/', 'webp', $request->file('logo'));
            $shop->banner = ImageManager::upload('shop/banner/', 'webp', $request->file('banner'));

            //008
            $shop->bottom_banner = ImageManager::upload('shop/banner/', 'webp', $request->file('bottom_banner'));
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
        });

        if ($request->status == 'approved') {
            Toastr::success(translate('shop_apply_successfully'));
            return back();
        } else {
            // Toastr::success('Request data: ' . json_encode($request->all()));

            Toastr::success(translate('shop_apply_successfully'));
            return redirect()->route('seller.auth.login');
        }
    }

  



}
