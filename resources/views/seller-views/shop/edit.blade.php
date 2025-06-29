@extends('layouts.back-end.app-seller')
@section('title', translate('shop_Edit'))
@push('css_or_js')
<!-- Custom styles for this page -->
<link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<!-- Custom styles for this page -->
<link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
<!-- Content Row -->
<div class="content container-fluid">

    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img width="20" src="{{asset('/public/assets/back-end/img/shop-info.png')}}" alt="">
            {{translate('edit_Shop_Info')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 ">{{translate('edit_Shop_Info')}}</h5>
                    <a href="{{route('seller.shop.view')}}" class="btn btn--primary __inline-70 px-4 text-white">{{ translate('back') }}</a>
                </div>
                <div class="card-body">
                    <form action="{{route('seller.shop.update',[$shop->id])}}" method="post" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="title-color">{{translate('shop_Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{$shop->name}}" class="form-control" id="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="title-color">{{translate('contact')}} <span class="text-info">( * {{translate('country_code_is_must_like_for_BD_880')}} )</span></label>
                                    <input type="number" name="contact" value="{{$shop->contact}}" class="form-control" id="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="address" class="title-color d-flex gap-1 align-items-center">{{translate('address')}} <span class="text-info"></span></label>

                                    <textarea type="text" rows="4" name="address" value="" class="form-control" id="address" required>{{$shop->address}}</textarea>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="city" class="title-color">{{translate('city')}} </label>
                                        <input type="text" name="city" value="{{$shop->city}}" class="form-control" id="city" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="state" class="title-color">{{translate('state')}} </label>
                                        <input type="text" name="state" value="{{$shop->state}}" class="form-control" id="state" required>
                                    </div>
                                   
                                </div>


                            </div>



                            <div class="col-md-6">

                                <div class="form-group">
                                    <label for="name" class="title-color">{{translate('upload_Image')}}</label>
                                    <div class="custom-file text-left">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileUpload">{{translate('choose_File')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img class="upload-img-view" id="viewer" onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" src="{{asset('storage/app/public/shop/'.$shop->image)}}" alt="Product thumbnail" />
                                </div>

                                <div class="row">
                                
                                    <div class="form-group col-md-6">
                                        <label for="house_no" class="title-color">{{translate('House_no./Flat_No.')}} </label>
                                        <input type="text" name="house_no" value="{{$shop->house_no}}" class="form-control" id="house_no" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="street_no" class="title-color">{{translate('street_no')}} </label>
                                        <input type="text" name="street_no" value="{{$shop->street_no}}" class="form-control" id="street_no" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="country" class="title-color">{{translate('country')}} </label>
                                        <input type="text" name="country" value="{{$shop->country}}" class="form-control" id="country" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="pinCode" class="title-color">{{translate('pincode')}} </label>
                                        <input type="number" name="pinCode" value="{{$shop->pinCode}}" class="form-control" id="pinCode" required>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-md-6 mb-4 mt-2">
                                <div class="form-group">
                                    <div class="flex-start">
                                        <label for="name" class="title-color">{{translate('upload_Banner')}} </label>
                                        <div class="mx-1" for="ratio">
                                            <span class="text-info">{{translate('ratio')}} : ( 6:1 )</span>
                                        </div>
                                    </div>
                                    <div class="custom-file text-left">
                                        <input type="file" name="banner" id="BannerUpload" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="BannerUpload">{{translate('choose_File')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <center>
                                        <img class="upload-img-view upload-img-view__banner" id="viewerBanner" onerror="this.src='{{asset('public/assets/back-end/img/400x400/img2.jpg')}}'" src="{{asset('storage/app/public/shop/banner/'.$shop->banner)}}" alt="banner image" />
                                    </center>
                                </div>
                            </div>

                            @if(theme_root_path() == "theme_aster")
                            <div class="col-md-6 mb-4 mt-2">
                                <div class="form-group">
                                    <div class="flex-start">
                                        <label for="name" class="title-color">{{translate('Upload')}} {{translate('Secondary')}} {{translate('Banner')}} </label>
                                        <div class="mx-1" for="ratio">
                                            <span class="text-info">{{translate('Ratio')}} : ( 6:1 )</span>
                                        </div>
                                    </div>
                                    <div class="custom-file text-left">
                                        <input type="file" name="bottom_banner" id="BottomBannerUpload" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="BottomBannerUpload">{{translate('choose')}} {{translate('file')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <center>
                                        <img class="upload-img-view upload-img-view__banner" id="viewerBottomBanner" onerror="this.src='{{asset('public/assets/back-end/img/400x400/img2.jpg')}}'" src="{{asset('storage/app/public/shop/banner/'.$shop->bottom_banner)}}" alt="banner image" />
                                    </center>
                                </div>
                            </div>
                            @endif
                            <!--Start  Offer Banner for theme fashion -->
                            @if(theme_root_path() == "theme_fashion")
                            <div class="col-md-6 mb-4 mt-2">
                                <div class="form-group">
                                    <div class="flex-start">
                                        <label for="name" class="title-color">{{translate('Upload')}} {{translate('Offer')}} {{translate('Banner')}} </label>
                                        <div class="mx-1" for="ratio">
                                            <span class="text-info">{{translate('Ratio')}} : ( 7:1 )</span>
                                        </div>
                                    </div>
                                    <div class="custom-file text-left">
                                        <input type="file" name="offer_banner" id="OfferBannerUpload" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="OfferBannerUpload">{{translate('choose')}} {{translate('file')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <center>
                                        <img class="upload-img-view upload-img-view__banner" id="viewerOfferBanner" onerror="this.src='{{asset('public/assets/back-end/img/400x400/img2.jpg')}}'" src="{{asset('storage/app/public/shop/banner/'.$shop->offer_banner)}}" alt="banner image" />
                                    </center>
                                </div>
                            </div>
                            @endif
                            <!--End  Offer Banner for theme fashion -->

                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a class="btn btn-danger" href="{{route('seller.shop.view')}}">{{translate('cancel')}}</a>
                            <button type="submit" class="btn btn--primary" id="btn_update">{{translate('update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#viewer').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readBannerURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#viewerBanner').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readBottomBannerURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#viewerBottomBanner').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileUpload").change(function() {
        readURL(this);
    });

    $("#BannerUpload").change(function() {
        readBannerURL(this);
    });
    $("#BottomBannerUpload").change(function() {
        readBottomBannerURL(this);
    });

    // Start Js For Theme fashion Offer Banner
    function readOfferBannerURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#viewerOfferBanner').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $(document).ready(function() {
        $("#OfferBannerUpload").change(function() {
            readOfferBannerURL(this);
        });
    });
    // End Js For Theme fashion Offer Banner
</script>

@endpush