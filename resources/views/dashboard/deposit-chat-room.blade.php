@extends('dashboard.master')
@section('page_title')
    Chat Room
@stop
@section('styles')
    <style>
        .chat-container {
            height: 400px;
            overflow-y: auto;
        }

        .tab-content,
        .tab-pane {
            border: 0px;
        }

        .swal-modal .swal-text {
            text-align: center;
        }

        /*.swal-footer { text-align: center; }*/
        .mod-width {
            width: 600px;
        }
    </style>
@endsection
@section('page_content')
    <h4>Messages</h4>
    <div class="row">
        <div class="col-md-3"><strong>Institution:
            </strong>{{ auth()->user()->user_type == 'Bank' ? $deposit->offer->invited->depositRequest->user->name : $deposit->offer->invited->bank->name }}
        </div>
        <div class="col-md-3"><strong>Deposit ID: </strong>{{ $deposit->reference_no }}</div>
        <div class="col-md-3"><strong>Awarded Amount:
            </strong>{{ $deposit->offer->invited->depositRequest->currency . ' ' . number_format($deposit->offered_amount) }}
        </div>
        <br />
        <br />
    </div>
    <div id="chatApp">
        <div class="tab-content card card-body border-top-0 rounded-0 rounded-bottom mb-0">
            <div class="tab-pane fade active show">
                <div class="tab-content card card-body border-top-0 rounded-0 rounded-bottom mb-0">
                    <chat-messages :messages="messages" :user="{{ auth()->user() }}"></chat-messages>
                </div>
            </div>
            <chat-form v-on:messagesent="addMessage" :user="{{ json_encode(auth()->user()) }}"
                :deposit="{{ json_encode($deposit) }}" :token="{{ json_encode(csrf_token()) }}"
                :can-chat="{{ json_encode($can_chat) }}"></chat-form>
        </div>
    </div>

    <!--<div class="modal fade" id="chat_modal" role="dialog">
            <div class="modal-dialog">-->

    <!-- Modal content-->
    <!--<div class="modal-content">
                    <div class="modal-header">
                        <h4 style="color:red" class="modal-title">Warning</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Do not share or request Bank account number/s or any related sensitive information in the chat rooms.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn custom-primary round mmy_btn" data-dismiss="modal">Sure</button>
                    </div>
                </div>

            </div>
        </div>-->
@endsection
@section('mix-scripts')
    <script>
        let deposit_object = @php echo $deposit @endphp;
    </script>
    <script>
        //$(document).ready(function () {

        swal({
            title: "Caution",
            text: "Try to limit how much sensitive information you share in the chat room.",
            // icon: "warning",
            //buttons: ["Ok"],
            className: "mod-width",
        }).then((response) => {
            if (response) {}
        });
        //}):
    </script>
@endsection
