@extends('user::layouts.master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
          <li class="breadcrumb-item active">Create User</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    
    <div class="card-body">

<button onclick="window.location.href='{{ route('users.index') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
<i class="pe-7s-back btn-icon-wrapper"></i>Back
</button>     
         
     </div>
    <div class="card">
        {{ Form::open(['route' => ['users.store'], 'method' => 'post', 'enctype'=> 'multipart/form-data']) }}
       
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('company_id')) has-error @endif">
                        {{ Form::label('company_id', 'Company') }}<span class="asterisk">*</span>
                        {{ Form::select('company_id', $companies, old('company_id'), [
                            'class' => "form-control multiselect-dropdown company_id",
                            'id' => "company_id",
                            'data-live-search'=>'true'
                        ]) }}
                        @if($errors->has('company_id'))
                            <span style="color:red" class="error">{{ $errors->first('company_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('role_id')) has-error @endif">
                        {{ Form::label('role_id', 'Role') }}<span class="asterisk">*</span>
                        {{ Form::select('role_id', $roles, old('role_id'), [
                            'class' => "form-control multiselect-dropdown role_id",
                            'id' => "role_id",
                            'data-live-search'=>'true'
                        ]) }}
                        @if($errors->has('role_id'))
                            <span  style="color:red"  class="error">{{ $errors->first('role_id') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('full_name')) has-error @endif">
                        {{ Form::label('full_name', 'Full name') }}<span class="asterisk">*</span>
                        {{ Form::text('full_name', old('full_name'), [
                            'class' => "form-control full_name",
                            'id' => "full_name",
                        ]) }}
                        @if($errors->has('full_name'))
                            <span  style="color:red"  class="error">{{ $errors->first('full_name') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('email')) has-error @endif">
                        {{ Form::label('email', 'Email') }}<span class="asterisk">*</span>
                        {{ Form::text('email', old('email'), [
                            'class' => "form-control email",
                            'id' => "email",
                        ]) }}
                        @if($errors->has('email'))
                            <span  style="color:red"  class="error">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('password')) has-error @endif">
                        {{ Form::label('password', 'Password') }}<span class="asterisk">*</span>
                        {{ Form::text('password', old('password'), [
                            'class' => "form-control password",
                            'id' => "password",
                        ]) }}
                        @if($errors->has('password'))
                            <span  style="color:red"  class="error">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('confirm_password')) has-error @endif">
                        {{ Form::label('confirm_password', 'Confirm password') }}<span class="asterisk">*</span>
                        {{ Form::text('confirm_password', old('confirm_password'), [
                            'class' => "form-control confirm_password",
                            'id' => "confirm_password",
                        ]) }}
                        @if($errors->has('confirm_password'))
                            <span  style="color:red"  class="error">{{ $errors->first('confirm_password') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            
             <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('phone')) has-error @endif">
                        {{ Form::label('phone', 'Phone') }}<span class="asterisk">*</span>
                        {{ Form::text('phone', old('phone'), [
                            'class' => "form-control phone",
                            'id' => "phone",
                        ]) }}
                        @if($errors->has('phone'))
                            <span  style="color:red"  class="error">{{ $errors->first('phone') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('fax')) has-error @endif">
                        {{ Form::label('fax', 'Fax') }}
                        {{ Form::text('fax', old('fax'), [
                            'class' => "form-control fax",
                            'id' => "fax",
                        ]) }}
                        @if($errors->has('fax'))
                            <span  style="color:red"  class="error">{{ $errors->first('fax') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            
             <div class="row">
                <div class="col-md-10">
                    <div class="form-group @if($errors->has('phone')) has-error @endif">
                        {{ Form::label(' ', ' ') }}
                        {{ Form::text('post_code_get', old('post_code_get'), [
                            'class' => "form-control phone",
                            'id' => "post_code_get",'placeholder' =>"Enter your Post code to fetch your Address "
                            ,'oninput' => 'postFetch();'
                        ]) }}
                      
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                 <button type="button" onclick="postFetch();" class="mb-2 mr-2 btn-icon-vertical btn btn-alternate">
              <i class="pe-7s-search btn-icon-wrapper"></i>Search
                 </button>
                </div>
            </div>
                 
                 <br>                 
                <div id="hideAddress"  style="display: none" class="col-md-12">
                    <div class="form-group">
                        <select class="multiselect-dropdown form-control" id="placePost">
                                <option></option>
                        </select>
                    </div>
                </div>
                 <br>
             </div>
            
            
            
            
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('address_line1')) has-error @endif">
                        {{ Form::label('address_line1', 'Address line1') }}
                        {{ Form::text('address_line1', old('address_line1'), [
                            'class' => "form-control address_line1",
                            'id' => "address_line1",
                        ]) }}
                        @if($errors->has('address_line1'))
                            <span  style="color:red"  class="error">{{ $errors->first('address_line1') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('address_line2')) has-error @endif">
                        {{ Form::label('address_line2', 'Address line2') }}
                        {{ Form::text('address_line2', old('address_line2'), [
                            'class' => "form-control address_line2",
                            'id' => "address_line2",
                        ]) }}
                        @if($errors->has('address_line2'))
                            <span  style="color:red"  class="error">{{ $errors->first('address_line2') }}</span>
                        @endif
                    </div>
                </div>
            </div>
           
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('suburb')) has-error @endif">
                        {{ Form::label('suburb', 'Town') }}
                        {{ Form::text('suburb', old('suburb'), [
                            'class' => "form-control suburb",
                            'id' => "suburb",
                        ]) }}
                        @if($errors->has('suburb'))
                            <span  style="color:red"  class="error">{{ $errors->first('suburb') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('postcode')) has-error @endif">
                        {{ Form::label('postcode', 'Post code') }}
                        {{ Form::text('postcode', old('postcode'), [
                            'class' => "form-control postcode",
                            'id' => "postcode",
                        ]) }}
                        @if($errors->has('postcode'))
                            <span  style="color:red"  class="error">{{ $errors->first('postcode') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('status')) has-error @endif">
                        {{ Form::label('status', 'Status') }}
                        {{ Form::select('status', [1=> 'Active', 0=> 'In-active'], old('status'), [
                            'class' => "form-control status",
                            'id' => "status",
                        ]) }}
                        @if($errors->has('status'))
                            <span  style="color:red"  class="error">{{ $errors->first('status') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('file')) has-error @endif">
                        {{ Form::label('file', 'Avatar') }}
                        {{ Form::file('file', [
                            'class' => "form-control file",
                            'id' => "file",
                        ]) }}
                        @if($errors->has('file'))
                            <span  style="color:red"  class="error">{{ $errors->first('file') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if($errors->has('rate')) has-error @endif">
                        {{ Form::label('rate', 'Rate') }}<span class="asterisk">*</span>
                        {{ Form::number('rate', old('rate'), [
                            'class' => "form-control rate",
                            'id' => "rate",
                        ]) }}
                        @if($errors->has('rate'))
                            <span  style="color:red"  class="error">{{ $errors->first('rate') }}</span>
                        @endif
                    </div>
                </div>
              
            </div>
        </div>
        <div class="card-footer">
            {{ Form::submit('Save user', [ 'class' => "btn btn-primary btn-flat" ]) }}
        </div><br>
        {{ Form::close() }}
    </div>
</section>
@stop

@push('scripts')
<script>

function postFetch()
  {

    var postal = $('#post_code_get').val();
    var string = postal.replace(/\s/g, '');
     $('#postcode').val(postal);
      
    if(string.length>='3')
    {
      $.ajax({
        type:'get',
        url:"{{url('/address/fetch/firstclasspostcodes')}}/"+string,
        cache:false,
        dataType: 'json',
        data:{},
        success:function(data)
        { 
            console.log(data);
          if(data!='')
          {
             
                var addresses = data.addresses
            
             
                $('#hideAddress').css('display','block');
                
                console.log(addresses);
                $.each(addresses, function(i, addresses)
                {
                    
                  var replace = '<option value="'+addresses.line_1+'#'+addresses.line_2+'#'+addresses.line_3+'#'+addresses.town_or_city+'">'+addresses.line_1+' , '+addresses.line_2+' , '+addresses.line_3+' , '+addresses.town_or_city+'</option>';
                    console.log('replace',replace)
                    $('#placePost').append(replace);
                });
          }
          else 
          {           
            $('#hideAddress').css('display','none');
          }                                                 
        }
      });
    }
    else if(postal==''|| postal.length<=3)
    {
      $('#address_line1').val('');
    
      $('#hideAddress').css('display','none');
    }

  }
  
    $('select').on('change', function() 
  {
      var temp=this.value;
      var address_line1=temp.split('#')[0];
      var address_line2=temp.split('#')[1];
       address_line2+=temp.split('#')[2];
      var town=temp.split('#')[3] 
    $('#address_line1').val(address_line1);
    $('#address_line2').val(address_line2);
    $('#suburb').val(town);
   
  });

  </script>
  
  
  @endpush