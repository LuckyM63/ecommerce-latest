@push('css_or_js')
<style>

.inactive-button {
    background-color: #e9ecef; /* Lighter shade of primary color */
    color: #6c757d; /* Text color */
    border-color: #e9ecef; /* Border color */
}

.inactive-button:hover {
    background-color: #e9ecef; /* Keep the same color on hover */
    border-color: #e9ecef; /* Keep the same border color on hover */
}
    .cart_title {
        font-weight: 400 !important;
        font-size: 16px;
    }

    .cart_value {
        font-weight: 600 !important;
        font-size: 16px;
    }

    @media (max-width: 575px) {
        .cart_title,
        .cart_value {
            font-size: 14px;
        }
    }

    .cart_total_value {
        font-weight: 700 !important;
        font-size: 25px !important;
        color: {{$web_config['primary_color']}}     !important;
    }

    .__cart-total_sticky {
        position: sticky;
        top: 80px;
    }
    /**/
</style>
@endpush

<aside class="col-lg-4 pt-4 pt-lg-2 px-max-md-0">
    <div class="__cart-total __cart-total_sticky">
        <div class="cart_total p-0">
            @php($shippingMethod=\App\CPU\Helpers::get_business_settings('shipping_method'))
            @php($sub_total=0)
            @php($total_tax=0)
            @php($total_shipping_cost=0)
            @php($order_wise_shipping_discount=\App\CPU\CartManager::order_wise_shipping_discount())
            @php($total_discount_on_product=0)
            @php($cart=\App\CPU\CartManager::get_cart())
            @php($cart_group_ids=\App\CPU\CartManager::get_cart_group_ids())
            @php($shipping_cost=\App\CPU\CartManager::get_shipping_cost())
            @php($get_shipping_cost_saved_for_free_delivery=\App\CPU\CartManager::get_shipping_cost_saved_for_free_delivery())
            @if($cart->count() > 0)
                @foreach($cart as $key => $cartItem)
                    @php($sub_total+=$cartItem['price']*$cartItem['quantity'])
                    @php($total_tax+=$cartItem['tax_model']=='exclude' ? ($cartItem['tax']*$cartItem['quantity']):0)
                    @php($total_discount_on_product+=$cartItem['discount']*$cartItem['quantity'])
                    @php($shipping_cost = $sub_total > 699 ? 0 : $shipping_cost)
                   
                    
                @endforeach
                <!-- {{ "Sub Total: $sub_total, Shipping Cost: $shipping_cost, Total Shipping Cost: $total_shipping_cost" }} -->
                
                <!-- @if(session()->missing('coupon_type') || session('coupon_type') !='free_delivery')
                    @php($total_shipping_cost=$shipping_cost - $get_shipping_cost_saved_for_free_delivery)
                @else
                    @php($total_shipping_cost=$shipping_cost)
                    
                @endif -->
                
            @endif
            @php($total_shipping_cost = $sub_total > 699 ? 0 : $shipping_cost)
            <!-- {{ "Sub Total: $sub_total, Shipping Cost: $shipping_cost, Total Shipping Cost: $total_shipping_cost" }} -->
            @if($total_discount_on_product > 0)
            <h6 class="text-center text-primary mb-4 d-flex align-items-center justify-content-center gap-2">
                <img src="{{asset('public/assets/front-end/img/icons/offer.svg')}}" alt="">
                {{translate('you_have_Saved')}} <strong>{{\App\CPU\Helpers::currency_converter($total_discount_on_product)}}!</strong>
            </h6>
            @endif

            <div class="d-flex justify-content-between">
                <span class="cart_title">{{translate('sub_total')}}</span>
                <span class="cart_value">
                    {{\App\CPU\Helpers::currency_converter($sub_total)}}
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="cart_title">{{translate('tax')}}</span>
                <span class="cart_value">
                    {{\App\CPU\Helpers::currency_converter($total_tax)}}
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="cart_title">{{translate('shipping')}}</span>
                <span class="cart_value">
                    {{\App\CPU\Helpers::currency_converter($total_shipping_cost)}}
                </span>
            </div>
            <div class="d-flex justify-content-between">
                <span class="cart_title">{{translate('discount_on_product')}}</span>
                <span class="cart_value">
                    - {{\App\CPU\Helpers::currency_converter($total_discount_on_product)}}
                </span>
            </div>
            @php($coupon_dis=0)
            @if(auth('customer')->check())
                @if(session()->has('coupon_discount'))
                    @php($coupon_discount = session()->has('coupon_discount')?session('coupon_discount'):0)
                    <div class="d-flex justify-content-between">
                        <span class="cart_title">{{translate('coupon_discount')}}</span>
                        <span class="cart_value" id="coupon-discount-amount">
                            - {{\App\CPU\Helpers::currency_converter($coupon_discount+$order_wise_shipping_discount)}}
                        </span>
                    </div>
                    @php($coupon_dis=session('coupon_discount'))
                @else
                    <div class="pt-2">
                        <form class="needs-validation coupon-code-form" action="javascript:" method="post" novalidate id="coupon-code-ajax">
                            <div class="d-flex form-control rounded-pill pl-3 p-1">
                                <img width="24" src="{{asset('public/assets/front-end/img/icons/coupon.svg')}}" alt="">
                                <input class="input_code border-0 px-2 text-dark bg-transparent outline-0 w-100" type="text" name="code" placeholder="{{translate('coupon_code')}}" required>
                                <button class="btn btn--primary rounded-pill text-uppercase py-1 fs-12" type="button" onclick="couponCode()">{{translate('apply')}}</button>
                            </div>
                            <div class="invalid-feedback">{{translate('please_provide_coupon_code')}}</div>
                        </form>
                    </div>

                    
             <!-- Display coupons below the input field -->
        <div class="pt-2">
            <div class="d-flex justify-content-between align-items-center">
                <span>{{translate('Available Coupons')}}</span>
                <button id="show-coupons-btn" class="btn btn-primary">{{translate('Show')}}</button>
            </div>
            <div id="available-coupons" class="pt-2" style="display: none;">
                <!-- Coupons will be dynamically added here -->
            </div>
        </div>
                    @php($coupon_dis=0)
                @endif
            @endif
            <hr class="my-2">
            <div class="d-flex justify-content-between">
                <span class="cart_title text-primary font-weight-bold">{{translate('total')}}</span>
                <span class="cart_value">
                {{\App\CPU\Helpers::currency_converter($sub_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)}}
                </span>
            </div>
        </div>
        @php($company_reliability = \App\CPU\Helpers::get_business_settings('company_reliability'))
        @if($company_reliability != null)
            <div class="mt-5">
                <div class="row justify-content-center g-4">
                    @foreach ($company_reliability as $key=>$value)
                        @if ($value['status'] == 1 && !empty($value['title']))
                            <div class="col-sm-3 px-0 text-center mobile-padding">
                                <img class="order-summery-footer-image" src="{{asset("/storage/app/public/company-reliability").'/'.$value['image']}}"
                                onerror="this.src='{{asset('/public/assets/front-end/img').'/'.$value['item'].'.png'}}'" alt="">
                                <div class="deal-title">{{translate($value['title'] ?? 'title_not_found')}}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-4">
            @if($web_config['guest_checkout_status'] || auth('customer')->check())
                <a onclick="checkout()" class="btn btn--primary btn-block proceed_to_next_button {{$cart->count() <= 0 ? 'disabled' : ''}}" >{{translate('proceed_to_Next')}}</a>
            @else
                <a href="{{route('customer.auth.login')}}" class="btn btn--primary btn-block proceed_to_next_button {{$cart->count() <= 0 ? 'disabled' : ''}}" >{{translate('proceed_to_Next')}}</a>
            @endif
        </div>
        @if( $cart->count() != 0)

            <div class="d-flex justify-content-center mt-3">
                <a href="{{route('home')}}" class="d-flex align-items-center gap-2 text-primary font-weight-bold">
                    <i class="tio-back-ui fs-12"></i> {{translate('continue_Shopping')}}
                </a>
            </div>
        @endif

    </div>
