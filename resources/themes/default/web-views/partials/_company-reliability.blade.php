
<div class="accordion">
    <div class="accordion-item">
        <div class="accordion-header">
            More About My Glamour
            <span class="arrow-icon">▼</span>
        </div>
        <div class="accordion-content">
          @php
          $data=App\Models\categoryseo::all();
      @endphp
   
      @foreach ($data as $item)
      <h5>{{ $item->Category }}</h5>
          <p>{!! $item->Content !!}</p>
      @endforeach
        
        </div>
    </div>
</div>

<style>
    .accordion {
        border: 1px solid #ccc;
        margin: 25px;
        border-radius: 5px;
    }

    .accordion-item {
        border-bottom: 1px solid #ccc;
        border-radius: 5px;
    }

    .accordion-header {
        background-color: #fff;
        color: #000;
        padding: 10px;
        cursor: pointer;
        font-size: x-large;
        font-weight: 700;
        position: relative;
    }

    .arrow-icon {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
    }

    .accordion-content {
        background-color: #fff;
        color: #000;
        display: none;
        padding: 10px;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const accordionHeaders = document.querySelectorAll('.accordion-header');

        accordionHeaders.forEach(header => {
            header.addEventListener('click', function() {
                const accordionItem = this.parentElement;
                const accordionContent = accordionItem.querySelector('.accordion-content');

                if (accordionContent.style.display === 'block') {
                    accordionContent.style.display = 'none';
                } else {
                    accordionContent.style.display = 'block';
                }
            });
        });
    });
</script>

<div class="container rtl pb-4 pt-3 px-0 px-md-3">
    <div class="shipping-policy-web">
        <div class="row g-3 justify-content-center mx-max-md-0">
            @foreach ($company_reliability as $key => $value)
                @if ($value['status'] == 1 && !empty($value['title']))
                    <div class="col-md-3 d-flex justify-content-center px-max-md-0">
                        <div class="shipping-method-system">
                            <div class="text-center">
                                <img class="{{ Session::get('direction') === 'rtl' ? 'float-right ml-2' : 'mr-2' }} size-60"
                                    src="{{ asset('/storage/app/public/company-reliability') . '/' . $value['image'] }}"
                                    onerror="this.src='{{ asset('/public/assets/front-end/img') . '/' . $value['item'] . '.png' }}'"
                                    alt="">
                            </div>
                            <div class="text-center">
                                <p class="m-0">
                                    {{ $value['title'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
