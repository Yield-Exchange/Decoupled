@extends('dashboard.master')
@section('page_title')
    Account Setting
@stop
@section('styles')
@endsection
@section('page_content')
    @php
        $user_data = auth()->user();
    @endphp
    <!-- Main charts -->
    <div class="row" id="VueApp">
        <div class="col-xl-12">
            <!-- Traffic sources -->

                <div class="row">
                  
                    
                  
                        @if($organization->type=="BANK")
                        <div class="col-md-12">
                        <account-setting
                            :deposit_insurances="{{ json_encode($deposit_insurances) }}"
                            :credit_rating_types="{{ json_encode($credit_rating_types) }}"
                            :user="{{ $user_data }}"
                            :naics-codes="{{ json_encode($naics) }}"
                            :potential-deposits="{{ json_encode($potential_yearly_deposits) }}"
                            :deposit-portfolio="{{ json_encode($wholesale_deposits_portfolio) }}"
                            :fi-types="{{ json_encode($fi_types) }}"
                            :provinces="{{ json_encode($provinces) }}"
                            :organization="{{ ($organization) }}"
                            :update-account-setting-route="{{ json_encode(route('user.update-account-setting')) }}"
                            redirect-route="{{ (request()->has('fromPage') ? request()->fromPage : '/account-setting') }}"
                            :permitted-submit-button="{{ json_encode($permittedSubmitButton) }}"
                            :organizations_list="{{ json_encode($organizations_list) }}"
                        >
                        </account-setting>
                    </div>
                        @elseif($organization->type=="DEPOSITOR")
                          <div class="col-md-3"></div>
                          <div class="col-md-7">
                        <depositor-account-setting
                        :deposit_insurances="{{ json_encode($deposit_insurances) }}"
                        :credit_rating_types="{{ json_encode($credit_rating_types) }}"
                        :user="{{ $user_data }}"
                        :naics-codes="{{ json_encode($naics) }}"
                        :potential-deposits="{{ json_encode($potential_yearly_deposits) }}"
                        :deposit-portfolio="{{ json_encode($wholesale_deposits_portfolio) }}"
                        :fi-types="{{ json_encode($fi_types) }}"
                        :provinces="{{ json_encode($provinces) }}"
                        :organization="{{ ($organization) }}"
                        :update-account-setting-route="{{ json_encode(route('user.update-account-setting')) }}"
                        redirect-route="{{ (request()->has('fromPage') ? request()->fromPage : '/account-setting') }}"
                        :permitted-submit-button="{{ json_encode($permittedSubmitButton) }}"
                    >
                    </depositor-account-setting>
                          </div>
                      <div class="col-md-2"></div>
                        
                        @endif

                 
                  
                </div>
              
            <!-- /support tickets -->
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        // $(document).ready(function(){
        //     $('a[data-toggle="tooltip"]').tooltip({
        //         animated: 'fade',
        //         placement: 'top',
        //         html: true,
        //         viewport: '#VueApp'
        //     });
        // });
    </script>
@endsection