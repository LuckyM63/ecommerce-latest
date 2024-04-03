@extends('layouts.back-end.app')

@section('title', $seller->shop? $seller->shop->name : translate("shop_Name"))

@push('css_or_js')
<style>
    .pair-list>div {
        flex-wrap: wrap;
    }

    .certificates-list {
        display: flex;
        flex-direction: column;
    }

    .certificates-list .mb-3 {
        display: flex;
        align-items: center;
    }

    .certificates-list .key {
        min-width: 190px;
        /* Adjust the width as needed */
    }

    .certificates-list .value {
        display: flex;
        gap: 21px;
        /* Adjust the gap between colon and value as needed */
        align-items: center;
        margin-left: 10px;
        /* Adjust the gap between colon and value as needed */
    }

    .file-input {}
</style>


@endpush


@section('content')

<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/add-new-seller.png')}}" alt="">
            {{translate('seller_details')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Page Header -->
    <div class="page-header border-0 mb-4">
        <!-- Nav Scroller -->
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <!-- Nav -->
            <ul class="nav nav-tabs flex-wrap page-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.sellers.view',$seller->id) }}">{{translate('shop_overview')}}</a>
                </li>
                @if ($seller->status!="pending")
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'order']) }}">{{translate('order')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'product']) }}">{{translate('product')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'setting']) }}">{{translate('setting')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'transaction']) }}">{{translate('transaction')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'review']) }}">{{translate('review')}}</a>
                </li>
                @endif
            </ul>
            <!-- End Nav -->
        </div>
        <!-- End Nav Scroller -->
    </div>
    <!-- End Page Header -->

    <div class="card card-top-bg-element mb-5">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-3 justify-content-between">
                <div class="media flex-column flex-sm-row gap-3">
                    <img class="avatar avatar-170 rounded-0" onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" src="{{asset('storage/app/public/shop/'.$seller->shop->image)}}" alt="image">

                    <div class="media-body">
                        @if($seller->shop->temporary_close || ($seller->shop->vacation_status && $current_date >= date('Y-m-d', strtotime($seller->shop->vacation_start_date)) && $current_date <= date('Y-m-d', strtotime($seller->shop->vacation_end_date))))
                            <div class="d-flex justify-content-between gap-2 mb-4">
                                @if($seller->shop->temporary_close)
                                <div class="btn btn-soft-danger">{{translate('this_shop_currently_close_now')}} </div>
                                @elseif($seller->shop->vacation_status && $current_date >= date('Y-m-d', strtotime($seller->shop->vacation_start_date)) && $current_date <= date('Y-m-d', strtotime($seller->shop->vacation_end_date)))
                                    <div class="btn btn-soft-danger">{{translate('this_shop_currently_on_vacation')}} </div>
                                    @endif
                            </div>
                            @endif
                            <div class="d-block">
                                <h2 class="mb-2 pb-1">{{ $seller->shop? $seller->shop->name : translate("shop_Name")." : ".translate("update_Please") }}</h2>
                                <div class="d-flex gap-3 flex-wrap mb-3 lh-1">
                                    <div class="review-hover position-relative cursor-pointer d-flex gap-2 align-items-center">
                                        <i class="tio-star"></i>
                                        <span>{{ round($seller->average_rating, 1) }}</span>

                                        <div class="review-details-popup">
                                            <h6 class="mb-2">{{translate('rating')}}</h6>
                                            <div class="">
                                                <ul class="list-unstyled list-unstyled-py-2 mb-0">
                                                    <!-- Review Ratings -->
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        <span class="mr-3">5 {{translate('star')}}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="ml-3">{{$seller->single_rating_5}}</span>
                                                    </li>
                                                    <!-- End Review Ratings -->

                                                    <!-- Review Ratings -->
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        <span class="mr-3">4 {{translate('star')}}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="ml-3">{{$seller->single_rating_4}}</span>
                                                    </li>
                                                    <!-- End Review Ratings -->

                                                    <!-- Review Ratings -->
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        <span class="mr-3">3 {{translate('star')}}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="ml-3">{{$seller->single_rating_3}}</span>
                                                    </li>
                                                    <!-- End Review Ratings -->

                                                    <!-- Review Ratings -->
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        <span class="mr-3">2 {{translate('star')}}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="ml-3">{{$seller->single_rating_2}}</span>
                                                    </li>
                                                    <!-- End Review Ratings -->

                                                    <!-- Review Ratings -->
                                                    <li class="d-flex align-items-center font-size-sm">
                                                        <span class="mr-3">1 {{translate('star')}}</span>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="ml-3">{{$seller->single_rating_1}}</span>
                                                    </li>
                                                    <!-- End Review Ratings -->
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="border-left"></span>
                                    <a href="javascript:" class="text-dark">{{$seller->total_rating}} {{translate('ratings')}}</a>
                                    <span class="border-left"></span>
                                    <a href="{{ $seller->status!="pending" ? route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'review']): 'javascript:' }}" class="text-dark">{{$seller->rating_count}} {{translate('reviews')}}</a>
                                </div>
                                @if ( $seller->status!="pending" && $seller->status!="suspended" && $seller->status!="rejected")
                                <a href="{{route('shopView',['id'=>$seller->id])}}" class="btn btn-outline--primary px-4" target="_blank"><i class="tio-globe"></i> {{translate('view_live')}}
                                    @endif
                                </a>
                            </div>
                            <!-- <div>
                                {{ translate('Pickup Address Update On Shiprocket:') }}
                                @if(!$seller->shop->is_added_on_shiprocket)
                                <button type="button" onclick="updatePickupAddress()" class="btn btn-primary px-4">{{ translate('Update') }}</button>
                                @else
                                <button type="button" class="btn btn-success" disabled>{{ translate('Already Updated') }}</button>
                                @endif
                            </div> -->

                    </div>
                </div>

                @if ($seller->status=="pending")
                <div class="d-flex justify-content-sm-end flex-wrap gap-2 mb-3">
                    <form class="d-inline-block" action="{{route('admin.sellers.updateStatus')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{$seller->id}}">
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-danger px-5">{{translate('reject')}}</button>
                    </form>
                    <form class="d-inline-block" action="{{route('admin.sellers.updateStatus')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{$seller->id}}">
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success px-5">{{translate('approve')}}</button>
                    </form>
                </div>
                @endif
                @if ($seller->status=="approved")
                <div class="d-flex justify-content-sm-end flex-wrap gap-2 mb-3">
                    <form class="d-inline-block" action="{{route('admin.sellers.updateStatus')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{$seller->id}}">
                        <input type="hidden" name="status" value="suspended">
                        <button type="submit" class="btn btn-danger px-5">{{translate('suspend_this_seller')}}</button>
                    </form>
                </div>
                @endif
                @if ($seller->status=="suspended" || $seller->status=="rejected")
                <div class="d-flex justify-content-sm-end flex-wrap gap-2 mb-3">
                    <form class="d-inline-block" action="{{route('admin.sellers.updateStatus')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{$seller->id}}">
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success px-5">{{translate('active')}}</button>
                    </form>
                </div>
                @endif
            </div>

            <hr>

            <div class="d-flex gap-3 flex-wrap flex-lg-nowrap">
                <!-- First Row -->
                <div class="border p-3 w-170">
                    <div class="d-flex flex-column mb-1">
                        <h6 class="font-weight-normal">{{translate('total_products')}} :</h6>
                        <h3 class="text-primary fs-18">{{$seller->product_count}}</h3>
                    </div>
                    <div class="d-flex flex-column">
                        <h6 class="font-weight-normal">{{translate('total_orders')}} :</h6>
                        <h3 class="text-primary fs-18">{{$seller->orders_count}}</h3>
                    </div>
                </div>

                <!-- seller info -->

                <div class="col-sm-6 col-xxl-3">
                    <h4 class="mb-3 text-capitalize">{{translate('seller_information')}}</h4>

                    <div class="pair-list">
                        <div>
                            <span class="key">{{translate('name')}}</span>
                            <span>:</span>
                            <span class="value">{{$seller->f_name}} {{$seller->l_name}}</span>
                        </div>

                        <div>
                            <span class="key">{{translate('email')}}</span>
                            <span>:</span>
                            <span class="value">{{$seller->email}}</span>
                        </div>

                        <div>
                            <span class="key">{{translate('phone')}}</span>
                            <span>:</span>
                            <span class="value">{{$seller->phone}}</span>
                        </div>
                    </div>
                </div>
                <!-- shop info -->
                <div class="col-sm-6 col-xxl-3">
                    <h4 class="mb-3 text-capitalize">{{translate('shop_information')}}</h4>

                    <div class="pair-list">
                        <div>
                            <span class="key text-nowrap">{{translate('shop_name')}}</span>
                            <span>:</span>
                            <span class="value ">{{$seller->shop->name}}</span>
                        </div>

                        <div>
                            <span class="key">{{translate('phone')}}</span>
                            <span>:</span>
                            <span class="value">{{$seller->shop->contact}}</span>
                        </div>

                        <div>
                            <span class="key">{{translate('address')}}</span>
                            <span>:</span>
                            <span class="value">{{$seller->shop->address}}</span>
                        </div>
                        <div>
                            <span class="key">{{translate('HouseNo./Flat No.')}}</span>
                            <span>:</span>
                            <span class="value">{{$seller->shop->house_no}}</span>
                        </div>
                        <div>
                            <span class="key">{{translate('street No.')}}</span>
                            <span>:</span>
                            <span class="value">{{$seller->shop->street_no}}</span>
                        </div>
                        <div>
                            <span class="key">{{translate('City')}}</span>
                            <span>:</span>
                            <span class="value">{{$seller->shop->city}}</span>
                        </div>
                        <div>
                            <span class="key">{{translate('state')}}</span>
                            <span>:</span>
                            <span class="value">{{$seller->shop->state}}</span>
                        </div>
                        <div>
                            <span class="key">{{translate('country')}}</span>
                            <span>:</span>
                            <span class="value">{{$seller->shop->country}}</span>
                        </div>
                        <div>
                            <span class="key">{{translate('pincode')}}</span>
                            <span>:</span>
                            <span class="value">{{$seller->shop->pinCode}}</span>
                        </div>
                        <!-- <div>
                                <span class="key">{{translate('pickup_address')}}</span>
                                <span>:</span>
                                <span class="value">{{$seller->shop->pickup_address}}</span>
                            </div> -->
                        <!-- 008 -->
                        <div>
                            <span class="key">{{ translate('Business PAN') }}</span>
                            <span>:</span>
                            <span class="value">
                                @if($seller->shop->business_pan)
                                {{ $seller->shop->business_pan }}
                                @else
                                {{ translate('Not Available') }}
                                @endif
                            </span>
                        </div>

                        <!-- 008 -->

                        <div>
                            <span class="key">{{translate('status')}}</span>
                            <span>:</span>
                            <span class="value">
                                <span class="badge badge-{{$seller->status=='approved'? 'info' :'danger'}}">
                                    {{ $seller->status=='approved'? translate('active') : translate('inactive') }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- pickup_address -->
                <!-- Column for Pickup Address -->
                <div class="col-sm-6 col-xxl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">{{ translate('pickup_address') }}</h5>
                            <button type="button" class="btn btn--primary px-4 text-white" onclick="editPickupAddress()">{{ translate('Edit') }}</button>

                            @if($seller->shop->default_pickup_address)
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="flex-start">
                                <h6>{{ translate('address') }}:</h6>
                                <h6>{{ $seller->shop->default_pickup_address->address ?? '' }}</h6>
                            </div>
                            <div class="flex-start">
                                <h6>{{ translate('house_no.') }}:</h6>
                                <h6>{{ $seller->shop->default_pickup_address->house_no ?? '' }}</h6>
                            </div>
                            <div class="flex-start">
                                <h6>{{ translate('street_no.') }}:</h6>
                                <h6>{{ $seller->shop->default_pickup_address->street_no ?? '' }}</h6>
                            </div>
                            <div class="flex-start">
                                <h6>{{ translate('city') }}:</h6>
                                <h6>{{ $seller->shop->default_pickup_address->city ?? '' }}</h6>
                            </div>
                            <div class="flex-start">
                                <h6>{{ translate('state') }}:</h6>
                                <h6>{{ $seller->shop->default_pickup_address->state ?? '' }}</h6>
                            </div>
                            <div class="flex-start">
                                <h6>{{ translate('country') }}:</h6>
                                <h6>{{ $seller->shop->default_pickup_address->country ?? '' }}</h6>
                            </div>
                            <div class="flex-start">
                                <h6>{{ translate('pincode') }}:</h6>
                                <h6>{{ $seller->shop->default_pickup_address->pincode ?? '' }}</h6>
                            </div>
                            <div>
                                {{ translate('Pickup Address Update On Shiprocket:') }}
                                @if($seller->shop->default_pickup_address && $seller->shop->default_pickup_address->is_added_on_shiprocket == false)
                                <button type="button" onclick="updatePickupAddress()" class="btn btn-primary btn-sm px-4">{{ translate('Update') }}</button>
                                @elseif($seller->shop->default_pickup_address && $seller->shop->default_pickup_address->is_added_on_shiprocket == true)
                                <button type="button" class="btn btn-success" disabled>{{ translate('Already Updated') }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>



            </div>
            <div class="d-flex gap-3 flex-wrap flex-lg-nowrap mt-5">
                <!-- 008 -->
                <div class="certificates-list">
                    <h4 class="mb-3 text-capitalize">{{translate('certificates')}}</h4>


                    <form action="{{ route('admin.sellers.update_certificate', ['id' => $seller->shop->id]) }}" method="post" style="text-align: {{ Session::get('direction') === "rtl" ? 'right' : 'left' }};" enctype="multipart/form-data" class="certificate-form" id="gst-certificate-form">
                        @csrf

                        <div class="mb-3">
                            <span class="key text-nowrap">{{ translate('GST Certificate') }}</span>
                            <span>:</span>
                            <span class="value">
                                @if($seller->shop->gst_certificate)
                                <!-- View, Download, and Update buttons -->
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->gst_certificate) }}" target="_blank" class="btn btn-sm btn-info btn-success view-btn">{{ translate('View') }}</a>
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->gst_certificate) }}" download="{{ $seller->shop->name . '_gst_certificate.pdf' }}" class="btn btn-sm btn-primary download-btn">{{ translate('Download') }}</a>

                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-warning update-btn" onclick="showUploadForm('gst-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="gst_certificate" id="gst_certificate" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('gst-certificate-form')">{{ translate('Cancel') }}</button>
                                @else
                                {{ translate('Not Available') }}
                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-success update-btn" onclick="showUploadForm('gst-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="gst_certificate" id="gst_certificate" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('gst-certificate-form')">{{ translate('Cancel') }}</button>
                                @endif
                            </span>
                        </div>
                    </form>
                    <form action="{{ route('admin.sellers.update_certificate', ['id' => $seller->shop->id]) }}" method="post" style="text-align: {{ Session::get('direction') === "rtl" ? 'right' : 'left' }};" enctype="multipart/form-data" class="certificate-form" id="import_license-certificate-form">
                        @csrf

                        <div class="mb-3">
                            <span class="key text-nowrap">{{ translate('Import License') }}</span>
                            <span>:</span>
                            <span class="value">
                                @if($seller->shop->import_license)
                                <!-- View, Download, and Update buttons -->
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->import_license) }}" target="_blank" class="btn btn-sm btn-info btn-success view-btn">{{ translate('View') }}</a>
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->import_license) }}" download="{{ $seller->shop->name . '_import_license.pdf' }}" class="btn btn-sm btn-primary download-btn">{{ translate('Download') }}</a>

                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-warning update-btn" onclick="showUploadForm('import_license-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="import_license" id="import_license" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('import_license-certificate-form')">{{ translate('Cancel') }}</button>
                                @else
                                {{ translate('Not Available') }}
                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-success update-btn" onclick="showUploadForm('import_license-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="import_license" id="import_license" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('import_license-certificate-form')">{{ translate('Cancel') }}</button>
                                @endif
                            </span>
                        </div>
                    </form>
                    <form action="{{ route('admin.sellers.update_certificate', ['id' => $seller->shop->id]) }}" method="post" style="text-align: {{ Session::get('direction') === "rtl" ? 'right' : 'left' }};" enctype="multipart/form-data" class="certificate-form" id="seller_license-certificate-form">
                        @csrf

                        <div class="mb-3">
                            <span class="key text-nowrap">{{ translate('seller_license') }}</span>
                            <span>:</span>
                            <span class="value">
                                @if($seller->shop->seller_license)
                                <!-- View, Download, and Update buttons -->
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->seller_license) }}" target="_blank" class="btn btn-sm btn-info btn-success view-btn">{{ translate('View') }}</a>
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->seller_license) }}" download="{{ $seller->shop->name . '_seller_license.pdf' }}" class="btn btn-sm btn-primary download-btn">{{ translate('Download') }}</a>

                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-warning update-btn" onclick="showUploadForm('seller_license-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="seller_license" id="seller_license" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('seller_license-certificate-form')">{{ translate('Cancel') }}</button>
                                @else
                                {{ translate('Not Available') }}
                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-success update-btn" onclick="showUploadForm('seller_license-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="seller_license" id="seller_license" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('seller_license-certificate-form')">{{ translate('Cancel') }}</button>
                                @endif
                            </span>
                        </div>
                    </form>
                    <form action="{{ route('admin.sellers.update_certificate', ['id' => $seller->shop->id]) }}" method="post" style="text-align: {{ Session::get('direction') === "rtl" ? 'right' : 'left' }};" enctype="multipart/form-data" class="certificate-form" id="ayush_license-certificate-form">
                        @csrf

                        <div class="mb-3">
                            <span class="key text-nowrap">{{ translate('ayush_license') }}</span>
                            <span>:</span>
                            <span class="value">
                                @if($seller->shop->ayush_license)
                                <!-- View, Download, and Update buttons -->
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->ayush_license) }}" target="_blank" class="btn btn-sm btn-info btn-success view-btn">{{ translate('View') }}</a>
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->ayush_license) }}" download="{{ $seller->shop->name . '_ayush_license.pdf' }}" class="btn btn-sm btn-primary download-btn">{{ translate('Download') }}</a>

                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-warning update-btn" onclick="showUploadForm('ayush_license-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="ayush_license" id="ayush_license" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('ayush_license-certificate-form')">{{ translate('Cancel') }}</button>
                                @else
                                {{ translate('Not Available') }}
                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-success update-btn" onclick="showUploadForm('ayush_license-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="ayush_license" id="ayush_license" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('ayush_license-certificate-form')">{{ translate('Cancel') }}</button>
                                @endif
                            </span>
                        </div>
                    </form>
                    <form action="{{ route('admin.sellers.update_certificate', ['id' => $seller->shop->id]) }}" method="post" style="text-align: {{ Session::get('direction') === "rtl" ? 'right' : 'left' }};" enctype="multipart/form-data" class="certificate-form" id="factory_license-certificate-form">
                        @csrf

                        <div class="mb-3">
                            <span class="key text-nowrap">{{ translate('factory_license') }}</span>
                            <span>:</span>
                            <span class="value">
                                @if($seller->shop->factory_license)
                                <!-- View, Download, and Update buttons -->
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->factory_license) }}" target="_blank" class="btn btn-sm btn-info btn-success view-btn">{{ translate('View') }}</a>
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->factory_license) }}" download="{{ $seller->shop->name . '_factory_license.pdf' }}" class="btn btn-sm btn-primary download-btn">{{ translate('Download') }}</a>

                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-warning update-btn" onclick="showUploadForm('factory_license-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="factory_license" id="factory_license" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('factory_license-certificate-form')">{{ translate('Cancel') }}</button>
                                @else
                                {{ translate('Not Available') }}
                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-success update-btn" onclick="showUploadForm('factory_license-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="factory_license" id="factory_license" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('factory_license-certificate-form')">{{ translate('Cancel') }}</button>
                                @endif
                            </span>
                        </div>
                    </form>
                    <form action="{{ route('admin.sellers.update_certificate', ['id' => $seller->shop->id]) }}" method="post" style="text-align: {{ Session::get('direction') === "rtl" ? 'right' : 'left' }};" enctype="multipart/form-data" class="certificate-form" id="registration-certificate-form">
                        @csrf

                        <div class="mb-3">
                            <span class="key text-nowrap">{{ translate('registration_certificate') }}</span>
                            <span>:</span>
                            <span class="value">
                                @if($seller->shop->registration_certificate)
                                <!-- View, Download, and Update buttons -->
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->registration_certificate) }}" target="_blank" class="btn btn-sm btn-info btn-success view-btn">{{ translate('View') }}</a>
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->registration_certificate) }}" download="{{ $seller->shop->name . '_registration_certificate.pdf' }}" class="btn btn-sm btn-primary download-btn">{{ translate('Download') }}</a>

                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-warning update-btn" onclick="showUploadForm('registration-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="registration_certificate" id="registration_certificate" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('registration-certificate-form')">{{ translate('Cancel') }}</button>
                                @else
                                {{ translate('Not Available') }}
                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-success update-btn" onclick="showUploadForm('registration-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="registration_certificate" id="registration_certificate" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('registration-certificate-form')">{{ translate('Cancel') }}</button>
                                @endif
                            </span>
                        </div>
                    </form>
                    <form action="{{ route('admin.sellers.update_certificate', ['id' => $seller->shop->id]) }}" method="post" style="text-align: {{ Session::get('direction') === "rtl" ? 'right' : 'left' }};" enctype="multipart/form-data" class="certificate-form" id="iso_9001-certificate-form">
                        @csrf

                        <div class="mb-3">
                            <span class="key text-nowrap">{{ translate('iso_certificate') }}</span>
                            <span>:</span>
                            <span class="value">
                                @if($seller->shop->iso_certificate)
                                <!-- View, Download, and Update buttons -->
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->iso_certificate) }}" target="_blank" class="btn btn-sm btn-info btn-success view-btn">{{ translate('View') }}</a>
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->iso_certificate) }}" download="{{ $seller->shop->name . '_iso_certificate.pdf' }}" class="btn btn-sm btn-primary download-btn">{{ translate('Download') }}</a>

                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-warning update-btn" onclick="showUploadForm('iso_9001-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="iso_9001_certificate" id="iso_9001_certificate" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('iso_9001-certificate-form')">{{ translate('Cancel') }}</button>
                                @else
                                {{ translate('Not Available') }}
                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-success update-btn" onclick="showUploadForm('iso_9001-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="iso_9001_certificate" id="iso_9001_certificate" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('iso_9001-certificate-form')">{{ translate('Cancel') }}</button>
                                @endif
                            </span>
                        </div>
                    </form>
                    <form action="{{ route('admin.sellers.update_certificate', ['id' => $seller->shop->id]) }}" method="post" style="text-align: {{ Session::get('direction') === "rtl" ? 'right' : 'left' }};" enctype="multipart/form-data" class="certificate-form" id="international_license-certificate-form">
                        @csrf

                        <div class="mb-3">
                            <span class="key text-nowrap">{{ translate('international_license') }}</span>
                            <span>:</span>
                            <span class="value">
                                @if($seller->shop->international_license)
                                <!-- View, Download, and Update buttons -->
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->international_license) }}" target="_blank" class="btn btn-sm btn-info btn-success view-btn">{{ translate('View') }}</a>
                                <a href="{{ asset('storage/app/public/shop/certificates/' . $seller->shop->international_license) }}" download="{{ $seller->shop->name . '_international_license.pdf' }}" class="btn btn-sm btn-primary download-btn">{{ translate('Download') }}</a>

                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-warning update-btn" onclick="showUploadForm('international_license-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="international_license" id="international_license" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('international_license-certificate-form')">{{ translate('Cancel') }}</button>
                                @else
                                {{ translate('Not Available') }}
                                <!-- Update button -->
                                <button type="button" class="btn btn-sm btn-success update-btn" onclick="showUploadForm('international_license-certificate-form')">{{ translate('Update') }}</button>

                                <!-- File input and upload button -->
                                <input type="hidden" name="id" value="{{ $seller->shop->id }}">
                                <input type="file" name="international_license" id="international_license" class="file-input" style="display: none;" accept=".pdf">
                                <button type="submit" class="btn btn-sm btn-primary upload-btn" style="display: none;">{{ translate('Upload') }}</button>
                                <button type="button" class="btn btn-sm btn-danger cancel-btn" style="display: none;" onclick="cancelUpload('international_license-certificate-form')">{{ translate('Cancel') }}</button>
                                @endif
                            </span>
                        </div>
                    </form>

                </div>
                <!-- 008 -->

                @if ($seller->status!="pending")
                <div class="col-xxl-6">
                    <div class="bg-light p-3 border border-primary-light rounded">
                        <h4 class="mb-3 text-capitalize">{{translate('bank_information')}}</h4>

                        <div class="d-flex gap-5">
                            <div class="pair-list">
                                <div>
                                    <span class="key text-nowrap">{{translate('bank_name')}}</span>
                                    <span class="px-2">:</span>
                                    <span class="value ">{{ $seller->bank_name ? $seller->bank_name : translate('no_data_found') }}</span>
                                </div>

                                <div>
                                    <span class="key text-nowrap">{{translate('branch')}}</span>
                                    <span class="px-2">:</span>
                                    <span class="value">{{ $seller->branch ? $seller->branch : translate('no_data_found') }}</span>
                                </div>
                            </div>
                            <div class="pair-list">
                                <div>
                                    <span class="key text-nowrap">{{translate('holder_name')}}</span>
                                    <span class="px-2">:</span>
                                    <span class="value">{{ $seller->holder_name ? $seller->holder_name : translate('no_data_found') }}</span>
                                </div>

                                <div>
                                    <span class="key text-nowrap">{{translate('A/C_No')}}</span>
                                    <span class="px-2">:</span>
                                    <span class="value">{{ $seller->account_no ? $seller->account_no : translate('no_data_found') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@if ($seller->status!="pending")
<div class="card mt-3">
    <div class="card-body">
        <div class="row justify-content-between align-items-center g-2 mb-3">
            <div class="col-sm-6">
                <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                    <img width="20" class="mb-1" src="{{asset('/public/assets/back-end/img/admin-wallet.png')}}" alt="">
                    {{translate('seller_Wallet')}}
                </h4>
            </div>
        </div>

        <div class="row g-2" id="order_stats">
            <div class="col-lg-4">
                <!-- Card -->
                <div class="card h-100 d-flex justify-content-center align-items-center">
                    <div class="card-body d-flex flex-column gap-10 align-items-center justify-content-center">
                        <img width="48" class="mb-2" src="{{asset('/public/assets/back-end/img/withdraw.png')}}" alt="">
                        <h3 class="for-card-count mb-0 fz-24">{{ $seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->total_earning)) : 0 }}</h3>
                        <div class="font-weight-bold text-capitalize mb-30">
                            {{translate('withdrawable_balance')}}
                        </div>
                        {{-- <a href="#" class="btn btn--primary px-5 w-100"><span class="px-xl-5">{{translate('withdraw')}}</span></a> --}}
                    </div>
                </div>
                <!-- End Card -->
            </div>
            <div class="col-lg-8">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="card card-body h-100 justify-content-center">
                            <div class="d-flex gap-2 justify-content-between align-items-center">
                                <div class="d-flex flex-column align-items-start">
                                    <h3 class="mb-1 fz-24">{{ $seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->pending_withdraw)) : 0}}</h3>
                                    <div class="text-capitalize mb-0">{{translate('pending_Withdraw')}}</div>
                                </div>
                                <div>
                                    <img width="40" class="mb-2" src="{{asset('/public/assets/back-end/img/pw.png')}}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-body h-100 justify-content-center">
                            <div class="d-flex gap-2 justify-content-between align-items-center">
                                <div class="d-flex flex-column align-items-start">
                                    <h3 class="mb-1 fz-24">{{ $seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->commission_given)) : 0}}</h3>
                                    <div class="text-capitalize mb-0">{{translate('total_Commission_given')}}</div>
                                </div>
                                <div>
                                    <img width="40" src="{{asset('/public/assets/back-end/img/tcg.png')}}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-body h-100 justify-content-center">
                            <div class="d-flex gap-2 justify-content-between align-items-center">
                                <div class="d-flex flex-column align-items-start">
                                    <h3 class="mb-1 fz-24">{{$seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->withdrawn)) : 0}}</h3>
                                    <div class="text-capitalize mb-0">{{translate('aready_Withdrawn')}}</div>
                                </div>
                                <div>
                                    <img width="40" src="{{asset('/public/assets/back-end/img/aw.png')}}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-body h-100 justify-content-center">
                            <div class="d-flex gap-2 justify-content-between align-items-center">
                                <div class="d-flex flex-column align-items-start">
                                    <h3 class="mb-1 fz-24">{{ $seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->delivery_charge_earned)) : 0}}</h3>
                                    <div class="text-capitalize mb-0">{{translate('total_delivery_charge_earned')}}</div>
                                </div>
                                <div>
                                    <img width="40" src="{{asset('/public/assets/back-end/img/tdce.png')}}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-body h-100 justify-content-center">
                            <div class="d-flex gap-2 justify-content-between align-items-center">
                                <div class="d-flex flex-column align-items-start">
                                    <h3 class="mb-1 fz-24">{{ $seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->total_tax_collected)) : 0}}</h3>
                                    <div class="text-capitalize mb-0">{{translate('total_tax_given')}}</div>
                                </div>
                                <div>
                                    <img width="40" src="{{asset('/public/assets/back-end/img/ttg.png')}}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-body h-100 justify-content-center">
                            <div class="d-flex gap-2 justify-content-between align-items-center">
                                <div class="d-flex flex-column align-items-start">
                                    <h3 class="mb-1 fz-24">{{ $seller->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($seller->wallet->collected_cash)) : 0}}</h3>
                                    <div class="text-capitalize mb-0">{{translate('collected_cash')}}</div>
                                </div>
                                <div>
                                    <img width="40" src="{{asset('/public/assets/back-end/img/cc.png')}}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
