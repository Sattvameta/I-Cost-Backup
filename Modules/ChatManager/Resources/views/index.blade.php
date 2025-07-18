@extends('user::layouts.masterlist')
@section('content')
    <style type="text/css">
        .contacts-list > li.active{
            background:#ebeced;
        }
		.callout.callout-warning {
           border-left-color: #d39e00;
       }
	   .callout {
		border-radius: .25rem;
		box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 1px 2px rgb(0 0 0 / 24%);
		background-color: #fff;
		border-left: 5px solid #e9ecef;
		margin-bottom: 1rem;
		padding: 1rem;
		}
		.callout p:last-child {
          margin-bottom: 0;
      }
	  *, ::after, ::before {
         box-sizing: border-box;
      }
	  .body {
			margin: 0;
			font-family: "Source Sans Pro",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
			font-size: 1rem;
			font-weight: 400;
			line-height: 1.5;
			color: #212529;
			text-align: left;
	  }
	  .contacts-list {
		padding-left: 0;
		list-style: none;
	}
	.contacts-list>li {
		border-bottom: 1px solid rgba(0,0,0,.2);
		margin: 0;
		padding: 10px;
	}
	.direct-chat-contacts {
		-webkit-transform: translate(101%,0);
		transform: translate(101%,0);
		background: #ebeced;
		bottom: 0;
		color: #fff;
		height: 250px;
		overflow: auto;
		position: absolute;
		top: 0;
		width: 100%;
	}
	.direct-chat-contacts-open .direct-chat-contacts {
    /* -webkit-transform: translate(0,0); */
    transform: translate(0,0);
	}
	.contacts-list-img {
		border-radius: 50%;
		float: left;
		width: 40px;
	}
	.img {
		vertical-align: middle;
		border-style: none;
	}
	.contacts-list-name {
    font-weight: 600px;
	}
	.contacts-list-name, .contacts-list-status {
		display: block;
	}
	.contacts-list-info {
    color: #000;
    margin-left: 45px;
	}
	.contacts-list-date {
		color: #000;
		font-weight: 400;
	}
	.small, small {
    font-size: 80%;
    font-weight: 400;
    }
	.contacts-list-msg {
    color: #000;
    }
		a:hover {
		   
			text-decoration: none;
		}
		.btn-success:not(:disabled):not(.disabled).active, .btn-success:not(:disabled):not(.disabled):active, .show>.btn-success.dropdown-toggle {
			color: #fff;
			background-color: #1e7e34;
			border-color: #1c7430;
		}
		.btn:not(:disabled):not(.disabled).active, .btn:not(:disabled):not(.disabled):active {
			box-shadow: none;
		}
		.btn.btn-flat {
			border-radius: 0;
			border-width: 1px;
			box-shadow: none;
		}
		.direct-chat-primary .right>.direct-chat-text {
			background: #007bff;
			border-color: #007bff;
			color: #fff;
		}
		.right .direct-chat-text {
			margin-left: 0;
			margin-right: 50px;
		}
		.direct-chat-text {
			border-radius: .3rem;
			background: #d2d6de;
			border: 1px solid #d2d6de;
			color: #444;
			margin: 5px 0 0 50px;
			padding: 5px 10px;
			position: relative !important;
		}
		msg, .direct-chat-text {
			display: block;
		}
		.direct-chat-infos {
			display: block;
			font-size: .875rem;
			margin-bottom: 2px;
		}
		.direct-chat-name {
			font-weight: 600;
		}
		.float-left {
			float: left!important;
		}
		.right .direct-chat-img {
			float: right;
		}
		.direct-chat-img {
			border-radius: 50%;
			float: left;
			height: 40px;
			width: 40px;
		}
		.direct-chat-timestamp {
			color: #697582;
		}
		.float-right {
			float: right!important;
		}
		.input-group-sm>.custom-select, .input-group-sm>.form-control:not(textarea) {
		height: calc(1.8125rem + 2px);
		 }
		.input-group-sm>.custom-select, .input-group-sm>.form-control, .input-group-sm>.input-group-append>.btn, .input-group-sm>.input-group-append>.input-group-text, .input-group-sm>.input-group-prepend>.btn, .input-group-sm>.input-group-prepend>.input-group-text {
			padding: .25rem .5rem;
			font-size: .875rem;
			line-height: 1.5;
			border-radius: .2rem;
		}
		.direct-chat-primary .right>.direct-chat-text::after, .direct-chat-primary .right>.direct-chat-text::before {
          border-left-color: #007bff;
         }
		 .right .direct-chat-text::after, .right .direct-chat-text::before {
			border-left-color: #d2d6de;
			border-right-color: transparent;
			left: 100%;
			right: auto;
		}
		.direct-chat-text::before {
			border-width: 6px;
			margin-top: -6px;
		}
		.direct-chat-text::after, .direct-chat-text::before {
			border: solid transparent;
			border-right-color: #d2d6de;
			content: ' ';
			height: 0;
			pointer-events: none;
			position: absolute;
			right: 100%;
			top: 15px;
			width: 0;
		}
		*, ::after, ::before {
			box-sizing: border-box;
		}
		.direct-chat-text::before {
			border-width: 6px;
			margin-top: -6px;
		}
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Conversations</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>                 
                        <li class="breadcrumb-item active">Conversations</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        @include('layouts.flash.alert')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{ Form::open(['route' => ['conversations.send'], 'method' => 'post', 'class' => 'conversation-form']) }}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                @if(isset($conversations))
                                    <input  type="hidden" name="receiver_id" value="{{ $receiver->id }}">
                                    @if($conversations->isNotEmpty())
                                        <div class="direct-chat direct-chat-primary">
                                            <div class="direct-chat-messages all-conversations">
                                                @foreach($conversations as $conversation)
                                                    <div class="direct-chat-msg @if($conversation->sender_id == auth()->id()) right @endif">
                                                        <div class="direct-chat-infos clearfix">
                                                            <span class="direct-chat-name float-left">{{ $conversation->sender->full_name }}</span>
                                                            <span class="direct-chat-timestamp float-right">{{ $conversation->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        @if(\Storage::disk('public')->has($conversation->sender->avatar))
                                                            <img class="direct-chat-img" src="{{ asset('storage/'.$conversation->sender->avatar) }}" alt="" >
                                                        @else
                                                            <img class="direct-chat-img" src="{{ asset('images/no-img-100x92.jpg') }}" alt="" >
                                                        @endif
                                                        <div class="direct-chat-text">
                                                            {{ $conversation->message }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div> 
                                    @else
                                        <div class="direct-chat direct-chat-primary">
                                            <div class="direct-chat-messages all-conversations">
                                                <div class="callout callout-warning should-hide">
                                                    <p>Type somthing to start conversation.</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="callout callout-warning">
                                        <p>Please select any user to start conversation.</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <div class="direct-chat-contacts-open">
                                    <div class="direct-chat-contacts" style="position: static !important;">
                                        <div class="input-group input-group-sm" style="padding:2px;">
                                            <input type="text" name="keyword" class="form-control" placeholder="Search" style="border:1px solid #218838; border-radius:0px">
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-success btn-flat search-contacts">Search</button>
                                            </span>
                                        </div>
                                        <ul class="contacts-list contact-list-wrapper">
                                            @include('chatmanager::contacts')
                                        </ul>
                                    </div>
                                    <!-- /.contatcts-list -->
                                </div>
                            </div>
                        </div>
                    </div>
                    @isset($conversations)
                        <div class="card-footer">
                            <div class="input-group @if($errors->has('message')) has-error @endif">
                                <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                                @if($errors->has('message'))
                                    <span class="invalid-feedback">{{ $errors->first('message') }}</span>
                                @endif
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-success btn-flat send-btn" style="height: 38px;">Send</button>
                                </span>
                            </div>
                        </div>
                    @endisset
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
@stop
@push('scripts')
    <script type="text/javascript">
        function scrollToBottom(){
            var height = 0;
            $('.all-conversations .direct-chat-msg').each(function(i, value){
                height += parseInt($(this).height());
            });
            $('.all-conversations').animate({ scrollTop: height}, 1000);
        }
        $(document).ready(function() {
            scrollToBottom();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{!! csrf_token() !!}'
                }
            });
            $(document).on('submit', '.conversation-form', function(e){
                e.preventDefault();
                var token = $('meta[name="csrf-token"]').attr('content');
                var receiver_id = $(document).find('input[name=receiver_id]').val();
                var message = $(document).find('input[name=message]').val();
                $('.send-btn').text('sending...');
                $.ajax({
                    type:'POST',
                    url: '{{ route("conversations.send") }}',
                    dataType: 'json',
                    data:{
                        _token: '{!! csrf_token() !!}',
                        receiver_id:receiver_id,
                        message:message,
                    },
                    success: function(data){
                        $(document).find('.send-btn').text('send');
                        if(data.status == 'success'){
                            $(document).find('.should-hide').hide();
                            $(document).find('.all-conversations').append(data.html);
                            $(document).find('input[name=message]').val('');
                            scrollToBottom();
                        }else{
                            alert(data.message);
                        }
                    },
                    error: function(error){
                        $(document).find('.send-btn').text('send');
                    }
                });
            });

            $(document).on('click', '.search-contacts', function(){
                var keyword = $(document).find('input[name=keyword]').val();
                var route = "{{ route('conversations.search.contacts') }}";
                route = route+"?keyword="+keyword;
                $.get(route, function(data){
                    $(document).find('.contact-list-wrapper').html(data.html);
                }); 
            })
        });
    </script>
@endpush