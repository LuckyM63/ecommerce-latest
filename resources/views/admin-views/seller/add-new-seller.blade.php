@extends('layouts.back-end.app')

@section('title', translate('add_new_seller'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid main-card {{Session::get('direction')}}">

    <!-- Page Title -->
    <div class="mb-4">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/add-new-seller.png')}}" class="mb-1" alt="">
            {{translate('add_new_seller')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <form class="user" action="{{route('shop.apply')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <input type="hidden" name="status" value="approved">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" class="mb-1" alt="">
                    {{translate('seller_information')}}
                </h5>
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="form-group">
                            <label for="exampleFirstName" class="title-color d-flex gap-1 align-items-center">{{translate('first_name')}}</label>
                            <input type="text" class="form-control form-control-user" id="exampleFirstName" name="f_name" value="{{old('f_name')}}" placeholder="{{translate('ex')}}: Jhone" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleLastName" class="title-color d-flex gap-1 align-items-center">{{translate('last_name')}}</label>
                            <input type="text" class="form-control form-control-user" id="exampleLastName" name="l_name" value="{{old('l_name')}}" placeholder="{{translate('ex')}}: Doe" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPhone" class="title-color d-flex gap-1 align-items-center">{{translate('phone')}}</label>
                            <input type="number" class="form-control form-control-user" id="exampleInputPhone" name="phone" value="{{old('phone')}}" placeholder="{{translate('ex')}}: +09587498" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <center>
                                <img class="upload-img-view" id="viewer" src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="banner image" />
                            </center>
                        </div>

                        <div class="form-group">
                            <div class="title-color mb-2 d-flex gap-1 align-items-center">{{translate('seller_Image')}} <span class="text-info">({{translate('ratio')}} {{translate('1')}}:{{translate('1')}})</span></div>
                            <div class="custom-file text-left">
                                <input type="file" name="image" id="customFileUpload" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileUpload">{{translate('upload_image')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <input type="hidden" name="status" value="approved">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" class="mb-1" alt="">
                    {{translate('account_information')}}
                </h5>
                <div class="row">
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail" class="title-color d-flex gap-1 align-items-center">{{translate('email')}}</label>
                        <input type="email" class="form-control form-control-user" id="exampleInputEmail" name="email" value="{{old('email')}}" placeholder="{{translate('ex')}}: Jhone@company.com" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputPassword" class="title-color d-flex gap-1 align-items-center">{{translate('password')}}</label>
                        <input type="password" class="form-control form-control-user" minlength="8" id="exampleInputPassword" name="password" placeholder="{{translate('ex')}} : {{ translate('8+_Character') }}" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleRepeatPassword" class="title-color d-flex gap-1 align-items-center">{{translate('confirm_password')}}</label>
                        <input type="password" class="form-control form-control-user" minlength="8" id="exampleRepeatPassword" placeholder="{{translate('ex')}} : {{ translate('8+_Character') }}" required>
                        <div class="pass invalid-feedback">{{translate('repeat')}} {{translate('password')}} {{translate('not_match')}} .</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-2 border-bottom pb-3 mb-4 pl-4">
                    <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" class="mb-1" alt="">
                    {{translate('shop_information')}}
                </h5>

                <div class="row">
                    <div class="col-lg-6 form-group">
                        <label for="shop_name" class="title-color d-flex gap-1 align-items-center">{{translate('shop_name')}}</label>
                        <input type="text" class="form-control form-control-user" id="shop_name" name="shop_name" placeholder="{{translate('ex')}}: Jhon" value="{{old('shop_name')}}" required>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="shop_address" class="title-color d-flex gap-1 align-items-center">{{translate('shop_address')}} <span class="text-info"></span></label>
                        <textarea name="shop_address" class="form-control" id="shop_address" rows="1" placeholder="{{translate('ex')}}: Doe">{{old('shop_address')}}</textarea>
                    </div>

                    <div class="form-group col-lg-3">
                        <label for="house_no" class="title-color">{{translate('House No./Flat No.')}} </label>
                        <input type="text" name="house_no" value="{{old('house_no')}}" class="form-control" id="house_no" >
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="street_no" class="title-color">{{translate('street No.')}} </label>
                        <input type="text" name="street_no" value="{{old('street_no')}}" class="form-control" id="street_no" >
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="shop_city" class="title-color">{{translate('city')}} </label>
                        <input type="text" name="shop_city" value="{{old('shop_city')}}" class="form-control" id="shop_city" required>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="shop_state" class="title-color">{{translate('state')}} </label>
                        <input type="text" name="shop_state" value="{{old('shop_state')}}" class="form-control" id="shop_state" required>
                    </div>
                    <div class="form-group col-lg-3">
                        <label for="shop_country" class="title-color">{{translate('country')}} </label>
                        <input type="text" name="shop_country" value="{{old('shop_country')}}" class="form-control" id="shop_country" required>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="shop_pincode" class="title-color">{{translate('pincode')}} </label>
                        <input type="number" name="shop_pincode" value="{{old('shop_pincode')}}" class="form-control" id="shop_pincode" required>
                    </div>

                    <!-- Pickup Address Checkbox -->
                    <div class="col-lg-12">
                        <label class="fs-6" >
                            <input type="checkbox" id="pickup_same_as_shop" onchange="togglePickupAddressFields()">
                            {{ translate('Pickup Address same as Shop Address') }}
                        </label>
                    </div>
                    <div id="pickup_address_fields" style="display: block;">
                        <div class="row g-3  mx-1">
                            <div class="col-lg-6">
                            <label for="pickup_address" class="title-color d-flex gap-1 align-items-center">{{translate('pickup_address')}} <span class="text-info"></span></label>

                                <textarea name="pickup_address" class="form-control" id="pickup_address" rows="1" placeholder="{{translate('pickup_address (must_include_House No./Flat No.)')}}">{{old('pickup_address')}}</textarea>
                            </div>
                            <div class="col-lg-3">
                            <label for="pickup_house_no" class="title-color">{{translate('pickup_house_no/_flat_no.')}} </label>

                                <input type="text" name="pickup_house_no" class="form-control" id="pickup_house_no" placeholder="{{ translate('pickup_house_no') }}" value="{{ old('pickup_house_no') }}">
                            </div>
                            <div class="col-lg-3">
                            <label for="pickup_street_no" class="title-color">{{translate('pickup_street_no')}} </label>

                                <input type="text" name="pickup_street_no" class="form-control" id="pickup_street_no" placeholder="{{ translate('pickup_street_no') }}" value="{{ old('pickup_street_no') }}">
                            </div>
                            <div class="col-lg-3">
                            <label for="pickup_city" class="title-color">{{translate('pickup_city')}} </label>

                                <input type="text" name="pickup_city" class="form-control" id="pickup_city" placeholder="{{ translate('pickup_city') }}" value="{{ old('pickup_city') }}">
                            </div>
                            <div class="col-lg-3">
                            <label for="pickup_state" class="title-color">{{translate('pickup_state')}} </label>

                                <input type="text" name="pickup_state" class="form-control" id="pickup_state" placeholder="{{ translate('pickup_state') }}" value="{{ old('pickup_state') }}">
                            </div>
                            <div class="col-lg-3">
                            <label for="pickup_country" class="title-color">{{translate('pickup_country')}} </label>

                                <input type="text" name="pickup_country" class="form-control" id="pickup_country" placeholder="{{ translate('pickup_country') }}" value="{{ old('pickup_country') }}">
                            </div>
                            <div class="col-lg-3">
                            <label for="pickup_pincode" class="title-color">{{translate('pickup_pinCode')}} </label>

                                <input type="number" name="pickup_pincode" class="form-control" id="pickup_pincode" placeholder="{{ translate('pickup_pincode') }}" value="{{ old('pickup_pincode') }}">
                            </div>
                        </div>
                        <!-- Other pickup address fields (city, state, country, pincode) go here -->
                    </div>
                    <!-- 008 -->
                    <div class="col-lg-12 form-group">
                        <label for="business_pan" class="title-color d-flex gap-1 align-items-center">{{translate('Business PAN')}}</label>
                        <input type="text" class="form-control form-control-user" id="business_pan" name="business_pan" placeholder="{{translate('ex')}}: ABCTY1234D" value="{{old('business_pan')}}" required>
                    </div>
                    <!-- 008 -->
                    <div class="col-lg-6 form-group">
                        <center>
                            <img class="upload-img-view" id="viewerLogo" src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="banner image" />
                        </center>

                        <div class="mt-4">
                            <div class="d-flex gap-1 align-items-center title-color mb-2">
                                {{translate('shop_logo')}}
                                <span class="text-info">({{translate('ratio')}} {{translate('1')}}:{{translate('1')}})</span>
                            </div>

                            <div class="custom-file">
                                <input type="file" name="logo" id="LogoUpload" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="LogoUpload">{{translate('upload_logo')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <center>
                            <img class="upload-img-view upload-img-view__banner" id="viewerBanner" src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="banner image" />
                        </center>

                        <div class="mt-4">
                            <div class="d-flex gap-1 align-items-center title-color mb-2">
                                {{translate('shop_banner')}}
                                <span class="text-info">{{ THEME_RATIO[theme_root_path()]['Store cover Image'] }}</span>
                            </div>

                            <div class="custom-file">
                                <input type="file" name="banner" id="BannerUpload" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="BannerUpload">{{translate('upload_Banner')}}</label>
                            </div>
                        </div>
                    </div>

                    <!-- 008 -->
                    <div class="col-lg-6 form-group">
                        <div class="mt-4">{{translate('GST Certificate')}}
                            <div class="custom-file">

                                <input type="file" name="gst_certificate" id="gst_certificate" class="custom-file-input" accept=".pdf">
                                <label class="custom-file-label" for="gst_certificate">{{translate('upload_document')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <div class="mt-4">{{translate('Import License')}}
                            <div class="custom-file">
                                <input type="file" name="import_license" id="import_license" class="custom-file-input" accept=".pdf">
                                <label class="custom-file-label" for="import_license">{{translate('upload_document')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <div class="mt-4"> {{translate('Seller License')}}
                            <div class="custom-file">
                                <input type="file" name="seller_license" id="seller_license" class="custom-file-input" accept=".pdf">
                                <label class="custom-file-label" for="seller_license">{{translate('upload_document')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <div class="mt-4"> {{translate('Ayush License')}}
                            <div class="custom-file">
                                <input type="file" name="ayush_license" id="ayush_license" class="custom-file-input" accept=".pdf">
                                <label class="custom-file-label" for="ayush_license">{{translate('upload_document')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <div class="mt-4"> {{translate('Factory License')}}
                            <div class="custom-file">
                                <input type="file" name="factory_license" id="factory_license" class="custom-file-input" accept=".pdf">
                                <label class="custom-file-label" for="factory_license">{{translate('upload_document')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <div class="mt-4">{{translate('Registration Certificate')}}
                            <div class="custom-file">{{translate('Registration Certificate')}}
                                <input type="file" name="registration_certificate" id="registration_certificate" class="custom-file-input" accept=".pdf">
                                <label class="custom-file-label" for="registration_certificate">{{translate('upload_document')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <div class="mt-4"> {{translate('ISO 9001 Certificate')}}
                            <div class="custom-file">
                                <input type="file" name="iso_certificate" id="iso_certificate" class="custom-file-input" accept=".pdf">
                                <label class="custom-file-label" for="iso_certificate">{{translate('upload_document')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <div class="mt-4">{{translate('ISI/BSI/International_License')}}
                            <div class="custom-file">
                                <input type="file" name="international_license" id="international_license" class="custom-file-input" accept=".pdf">
                                <label class="custom-file-label" for="international_license">{{translate('upload_document')}}</label>
                            </div>
                        </div>
                    </div>
                    <!-- 008 -->

                    @if(theme_root_path() == "theme_aster")
                    <div class="col-lg-6 form-group">
                        <center>
                            <img class="upload-img-view upload-img-view__banner" id="viewerBottomBanner" src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="banner image" />
                        </center>

                        <div class="mt-4">
                            <div class="d-flex gap-1 align-items-center title-color mb-2">
                                {{translate('shop_secondary_banner')}}
                                <span class="text-info">{{ THEME_RATIO[theme_root_path()]['Store Banner Image'] }}</span>
                            </div>

                            <div class="custom-file">
                                <input type="file" name="bottom_banner" id="BottomBannerUpload" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="BottomBannerUpload">{{translate('Upload')}} {{translate('Bottom')}} {{translate('Banner')}}</label>
                            </div>
                        </div>
                    </div>
                    @endif




                </div>

                <div class="d-flex align-items-center justify-content-end gap-10">
                    <input type="hidden" name="from_submit" value="admin">
                    <button type="reset" onclick="resetBtn()" class="btn btn-secondary">{{translate('reset')}} </button>
                    <button type="submit" class="btn btn--primary btn-user" id="apply">{{translate('submit')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


<script>
    function resetBtn() {
        let placeholderImg = $("#placeholderImg").data('img');
        $('#viewer').attr('src', placeholderImg);
        $('#viewerBanner').attr('src', placeholderImg);
        $('#viewerBottomBanner').attr('src', placeholderImg);
        $('#viewerLogo').attr('src', placeholderImg);
        $('.spartan_remove_row').click();
    }

    function openInfoWeb() {
        var x = document.getElementById("website_info");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
@push('script')
<script>
    $('#inputCheckd').change(function() {
        // console.log('jell');
        if ($(this).is(':checked')) {
            $('#apply').removeAttr('disabled');
        } else {
            $('#apply').attr('disabled', 'disabled');
        }

    });

    $('#exampleInputPassword ,#exampleRepeatPassword').on('keyup', function() {
        var pass = $("#exampleInputPassword").val();
        var passRepeat = $("#exampleRepeatPassword").val();
        if (pass == passRepeat) {
            $('.pass').hide();
        } else {
            $('.pass').show();
        }
    });
    $('#apply').on('click', function() {

        var image = $("#image-set").val();
        if (image == "") {
            $('.image').show();
            return false;
        }
        var pass = $("#exampleInputPassword").val();
        var passRepeat = $("#exampleRepeatPassword").val();
        if (pass != passRepeat) {
            $('.pass').show();
            return false;
        }


    });

    function Validate(file) {
        var x;
        var le = file.length;
        var poin = file.lastIndexOf(".");
        var accu1 = file.substring(poin, le);
        var accu = accu1.toLowerCase();
        if ((accu != '.png') && (accu != '.jpg') && (accu != '.jpeg')) {
            x = 1;
            return x;
        } else {
            x = 0;
            return x;
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#viewer').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#customFileUpload").change(function() {
        readURL(this);
    });

    function readlogoURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#viewerLogo').attr('src', e.target.result);
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

    $("#LogoUpload").change(function() {
        readlogoURL(this);
    });
    $("#BannerUpload").change(function() {
        readBannerURL(this);
    });
    $("#BottomBannerUpload").change(function() {
        readBottomBannerURL(this);
    });
</script>

<script>
    function togglePickupAddressFields() {
        var pickupAddressFields = document.getElementById('pickup_address_fields');
        var checkbox = document.getElementById('pickup_same_as_shop');
        
        if (checkbox.checked) {
            pickupAddressFields.style.display = 'none';
        } else {
            pickupAddressFields.style.display = 'block';
        }
    }
</script>
@endpush