</div>



@php
    $fullAddress = optional($seller->shop->default_pickup_address)->address ?? '';
    $fullAddress .= $fullAddress ? ", House No - " . optional($seller->shop->default_pickup_address)->house_no ?? '' : '';
    $fullAddress .= $fullAddress ? ", Road No - " . optional($seller->shop->default_pickup_address)->street_no ?? '' : '';
@endphp
<!-- Show pickup address  Modal -->
<div class="modal" id="update_pickup_address_modal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('update_pickup_address') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('seller.shop.create_new_pickup_address') }}" method="POST">
                            @csrf
                            <input type="hidden" name="shop_id" value="{{ $seller->shop->id }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>{{ translate('pickup_address') }}</label>
                                    <input class="form-control" type="text" name="pickup_address" value="{{ $seller->shop->default_pickup_address->address ?? '' }}" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>{{ translate('house_no/Flat_no.') }}</label>
                                        <input class="form-control" type="text" name="house_no" value="{{ $seller->shop->default_pickup_address->house_no ?? '' }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ translate('street_no') }}</label>
                                        <input class="form-control" type="text" name="street_no" value="{{ $seller->shop->default_pickup_address->street_no ?? ''}}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ translate('city') }}</label>
                                        <input class="form-control" type="text" name="city" value="{{ $seller->shop->default_pickup_address->city ?? '' }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ translate('state') }}</label>
                                        <input class="form-control" type="text" name="state" value="{{ $seller->shop->default_pickup_address->state ?? ''}}" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>{{ translate('country') }}</label>
                                        <input class="form-control" type="text" name="country" value="{{ $seller->shop->default_pickup_address->country ?? '' }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ translate('pincode') }}</label>
                                        <input class="form-control" type="text" name="pincode" value="{{ $seller->shop->default_pickup_address->pincode ?? '' }}" required>
                                    </div>
                                </div>



                                <button class="btn btn--primary" type="submit">{{ translate('update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script')





<!-- ... -->

<!-- JavaScript to handle the functionality with AJAX -->
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all update buttons
        const updateButtons = document.querySelectorAll('.update-btn');

        // Add event listener to each update button
        updateButtons.forEach((button) => {
            button.addEventListener('click', function() {
                // Show file input and buttons
                const fileInput = this.parentElement.querySelector('.file-input');
                const uploadBtn = this.parentElement.querySelector('.upload-btn');
                const cancelBtn = this.parentElement.querySelector('.cancel-btn');

                fileInput.style.display = 'inline-block';
                uploadBtn.style.display = 'inline-block';
                cancelBtn.style.display = 'inline-block';

                // Hide view, download, and update buttons
                const viewBtn = this.parentElement.querySelector('.view-btn');
                const downloadBtn = this.parentElement.querySelector('.download-btn');
                viewBtn.style.display = 'none';
                downloadBtn.style.display = 'none';
                this.style.display = 'none';
            });
        });

        // Add event listener to upload button to send update request using AJAX
        const uploadButtons = document.querySelectorAll('.upload-btn');
        uploadButtons.forEach((button) => {
            button.addEventListener('click', function() {
                // Check if a file is selected
                const fileInput = this.parentElement.querySelector('.file-input');
                if (!fileInput.files.length || !fileInput.files[0].name.endsWith('.pdf')) {
                    // Display a warning toast
                    alert('Please choose a PDF file');
                    return;
                }

                // Create a FormData object to send the file
                const formData = new FormData();
                formData.append('pdf_file', fileInput.files[0]);


                // Log each entry in the FormData object
                formData.forEach((value, key) => {
                    console.log(`${key}: ${value}`);
                });
                // Send AJAX request
                $.ajax({
                    url: '{{ route("seller.shop.update", [$seller->shop->id]) }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle success response
                        alert('Update successful');
                        // You may redirect or perform further actions as needed
                    },
                    error: function(error) {
                        // Handle error response
                        console.log(error);
                        alert('Update failed');
                    }
                });
            });
        });

        // Add event listener to cancel button to hide file input and buttons
        const cancelButtons = document.querySelectorAll('.cancel-btn');
        cancelButtons.forEach((button) => {
            button.addEventListener('click', function() {
                // Hide file input and buttons
                const fileInput = this.parentElement.querySelector('.file-input');
                const uploadBtn = this.parentElement.querySelector('.upload-btn');
                const cancelBtn = this.parentElement.querySelector('.cancel-btn');

                fileInput.style.display = 'none';
                uploadBtn.style.display = 'none';
                cancelBtn.style.display = 'none';

                // Show view, download, and update buttons
                const viewBtn = this.parentElement.querySelector('.view-btn');
                const downloadBtn = this.parentElement.querySelector('.download-btn');
                const updateBtn = this.parentElement.querySelector('.update-btn');
                viewBtn.style.display = 'inline-block';
                downloadBtn.style.display = 'inline-block';
                updateBtn.style.display = 'inline-block';
            });
        });
    });
