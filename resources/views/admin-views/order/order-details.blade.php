@extends('layouts.back-end.app')

@section('title', translate('order_Details'))

@push('css_or_js')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-4">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/all-orders.png')}}" alt="">
            {{translate('order_Details')}}
        </h2>
    </div>
    <!-- End Page Title -->


    <!-- 008 -->
    @php($order_items=[])
    <!-- 008 -->


    <div class="row gy-3" id="printableArea">
        <div class="col-lg-8">
            <!-- Card -->
            <div class="card h-100">
                <!-- Body -->
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-10 justify-content-between mb-4">
                        <div class="d-flex flex-column gap-10">
                            <!-- <h3> {{$order['id']}}</h3> -->
                            <h4 class="text-capitalize">{{translate('Order_ID')}} #{{$order['id']}}</h4>
                            <div class="">
                                {{date('d M, Y , h:i A',strtotime($order['created_at']))}}
                            </div>
                            @if ($linked_orders->count() >0)
                            <div class="d-flex flex-wrap gap-10">
                                <div class="color-caribbean-green-soft font-weight-bold d-flex align-items-center rounded py-1 px-2"> {{translate('linked_orders')}} ({{$linked_orders->count()}}) : </div>
                                @foreach($linked_orders as $linked)
                                <a href="{{route('admin.orders.details',[$linked['id']])}}" class="btn color-caribbean-green text-white rounded py-1 px-2">{{$linked['id']}}</a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="text-sm-right">
                            <div class="d-flex flex-wrap gap-10 justify-content-end">
                                <!-- order verificaiton button-->
                                @if (count($order->verification_images)>0 && $order->verification_status ==1)
                                <div>
                                    <button class="btn btn--primary px-4" data-toggle="modal" data-target="#order_verification_modal"><i class="tio-verified"></i> {{translate('order_verification')}}
                                    </button>
                                </div>
                                @endif
                                <!-- order verificaiton button-->
                                @if (isset($shipping_address['latitude']) && isset($shipping_address['longitude']))
                                <div class="">
                                    <button class="btn btn--primary px-4" data-toggle="modal" data-target="#locationModal"><i class="tio-map"></i> {{translate('show_locations_on_map')}}</button>
                                </div>
                                @endif

                                <a class="btn btn--primary px-4" target="_blank" href={{route('admin.orders.generate-invoice',[$order['id']])}}>
                                    <img src="{{ asset('public/assets/back-end/img/icons/uil_invoice.svg') }}" alt="" class="mr-1">
                                    {{translate('print_Invoice')}}
                                </a>
                            </div>
                            <div class="d-flex flex-column gap-2 mt-3">
                                <!-- Order status -->
                                <div class="order-status d-flex justify-content-sm-end gap-10 text-capitalize">
                                    <span class="title-color">{{translate('status')}}: </span>
                                    @if($order['order_status']=='pending')
                                    <span class="badge color-caribbean-green-soft font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{translate(str_replace('_',' ',$order['order_status']))}}</span>
                                    @elseif($order['order_status']=='failed')
                                    <span class="badge badge-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{translate(str_replace('_',' ',$order['order_status'] == 'failed' ? 'Failed to Deliver' : ''))}}
                                    </span>
                                    @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                    <span class="badge badge-soft-warning font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                        {{translate(str_replace('_',' ',$order['order_status'] == 'processing' ? 'Packaging' : $order['order_status']))}}
                                    </span>
                                    @elseif($order['order_status']=='delivered' || $order['order_status']=='confirmed')
                                    <span class="badge badge-soft-success font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                        {{translate(str_replace('_',' ',$order['order_status']))}}
                                    </span>
                                    @else
                                    <span class="badge badge-soft-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                        {{translate(str_replace('_',' ',$order['order_status']))}}
                                    </span>
                                    @endif
                                </div>

                                <!-- Payment Method -->
                                <div class="payment-method d-flex justify-content-sm-end gap-10 text-capitalize">
                                    <span class="title-color">{{translate('payment_Method')}} :</span>
                                    <strong>{{translate($order['payment_method'])}}</strong>
                                </div>

                                <!-- reference-code -->
                                @if($order->payment_method != 'cash_on_delivery' && $order->payment_method != 'pay_by_wallet' && !isset($order->offline_payments))
                                <div class="reference-code d-flex justify-content-sm-end gap-10 text-capitalize">
                                    <span class="title-color">{{translate('reference_Code')}} :</span>
                                    <strong>{{str_replace('_',' ',$order['transaction_ref'])}} {{ $order->payment_method == 'offline_payment' ? '('.$order->payment_by.')':'' }}</strong>
                                </div>
                                @endif

                                <!-- Payment Status -->
                                <div class="payment-status d-flex justify-content-sm-end gap-10">
                                    <span class="title-color">{{translate('payment_Status')}}:</span>
                                    @if($order['payment_status']=='paid')
                                    <span class="text-success payment-status-span font-weight-bold">
                                        {{translate('paid')}}
                                    </span>
                                    @else
                                    <span class="text-danger payment-status-span font-weight-bold">
                                        {{translate('unpaid')}}
                                    </span>
                                    @endif
                                </div>

                                @if(\App\CPU\Helpers::get_business_settings('order_verification'))
                                <span class="ml-2 ml-sm-3">
                                    <b>
                                        {{translate('order_verification_code')}} : {{$order['verification_code']}}
                                    </b>
                                </span>
                                @endif

                            </div>
                        </div>
                        <!-- Order Note -->
                        @if ($order->order_note !=null)
                        <div class="mt-2 mb-5 w-100 d-block">
                            <div class="gap-10">
                                <h4>{{translate('order_Note')}}:</h4>
                                <div class="text-justify">{{$order->order_note}}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="table-responsive datatable-custom">
                        <table class="table fz-12 table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('item_details')}}</th>
                                    <th>{{translate('item_price')}}</th>
                                    <th>{{translate('tax')}}</th>
                                    <th>{{translate('item_discount')}}</th>
                                    <th>{{translate('total_price')}}</th>
                                </tr>
                            </thead>

                            <tbody>

                                @php($item_price=0)
                                @php($total_price=0)
                                @php($subtotal=0)
                                @php($total=0)
                                @php($shipping=0)
                                @php($discount=0)
                                @php($tax=0)
                                @php($row=0)

                                @foreach($order->details as $key=>$detail)
                                @if($detail->product_all_status)

                                <!-- 008 -->

                                <!-- {{ $detail->product_all_status['slug'] }} -->


                                @php($order_item = [
                                'name' => $detail->product_all_status['name'],
                                'sku' => $detail->product_all_status['slug'], // Assuming 'sku' is available in $detail ($detail->sku)
                                'units' => $detail['qty'],
                                'selling_price' => $detail['price'],
                                'discount' => $detail['discount'],
                                'tax' => $detail['tax'],
                                'hsn' => 12245 // Assuming 'hsn' is available in $detail ($detail->hsn)
                                ])

                                <!-- @php($order_item_json = json_encode($order_item)) -->
                                @php($order_items[] = $order_item)

                                <!-- 008 -->
                                <tr>
                                    <td>{{ ++$row }}</td>
                                    <td>
                                        <div class="media align-items-center gap-10">
                                            <img class="avatar avatar-60 rounded" onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'" src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$detail->product_all_status['thumbnail']}}" alt="Image Description">
                                            <div>
                                                <h6 class="title-color">{{substr($detail->product_all_status['name'],0,30)}}{{strlen($detail->product_all_status['name'])>10?'...':''}}</h6>
                                                <div><strong>{{translate('qty')}} :</strong> {{$detail['qty']}}</div>
                                                <div>
                                                    <strong>{{translate('unit_price')}} :</strong>
                                                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['price']+($detail->tax_model =='include' ? $detail['tax']:0)))}}
                                                    @if ($detail->tax_model =='include')
                                                    ({{translate('tax_incl.')}})
                                                    @else
                                                    ({{translate('tax').":".($detail->product_all_status->tax)}}{{$detail->product_all_status->tax_type ==="percent" ? '%' :''}})
                                                    @endif

                                                </div>
                                                @if ($detail->variant)
                                                <div><strong>{{translate('variation')}} :</strong> {{$detail['variant']}}</div>
                                                @endif
                                            </div>
                                        </div>
                                        @if($detail->product_all_status->digital_product_type == 'ready_after_sell')
                                        <button type="button" class="btn btn-sm btn--primary mt-2" title="File Upload" data-toggle="modal" data-target="#fileUploadModal-{{ $detail->id }}" onclick="modalFocus('fileUploadModal-{{ $detail->id }}')">
                                            <i class="tio-file-outlined"></i> {{translate('file')}}
                                        </button>
                                        @endif
                                    </td>
                                    <td>
                                        {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['price']*$detail['qty'])) }}
                                    </td>
                                    <td>
                                        {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['tax'])) }}
                                    </td>

                                    <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['discount']))}}</td>

                                    @php($subtotal=$detail['price']*$detail['qty']+$detail['tax']-$detail['discount'])
                                    <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}</td>
                                </tr>
                                @php($item_price+=$detail['price']*$detail['qty'])
                                @php($discount+=$detail['discount'])
                                @php($tax+=$detail['tax'])
                                @php($total+=$subtotal)

                                <!-- 008 -->
                                <!-- {{$detail->product_all_status}} -->
                                <!-- {{ json_encode($order_items) }}  -->
                                <!-- 008 -->
                                <!-- End Media -->
                                @endif
                                @php($sellerId=$detail->seller_id)

                                

                                @if(isset($detail->product_all_status->digital_product_type) && $detail->product_all_status->digital_product_type == 'ready_after_sell')
                                @php($product_details = json_decode($detail->product_details))
                                <div class="modal fade" id="fileUploadModal-{{ $detail->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.orders.digital-file-upload-after-sell') }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    @if($detail->digital_file_after_sell)
                                                    <div class="mb-4">
                                                        {{translate('uploaded_file')}} :
                                                        <a href="{{ asset('storage/app/public/product/digital-product/'.$detail->digital_file_after_sell) }}" class="btn btn-success btn-sm" title="Download" download><i class="tio-download"></i> {{translate('download')}}</a>
                                                    </div>
                                                    @else
                                                    <h4 class="text-center">{{translate('file_not_found')}}!</h4>
                                                    @endif
                                                    @if(($product_details->added_by == 'admin') && $detail->seller_id == 1)
                                                    <div class="inputDnD">
                                                        <div class="form-group inputDnD input_image input_image_edit rounded-lg" data-title="{{translate('drag_&_drop_file_or_browse_file')}}" data-title="{{translate('drag_&_drop_file_or_browse_file')}}">
                                                            <input type="file" name="digital_file_after_sell" class="form-control-file text--primary font-weight-bold" id="inputFile" accept=".jpg, .jpeg, .png, .gif, .zip, .pdf" onchange="readUrl(this)">
                                                        </div>
                                                    </div>
                                                    <div class="mt-1 text-info">{{translate('file_type')}}: jpg, jpeg, png, gif, zip, pdf</div>
                                                    <input type="hidden" value="{{ $detail->id }}" name="order_id">
                                                    @else
                                                    <h4 class="mt-3 text-center">{{translate('admin_have_no_permission_for_sellers_digital_product_upload')}}</h4>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('close')}}</button>
                                                    @if(($product_details->added_by == 'admin') && $detail->seller_id == 1)
                                                    <button type="submit" class="btn btn--primary">{{translate('upload')}}</button>
                                                    @endif
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @php($shipping=$order['shipping_cost'])
                    @php($coupon_discount=$order['discount_amount'])
                    <hr />
                    <div class="row justify-content-md-end mb-3">
                        <div class="col-md-9 col-lg-8">
                            <dl class="row gy-1 text-sm-right">
                                <dt class="col-5">{{translate('item_price')}}</dt>
                                <dd class="col-6 title-color">
                                    <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item_price))}}</strong>
                                </dd>
                                <dt class="col-5 text-capitalize">{{translate('item_discount')}}</dt>
                                <dd class="col-6 title-color">
                                    - <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($discount))}}</strong>
                                </dd>
                                <dt class="col-5 text-capitalize">{{translate('sub_total')}}</dt>
                                <dd class="col-6 title-color">
                                    <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($item_price-$discount))}}</strong>
                                </dd>
                                <dt class="col-5">{{translate('coupon_discount')}}</dt>
                                <dd class="col-6 title-color">
                                    - <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon_discount))}}</strong>
                                </dd>
                                <dt class="col-5 text-uppercase">{{translate('vat')}}/{{translate('tax')}}</dt>
                                <dd class="col-6 title-color">
                                    <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($tax))}}</strong>
                                </dd>
                                <dt class="col-5 text-capitalize">{{translate('delivery_fee')}}</dt>
                                <dd class="col-6 title-color">
                                    <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($shipping))}}</strong>
                                </dd>

                                @php($delivery_fee_discount = 0)
                                @if ($order['is_shipping_free'])
                                <dt class="col-5">{{translate('delivery_fee_discount')}} ({{ translate($order['free_delivery_bearer']) }} {{translate('bearer')}})</dt>
                                <dd class="col-6 title-color">
                                    + {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($shipping))}}
                                </dd>
                                @php($delivery_fee_discount = $shipping)
                                @php($total += $delivery_fee_discount)
                                @endif

                                @if($order['coupon_discount_bearer'] == 'inhouse' && !in_array($order['coupon_code'], [0, NULL]))
                                <dt class="col-5">{{translate('coupon_discount')}} ({{translate('admin_bearer')}})</dt>
                                <dd class="col-6 title-color">
                                    + {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon_discount))}}
                                </dd>
                                @php($total += $coupon_discount)
                                @endif

                                <dt class="col-5"><strong>{{translate('total')}}</strong></dt>
                                <dd class="col-6 title-color">
                                    <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total+$shipping-$coupon_discount -$delivery_fee_discount))}}</strong>
                                </dd>
                            </dl>
                            <!-- End Row -->
                        </div>
                    </div>
                    <!-- End Row -->
                </div>
                <!-- End Body -->
            </div>
            <!-- End Card -->
        </div>

        <div class="col-lg-4 d-flex flex-column gap-3">
            {{-- Payment Information --}}
            @if($order->payment_method == 'offline_payment' && isset($order->offline_payments))
            <div class="card">
                <!-- Body -->
                <div class="card-body">
                    <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                        <h4 class="d-flex gap-2">
                            <img src="{{asset('/public/assets/back-end/img/product_setup.png')}}" alt="" width="20">
                            {{translate('Payment_Information')}}
                        </h4>
                    </div>

                    <div>
                        <table>
                            <tbody>
                                <tr>
                                    <td>{{translate('payment_Method')}}</td>
                                    <td class="py-1 px-2">:</td>
                                    <td><strong>{{ translate($order['payment_method']) }}</strong></td>
                                </tr>
                                @foreach (json_decode($order->offline_payments->payment_info) as $key=>$item)
                                @if (isset($item) && $key != 'method_id')
                                <tr>
                                    <td>{{translate($key)}}</td>
                                    <td class="py-1 px-2">:</td>
                                    <td><strong>{{ $item }}</strong></td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(isset($order->payment_note) && $order->payment_method == 'offline_payment')
                    <div class="payment-status mt-3">
                        <h4>{{translate('payment_Note')}}:</h4>
                        <p class="text-justify">
                            {{ $order->payment_note }}
                        </p>
                    </div>
                    @endif
                </div>
                <!-- End Body -->
            </div>
            @endif

            <!-- Order & Shipping Info Card -->
            <div class="card">
                <div class="card-body text-capitalize d-flex flex-column gap-4">
                    <div class="d-flex flex-column align-items-center gap-2">
                        <h4 class="mb-0 text-center">{{translate('order_&_Shipping_Info')}}</h4>
                    </div>

                    <div class="">
                        <label class="font-weight-bold title-color fz-14">{{translate('change_order_status')}}</label>
                        <select name="order_status" onchange="order_status(this.value)" class="status form-control" data-id="{{$order['id']}}">

                            <option value="pending" {{$order->order_status == 'pending'?'selected':''}}> {{translate('pending')}}</option>
                            <option value="confirmed" {{$order->order_status == 'confirmed'?'selected':''}}> {{translate('confirmed')}}</option>
                            <option value="processing" {{$order->order_status == 'processing'?'selected':''}}>{{translate('packaging')}} </option>
                            <option value="intransit" {{$order->order_status == 'intransit'?'selected':''}}>{{translate('intransit')}} </option>
                            <option class="text-capitalize" value="out_for_delivery" {{$order->order_status == 'out_for_delivery'?'selected':''}}>{{translate('out_for_delivery')}} </option>
                            <option value="delivered" {{$order->order_status == 'delivered'?'selected':''}}>{{translate('delivered')}} </option>
                            <option value="returned" {{$order->order_status == 'returned'?'selected':''}}> {{translate('returned')}}</option>
                            <option value="failed" {{$order->order_status == 'failed'?'selected':''}}>{{translate('failed_to_Deliver')}} </option>
                            <option value="canceled" {{$order->order_status == 'canceled'?'selected':''}}>{{translate('canceled')}} </option>
                        </select>
                    </div>

                    <!-- Payment Status -->
                    <div class="d-flex justify-content-between align-items-center gap-10 form-control h-auto flex-wrap">
                        <span class="title-color">
                            {{translate('payment_status')}}
                        </span>
                        <div class="d-flex justify-content-end min-w-100 align-items-center gap-2">
                            <span class="text--primary font-weight-bold">{{ $order->payment_status=='paid' ? translate('paid'):translate('unpaid')}}</span>
                            <label class="switcher payment-status-text">
                                <input class="switcher_input payment_status" type="checkbox" name="status" value="{{$order->payment_status}}" {{ $order->payment_status=='paid' ? 'checked':''}}>
                                <span class="switcher_control switcher_control_add"></span>
                            </label>
                        </div>

                    </div>
                    
                    @if($physical_product)

                    <div class="p-2 bg-light rounded">
                                <div class="media m-1 gap-3">
                                    <img class="avatar rounded-circle" onerror="this.src='{{asset('public/assets/back-end/img/image-place-holder.png')}}'" src="{{asset('public/assets/back-end/img/third-party-delivery.png')}}" alt="Image">
                                    <div class="media-body">
                                        <h5 class="">
                                            {{ isset($order->delivery_service_name) ? $order->delivery_service_name : translate('not_assign_yet') }}
                                        </h5>
                                        <span class="fz-12 title-color">{{ translate('Order_ID') }} : {{ $order->third_party_delivery_tracking_id }}</span><br>
                                        <span class="fz-12 title-color">{{ translate('Shipment_ID') }} : {{ $order->third_party_delivery_shipment_id }}</span>

                                        @if(isset($order->third_party_delivery_AWB_id))
                                        <br>
                                        <span class="fz-12 title-color">{{ translate('AWB') }} : {{ $order->third_party_delivery_AWB_id }}</span>
                                        @else
                                        <br>
                                        <span class="fz-12 title-color">{{ translate('AWB') }} : {{ translate('not_assign_yet') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                    @endif
                    <!-- @if($physical_product) -->
                    <ul class="list-unstyled list-unstyled-py-4">
                        <!-- <li>
                            @if ($order->shipping_type == 'order_wise')
                            <label class="font-weight-bold title-color fz-14">
                                {{translate('shipping_Method')}}
                                ({{$order->shipping ? translate(str_replace('_',' ',$order->shipping->title)) :translate('no_shipping_method_selected')}})
                            </label>
                            @endif

                            <select class="form-control text-capitalize" name="delivery_type" onchange="choose_delivery_type(this.value)">
                                <option value="0">
                                    {{translate('choose_delivery_type')}}
                                </option>

                                <option value="self_delivery" {{$order->delivery_type=='self_delivery'?'selected':''}}>
                                    {{translate('by_self_delivery_man')}}
                                </option>
                                
                                <option value="ship_rocket_delivery" {{$order->delivery_type=='ship_rocket_delivery'?'selected':''}}>
                                    {{translate('by_ShipRrocket_delivery_service')}}
                                </option>

                                
                            </select>
                        </li> -->

                        <!-- <li class="choose_delivery_man">
                            <label class="font-weight-bold title-color fz-14">
                                {{translate('delivery_man')}}
                            </label>
                            <select class="form-control text-capitalize js-select2-custom" name="delivery_man_id" onchange="addDeliveryMan(this.value)">
                                <option value="0">{{translate('select')}}</option>
                                @foreach($delivery_men as $deliveryMan)
                                <option value="{{$deliveryMan['id']}}" {{$order['delivery_man_id']==$deliveryMan['id']?'selected':''}}>
                                    {{$deliveryMan['f_name'].' '.$deliveryMan['l_name'].' ('.$deliveryMan['phone'].' )'}}
                                </option>
                                @endforeach
                            </select>

                            @if (isset($order->delivery_man))
                            <div class="p-2 bg-light rounded mt-4">
                                <div class="media m-1 gap-3">
                                    <img class="avatar rounded-circle" onerror="this.src='{{asset('public/assets/back-end/img/image-place-holder.png')}}'" src="{{asset('storage/app/public/profile/'.isset($order->delivery_man->image) ?? '')}}" alt="Image">
                                    <div class="media-body">
                                        <h5 class="mb-1">{{ isset($order->delivery_man) ? $order->delivery_man->f_name.' '.$order->delivery_man->l_name :''}}</h5>
                                        <a href="tel:{{isset($order->delivery_man) ? $order->delivery_man->phone : ''}}" class="fz-12 title-color">{{isset($order->delivery_man) ? $order->delivery_man->phone :''}}</a>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="p-2 bg-light rounded mt-4">
                                <div class="media m-1 gap-3">
                                    <img class="avatar rounded-circle" onerror="this.src='{{asset('public/assets/back-end/img/image-place-holder.png')}}'" src="{{asset('public/assets/back-end/img/delivery-man.png')}}" alt="Image">
                                    <div class="media-body">
                                        <h5 class="mt-3">{{translate('no_delivery_man_assigned')}}</h5>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </li> -->
                        <!-- @if (isset($order->delivery_man))
                        <li class="choose_delivery_man">
                            <label class="font-weight-bold title-color fz-14">
                                {{translate('deliveryman_will_get')}} ({{ session('currency_symbol') }})
                            </label>
                            <input type="number" id="deliveryman_charge" onkeyup="amountDateUpdate(this, event)" value="{{ $order->deliveryman_charge }}" name="deliveryman_charge" class="form-control" placeholder="Ex: 20" required>
                        </li>
                        <li class="choose_delivery_man">
                            <label class="font-weight-bold title-color fz-14">
                                {{translate('expected_delivery_date')}}
                            </label>
                            <input type="date" onchange="amountDateUpdate(this, event)" value="{{ $order->expected_delivery_date }}" name="expected_delivery_date" id="expected_delivery_date" class="form-control" required>
                        </li>
                        @endif -->

                        <!-- <li class="mt-1" id="by_third_party_delivery_service_info">
                            <div class="p-2 bg-light rounded">
                                <div class="media m-1 gap-3">
                                    <img class="avatar rounded-circle" onerror="this.src='{{asset('public/assets/back-end/img/image-place-holder.png')}}'" src="{{asset('public/assets/back-end/img/third-party-delivery.png')}}" alt="Image">
                                    <div class="media-body">
                                        <h5 class="">
                                            {{ isset($order->delivery_service_name) ? $order->delivery_service_name : translate('not_assign_yet') }}
                                        </h5>
                                        <span class="fz-12 title-color">{{ translate('Order_ID') }} : {{ $order->third_party_delivery_tracking_id }}</span><br>
                                        <span class="fz-12 title-color">{{ translate('Shipment_ID') }} : {{ $order->third_party_delivery_shipment_id }}</span>

                                        @if(isset($order->third_party_delivery_AWB_id))
                                        <br>
                                        <span class="fz-12 title-color">{{ translate('AWB') }} : {{ $order->third_party_delivery_AWB_id }}</span>
                                        @else
                                        <br>
                                        <span class="fz-12 title-color">{{ translate('AWB') }} : {{ translate('not_assign_yet') }}</span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </li> -->


                    </ul>
                    <!-- @endif -->
                </div>
            </div>

            <!-- Customer Info Card -->
            @if(!$order->is_guest && $order->customer)
            <div class="card">
                <!-- Body -->
                <div class="card-body">
                    <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                        <h4 class="d-flex gap-2">
                            <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" alt="">
                            {{translate('customer_information')}}
                        </h4>
                    </div>
                    <div class="media flex-wrap gap-3">
                        <div class="">
                            <img class="avatar rounded-circle avatar-70" onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" src="{{asset('storage/app/public/profile/'.$order->customer->image)}}" alt="Image">
                        </div>
                        <div class="media-body d-flex flex-column gap-1">
                            <span class="title-color"><strong>{{$order->customer['f_name'].' '.$order->customer['l_name']}} </strong></span>
                            <span class="title-color"> <strong>{{\App\Model\Order::where('customer_id',$order['customer_id'])->count()}}</strong> {{translate('orders')}}</span>
                            <span class="title-color break-all"><strong>{{$order->customer['phone']}}</strong></span>
                            <span class="title-color break-all">{{$order->customer['email']}}</span>
                        </div>
                    </div>
                </div>
                <!-- End Body -->
            </div>
            @endif
            <!-- End Card -->

            <!-- Shipping Address Card -->
            @if($physical_product)
            <div class="card">
                <!-- Body -->
                @php($shipping_address=json_decode($order['shipping_address_data']))
                @if($shipping_address)
                <div class="card-body">
                    <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                        <h4 class="d-flex gap-2">
                            <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" alt="">
                            {{translate('shipping_address')}}
                        </h4>

                        <button class="btn btn-outline-primary btn-sm square-btn" title="Edit" data-toggle="modal" data-target="#shippingAddressUpdateModal">
                            <i class="tio-edit"></i>
                        </button>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <div>
                            <span>{{translate('name')}} :</span>
                            <strong>{{$shipping_address->contact_person_name}}</strong> {{ $order->is_guest ? '('. translate('guest_customer') .')':''}}
                        </div>
                        <div>
                            <span>{{translate('contact')}} :</span>
                            <strong>{{$shipping_address->phone}}</strong>
                        </div>
                        @if ($order->is_guest && $shipping_address->email)
                        <div>
                            <span>{{translate('email')}} :</span>
                            <strong>{{$shipping_address->email}}</strong>
                        </div>
                        @endif
                        <div>
                            <span>{{translate('city')}} :</span>
                            <strong>{{$shipping_address->city}}</strong>
                        </div>
                        <div>
                            <span>{{translate('zip_code')}} :</span>
                            <strong>{{$shipping_address->zip}}</strong>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <!-- <span>{{translate('address')}} :</span> -->
                            <img src="{{asset('/public/assets/back-end/img/location.png')}}" alt="">
                            {{$shipping_address->address ?? translate('empty')}}
                        </div>
                    </div>
                </div>
                @else
                <div class="card-body">
                    <div class="media align-items-center">
                        <span>{{translate('no_shipping_address_found')}}</span>
                    </div>
                </div>
                @endif
                <!-- End Body -->
            </div>
            @endif
            <!-- End Card -->

            <!-- Billing Address Card -->
            <div class="card">
                <!-- Body -->
                @php($billing=json_decode($order['billing_address_data']))
                @if($billing)
                <div class="card-body">
                    <div class="d-flex gap-2 align-items-center justify-content-between mb-4">
                        <h4 class="d-flex gap-2">
                            <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" alt="">
                            {{translate('billing_address')}}
                        </h4>

                        <button class="btn btn-outline-primary btn-sm square-btn" title="Edit" data-toggle="modal" data-target="#billingAddressUpdateModal">
                            <i class="tio-edit"></i>
                        </button>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div>
                            <span>{{translate('name')}} :</span>
                            <strong>{{$billing->contact_person_name}}</strong> {{ $order->is_guest ? '('. translate('guest_customer') .')':''}}
                        </div>
                        <div>
                            <span>{{translate('contact')}} :</span>
                            <strong>{{$billing->phone}}</strong>
                        </div>
                        @if ($order->is_guest && $billing->email)
                        <div>
                            <span>{{translate('email')}} :</span>
                            <strong>{{$billing->email}}</strong>
                        </div>
                        @endif
                        <div>
                            <span>{{translate('city')}} :</span>
                            <strong>{{$billing->city}}</strong>
                        </div>
                        <div>
                            <span>{{translate('zip_code')}} :</span>
                            <strong>{{$billing->zip}}</strong>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <!-- <span>{{translate('address')}} :</span> -->
                            <img src="{{asset('/public/assets/back-end/img/location.png')}}" alt="">
                            {{$billing->address}}
                        </div>
                    </div>
                </div>
                @else
                <div class="card-body">
                    <div class="media align-items-center">
                        <span>{{translate('no_billing_address_found')}}</span>
                    </div>
                </div>
                @endif
                <!-- End Body -->
            </div>
            <!-- End Card -->

            <!-- Shop Info Card -->
            <div class="card">
                <div class="card-body">
                    <h4 class="d-flex gap-2 mb-4">
                        <img src="{{asset('/public/assets/back-end/img/shop-information.png')}}" alt="">
                        {{translate('shop_Information')}}
                    </h4>

                    <div class="media">
                        @if($order->seller_is == 'admin')
                        <div class="mr-3">
                            <img class="avatar rounded avatar-70 img-fit-contain" onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'" src="{{asset("storage/app/public/company/$company_web_logo")}}" alt="">
                        </div>

                        <div class="media-body d-flex flex-column gap-2">
                            <h5>{{ $company_name }}</h5>
                            <span class="title-color"><strong>{{ $total_delivered }}</strong> {{translate('orders_Served')}}</span>
                        </div>
                        @else
                        @if(!empty($order->seller->shop))
                        <div class="mr-3">
                            <img class="avatar rounded avatar-70 img-fit-contain" onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'" src="{{asset('storage/app/public/shop')}}/{{$order->seller->shop->image}}" alt="">
                        </div>
                        <div class="media-body d-flex flex-column gap-2">
                            <h5>{{ $order->seller->shop->name }}</h5>
                            <span class="title-color"><strong>{{ $total_delivered }}</strong> {{translate('orders_Served')}}</span>
                            <span class="title-color"> <strong>{{ $order->seller->shop->contact }}</strong></span>
                            <div class="d-flex align-items-start gap-2">
                                <img src="{{asset('/public/assets/back-end/img/location.png')}}" class="mt-1" alt="">
                                {{ $order->seller->shop->address }}
                            </div>
                        </div>
                        @else
                        <div class="card-body">
                            <div class="media align-items-center">
                                <span>{{translate('no_data_found')}}</span>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
    </div>
    <!-- End Row -->
</div>

<!-- order verificaiton modal-->
@if (count($order->verification_images)>0)
<div class="modal fade" id="order_verification_modal" tabindex="-1" aria-labelledby="order_verification_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-4">
                <h3 class="mb-0">{{translate('order_verification_images')}}</h3>
                <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
            </div>
            <div class="modal-body px-4 px-sm-5 pt-0">
                <div class="d-flex flex-column align-items-center gap-2">
                    <div class="row gx-2">
                        @foreach ($order->verification_images as $image)
                        <div class="col-lg-4 col-sm-6 ">
                            <div class="mb-2 mt-2 border-1">
                                <img src="{{asset("storage/app/public/delivery-man/verification-image/".$image->image)}}" class="w-100" onerror="this.src='{{asset('public/assets/back-end/img/image-place-holder.png')}}'">
                            </div>
                        </div>
                        @endforeach
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-3">
                                <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">{{translate('close')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- end order verificaiton modal-->

<!-- Shipping Address Update Modal -->
<div class="modal fade" id="shippingAddressUpdateModal" tabindex="-1" aria-labelledby="shippingAddressUpdateModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-4">
                <h3 class="mb-0 text-center w-100">{{translate('shipping_address')}}</h3>
                <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
            </div>
            <div class="modal-body px-4 px-sm-5 pt-0">
                <form action="{{route('admin.orders.address-update')}}" method="post">
                    @csrf
                    <div class="d-flex flex-column align-items-center gap-2">
                        <input name="address_type" value="shipping" hidden>
                        <input name="order_id" value="{{$order->id}}" hidden>
                        <div class="row gx-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="title-color">{{translate('contact_person_name')}}</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{$shipping_address? $shipping_address->contact_person_name : ''}}" placeholder="{{ translate('ex') }}: {{translate('john_doe')}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number" class="title-color">{{translate('phone_number')}}</label>
                                    <input type="tel" name="phone_number" id="phone_number" value="{{$shipping_address ? $shipping_address->phone  : ''}}" class="form-control" placeholder="{{ translate('ex') }}:32416436546" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country" class="title-color">{{translate('country')}}</label>
                                    <select name="country" id="country" class="form-control">
                                        @forelse($countries as $country)
                                        <option value="{{ $country['name'] }}" {{ isset($shipping_address) && $country['name'] == $shipping_address->country ? 'selected'  : ''}}>{{ $country['name'] }}</option>
                                        @empty
                                        <option value="">{{ translate('No_country_to_deliver') }}</option>
                                        @endforelse
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="city" class="title-color">{{translate('city')}}</label>
                                    <input type="text" name="city" id="city" value="{{$shipping_address ? $shipping_address->city : ''}}" class="form-control" placeholder="{{ translate('ex') }}:{{translate('dhaka')}}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="zip_code" class="title-color">{{translate('zip')}}</label>
                                    @if($zip_restrict_status == 1)
                                    <select name="zip" class="form-control" data-live-search="true" required>
                                        @forelse($zip_codes as $code)
                                        <option value="{{ $code->zipcode }}" {{isset($shipping_address) && $code->zipcode == $shipping_address->zip ? 'selected'  : ''}}>{{ $code->zipcode }}</option>
                                        @empty
                                        <option value="">{{ translate('No_zip_to_deliver') }}</option>
                                        @endforelse
                                    </select>
                                    @else
                                    <input type="text" class="form-control" value="{{$shipping_address ? $shipping_address->zip  : ''}}" id="zip" name="zip" placeholder="{{ translate('ex') }}: 1216" {{$shipping_address?'required':''}}>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address" class="title-color">{{translate('address')}}</label>
                                    <textarea name="address" id="address" name="address" rows="3" class="form-control" placeholder="{{ translate('ex') }} : {{translate('street_1,_street_2,_street_3,_street_4')}}">{{$shipping_address ? $shipping_address->address : ''}}</textarea>
                                </div>
                            </div>
                            <input type="hidden" id="latitude" name="latitude" class="form-control d-inline" placeholder="{{ translate('Ex') }} : -94.22213" value="{{$shipping_address->latitude ?? 0}}" required readonly>
                            <input type="hidden" name="longitude" class="form-control" placeholder="{{ translate('Ex') }} : 103.344322" id="longitude" value="{{$shipping_address->longitude??0}}" required readonly>
                            <!--End -->
                            <div class="col-12 ">
                                <input id="pac-input" class="form-control rounded __map-input mt-1" title="{{translate('search_your_location_here')}}" type="text" placeholder="{{translate('search_here')}}" />
                                <div class="dark-support rounded w-100 __h-200px mb-5" id="location_map_canvas_shipping"></div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-3">
                                    <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">{{translate('cancel')}}</button>
                                    <button type="submit" class="btn btn--primary px-5">{{translate('update')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

@if($billing)
<!-- Billing Address Update Modal -->
<div class="modal fade" id="billingAddressUpdateModal" tabindex="-1" aria-labelledby="billingAddressUpdateModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-4">
                <h3 class="mb-0 text-center w-100">{{translate('billing_address')}}</h3>
                <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
            </div>
            <div class="modal-body px-4 px-sm-5 pt-0">
                <div class="d-flex flex-column align-items-center gap-2">
                    <form action="{{route('admin.orders.address-update')}}" method="post">
                        @csrf
                        <div class="d-flex flex-column align-items-center gap-2">
                            <input name="address_type" value="billing" hidden>
                            <input name="order_id" value="{{$order->id}}" hidden>
                            <div class="row gx-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="title-color">{{translate('contact_person_name')}}</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{$billing? $billing->contact_person_name : ''}}" placeholder="{{ translate('ex') }}: {{translate('john_doe')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number" class="title-color">{{translate('phone_number')}}</label>
                                        <input type="tel" name="phone_number" id="phone_number" value="{{$billing ? $billing->phone  : ''}}" class="form-control" placeholder="{{ translate('ex') }}:32416436546" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country" class="title-color">{{translate('country')}}</label>
                                        <select name="country" id="country" class="form-control">
                                            @forelse($countries as $country)
                                            <option value="{{ $country['name'] }}" {{ isset($billing) && $country['name'] == $billing->country ? 'selected'  : ''}}>{{ $country['name'] }}</option>
                                            @empty
                                            <option value="">{{ translate('No_country_to_deliver') }}</option>
                                            @endforelse
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="city" class="title-color">{{translate('city')}}</label>
                                        <input type="text" name="city" id="city" value="{{$billing ? $billing->city : ''}}" class="form-control" placeholder="{{ translate('ex') }}:{{translate('dhaka')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="zip_code" class="title-color">{{translate('zip')}}</label>
                                        @if($zip_restrict_status == 1)
                                        <select name="zip" class="form-control" data-live-search="true" required>
                                            @forelse($zip_codes as $code)
                                            <option value="{{ $code->zipcode }}" {{isset($billing) && $code->zipcode == $billing->zip ? 'selected'  : ''}}>{{ $code->zipcode }}</option>
                                            @empty
                                            <option value="">{{ translate('no_zip_to_deliver') }}</option>
                                            @endforelse
                                        </select>
                                        @else
                                        <input type="text" class="form-control" value="{{$billing ? $billing->zip  : ''}}" id="zip" name="zip" placeholder="{{ translate('ex') }}: 1216" {{$billing?'required':''}}>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address" class="title-color">{{translate('address')}}</label>
                                        <textarea name="address" id="billing_address" rows="3" class="form-control" placeholder="{{ translate('ex') }} : {{translate('street_1,_street_2,_street_3,_street_4')}}">{{$billing ? $billing->address : ''}}</textarea>
                                    </div>
                                </div>
                                <input type="hidden" id="billing_latitude" name="latitude" class="form-control d-inline" placeholder="{{ translate('ex') }} : -94.22213" value="{{$billing->latitude ?? 0}}" required readonly>
                                <input type="hidden" name="longitude" class="form-control" placeholder="{{ translate('ex') }} : 103.344322" id="billing_longitude" value="{{$billing->longitude ?? 0}}" required readonly>
                                <!--End -->
                                <div class="col-12 ">
                                    <!-- search -->
                                    <input id="billing-pac-input" class="form-control rounded __map-input mt-1" title="{{translate('search_your_location_here')}}" type="text" placeholder="{{translate('search_here')}}" />
                                    <!-- search -->
                                    <div class="rounded w-100 __h-200px mb-5" id="location_map_canvas_billing"></div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-3">
                                        <button type="button" class="btn btn-secondary px-5" data-dismiss="modal">{{translate('cancel')}}</button>
                                        <button type="submit" class="btn btn--primary px-5">{{translate('update')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
@endif

<!-- Show locations on map Modal -->
<div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="locationModalLabel">{{translate('location_Data')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 modal_body_map">
                        <div class="location-map" id="location-map">
                            <div class="w-100 __h-400px" id="location_map_canvas"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- Show third party delivery info Modal -->
<!-- <div class="modal" id="third_party_delivery_service_modal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{translate('update_third_party_delivery_info')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{route('admin.orders.update-deliver-info')}}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{$order['id']}}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">{{translate('delivery_service_name')}}</label>
                                    <input class="form-control" type="text" name="delivery_service_name" value="{{$order['delivery_service_name']}}" id="" required>
                                </div>
                                <div class="form-group">
                                    <label for="">{{translate('tracking_id')}} ({{translate('optional')}})</label>
                                    <input class="form-control" type="text" name="third_party_delivery_tracking_id" value="{{$order['third_party_delivery_tracking_id']}}" id="">
                                </div>
                                <button class="btn btn--primary" type="submit">{{translate('update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div> -->
<!-- End Modal -->
<!-- Show third party delivery info Modal -->
<div class="modal" id="third_party_delivery_service_modal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{translate('update_third_party_delivery_info')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{route('admin.orders.update-deliver-info')}}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{$order['id']}}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">{{translate('delivery_service_name')}}</label>
                                    <input class="form-control" type="text" name="delivery_service_name" value="{{$order['delivery_service_name']}}" id="" required>
                                </div>
                                <div class="form-group">
                                    <label for="">{{translate('Order_id')}} ({{translate('optional')}})</label>
                                    <input class="form-control" type="text" name="third_party_delivery_tracking_id" value="{{$order['third_party_delivery_tracking_id']}}" id="">
                                </div>
                                <div class="form-group">
                                    <label for="">{{translate('shipment_id')}} ({{translate('optional')}})</label>
                                    <input class="form-control" type="text" name="third_party_delivery_shipment_id" value="{{$order['third_party_delivery_shipment_id']}}" id="">
                                </div>
                                <button class="btn btn--primary" onclick="changeDeliveryStatus('confirmed')" type="submit">{{translate('update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- order dimension update modal -->
<div class="modal" id="order_dimension_update_modal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{translate('update_order_dimensions')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{route('admin.orders.update-dimensions-of-order')}}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{$order['id']}}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">{{'length of parcel  (in cm.)'}}</label>
                                    <input class="form-control" type="text" name="length" value="{{$order['length']}}" id="" required>
                                </div>
                                <div class="form-group">
                                    <label for="">{{translate('breadth_of_parcel  (in_cm.)')}} </label>
                                    <input class="form-control" type="text" name="breadth" value="{{$order['breadth']}}" id="" required>
                                </div>
                                <div class="form-group">
                                    <label for="">{{translate('height_of_parcel  (in_cm.)')}} </label>
                                    <input class="form-control" type="text" name="height" value="{{$order['height']}}" id="" required>
                                </div>
                                <div class="form-group">
                                    <label for="">{{translate('weight_of_parcel  (in_kg)')}} </label>
                                    <input class="form-control" type="text" name="weight" value="{{$order['weight']}}" id="" required>
                                </div>
                                <button class="btn btn--primary"  type="submit">{{translate('update')}}</button>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>


<!-- 008 -->
<!-- Show confirmation modal -->
<div class="modal" id="ship_rocket_confirmation_modal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="hideConfirmationModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to deliver by Ship Rocket?</p>
                <button class="btn btn--primary" onclick="confirmDelivery()">Yes</button>
                <button class="btn btn-secondary" data-dismiss="modal" onclick="hideConfirmationModal()">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="order_creation_response_modal" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Creation Response</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="hideOrderCreationResponseModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="order_creation_response_modal_body">
                <!-- Response details will be inserted here dynamically -->
            </div>
        </div>
    </div>
</div>

<!-- 008 -->



<!-- 008 -->
@endsection

@push('script_2')



<!-- payment status change -->
<script>
    $(document).on('click', '.payment_status', function(e) {
        e.preventDefault();
        var id ='{{$order->id}}';
        var value = $(this).val();
        Swal.fire({
            title: '{{translate("are_you_sure_change_this")}}?',
            text: "{{translate('you_will_not_be_able_to_revert_this')}}!",
            showCancelButton: true,
            confirmButtonColor: '#377dff',
            cancelButtonColor: 'secondary',
            confirmButtonText: '{{translate("yes_change_it")}}!',
            cancelButtonText: '{{ translate("cancel") }}',
        }).then((result) => {
            if (value == 'paid') {
                value = 'unpaid'
            } else {
                value = 'paid'
            }
            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.orders.payment-status')}}",
                    method: 'POST',
                    data: {
                        "id": id,
                        "payment_status": value
                    },
                    success: function(data) {
                        if (data.customer_status == 0) {
                            toastr.warning('{{translate("account_has_been_deleted_you_can_not_change_the_status")}}!');
                            location.reload();
                        } else {

                            toastr.success('{{translate("status_change_successfully")}}');
                            location.reload();
                        }
                    }
                });
            }
        })
    });


    // function order_status(status) {
    //     @if($order['order_status'] == 'delivered')
    //     Swal.fire({
    //         title: '{{translate("Order_is_already_delivered_and_transaction_amount_has_been_disbursed_changing_status_can_be_the_reason_of_miscalculation")}}!',
    //         text: "{{translate('think_before_you_proceed')}}.",
    //         showCancelButton: true,
    //         confirmButtonColor: '#377dff',
    //         cancelButtonColor: 'secondary',
    //         confirmButtonText: '{{translate("yes_change_it")}}!',
    //         cancelButtonText: '{{ translate("cancel") }}',
    //     }).then((result) => {
    //         if (result.value) {
    //             $.ajaxSetup({
    //                 headers: {
    //                     'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    //                 }
    //             });
    //             $.ajax({
    //                 url: "{{route('admin.orders.status')}}",
    //                 method: 'POST',
    //                 data: {
    //                     "id": '{{$order->id}}',
    //                     // "id": '{{$order['id ']}}',
    //                     "order_status": status
    //                 },
    //                 success: function(data) {

    //                     if (data.success == 0) {
    //                         toastr.success('{{translate("order_is_already_delivered_you_can_not_change_it")}} !!');
    //                         location.reload();
    //                     } else {

    //                         if (data.payment_status == 0) {
    //                             toastr.warning('{{translate("before_delivered_you_need_to_make_payment_status_paid")}}!');
    //                             location.reload();
    //                         } else if (data.customer_status == 0) {
    //                             toastr.warning('{{translate("account_has_been_deleted_you_can_not_change_the_status")}}!');
    //                             location.reload();
    //                         } else {
    //                             toastr.success('{{translate("status_change_successfully")}}!');
    //                             location.reload();
    //                         }
    //                     }

    //                 }
    //             });
    //         }
    //     })
    //     @else
    //     Swal.fire({
    //         title: '{{translate("are_you_sure_change_this")}}?',
    //         text: "{{translate('you_will_not_be_able_to_revert_this')}}!",
    //         showCancelButton: true,
    //         confirmButtonColor: '#377dff',
    //         cancelButtonColor: 'secondary',
    //         confirmButtonText: '{{translate("yes_change_it")}}!',
    //         cancelButtonText: '{{ translate("cancel") }}',
    //     }).then((result) => {
    //         if (result.value) {
    //             $.ajaxSetup({
    //                 headers: {
    //                     'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    //                 }
    //             });
    //             $.ajax({
    //                 url: "{{route('admin.orders.status')}}",
    //                 method: 'POST',
    //                 data: {
    //                     "id": '{{$order['id']}}',
    //                     "order_status": status
    //                 },
    //                 success: function(data) {
    //                     if (data.success == 0) {
    //                         toastr.success('{{translate("order_is_already_delivered_you_can_not_change_it")}} !!');
    //                         location.reload();
    //                     } else {
    //                         if (data.payment_status == 0) {
    //                             toastr.warning('{{translate("before_delivered_you_need_to_make_payment_status_paid")}}!');
    //                             location.reload();
    //                         } else if (data.customer_status == 0) {
    //                             toastr.warning('{{translate("account_has_been_deleted_you_can_not_change_the_status")}}!');
    //                             location.reload();
    //                         } else {
    //                             toastr.success('{{translate("status_change_successfully")}}!');
    //                             location.reload();
    //                         }
    //                     }

    //                 }
    //             });
    //         }
    //     })
    //     @endif
    // }
</script>
<!-- end payment status change -->

<!-- delivery type -->
<script>
    $(document).ready(function() {
        let delivery_type = '{{$order->delivery_type}}';


        if (delivery_type === 'self_delivery') {
            $('.choose_delivery_man').show();
            $('#by_third_party_delivery_service_info').hide();
        } else if (delivery_type === 'third_party_delivery') {

            $('.choose_delivery_man').hide();
            $('#by_third_party_delivery_service_info').show();
        } else {
            $('.choose_delivery_man').hide();
            $('#by_third_party_delivery_service_info').hide();

        }
    });
</script>
<!-- end delivery type -->

<!-- Choose delivery type -->
<script>
    function choose_delivery_type(val) {

        if (val === 'self_delivery') {
            $('.choose_delivery_man').show();
            $('#by_third_party_delivery_service_info').hide();
        } else if (val === 'third_party_delivery') {
            $('.choose_delivery_man').hide();
            $('#deliveryman_charge').val(null);
            $('#expected_delivery_date').val(null);
            $('#by_third_party_delivery_service_info').show();
            $('#third_party_delivery_service_modal').modal("show");
        }

        // 008
        else if (val === 'ship_rocket_delivery') {
            $('.choose_delivery_man').hide();
            $('#deliveryman_charge').val(null);
            $('#expected_delivery_date').val(null);
            //$('#ship_rocket_confirmation_modal').show();
            $('#ship_rocket_confirmation_modal').modal("show");
        }

        // 008
        else {
            $('.choose_delivery_man').hide();
            $('#by_third_party_delivery_service_info').hide();
        }

    }

    // function hideConfirmationModal() {
    //     // Hide the confirmation modal
    //     console.log(`{{{ json_encode($order_items) }}}`);
    //     console.log("Heloo");

    // }
</script>
<!-- End Choose delivery type -->


<script>
    function confirmDelivery() {
        //console.log("confirm del. function called");
        const proxyEndpoint = 'https://myglamour.store/proxy.php';

        // // Replace these values with your actual email and password
        const emailInput = "{{ config('shiprocket.user_id') }}";
        const passwordInput = "{{ config('shiprocket.password') }}";
        // const emailInput = 'admin1@myglamour.store';
        // const passwordInput = 'cGUNwXnDTZCnQuw';

        
        //console.log("email is: ", emailInput);
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
        }).then(response => {
            if (!response.ok) {
                throw new Error('Authentication failed');
            }
            return response.json();
        }).then(data => {

            if (data.token) {
                const authToken = data.token;
                //console.log('Authentication successful. Token:', authToken);
                const sub_total={{$item_price-$discount}};
                let shipping_charges=0;
                if(sub_total>699){
                    shipping_charges=0;
                }
                else{
                    shipping_charges={{$shipping}};
                }
                fetch(proxyEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${authToken}`,
                    },
                    body: JSON.stringify({
                        action: 'createOrder',
                        token: authToken,
                        data: {
                            "order_id": "{{$order->id}}",
                            "order_date": "{{$order->created_at}}",
                            // "pickup_location": "Primary",
                            "pickup_location": "{{ $order->seller->shop->default_pickup_address ? $order->seller->shop->default_pickup_address->id : '' }}",
                            "channel_id": "",
                            "comment": "",
                            "billing_customer_name": "{{$billing->contact_person_name}}",
                            "billing_last_name": "",
                            "billing_address": "{{$billing->address}}",
                            "billing_address_2": "",
                            "billing_city": "{{$billing->city}}",
                            "billing_pincode": "{{$billing->zip}}",
                            "billing_state": "Delhi",
                            "billing_country": "India",
                            "billing_email": "{{$billing->email}}",
                            "billing_phone": "{{$billing->phone}}",
                            "shipping_is_billing": true,
                            "shipping_customer_name": "{{$shipping_address->contact_person_name}}",
                            "shipping_last_name": "",
                            "shipping_address": "{{$shipping_address->address}}",
                            "shipping_address_2": "",
                            "shipping_city": "{{$shipping_address->city}}",
                            "shipping_pincode": "{{$shipping_address->zip}}",
                            "shipping_country": "",
                            "shipping_state": "",
                            "shipping_email": "{{$shipping_address->email}}",
                            "shipping_phone": "{{$shipping_address->email}}",
                            "order_items": <?php echo json_encode($order_items); ?>,
                            "payment_method": (`{{{$order->payment_method }}}` != 'cash_on_delivery') ? 'Prepaid' : 'COD',
                            "shipping_charges":shipping_charges,
                            "giftwrap_charges": 0,
                            "transaction_charges": 0,
                            "total_discount": 0,
                            "sub_total":sub_total ,
                            "length": {{$order->length}},
                            "breadth": {{$order->breadth}},
                            "height": {{$order->height}},
                            "weight": {{$order->weight}}
                        }
                    })
                }).then(response => {
                    if (!response.ok && response.status === 422) {
                        throw new Error('order creation failed');
                    }
                    return response.json();
                    // console.log("response is ",response);
                }).then(orderData => {

                    if (orderData.status_code === 422 && orderData.errors) {
                        if (orderData.errors.weight) {
                        const errorMessage = orderData.errors.weight[0];
                        toastr.error(errorMessage);
                        return; // Return here to stop further processing
                    } 
                    else{
                        toastr.error("unprocessable data");
                        return; // Return here to stop further processing
                    }
        }
                    // console.log("create order is ", orderData);
                    document.querySelector('input[name="delivery_service_name"]').value = "ShipRocket";
                    document.querySelector('input[name="third_party_delivery_tracking_id"]').value = orderData.order_id;
                    document.querySelector('input[name="third_party_delivery_shipment_id"]').value = orderData.shipment_id;
                    // Display the response in a modal
                    displayOrderCreationResponseModal(orderData);

                })

            } else {
                // Handle authentication failure
                // console.error('Authentication failed:', data);
                toastr.error(` ${data.message}`);
            }

        }).catch(error => {
            // console.error('Error in authentication:', error);
            toastr.error(`Error: ${error.message}`);
        });

   
    }

    // function confirmDelivery(){
    //     console.log("confirm del called");
    // }

    function displayOrderCreationResponseModal(orderData) {

        // Show the modal

        $('#third_party_delivery_service_modal').modal('show');
        //changeDeliveryStatus();
    }
</script>


<!-- 008 -->
<script>
    function hideConfirmationModal() {
        // Hide the confirmation modal
        // console.log("Heloo");
        $('#ship_rocket_confirmation_modal').modal("hide");

    }


    // function confirmDelivery() {
    //     console.log("confirm del. function called");
    //     const proxyEndpoint = 'https://myglamour.store/proxy.php';

    //     // Replace these values with your actual email and password
    //     const emailInput = 'admin1@myglamour.store';
    //     const passwordInput = 'cGUNwXnDTZCnQuw';
    //     console.log("email is: ", emailInput);
    //     fetch(proxyEndpoint, {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //             },
    //             body: JSON.stringify({
    //                 action: 'login',
    //                 data: {
    //                     email: emailInput,
    //                     password: passwordInput,
    //                 }
    //             }),
    //         }).then(response => {
    //             if (!response.ok) {
    //                 throw new Error('Authentication failed');
    //             }
    //             return response.json();
    //         }).then(data => {
    //             if (data.token) {
    //                 const authToken = data.token;
    //                 console.log('Authentication successful. Token:', authToken);

    //                 fetch(proxyEndpoint, {
    //                         method: 'POST',
    //                         headers: {
    //                             'Content-Type': 'application/json',
    //                             'Authorization': `Bearer ${authToken}`,
    //                         },
    //                         body: JSON.stringify({
    //                             action: 'createOrder',
    //                             token: authToken, // Include the token here
    //                             data: {
    //                                 "order_id": '{{$order->id}}',
    //                                 "order_date": "{{$order->created_at}}",
    //                                 "pickup_location": "Primary",
    //                                 "channel_id": "",
    //                                 "comment": "",
    //                                 "billing_customer_name": "{{$billing->contact_person_name}}",
    //                                 "billing_last_name": "",
    //                                 "billing_address": "{{$billing->address}}",
    //                                 "billing_address_2": "",
    //                                 "billing_city": "{{$billing->city}}",
    //                                 "billing_pincode": "{{$billing->zip}}",
    //                                 "billing_state": "Delhi",
    //                                 "billing_country": "India",
    //                                 "billing_email": "{{$billing->email}}",
    //                                 "billing_phone": "{{$billing->phone}}",
    //                                 "shipping_is_billing": true,
    //                                 "shipping_customer_name": "{{$shipping_address->contact_person_name}}",
    //                                 "shipping_last_name": "",
    //                                 "shipping_address": "{{$shipping_address->address}}",
    //                                 "shipping_address_2": "",
    //                                 "shipping_city": "{{$shipping_address->city}}",
    //                                 "shipping_pincode": "{{$shipping_address->zip}}",
    //                                 "shipping_country": "",
    //                                 "shipping_state": "",
    //                                 "shipping_email": "{{$shipping_address->email}}",
    //                                 "shipping_phone": "{{$shipping_address->phone}}",
    //                                 "order_items": <?php echo json_encode($order_items); ?>,
    //                                 // "order_items": [{
    //                                 //     "name": "Kunai",
    //                                 //     "sku": "chakra123",
    //                                 //     "units": 10,
    //                                 //     "selling_price": "900",
    //                                 //     "discount": "",
    //                                 //     "tax": "",
    //                                 //     "hsn": 441122
    //                                 // }],
    //                                 "payment_method": (`{{{$order->payment_method }}}` != 'cash_on_delivery') ? 'Prepaid' : 'COD',
    //                                 "shipping_charges": {
    //                                     {
    //                                         $shipping
    //                                     }
    //                                 },
    //                                 "giftwrap_charges": 0,
    //                                 "transaction_charges": 0,
    //                                 "total_discount": 0,
    //                                 "sub_total": {
    //                                     {
    //                                         $item_price
    //                                     }
    //                                 },
    //                                 "length": 10,
    //                                 "breadth": 15,
    //                                 "height": 20,
    //                                 "weight": 2.5
    //                             }
    //                         }),
    //                     }).then(response => response.json())
    //                     .then(orderData => {
    //                         // Handle the response from the order creation API
    //                         console.log('Order creation response:', orderData);
    //                         document.querySelector('input[name="delivery_service_name"]').value = "ShipRocket";
    //                         document.querySelector('input[name="third_party_delivery_tracking_id"]').value = orderData.order_id;
    //                         document.querySelector('input[name="third_party_delivery_shipment_id"]').value = orderData.shipment_id;
    //                         displayOrderCreationResponseModal(orderData);
    //                     }).catch(error => {
    //                         console.error('Error creating order:', error);
    //                     });
    //             } else {
    //                 // Handle authentication failure
    //                 console.error('Authentication failed:', data);
    //             }
    //         })
    //         .catch(error => {
    //             console.error('Error in authentication:', error);
    //         });
    // }
    // function confirmDelivery(){
    //     console.log("cofirmdel called");
    // }

    async function confirmpackaging() {
        try {
            // Login API call
            const proxyEndpoint = 'https://myglamour.store/proxy.php';

            //const loginEndpoint = 'https://apiv2.shiprocket.in/v1/external/auth/login';
            const loginResponse = await fetch(proxyEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'login',
                    data: {
                        email: "{{ config('shiprocket.user_id') }}",
                        password: "{{ config('shiprocket.password') }}",
                    }
                }),
            });

            if (!loginResponse.ok) {
                throw new Error('Login failed');
            }



            const loginData = await loginResponse.json();
            const token = loginData.token;
            //console.log("track id ",{{$order->third_party_delivery_tracking_id}});
            // Assign AWB API call
            // const assignAwbEndpoint = 'https://apiv2.shiprocket.in/v1/external/courier/assign/awb';
            const assignAwbResponse = await fetch(proxyEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                body: JSON.stringify({
                    action: 'assignAWB',
                    token: token, // Include the token here
                    data: {
                        shipment_id: '{{$order->third_party_delivery_shipment_id}}',
                        courier_id: '',
                    }
                }),
            });
            //console.log("response is ",assignAwbResponse);
            if (!assignAwbResponse.ok) {
                throw new Error('AWB assignment failed');
            }

            const assignAwbData = await assignAwbResponse.json();

            //console.log("assign awb data is ",assignAwbData);
            // Check if AWB assignment was successful
            if (assignAwbData.awb_assign_status === 1) {
                const shipmentId = assignAwbData.response.data.shipment_id;

                // Generate Pickup API call
                //const generatePickupEndpoint = 'https://apiv2.shiprocket.in/v1/external/courier/generate/pickup';
                const generatePickupResponse = await fetch(proxyEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`,
                    },
                    body: JSON.stringify({
                        action: 'generatePickup',
                        token: token, // Include the token here
                        data: {
                            shipment_id: [shipmentId],
                        }

                    }),
                });

                if (!generatePickupResponse.ok) {
                    throw new Error('Pickup generation failed');
                }

                const generatePickupData = await generatePickupResponse.json();
                //console.log(generatePickupData); // Handle the response as needed
                changeDeliveryStatus('processing');
            } else {
                throw new Error('AWB assignment failed');
            }
        } catch (error) {

            console.error('Error:', error.message);
            toastr.error(`Error: ${error.message}`);
        }
    }

    async function cancelOrder() {
        const proxyEndpoint = 'https://myglamour.store/proxy.php';
        const loginEndpoint = 'https://apiv2.shiprocket.in/v1/external/auth/login';
        const cancelOrderEndpoint = 'https://apiv2.shiprocket.in/v1/external/orders/cancel';

        try {
            // Login to get the token
            const loginResponse = await fetch(proxyEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'login',
                    data: {
                        email: "{{ config('shiprocket.user_id') }}",
                        password: "{{ config('shiprocket.password') }}",
                    }

                }),
            });

            if (!loginResponse.ok) {
                throw new Error('Login failed');
            }

            const loginData = await loginResponse.json();
            //console.log("login data is ", loginData);
            const token = loginData.token;
            orderIds = '{{$order->third_party_delivery_tracking_id}}';
            // Proceed with order cancellation
            const cancelOrderResponse = await fetch(proxyEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                body: JSON.stringify({
                    action: 'cancelorder',
                    token: token,
                    data: {
                        ids: [orderIds],
                    }

                }),
            });

            if (!cancelOrderResponse.ok) {
                throw new Error('Order cancellation failed');
            }

            const cancelOrderData = await cancelOrderResponse.json();

            if(cancelOrderData.message=='Cannot cancel order when shipment status is Canceled'){
            // Display a warning message using toastr for 10 seconds
            toastr.warning("This Order is already canceled at Shiprocket", "", { timeOut: 10000 });
            changeDeliveryStatus('canceled');
        }
        else if(cancelOrderData.message=='Order cancelled successfully.'){
            toastr.success(cancelOrderData.message);
            changeDeliveryStatus('canceled');
        }
        else{
            toastr.error(cancelOrderData.message);
        }
           // console.log('Orders Cancelled successfully:', cancelOrderData);
            changeDeliveryStatus('canceled');
        } catch (error) {

            //console.error('Error cancelling orders:', error.message);
        }
    }



    // async function track_shipRocket_shipment() {
    //     try {
    //         // Step 1: Login API call
    //         //const loginEndpoint = 'https://apiv2.shiprocket.in/v1/external/auth/login';
    //         const proxyEndpoint = 'https://myglamour.store/proxy.php';
    //         const loginResponse = await fetch(proxyEndpoint, {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',

    //             },
    //             body: JSON.stringify({
    //                 action:'login',
    //                 data:{
    //                     email: 'admin1@myglamour.store',
    //                 password: 'cGUNwXnDTZCnQuw',
    //                 }
    //             }),
    //         });

    //         if (!loginResponse.ok) {
    //             throw new Error('Login failed');
    //         }

    //         const loginData = await loginResponse.json();
    //         const token = loginData.token;

    //         // Step 2: Track Shipment API call
    //         const orderId = '{{$order->third_party_delivery_tracking_id}}'; // Replace with the actual order ID
    //         //const trackEndpoint = `https://apiv2.shiprocket.in/v1/external/courier/track/shipment/${orderId}`;
    //         const trackResponse = await fetch(proxyEndpoint, {
    //             method: 'GET',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'Authorization': `Bearer ${token}`,
    //             },
    // body: JSON.stringify({
    //             action: 'trackShipment',
    //             token: token, // Include the token here
    //             shipmentId: orderId
    //         });
    //         });

    //         // Check response status
    //         if (![200, 202].includes(trackResponse.status)) {
    //             throw new Error(`Track Shipment failed with status code: ${trackResponse.status}`);
    //         }

    //         const trackData = await trackResponse.json();

    //         // Check track_status
    //         if (trackData.tracking_data.track_status !== 1) {
    //             throw new Error(`Invalid track_status: ${trackData.tracking_data.track_status}`);
    //         }

    //         const currentStatus = trackData.tracking_data.shipment_track[0].current_status;

    //         // Step 3: Update Delivery Status based on current_status
    //         switch (currentStatus) {
    //             case 'PICKED UP':
    //                 changeDeliveryStatus('intransit');
    //                 break;
    //             case 'OUT FOR DELIVERY':
    //                 changeDeliveryStatus('out_for_delivery');
    //                 break;
    //             case 'DELIVERED':
    //                 changeDeliveryStatus('delivered');
    //                 break;
    //             case 'CANCELED':
    //                 changeDeliveryStatus('canceled');
    //                 break;
    //             case 'Return Delivered':
    //                 changeDeliveryStatus('returned');
    //                 break;
    //             case 'Undelivered':
    //                 changeDeliveryStatus('failed');
    //             default:
    //                 // Handle other statuses if needed
    //                 break;
    //         }

    //     } catch (error) {
    //         console.error('Error:', error.message);
    //         // Display toast with error message
    //         // Implement your toast logic here
    //         toastr.error(`Error: ${error.message}`);
    //     }
    // }

    // Call the function
    // track_shipRocket_shipment();



    function hideOrderCreationResponseModal() {
        // Hide the order creation response modal
        $('#order_creation_response_modal').modal('hide');
    }
</script>

<!-- 008 -->

<script>
    async function track_shipRocket_shipment() {
        try {
            // Step 1: Login API call
            //const loginEndpoint = 'https://apiv2.shiprocket.in/v1/external/auth/login';
            const proxyEndpoint = 'https://myglamour.store/proxy.php';

            const loginResponse = await fetch(proxyEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'login',
                    data: {
                        email: "{{ config('shiprocket.user_id') }}",
                        password: "{{ config('shiprocket.password') }}",
                    }
                }),
            });

            if (!loginResponse.ok) {
                throw new Error('Login failed');
            }

            const loginData = await loginResponse.json();
            const token = loginData.token;
            // console.log("login successful");
            // Step 2: Track Shipment API call
            const shipmentId = '{{$order->third_party_delivery_shipment_id}}'; // Replace with the actual order ID

            const trackEndpoint = `https://myglamour.store/proxy1.php?action=trackShipment&token=${token}&shipmentId=${shipmentId}`;
            // const trackEndpoint = `https://apiv2.shiprocket.in/v1/external/courier/track/shipment/${orderId}`;
            const trackResponse = await fetch(trackEndpoint, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                },
                // body: JSON.stringify({
                //             action: 'trackShipment',
                //             token: token, // Include the token here
                //             shipmentId: orderId
                //         });
            });

            // Check response status
            if (![200, 202].includes(trackResponse.status)) {
                throw new Error(`Track Shipment failed with status code: ${trackResponse.status}`);
            }

            const trackData = await trackResponse.json();
            // console.log(trackData); // Log the response for debugging
            // Check track_status
            if (trackData.tracking_data.track_status !== 1) {
                throw new Error(`Invalid track_status: ${trackData.tracking_data.track_status}`);
            }

            const currentStatus = trackData.tracking_data.shipment_track[0].current_status;

            // Step 3: Update Delivery Status based on current_status
            switch (currentStatus) {
                case 'PICKED UP':
                    changeDeliveryStatus('intransit');
                    break;
                case 'OUT FOR DELIVERY':
                    changeDeliveryStatus('out_for_delivery');
                    break;
                case 'Delivered':
                    changeDeliveryStatus('delivered');
                    break;
                    // case 'CANCELED':
                    //     changeDeliveryStatus('canceled');
                    //     break;
                case 'Return Delivered':
                    changeDeliveryStatus('returned');
                    break;
                case 'Undelivered':
                    changeDeliveryStatus('failed');
                default:
                    // Handle other statuses if needed
                    break;
            }

        } catch (error) {
            console.error('Error:', error.message);
            // Display toast with error message
            // Implement your toast logic here
            toastr.error(`Error: ${error.message}`);
        }
    }
