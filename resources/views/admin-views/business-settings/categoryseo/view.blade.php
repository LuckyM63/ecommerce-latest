<!-- resources/views/admin-views/business-settings/ship-rocket-config/view.blade.php -->

@extends('layouts.back-end.app')

@section('title', translate('showoffer'))

@push('css_or_js')
    <!-- Add any CSS or JS files if needed -->
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ asset('/public/assets/back-end/img/3rd-party.png') }}" alt="">
                {{ translate('3rd_party') }}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.business-settings.third-party-inline-menu')
        <!-- End Inlile Menu -->

        <!-- Your showoffer Form Goes Here -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.categoryseo.Add') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="offer_id">{{ translate('Category') }}</label>
                            <!-- <input type="text" class="form-control" id="offer_id" name="offer_id" value=""> -->
                            </div>
                        </div>
                    <div>                  
                   </div>
                    </div>
                    <select name="Category" style="padding:5px; margin:10px; border-radius:5px">
    <option value="">Select</option>
    <option value="Imported">Imported</option>
    <option value="Skin Care">Skin Care</option>
    <option value="Hair Care">Hair Care</option>
    <option value="Face">Face</option>
    <option value="Eyes">Eyes</option>
    <option value="Lips">Lips</option>
    <option value="Fragrance">Fragrance</option>
    <option value="Men">Men</option>
    <option value="Bath&Body">Bath&Body</option>
    <option value="Accesories">Accesories</option>
    <option value="Gifts&Combos">Gifts&Combos</option>
</select>
<div class="row">
                                        <div class="col-md-6">
                                            <label for="blog">Blog</label>
                                            <!-- <input type="textarea" style="width: 500px; height: 200px;" name="blog"> -->
                                            <textarea name="blog" style="width: 500px; height: 200px;" placeholder="Please enter details..."></textarea>

                                        </div>
                                    </div> <br>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End showoffer Configuration Form -->

        <div class="table-responsive datatable-custom">
            <table style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{ translate('sl') }}</th>
                        <th class="text-center">{{ translate('Category') }}</th>
                        <th class="text-center">{{ translate('Content') }}</th>
                        <th class="text-center">{{ translate('Toggle') }}</th>
                        <th class="text-center">{{ translate('Action') }}</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($Data as $Item)
                     
                
                    
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $Item->Category}}</td>
                            <td>{{ $Item->Content}}</td>
                            <td>{{ $Item->Toggle}}</td>
                            
                            <td>
                                <form action="{{route('admin.categoryseo.updateOffer',['id' => $Item->id])}}" method="POST" class="customer_status_form">
                                    @csrf
                                    @method('patch')
                                    {{-- <input type="hidden" name="id" value="{{$Item->id}}">
                                    <label class="switcher mx-auto">
                                        <input type="checkbox" class="switcher_input"  name="status" class="toggle-switch-input" {{ $Item->status == 1 ? 'checked':'' }} 
                                        id="status{{$Item->id}}" onclick="toogleStatusModal('{{$Item->status}}','status{{$Item->id}}','status-on.png','status-off.png','{{translate('Want_to_Turn_ON_Status')}}','{{translate('Want_to_Turn_OFF_Status')}}',`<p>{{translate('if_enabled_this_status_will_be_available_throughout_the_entire_system')}}</p>`,`<p>{{translate('if_disabled_this_status_will_be_hidden_from_the_entire_system')}}</p>`)">
                                        <span class="switcher_control"></span>
                                    </label>  --}}

                                    <a href="{{route('admin.categoryseo.updateOffer',['id' => $Item->id])}}" class="btn btn-sm btn-{{$Item->status?'success':'danger'}}"> 
                                        {{$Item->status?'enable':'disable'}}</a>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                </div>
                                <form action="{{route('admin.categoryseo.deleteOffer',['id' => $Item->id])}}"
                                    method="POST" >
                                @csrf
                                @method('delete')
                                <a href="{{route('admin.categoryseo.deleteOffer',['id' => $Item->id])}}"title="{{translate('delete')}}"
                                    class="btn btn-outline-danger btn-sm delete square-btn" href="javascript:"
                                    onclick="form_alert('Offerr-{{$Item->id}}','{{translate('want_to_delete_this_offer').'?'}}')">
                                    <i class="tio-delete"></i>
                                </a>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('script')
<!-- <script>
    function applyFormat(format) {
        var textarea = document.getElementById('textEditor');
        var text = textarea.value;
        var selectionStart = textarea.selectionStart;
        var selectionEnd = textarea.selectionEnd;
        
        switch(format) {
            case 'bold':
                text = text.slice(0, selectionStart) + '<b>' + text.slice(selectionStart, selectionEnd) + '</b>' + text.slice(selectionEnd);
                break;
            case 'italic':
                text = text.slice(0, selectionStart) + '<i>' + text.slice(selectionStart, selectionEnd) + '</i>' + text.slice(selectionEnd);
                break;
            case 'underline':
                text = text.slice(0, selectionStart) + '<u>' + text.slice(selectionStart, selectionEnd) + '</u>' + text.slice(selectionEnd);
                break;
        }
        
        textarea.value = text;
    }
</script> -->
    <!-- Add any additional scripts if needed -->
@endpush