</script> -->


<!-- Add this script in your HTML body section -->

<!-- Add this script in your HTML body section -->
<!-- Add this script in your HTML body section -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    function showUploadForm(formId) {
        const form = document.getElementById(formId);
        const fileInput = form.querySelector('.file-input');
        const uploadBtn = form.querySelector('.upload-btn');
        const cancelBtn = form.querySelector('.cancel-btn');
        const updateBtn = form.querySelector('.update-btn');
        const viewBtn = form.querySelector('.view-btn');
        const downloadBtn = form.querySelector('.download-btn');

        fileInput.style.display = 'inline';
        uploadBtn.style.display = 'inline';
        cancelBtn.style.display = 'inline';
        updateBtn.style.display = 'none';
        viewBtn.style.display = 'none';
        downloadBtn.style.display = 'none';
    }

    function cancelUpload(formId) {
        const form = document.getElementById(formId);
        const fileInput = form.querySelector('.file-input');
        const uploadBtn = form.querySelector('.upload-btn');
        const cancelBtn = form.querySelector('.cancel-btn');
        const updateBtn = form.querySelector('.update-btn');
        const viewBtn = form.querySelector('.view-btn');
        const downloadBtn = form.querySelector('.download-btn');

        fileInput.style.display = 'none';
        uploadBtn.style.display = 'none';
        cancelBtn.style.display = 'none';
        updateBtn.style.display = 'inline';
        viewBtn.style.display = 'inline';
        downloadBtn.style.display = 'inline';
    }

    // Validate file input before form submission
    function validateFormSubmission(formId) {
        const form = document.getElementById(formId);
        const fileInput = form.querySelector('.file-input');

        // Check if the file input is empty
        if (fileInput.style.display !== 'none' && fileInput.files.length === 0) {
            event.preventDefault(); // Prevent form submission
            toastr.error('Please select a file before uploading.'); // Show error message
        }
    }
