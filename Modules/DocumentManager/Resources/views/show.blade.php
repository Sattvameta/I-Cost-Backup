@extends('user::layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('documentmanager') }}"><i class="fa fa-dashboard"></i>Central Doc Manager</a></li>
                    <li class="breadcrumb-item active">Central Doc Manager</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    
      <div class="card-body">

<button onclick="window.location.href='{{ route('documentmanager') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
<i class="pe-7s-back btn-icon-wrapper"></i>Back
</button>

     </div>
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Central Doc Manager</h3>
            
        </div>
        <div class="card-body">
        <table class="table table-bordered">
        <tbody style="text-align: center;">
            <tr>
                <th>Print</th>
				@foreach($doc as $document)
				@if($document->doc_type == 'docx' )
					<td>
                <a id="MyLink"  style="display:none"></a>
                <input id="Button1" type="button" value="Download" onclick="OpenWord()" style="background-color: #1b191a;color: #fff;"/></td>
				
				 @elseif($document->doc_type == 'xlsx' )
				<td>
                <a id="MyLink"  style="display:none"></a>
                <input id="Button1" type="button" value="Download" onclick="OpenWord()" style="background-color: #1b191a;color: #fff;"/></td>
				@elseif($document->doc_type == 'NEF' )
				<td>
                <a id="MyLink"  style="display:none"></a>
                <input id="Button1" type="button" value="Download" onclick="OpenWord()" style="background-color: #1b191a;color: #fff;"/></td>
				@else
				 <td><span id="bt" onclick="print('../../../{{$document->storage}}/{{$document->cer_or_delnote}}')" value="Print PDF" /><a title="Print" href="#" class="btn btn-success btn-sm"><i class="fas fa-print"></i></a></span></td>
				 @endif
				@endforeach
            </tr>
        </tbody>
        </table>
            
        </div>
    </div>
    <!-- /.card -->

</section>
<script>
function ImagetoPrint(source) {
    return "<html><head><script>function step1(){\n" +
            "setTimeout('step2()', 10);}\n" +
            "function step2(){window.print();window.close()}\n" +
            "</scri" + "pt></head><body onload='step1()'>\n" +
            "<img src='" + source + "' /></body></html>";
}
function PrintImage(source) {
    Pagelink = "about:blank";
    var pwa = window.open(Pagelink, "_new");
    pwa.document.open();
    pwa.document.write(ImagetoPrint(source));
    pwa.document.close();
}
	</script>
	<script>
	let print = (doc) => {
    	let objFra = document.createElement('iframe');   // Create an IFrame.
        objFra.style.visibility = 'hidden';    // Hide the frame.
        objFra.src = doc;                      // Set source.
        document.body.appendChild(objFra);  // Add the frame to the web page.
        objFra.contentWindow.focus();       // Set focus.
        objFra.contentWindow.print();      // Print it.
    }
    
</script>
  <script>
            function OpenWord() {
                 
                var mylink = document.getElementById("MyLink");
                mylink.setAttribute("href", "../../../{{$document->storage}}/{{$document->cer_or_delnote}}");
                mylink.click();

            }
        </script>
@stop
