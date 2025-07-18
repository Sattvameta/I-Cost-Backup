@extends('user::layouts.masterlist')


@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('emailtemplates')}}"><i class="nav-icon far fa-envelope"></i> Email Templates</a></li>                    
                    <li class="breadcrumb-item active">Listing</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        @if(isset($_REQUEST['login']) && $_REQUEST['login'] =1 )
        <div class="alert alert-info">
            Please change your account password to continue using secure site.
        </div>
        @endif
        @include('layouts.flash.alert')
  <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">

                        <div class="card-body box">
            <div class="box-header with-border">
                <h3 class="box-title">Email Templates List</h3><hr>
                <div class="box-tools float-sm-right">
                    @if (auth()->user()->can('access', 'emailtemplates-edit'))
                        <a href="{{route('emailtemplates.add')}}" class="btn btn-primary">Add Email Template</a>
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-1">
                        <label>Status</label>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">

                            <select name="status" class="form-control" id="account_status">
                                 <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">InActive</option>
                            </select>
                        </div>
                    </div>


                </div>



            </div>
            <div class="box-body">
                @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
                @endif

                <table class="table table-bordered" id="emailtemplates-datatable" data-table="email_templates">
                    <thead>
                        <tr>
                        <th class="no-sort">Sr No.</th>
                        <th>Email Type</th>
                        <th>Subject</th>
                        <th>Status</th>

                        <th class="no-sort">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="pull-right"></div>
            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->
                    </div>
                </div>
  </div>
    </section>
    <!-- /.content -->
</div
<input type="checkbox" checked data-toggle="toggle">
@endsection


@push('scripts')
<script type="text/javascript">
  $(document).ready(function() {


        var t = $('#emailtemplates-datatable').DataTable({
            dom: 'Bfrtip',
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "processing": true,
            "serverSide": true,
            "searchable": true,
            "pageLength": 25,
            "order": [[0, 'asc']],
            ajax: {
                url: "{{ route('emailtemplates.ajax.list') }}",
                type: 'GET',
                data: function(d) {
                    d.status = $('#account_status').val();
                }
            },
            columns: [
               {data: 'id', name: 'id'},
                {data: 'type', name: 'type'},
                {data: 'subject', name: 'subject'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'}
            ],
            "deferRender": true,
             'columnDefs': [{
                     "targets": 'no-sort',
                "orderable": false,
                }]
        });
        $('#account_status').on('change', function() {

            $('#users-datatable').DataTable().draw(true);
        });
    });



</script>
@endpush