</aside>

<div class="bottom-sticky3 bg-white p-3 shadow-sm w-100 d-lg-none">
    <div class="d-flex justify-content-center align-items-center fs-14 mb-2">
        <div class="product-description-label fw-semibold text-capitalize">{{translate('total_price')}} : </div>
        &nbsp; <strong  class="text-base">{{\App\CPU\Helpers::currency_converter($sub_total+$total_tax+$total_shipping_cost-$coupon_dis-$total_discount_on_product-$order_wise_shipping_discount)}}</strong>
    </div>
    @if($web_config['guest_checkout_status'] || auth('customer')->check())
        <a onclick="checkout()" class="btn btn--primary btn-block proceed_to_next_button text-capitalize {{$cart->count() <= 0 ? 'disabled' : ''}}">{{translate('proceed_to_next')}}</a>
    @else
        <a href="{{route('customer.auth.login')}}" class="btn btn--primary btn-block proceed_to_next_button text-capitalize {{$cart->count() <= 0 ? 'disabled' : ''}}">{{translate('proceed_to_next')}}</a>
    @endif
</div>
@push('script')
    <script>
        $(document).ready(function() {
            const $stickyElement = $('.bottom-sticky3');
            const $offsetElement = $('.__cart-total_sticky');

            $(window).on('scroll', function() {
                const elementOffset = $offsetElement.offset().top;
                const scrollTop = $(window).scrollTop();
                console.log("scrollTop:", scrollTop, "elementOffset:", elementOffset);

                if (scrollTop >= elementOffset) {
                    $stickyElement.addClass('stick');
                } else {
                    $stickyElement.removeClass('stick');
                }
            });
        });

    </script>

