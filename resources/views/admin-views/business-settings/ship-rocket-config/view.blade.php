<!-- resources/views/admin-views/business-settings/ship-rocket-config/view.blade.php -->

@extends('layouts.back-end.app')

@section('title', translate('Shiprocket Configuration'))

@push('css_or_js')
<!-- Add any CSS or JS files if needed -->
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-4 pb-2">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img src="{{asset('/public/assets/back-end/img/3rd-party.png')}}" alt="">
            {{translate('3rd_party')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Inlile Menu -->
    @include('admin-views.business-settings.third-party-inline-menu')
    <!-- End Inlile Menu -->

    <!-- Your Shiprocket Configuration Form Goes Here -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.shiprocket.update') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user_id">{{ translate('User ID') }}</label>
                            <input type="text" class="form-control" id="user_id" name="user_id" value="{{ $shiprocket['user_id'] ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">{{ translate('Password') }}</label>
                            <input type="text" class="form-control" id="password" name="password" value="{{ $shiprocket['password'] ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                   
                </div>
                
            
              
                
            </form>
        </div>
    </div>
    <!-- End Shiprocket Configuration Form -->


</div>
@endsection

@push('script')
<!-- Add any additional scripts if needed -->
@endpush