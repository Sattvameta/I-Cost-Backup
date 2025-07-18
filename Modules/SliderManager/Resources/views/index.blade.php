@extends('user::layouts.masterlist')


@section('content')


<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Sliders</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>                 
                    <li class="breadcrumb-item active">Sliders</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>


<!-- Main content -->

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Status</label>
                                <select name="status" class="form-control" id="account_status">
                                    <option value="">Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">InActive</option>
                                </select>
                            </div>
                        </div>
        
                    </div
                </div>
            </div>
            <div class="card-body">

                <table  class="table table-bordered table-hover" id="sliders-datatable" data-table="sliders">
                    <thead>
                        <tr>
                            <th>Title</th>
                             <th>Slide Image</th>
                            <th>Description</th>
                           <th  class="project-state no-sort">Status</th>
                            <th class="project-actions no-sort">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</section>

@stop

@push('scripts')

<script type="text/javascript">
    $(document).ready(function() {

        var roleSlug = 'sliders';

        var t = $('#sliders-datatable').DataTable({
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
                url: "{{ route('slider.ajax.list.all') }}",
                type: 'GET',
                data: function(d) {
                    d.status = $('#account_status').val();
                }
            },
            columns: [
                {data: 'title', name: 'title'},
                 {
                        "name": "image",
                        "data": "image",
                        "render": function (data) {
                            
                        if(data==null) data=site_url+'images/placeholder.png';
                            return "<img src=\"" +site_url+"uploads/sliders/" + data + "\" height=\"50\" width=\"80\" />";
                        },
                        "title": "Image",
                        "searchable" : false,
                        "bSortable": false,
                    },
                {data: 'description', name: 'description'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action'}
            ],
            "deferRender": true,
            'columnDefs': [{
                     "targets": 'no-sort',
                "orderable": false,
                }]
        });
        $('#account_status,#role').on('change', function() {

            $('#sliders-datatable').DataTable().draw(true);
        });
    });



</script>
@endpush