<script>
    // JavaScript function to fetch and display available coupons
    function displayAvailableCoupons() {
    $.ajax({
        type: "GET",
        url: '{{ route('coupon.couponList') }}',
        success: function(response) {
            let coupons = response.coupons;
            let couponHtml = '';
            coupons.forEach(function(coupon) {
                couponHtml += '<div class="row mb-2">';
                couponHtml += '<div class="col-md-8">';
                couponHtml += '<div class="coupon card p-2" style="padding-top: 8px; padding-bottom: 8px;">'; // Adjust padding here
                couponHtml += '<p class="coupon-details" style="font-size: 12px; margin-bottom: 4px;"><strong>'; // Adjust margin here
                if (coupon.discount_type == 'percentage') {
                    couponHtml += coupon.discount + '% ' + '(max ' + '<strong>&#8377; ' + coupon.max_discount + '</strong> )';
                } else {
                    couponHtml += '<strong>&#8377; ' + coupon.discount + '</strong>';
                }
                couponHtml += '</strong></p>';
                couponHtml += '<p class="coupon-details" style="font-size: 10px; margin-bottom: 4px;"><strong>Min Order: &#8377; ' + coupon.min_purchase + '</strong></p>'; // Adjust margin here
                couponHtml += '<p class="coupon-details" style="margin-bottom: 0; font-size: 10px;"><strong>shop: ' + coupon.seller.shop.name + '</strong></p>'; // Adjust margin here
                couponHtml += '</div></div>';
                couponHtml += '<div class="col-md-4 align-self-center">';
                couponHtml += '<button class="btn btn-secondary float-right copy-coupon" style="font-size: 12px; margin-top: 8px;" data-code="' + coupon.code + '">Copy</button>'; // Adjust margin here
                couponHtml += '</div></div>';
            });
            $('#available-coupons').html(couponHtml);

            // Add click event listener to copy coupon button after coupons are loaded
            $('.copy-coupon').click(function() {
                let code = $(this).data('code');
                copyToClipboard(code, this); // Pass the clicked button reference
            });
        }
    });
}


    // Function to copy coupon code to clipboard
    function copyToClipboard(text, clickedButton) {
        var dummy = document.createElement("textarea");
        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);

        // Disable the clicked button and change its color and text
        $(clickedButton).prop('disabled', true);
        $(clickedButton).addClass('inactive-button');
        $(clickedButton).text('Copied');

        // Restore the button after 2 seconds
        setTimeout(function() {
            $(clickedButton).prop('disabled', false);
            $(clickedButton).removeClass('inactive-button');
            $(clickedButton).text('Copy');
        }, 3000);
    }

    // Toggle visibility of coupon list and change button color
    $('#show-coupons-btn').click(function() {
        $('#available-coupons').toggle();
        $(this).toggleClass('btn-primary btn-info'); // Toggle button color class
        if ($(this).hasClass('btn-info')) {
            $(this).text('Hide');
        } else {
            $(this).text('Show');
        }
    });

    // Call the function to display available coupons when the page loads
    $(document).ready(function() {
        displayAvailableCoupons();
    });







    $('.copy-coupon').click(function() {
        let code = $(this).data('code');
        copyToClipboard(code, this); // Pass the clicked button reference
    });
</script>
@endpush
