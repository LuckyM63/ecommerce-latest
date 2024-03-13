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
                <form action="{{ route('admin.showoffer.Add') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="offer_id">{{ translate('offer ID') }}</label>
                                <input type="text" class="form-control" id="offer_id" name="offer_id" value="">
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">{{ translate('Add') }}</button>

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
                        <th class="text-center">{{ translate('OfferId') }}</th>
                        <th class="text-center">{{ translate('toggle') }}</th>
                        <th class="text-center">{{ translate('action') }}</th>

                    </tr>
                </thead>
                <tbody>

                    
                    @foreach ($offersData as $offerItem)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $offerItem->offer_id }}</td>
                            <td>
                                <form action="{{route('admin.showoffer.updateOffer',['id' => $offerItem->id])}}" method="POST" class="customer_status_form">
                                    @csrf
                                    @method('patch')
                                    {{-- <input type="hidden" name="id" value="{{$offerItem->id}}">
                                    <label class="switcher mx-auto">
                                        <input type="checkbox" class="switcher_input"  name="status" class="toggle-switch-input" {{ $offerItem->status == 1 ? 'checked':'' }} 
                                        id="status{{$offerItem->id}}" onclick="toogleStatusModal('{{$offerItem->status}}','status{{$offerItem->id}}','status-on.png','status-off.png','{{translate('Want_to_Turn_ON_Status')}}','{{translate('Want_to_Turn_OFF_Status')}}',`<p>{{translate('if_enabled_this_status_will_be_available_throughout_the_entire_system')}}</p>`,`<p>{{translate('if_disabled_this_status_will_be_hidden_from_the_entire_system')}}</p>`)">
                                        <span class="switcher_control"></span>
                                    </label>  --}}

                                    <a href="{{route('admin.showoffer.updateOffer',['id' => $offerItem->id])}}" class="btn btn-sm btn-{{$offerItem->status?'success':'danger'}}"> 
                                        {{$offerItem->status?'enable':'disable'}}</a>
                                </form>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">

                              

                                </div>
                                <form action="{{route('admin.showoffer.deleteOffer',['id' => $offerItem->id])}}"
                                    method="POST" >
                                @csrf
                                @method('delete')
                                <a href="{{route('admin.showoffer.deleteOffer',['id' => $offerItem->id])}}"title="{{translate('delete')}}"
                                    class="btn btn-outline-danger btn-sm delete square-btn" href="javascript:"
                                    onclick="form_alert('Offerr-{{$offerItem->id}}','{{translate('want_to_delete_this_offer').'?'}}')">
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
    <!-- Add any additional scripts if needed -->
@endpush