</script>

<script>

function updatePickupAddress() {
    const proxyEndpoint = 'https://myglamour.store/proxy.php';
    const emailInput = "{{ config('shiprocket.user_id') }}";
    const passwordInput = "{{ config('shiprocket.password') }}";
    //console.log("email is ", emailInput);

    fetch(proxyEndpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'login',
            data: {
                email: emailInput,
                password: passwordInput,
            }
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to login');
        }
        return response.json();
    })
    .then(data => {
        const token = data.token;

        // Check if default_pickup_address exists before proceeding
        if ("{{ $seller->shop->default_pickup_address ? $seller->shop->default_pickup_address->id : '' }}" === "") {
                    toastr.warning("Default pickup address is not available.");
                    return; // Return here to stop further processing
                }
        return fetch(proxyEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                action: 'addPickupAddress',
                token: token,
                data: {
                    pickup_location: "{{ $seller->shop->default_pickup_address ? $seller->shop->default_pickup_address->id : '' }}",
                    name: "{{ $seller->shop->seller->f_name  }}",
                    email: "{{ $seller->shop->seller->email  }}",
                    phone: "{{ $seller->shop->seller->phone }}",
                    address: "{{ $fullAddress ?? ''}}",
                    address_2: '',
                    city: "{{ $seller->shop->default_pickup_address ? $seller->shop->default_pickup_address->city : '' }}",
                    state: "{{ $seller->shop->default_pickup_address ? $seller->shop->default_pickup_address->state : '' }}",
                    country: "{{ $seller->shop->default_pickup_address ? $seller->shop->default_pickup_address->country : '' }}",
                    pin_code: "{{ $seller->shop->default_pickup_address ? $seller->shop->default_pickup_address->pincode : '' }}"
                }
            })
        });
    })
    .then(response => {
        if (!response.ok && response.status === 422) {
        throw new Error('Invalid data provided for adding pickup address');
    }
        return response.json();
    })
    .then(data => {
          // Check if the status code is 422 and display the error message
        if (data.status_code === 422 && data.errors) {
            if (data.errors.pickup_location) {
                const errorMessage = data.errors.pickup_location[0];
                toastr.error(errorMessage);
                return; // Return here to stop further processing
            } else if (data.errors.address) {
                const errorMessage = data.errors.address[0];
                toastr.error(errorMessage);
                return; // Return here to stop further processing
            }
            else{
                toastr.error("unprocessable data");
                return; // Return here to stop further processing
            }
        }
       //console.log("success");
                // Handle response from add pickup address API
                update_shiprocket_pickup_address_addedd_status();
                // This part should only execute if the response status is ok
                //console.log('Response from add pickup address API:', data);
                // Optionally, you can display a success message or perform any other action here
    })
    .catch(error => {
        // Handle errors here
        console.error('Error:', error);
        toastr.error('Oops! Something went wrong.');
    });
}





function update_shiprocket_pickup_address_addedd_status() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ $seller->shop->default_pickup_address ? route('admin.sellers.update_pickup_added_on_shiprocket_status', ['id' => $seller->shop->default_pickup_address->id]) : '' }}",
            method: 'POST',
            success: function(data) {
                if (data.success) {
                    toastr.success('{{ translate("Status updated successfully") }}');
                    location.reload();
                } else {
                    toastr.error('{{ translate("Failed to update status") }}');
                }
            },
            error: function(xhr, status, error) {
                toastr.error('{{ translate("Error occurred while updating status") }}');
            }
        });
    }


function editPickupAddress() {
        $('#update_pickup_address_modal').modal('show');
    }

</script>








@endpush