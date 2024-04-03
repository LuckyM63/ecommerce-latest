@extends('layouts.back-end.app-seller')
@section('title', translate('shop_view'))
@push('css_or_js')
<!-- Custom styles for this page -->
<link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img width="20" src="{{asset('/public/assets/back-end/img/shop-info.png')}}" alt="">
            {{translate('shop_Info')}}
        </h2>
    </div>
    <!-- End Page Title -->

    @include('seller-views.shop.inline-menu')

    <div class="card mb-3">
        <div class="card-body">

            <form action="{{route('seller.shop.temporary-close')}}" method="POST" id="temporary_close_form">
                @csrf
                <div class="border rounded border-color-c1 px-4 py-3 d-flex justify-content-between mb-1">
                    <h5 class="mb-0 d-flex gap-1 c1">
                        {{translate('temporary_close')}}
                    </h5>
                    <input type="hidden" name="id" value="{{ $shop->id }}">
                    <div class="position-relative">
                        <label class="switcher">
                            <input type="checkbox" class="switcher_input" name="status" value="1" id="temporary_close" {{isset($shop->temporary_close) && $shop->temporary_close == 1 ? 'checked':''}} onclick="toogleStatusModal(event,'temporary_close','maintenance_mode-on.png','maintenance_mode-off.png','{{translate('Want_to_enable_the_Temporary_Close')}}','{{translate('Want_to_disable_the_Temporary_Close')}}',`<p>{{translate('if_you_enable_this_option_your_shop_will_be_shown_as_temporarily_closed_in_the_user_app_and_website_and_customers_cannot_add_products_from_your_shop')}}</p>`,`<p>{{translate('if_you_disable_this_option_your_shop_will_be_open_in_the_user_app_and_website_and_customers_can_add_products_from_your_shop')}}</p>`)">
                            <span class="switcher_control"></span>
                        </label>
                    </div>
                </div>
            </form>

            <p>* {{translate('by_turning_on_temporary_close_mode_your_shop_will_be_shown_as_temporary_off_in_the_website_and_app_for_the_customers._they_cannot_purchase_or_place_order_from_your_shop')}}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h4 class="mb-0">{{translate('my_shop_info')}} </h4>
                    </div>
                    <div class="d-inline-flex gap-2">
                        <button class="btn btn-block __inline-70" data-toggle="modal" data-target="#balance-modal">
                            {{translate('go_to_Vacation_Mode')}}
                        </button>

                        <a class="btn btn--primary __inline-70 px-4 text-white" href="{{route('seller.shop.edit',[$shop->id])}}">
                            {{translate('edit')}}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Column for Shop Image and Details -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-center flex-wrap gap-5">
                                @if($shop->image=='def.png')
                                <div class="text-{{ Session::get('direction') === "rtl" ? 'right' : 'left' }}">
                                    <img height="200" width="200" class="rounded-circle border" onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'" src="{{ asset('public/assets/back-end') }}/img/shop.png">
                                </div>
                                @else
                                <div class="text-{{ Session::get('direction') === "rtl" ? 'right' : 'left' }}">
                                    <img src="{{ asset('storage/app/public/shop/'.$shop->image) }}" onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'" class="rounded-circle border" height="200" width="200" alt="">
                                </div>
                                @endif

                                <div>
                                    <div class="flex-start">
                                        <h4>{{ translate('name') }} : </h4>
                                        <h4 class="mx-1">{{ $shop->name }}</h4>
                                    </div>
                                    <div class="flex-start">
                                        <h6>{{ translate('phone') }} : </h6>
                                        <h6 class="mx-1">{{ $shop->contact }}</h6>
                                    </div>
                                    <div class="flex-start">
                                        <h6>{{ translate('address') }} : </h6>
                                        <h6 class="mx-1">{{ $shop->address }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Column for Pickup Address -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">{{ translate('pickup_address') }}</h5>
                                    <button type="button" class="btn btn--primary px-4 text-white" onclick="editPickupAddress()">{{ translate('Edit') }}</button>

                                    @if($shop->default_pickup_address)
                                    @endif
                                </div>
                                <div class="card-body">
                                    <div class="flex-start">
                                        <h6>{{ translate('address') }}:</h6>
                                        <h6>{{ $shop->default_pickup_address->address ?? '' }}</h6>
                                    </div>
                                    <div class="flex-start">
                                        <h6>{{ translate('house_no.') }}:</h6>
                                        <h6>{{ $shop->default_pickup_address->house_no ?? '' }}</h6>
                                    </div>
                                    <div class="flex-start">
                                        <h6>{{ translate('street_no.') }}:</h6>
                                        <h6>{{ $shop->default_pickup_address->street_no ?? '' }}</h6>
                                    </div>
                                    <div class="flex-start">
                                        <h6>{{ translate('city') }}:</h6>
                                        <h6>{{ $shop->default_pickup_address->city ?? '' }}</h6>
                                    </div>
                                    <div class="flex-start">
                                        <h6>{{ translate('state') }}:</h6>
                                        <h6>{{ $shop->default_pickup_address->state ?? '' }}</h6>
                                    </div>
                                    <div class="flex-start">
                                        <h6>{{ translate('country') }}:</h6>
                                        <h6>{{ $shop->default_pickup_address->country ?? '' }}</h6>
                                    </div>
                                    <div class="flex-start">
                                        <h6>{{ translate('pincode') }}:</h6>
                                        <h6>{{ $shop->default_pickup_address->pincode ?? '' }}</h6>
                                    </div>
                                    <div>
                                        {{ translate('Pickup Address Update On Shiprocket:') }}
                                        @if($shop->default_pickup_address && $shop->default_pickup_address->is_added_on_shiprocket == false)
                                        <button type="button" onclick="updatePickupAddress()" class="btn btn-primary btn-sm px-4">{{ translate('Update') }}</button>
                                        @elseif($shop->default_pickup_address && $shop->default_pickup_address->is_added_on_shiprocket == true)
                                        <button type="button" class="btn btn-success" disabled>{{ translate('Already Updated') }}</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="balance-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                <form action="{{route('seller.shop.vacation-add', [$shop->id])}}" method="post">
                    <div class="modal-header border-bottom pb-2">
                        <div>
                            <h5 class="modal-title" id="exampleModalLabel">{{translate('vacation_Mode')}}</h5>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="switcher">
                                    <input type="checkbox" name="vacation_status" class="switcher_input" id="vacation_close" {{$shop->vacation_status == 1?'checked':''}}>
                                    <span class="switcher_control"></span>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="close pt-0" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">*{{translate('set_vacation_mode_for_shop_means_you_will_be_not_available_receive_order_and_provider_products_for_placed_order_at_that_time')}}</div>

                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label>{{translate('vacation_Start')}}</label>
                                <input type="date" name="vacation_start_date" value="{{ $shop->vacation_start_date }}" id="vacation_start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>{{translate('vacation_End')}}</label>
                                <input type="date" name="vacation_end_date" value="{{ $shop->vacation_end_date }}" id="vacation_end_date" class="form-control" required>
                            </div>
                            <div class="col-md-12 mt-2 ">
                                <label>{{translate('vacation_Note')}}</label>
                                <textarea class="form-control" name="vacation_note" id="vacation_note">{{ $shop->vacation_note }}</textarea>
                            </div>
                        </div>

                        <div class="text-end gap-5 mt-2">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('close')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('update')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Show  update pickup address  Modal -->

@php
    $fullAddress = optional($shop->default_pickup_address)->address ?? '';
    $fullAddress .= $fullAddress ? ", House No - " . optional($shop->default_pickup_address)->house_no ?? '' : '';
    $fullAddress .= $fullAddress ? ", Road No - " . optional($shop->default_pickup_address)->street_no ?? '' : '';
@endphp


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
                            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>{{ translate('pickup_address') }}</label>
                                    <input class="form-control" type="text" name="pickup_address" value="{{ $shop->default_pickup_address->address ?? '' }}" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>{{ translate('house_no/Flat_no.') }}</label>
                                        <input class="form-control" type="text" name="house_no" value="{{ $shop->default_pickup_address->house_no ?? '' }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ translate('street_no') }}</label>
                                        <input class="form-control" type="text" name="street_no" value="{{ $shop->default_pickup_address->street_no ?? ''}}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ translate('city') }}</label>
                                        <input class="form-control" type="text" name="city" value="{{ $shop->default_pickup_address->city ?? '' }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ translate('state') }}</label>
                                        <input class="form-control" type="text" name="state" value="{{ $shop->default_pickup_address->state ?? ''}}" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>{{ translate('country') }}</label>
                                        <input class="form-control" type="text" name="country" value="{{ $shop->default_pickup_address->country ?? '' }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{ translate('pincode') }}</label>
                                        <input class="form-control" type="text" name="pincode" value="{{ $shop->default_pickup_address->pincode ?? '' }}" required>
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
    <script>
        $('#temporary_close_form').on('submit', function (event){
            event.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('seller.shop.temporary-close')}}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (data) {
                    toastr.success(data.message);
                    location.reload();
                }
            });
        });

        $('#vacation_start_date,#vacation_end_date').change(function () {
            let fr = $('#vacation_start_date').val();
            let to = $('#vacation_end_date').val();
            if(fr != ''){
                $('#vacation_end_date').attr('required','required');
            }
            if(to != ''){
                $('#vacation_start_date').attr('required','required');
            }
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#vacation_start_date').val('');
                    $('#vacation_end_date').val('');
                    toastr.error('{{translate("invalid_date_range")}}!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })
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
        if ("{{ $shop->default_pickup_address ? $shop->default_pickup_address->id : '' }}" === "") {
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
                    pickup_location: "{{ $shop->default_pickup_address ? $shop->default_pickup_address->id : '' }}",
                    name: "{{ $shop->seller->f_name  }}",
                    email: "{{ $shop->seller->email  }}",
                    phone: "{{ $shop->seller->phone }}",
                    address: "{{ $fullAddress ?? ''}}",
                    address_2: '',
                    city: "{{ $shop->default_pickup_address ? $shop->default_pickup_address->city : '' }}",
                    state: "{{ $shop->default_pickup_address ? $shop->default_pickup_address->state : '' }}",
                    country: "{{ $shop->default_pickup_address ? $shop->default_pickup_address->country : '' }}",
                    pin_code: "{{ $shop->default_pickup_address ? $shop->default_pickup_address->pincode : '' }}"
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
            url: "{{ $shop->default_pickup_address ? route('admin.sellers.update_pickup_added_on_shiprocket_status', ['id' => $shop->default_pickup_address->id]) : '' }}",
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
