@extends('layouts.app')


@section('content')

@push('styles')
    <link href="{{ asset('/dist/css/flexslider.css')}}" rel="stylesheet" />
 
@endpush
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
   

                <div class="slider">
                    <div id="slider" class="flexslider">
                        <ul class="slides">
                            <?php foreach ($sliders as $slider) { ?>
                                <li>
                                    <img src="<?php echo "http://192.168.4.164/icost3/public/uploads/sliders/" . $slider['image'] ?>" />
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                  <div id="carousel" class="flexslider">
      <ul class="slides">
          <?php foreach ($sliders as $slider) { ?>
                                <li>
                                    <img src="<?php echo "http://192.168.4.164/icost3/public/uploads/sliders/" . $slider['image'] ?>" />
                                </li>
                            <?php } ?>
      </ul>
   </div>

      

</section>

@stop
@push('scripts')


<script src="{{asset('/dist/js/jquery.flexslider.js')}}"></script>
<script>
$(document).ready(function(){
	  $('#carousel').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 210,
        itemMargin: 5,
        asNavFor: '#slider'
      }); 
      $('#slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: "#carousel",
        start: function(slider){
          $('body').removeClass('loading');
        }
      });
      
});
</script>


@endpush
