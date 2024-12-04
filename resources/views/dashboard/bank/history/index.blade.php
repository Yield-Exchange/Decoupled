@extends('dashboard.master')
@section('page_title')
History
@stop
@section('page_content')
  <div id="VueApp">
    <bank-history></bank-history>
    {{-- @include('dashboard.bank.history.deposits') --}}
    {{-- @include('dashboard.bank.history.offers') --}}
  </div> 
@endsection
@section('scripts')
<script>
    // $(document).ready(function () {
    //     // DataTable
    //     $('.custom-data-tables-1').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         ajax: "{{route('bank.deposits-history-data')}}",
    //         columns: [
    //             { data: 'reference_no' },
    //             { data: 'date' },
    //             { data: 'depositor_name' },
    //             { data: 'province' },
    //             { data: 'amount' },
    //             { data: 'product' },
    //             { data: 'investment_period' },
    //             { data: 'interest_rate' },
    //             { data: 'status' },
    //             { data: 'action' }
    //         ],
    //         fnDrawCallback: function () {
    //             $('.total_records_pill_1').html(this.api().page.info().recordsTotal);
    //         }
    //     });

    //     $('.custom-data-tables-2').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         ajax: {
    //             url: "{{route('bank.offers-history-data')}}",
    //             data: function (d) {
    //                 d.fromPage="{{ Request::get('fromPage') ? Request::get('fromPage') : '' }}";
    //             }
    //         },
    //         columns: [
    //             { data: 'reference_no' },
    //             { data: 'date' },
    //             { data: 'depositor_name' },
    //             { data: 'province' },
    //             { data: 'amount' },
    //             { data: 'product' },
    //             { data: 'investment_period' },
    //             { data: 'interest_rate' },
    //             { data: 'status' },
    //             { data: 'action' }
    //         ],
    //         fnDrawCallback: function () {
    //             $('.total_records_pill_2').html(this.api().page.info().recordsTotal);
    //         }
    //     });
    //     $('.dataTables_length').addClass('bs-select');
    // });
</script>
@endsection