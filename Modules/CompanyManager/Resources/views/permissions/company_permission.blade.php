@extends('user::layouts.master')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('companies.index') }}"><i class="fa fa-dashboard"></i> Companies</a></li>
                    <li class="breadcrumb-item active">Permissions</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        @include('layouts.flash.alert')
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="card-body">

<button onclick="window.location.href='{{ route('companies.index') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
<i class="pe-7s-back btn-icon-wrapper"></i>Back
</button>                       

     </div>
                <div class="card">
                    {!! Form::open(array('route' => array('permissions.update'),'id'=>'permission')) !!}
                    <div class="card-header">
                        <h3 class="card-title">Company : {{ $company->company_name }}</h3>
                        
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="company_id" value="{{ $company->id }}" id="company_id">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Roles</label><span class="astrict">*</span>
                                            <select name="role_id" class="form-control roles-permissio multiselect-dropdown">
                                                @foreach($role as $role)
                                                <option value="{{$role->id}}" {{($role->name=='admin')?'selected':''}}>{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" container-fluid">
                            <div class="permission-section">
                                @php $privious_value = ''; $i=0;
                                foreach($permissions as $permission){
                                $newPermission[$permission->module][$i]=$permission;
                                $i++;
                                }
                                @endphp
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="permissions-listing">
                                            <table class="table table-hover text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Section</th>
                                                        <th> Read</th>
                                                        <th> Write</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($newPermission as $key=> $newpermission)
                                                    @if($key != 'purchaseorders')
                                                        <tr>
                                                            <td class="col-md-6 permissions-col">{{$key }}</td>
                                                            @foreach($newpermission as $permission)
                                                                <td>
                                                                    <div class="col-md-9  permissions-col chk">
                                                                        <div class="icheck-success d-inline">

                                                                            <input type="checkbox" id="permission{{$permission->id}}" name="permission_id[]" value="{{$permission->id}}" {{(!in_array($permission->id, $permissionsIds))?'':'checked'}}>
                                                                            <!--<label for="permission{{$permission->id}}">    {{ ucwords(str_replace('-', ' ', $permission->name)) }}  </label>  -->
                                                                            <label for="permission{{$permission->id}}"> {{ ucwords($permission->label)}} </label> </div>
                                                                        @php $privious_value = $permission->module; @endphp
                                                                    </div>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @if(Auth::user()->roles->first()->slug =='super_admin')
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{Form::label('default_permission', 'Set Permission')}}
                                                {!!Form::select('default_permission', ['0'=>'Set Custom Company Permission','1'=>'Reset Default Permission'], $is_default, ['class' => 'form-control'])!!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <!-- /.box -->

</section>
<!-- /.content -->
</div>
@stop

@push('scripts')
<script>
    $('document').ready(function() {
        $("select.roles-permissio").change(function() {

            var selectedRole = $(this).children("option:selected").val();
            var companyId = $("#company_id").val();
            var url = "{{route('permissions.companyroles')}}";
            url = url + "/" + selectedRole + "_" + companyId;

            $.ajax({

                url: url,
                type: 'GET',
                cache: false,
                success: function(result) {
                    if (result) {

                        $('.permission-section').html(result);
                    } else {
                        //toastr.error("Error ! while loading page content.");
                    }

                },
                error: function(data) {
                    // toastr.error("Error ! while loading page content.");
                }
            });
        })


        $('#permission').validate({
            rules: {
                role_id: {
                    required: true,

                },

            },

            errorElement: 'div',
            errorClass: 'help-block',
            highlight: function(element, errorClass, validClass) {
                $(element).parents("div.form-group").addClass('has-error');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents(".has-error").removeClass('has-error');
            }
        })

    })
</script>
@endpush