</script>
<!-- Add delivery man -->
<script>
    function addDeliveryMan(id) {
        $.ajax({
            type: "GET",
            url: '{{url(' / ')}}/admin/orders/add-delivery-man/{{$order['id ']}}/' + id,
            data: {
                'order_id': '{{$order['
                id ']}}',
                'delivery_man_id': id
            },
            success: function(data) {
                // console.log(data);
                if (data.status == true) {
                    toastr.success('{{ translate("delivery_man_successfully_assigned_or_changed") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    location.reload();
                } else {
                    toastr.error('{{ translate("deliveryman_man_can_not_assign_or_change_in_that_status") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            },
            error: function() {
                toastr.error('{{ translate("add_valid_data") }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });
    }

    function last_location_view() {
        toastr.warning('{{ translate("only_available_when_order_is_out_for_delivery") }}!', {
            CloseButton: true,
            ProgressBar: true
        });
    }

    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })

    function waiting_for_location() {
        toastr.warning('{{translate("waiting_for_location")}}', {
            CloseButton: true,
            ProgressBar: true
        });
    }

    function amountDateUpdate(t, e) {
        let field_name = $(t).attr('name');
        let field_val = $(t).val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{route('admin.orders.amount-date-update')}}",
            method: 'POST',
            data: {
                'order_id': '{{$order['
                id ']}}',
                'field_name': field_name,
                'field_val': field_val
            },
            success: function(data) {
                if (data.status == true) {
                    toastr.success('{{ translate("deliveryman_charge_add_successfully") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                } else {
                    toastr.error('{{ translate("failed_to_add_deliveryman_charge") }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            },
            error: function() {
                toastr.error('Add valid data', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });
    }
</script>
<!-- End add delivery man function-->

<!--shipping address map -->
<script src="https://maps.googleapis.com/maps/api/js?key={{\App\CPU\Helpers::get_business_settings('map_api_key')}}&callback=map_callback_fucntion&libraries=places&v=3.49" defer></script>
<script>
    /* shipping address  map */
    function initAutocomplete() {
        var myLatLng = {
            lat: {
                {
                    $shipping_address - > latitude ?? '-33.8688'
                }
            },
            lng: {
                {
                    $shipping_address - > longitude ?? '151.2195'
                }
            }
        };

        const map = new google.maps.Map(document.getElementById("location_map_canvas_shipping"), {
            center: {
                lat: {
                    {
                        $shipping_address - > latitude ?? '-33.8688'
                    }
                },
                lng: {
                    {
                        $shipping_address - > longitude ?? '151.2195'
                    }
                }
            },
            zoom: 13,
            mapTypeId: "roadmap",
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
        });

        marker.setMap(map);
        var geocoder = geocoder = new google.maps.Geocoder();
        google.maps.event.addListener(map, 'click', function(mapsMouseEvent) {
            var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
            var coordinates = JSON.parse(coordinates);
            var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
            marker.setPosition(latlng);
            map.panTo(latlng);

            document.getElementById('latitude').value = coordinates['lat'];
            document.getElementById('longitude').value = coordinates['lng'];

            geocoder.geocode({
                'latLng': latlng
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        document.getElementById('address').value = results[1].formatted_address;
                        console.log(results[1].formatted_address);
                    }
                }
            });
        });

        // Create the search box and link it to the UI element.
        const input = document.getElementById("pac-input");
        const searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
        // Bias the SearchBox results towards current map's viewport.
        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });
        let markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }
            // Clear out the old markers.
            markers.forEach((marker) => {
                marker.setMap(null);
            });
            markers = [];
            // For each place, get the icon, name and location.
            const bounds = new google.maps.LatLngBounds();
            places.forEach((place) => {
                if (!place.geometry || !place.geometry.location) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                var mrkr = new google.maps.Marker({
                    map,
                    title: place.name,
                    position: place.geometry.location,
                });

                google.maps.event.addListener(mrkr, "click", function(event) {
                    document.getElementById('latitude').value = this.position.lat();
                    document.getElementById('longitude').value = this.position.lng();

                });

                markers.push(mrkr);

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
    };

    $(document).on("keydown", "input", function(e) {
        if (e.which == 13) e.preventDefault();
    });
    /* end shipping address   map*/

    /* billing address  map */
    function billing_map() {
        var myLatLng = {
            lat: {
                {
                    $billing - > latitude ?? '-33.8688'
                }
            },
            lng: {
                {
                    $billing - > longitude ?? '151.2195'
                }
            }
        };

        const map = new google.maps.Map(document.getElementById("location_map_canvas_billing"), {
            center: {
                lat: {
                    {
                        $billing - > latitude ?? '-33.8688'
                    }
                },
                lng: {
                    {
                        $billing - > longitude ?? '151.2195'
                    }
                }
            },
            zoom: 13,
            mapTypeId: "roadmap",
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
        });

        marker.setMap(map);
        var geocoder = geocoder = new google.maps.Geocoder();
        google.maps.event.addListener(map, 'click', function(mapsMouseEvent) {
            var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
            var coordinates = JSON.parse(coordinates);
            var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
            marker.setPosition(latlng);
            map.panTo(latlng);

            document.getElementById('billing_latitude').value = coordinates['lat'];
            document.getElementById('billing_longitude').value = coordinates['lng'];

            geocoder.geocode({
                'latLng': latlng
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        document.getElementById('billing_address').value = results[1].formatted_address;
                        console.log(results[1].formatted_address);
                    }
                }
            });
        });

        // Create the search box and link it to the UI element.
        const input = document.getElementById("billing-pac-input");
        const searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
        // Bias the SearchBox results towards current map's viewport.
        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });
        let markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }
            // Clear out the old markers.
            markers.forEach((marker) => {
                marker.setMap(null);
            });
            markers = [];
            // For each place, get the icon, name and location.
            const bounds = new google.maps.LatLngBounds();
            places.forEach((place) => {
                if (!place.geometry || !place.geometry.location) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                var mrkr = new google.maps.Marker({
                    map,
                    title: place.name,
                    position: place.geometry.location,
                });

                google.maps.event.addListener(mrkr, "click", function(event) {
                    document.getElementById('latitude').value = this.position.lat();
                    document.getElementById('longitude').value = this.position.lng();

                });

                markers.push(mrkr);

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
    };
    $(document).on("keydown", "input", function(e) {
        if (e.which == 13) e.preventDefault();
    });


    /* end billing address map  */
    /* show location map */
    function show_location_map() {
        var myLatLng = {
            lat: {
                {
                    $shipping_address - > latitude ?? 'null'
                }
            },
            lng: {
                {
                    $shipping_address - > longitude ?? 'null'
                }
            }
        };

        const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
            center: {
                lat: {
                    {
                        $shipping_address - > latitude ?? 'null'
                    }
                },
                lng: {
                    {
                        $shipping_address - > longitude ?? 'null'
                    }
                }
            },
            zoom: 13,
            mapTypeId: "roadmap",
        });

        @if($shipping_address && isset($shipping_address))
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng({
                {
                    $shipping_address - > latitude
                }
            }, {
                {
                    $shipping_address - > longitude
                }
            }),
            map: map,
            title: "{{$order->customer['f_name']??"
            "}} {{$order->customer['l_name']??"
            "}}",
            icon: "{{asset('public/assets/front-end/img/customer_location.png')}}"
        });

        google.maps.event.addListener(marker, 'click', (function(marker) {
            return function() {
                infowindow.setContent("<div class='float-left'><img class='__inline-5' src='{{asset('storage/app/public/profile/')}}{{$order->customer->image??"
                    "}}'></div><div class='float-right __p-10'><b>{{$order->customer->f_name??"
                    "}} {{$order->customer->l_name??"
                    "}}</b><br/>{{$shipping_address->address??"
                    "}}</div>");
                infowindow.open(map, marker);
            }
        })(marker));
        locationbounds.extend(marker.getPosition());
        @endif
        google.maps.event.addListenerOnce(map, 'idle', function() {
            map.fitBounds(locationbounds);
        });

    }
    /*End Show location on map*/

    /* Map Callback function */
    function map_callback_fucntion() {
        initAutocomplete();
        billing_map();
        show_location_map();
    }
    /* End Map Callback function */

    /* File upload for digital product */
    function readUrl(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = (e) => {
                let imgData = e.target.result;
                let imgName = input.files[0].name;
                input.closest('[data-title]').setAttribute("data-title", imgName);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    /* End File upload for digital product */
</script>




<!-- change delivery status (008) -->

<script>

function displayOrderDimensionUpdateModal() {
    // This function should handle displaying the modal for updating dimensions
    // You can use JavaScript/jQuery to show the modal here
    // For example:
    $('#order_dimension_update_modal').modal('show');
}
  
    // 008
    // function changeDeliveryStatus(status) {
    //     //console.log("Hello",status);
    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    //         }
    //     });

    //     $.ajax({
    //         url: "{{route('admin.orders.status')}}",
    //         method: 'POST',
    //         data: {
    //             "id": '{{$order['id']}}',
    //             "order_status": status
    //         },
    //         success: function(data) {
    //             if (data.success == 0) {
    //                 toastr.success('{{translate("order_is_already_delivered_you_can_not_change_it")}} !!');
    //                 location.reload();
    //             } else {
    //                 if (data.payment_status == 0) {
    //                     toastr.warning('{{translate("before_delivered_you_need_to_make_payment_status_paid")}}!');
    //                     location.reload();
    //                 } else if (data.customer_status == 0) {
    //                     toastr.warning('{{translate("account_has_been_deleted_you_can_not_change_the_status")}}!');
    //                     location.reload();
    //                 } else {
    //                     toastr.success('{{translate("status_change_successfully")}}!');
    //                     location.reload();
    //                 }
    //             }

    //         }
    //     });

    // }
    function changeDeliveryStatus(status) {
        // console.log("Hello", status);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{route('admin.orders.status')}}",
            method: 'POST',
            data: {
                "id": '{{$order->id}}',
                "order_status": status
            },
            success: function(data) {
                if (data.success == 0) {
                    toastr.success('{{translate("order_is_already_delivered_you_can_not_change_it")}} !!');
                    location.reload();
                } else {
                    if (data.payment_status == 0) {
                        toastr.warning('{{translate("before_delivered_you_need_to_make_payment_status_paid")}}!');
                        location.reload();
                    } else if (data.customer_status == 0) {
                        toastr.warning('{{translate("account_has_been_deleted_you_can_not_change_the_status")}}!');
                        location.reload();
                    } else {
                        toastr.success('{{translate("status_change_successfully")}}!');
                        location.reload();
                    }
                }

            }
        });

    }

    // function test(){
    //     console.log("test");
    // }
    function order_status(status) {
        @if($order['order_status'] == 'delivered')
        Swal.fire({
            title: '{{translate("Order_is_already_delivered_and_transaction_amount_has_been_disbursed_changing_status_can_be_the_reason_of_miscalculation")}}!',
            text: "{{translate('think_before_you_proceed')}}.",
            showCancelButton: true,
            confirmButtonColor: '#377dff',
            cancelButtonColor: 'secondary',
            confirmButtonText: '{{translate("yes_change_it")}}!',
            cancelButtonText: '{{ translate("cancel") }}',
        }).then((result) => {
            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.orders.status')}}",
                    method: 'POST',
                    data: {
                        "id": '{{$order->id}}',
                        // "id": '{{$order['id ']}}',
                        "order_status": status
                    },
                    success: function(data) {

                        if (data.success == 0) {
                            toastr.success('{{translate("order_is_already_delivered_you_can_not_change_it")}} !!');
                            location.reload();
                        } else {

                            if (data.payment_status == 0) {
                                toastr.warning('{{translate("before_delivered_you_need_to_make_payment_status_paid")}}!');
                                location.reload();
                            } else if (data.customer_status == 0) {
                                toastr.warning('{{translate("account_has_been_deleted_you_can_not_change_the_status")}}!');
                                location.reload();
                            } else {
                                toastr.success('{{translate("status_change_successfully")}}!');
                                location.reload();
                            }
                        }

                    }
                });
            }
        })
        @else
        Swal.fire({
            title: '{{translate("are_you_sure_change_this")}}?',
            text: "{{translate('you_will_not_be_able_to_revert_this')}}!",
            showCancelButton: true,
            confirmButtonColor: '#377dff',
            cancelButtonColor: 'secondary',
            confirmButtonText: '{{translate("yes_change_it")}}!',
            cancelButtonText: '{{ translate("cancel") }}',
        }).then((result) => {
            if (result.value) {
                if (status == 'pending') {
                    changeDeliveryStatus('pending');
                }
                else if (status == 'canceled') {
                    if(`{{$order->third_party_delivery_shipment_id}}`){
                        // console.log({{$order->third_party_delivery_tracking_id}});
                        cancelOrder();
                    }
                    else{
                        // console.log("not avail");
                        toastr.warning('Order Id Not Available');
                        toastr.warning('First Confirm Order');
                    }
                    
                }
                else if (status == 'confirmed') {
                    if (!`{{$order->seller->shop->default_pickup_address_id}}`) {
                            // Seller has not been added to Shiprocket, display an error message
                            toastr.error("Add Pickup Address");
                        } else if (!`{{$order->seller->shop->default_pickup_address?->is_added_on_shiprocket}}`) {
                            // Pickup address is not added on Shiprocket, display an error message
                            toastr.error("Update pickup address on Shiprocket");
                        } else if (!`{{$order->length}}` || !`{{$order->breadth}}` || !`{{$order->height}}` || !`{{$order->weight}}`) {
                            // At least one dimension is not present, display the modal for updating dimensions
                            displayOrderDimensionUpdateModal();
                        } else {
                            // Confirm delivery
                            confirmDelivery();
                        }
                } else if (status == 'processing') {
                    if(`{{$order->third_party_delivery_shipment_id}}`){
                        // console.log({{$order->third_party_delivery_shipment_id}});
                        confirmpackaging();
                    }
                    else{
                        // console.log("not avail");
                        toastr.warning('Shipment Id Not Available');
                        toastr.warning('First Confirm Order');
                    }
                    

                }
                // else if (status=='out_for_delivery'){
                //     track_shipRocket_shipment();
                // }
                else {
                    if(`{{$order->third_party_delivery_shipment_id}}`){
                        // console.log({{$order->third_party_delivery_shipment_id}});
                        track_shipRocket_shipment();
                    }
                    else{
                        // console.log("not avail");
                        toastr.warning('Shipment Id Not Available');
                        toastr.warning('First Confirm Order');
                    }
                    
                    //     $.ajaxSetup({
                    //     headers: {
                    //         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    //     }
                    // });

                    // $.ajax({
                    //     url: "{{route('admin.orders.status')}}",
                    //     method: 'POST',
                    //     data: {
                    //         "id": '{{$order['id']}}',
                    //         "order_status": status
                    //     },
                    //     success: function(data) {
                    //         if (data.success == 0) {
                    //             toastr.success('{{translate("order_is_already_delivered_you_can_not_change_it")}} !!');
                    //             location.reload();
                    //         } else {
                    //             if (data.payment_status == 0) {
                    //                 toastr.warning('{{translate("before_delivered_you_need_to_make_payment_status_paid")}}!');
                    //                 location.reload();
                    //             } else if (data.customer_status == 0) {
                    //                 toastr.warning('{{translate("account_has_been_deleted_you_can_not_change_the_status")}}!');
                    //                 location.reload();
                    //             } else {
                    //                 toastr.success('{{translate("status_change_successfully")}}!');
                    //                 location.reload();
                    //             }
                    //         }

                    //     }
                    // });
                }


            }
        })
        @endif
    }
</script>



@endpush