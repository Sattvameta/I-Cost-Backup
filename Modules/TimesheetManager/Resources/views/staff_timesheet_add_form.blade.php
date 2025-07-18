<table class="table table-bordered">
    <thead>
        <tr class="table-success">
            <th>Activity Code</th>
            <th>Activity</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Hours</th>
            <th width="10%">
                <input type="checkbox" class="select_all" id="select_all" checked>
            </th>
        </tr>
    </thead>
    @if($activities->isNotEmpty())
        @foreach($activities as $activity)
            <tr>
                <input type="hidden" name="activities[{{ $activity->id }}][project_id]" value="{{ $activity->subActivity->mainActivity->project->id }}">
                <input type="hidden" name="activities[{{ $activity->id }}][main_activity_id]" value="{{ $activity->subActivity->mainActivity->id }}">
                <input type="hidden" name="activities[{{ $activity->id }}][sub_activity_id]" value="{{ $activity->subActivity->id }}">
                <input type="hidden" name="activities[{{ $activity->id }}][activity_id]" value="{{ $activity->id }}">
                <td class="tr{{ $activity->id }}">
                    {{ $activity->item_code }}
                </td>
                <td class="tr{{ $activity->id }}">
                    <input type="text" name="activities[{{ $activity->id }}][activity]" value="{{ $activity->activity }}" data-id="{{ $activity->id }}" id="activity{{ $activity->id }}"  class="form-control activity">
                </td>
                <td class="tr{{ $activity->id }}">
                    <input step="any" type="time" value="{{ date('H:i') }}" name="activities[{{ $activity->id }}][start_time]" data-id="{{ $activity->id }}" id="start_time{{ $activity->id }}"  class="form-control start_time">
                </td>
                <td class="tr{{ $activity->id }}">
                    <input step="any" type="time" value="{{ date('H:i') }}" name="activities[{{ $activity->id }}][end_time]" data-id="{{ $activity->id }}" id="end_time{{ $activity->id }}"  class="form-control end_time">
                </td>
                <td class="tr{{ $activity->id }}">
                    <input type="text" value="" name="activities[{{ $activity->id }}][hours]" data-id="{{ $activity->id }}" id="hours{{ $activity->id }}"  class="form-control hours" onkeypress="javascript:return isNumber(event)" readonly>
                    <input type="hidden"  name="activities[{{ $activity->id }}][peoples]" value="1" data-id="{{ $activity->id }}" id="peoples{{ $activity->id }}" onKeyPress="javascript:return isNumber(event)" class="form-control peoples">
                    <input type="hidden"  name="activities[{{ $activity->id }}][total_hours]" value="" data-id="{{ $activity->id }}" id="total_hours{{ $activity->id }}" onKeyPress="javascript:return isNumber(event)" class="form-control total_hours">
                    <input type="hidden"  name="activities[{{ $activity->id }}][selling_cost]" value="{{ $activity->selling_cost }}" data-id="{{ $activity->id }}" id="selling_cost{{ $activity->id }}" onKeyPress="javascript:return isNumber(event)" class="form-control selling_cost">
                    <input type="hidden"  name="activities[{{ $activity->id }}][total_cost]" value="" data-id="{{ $activity->id }}" id="total_cost{{ $activity->id }}" onKeyPress="javascript:return isNumber(event)" class="form-control total_cost">
                </td>
                <td class="tr{{ $activity->id }}">
                    <input name="selected_rows[]" type="checkbox" class="checkbox selected_rows" data-id="{{ $activity->id }}" id="selected_rows{{ $activity->id }}" value="{{ $activity->id }}" checked>
                </td>
            </tr>
        @endforeach
    @endif
</table>
@if($activities->isNotEmpty())
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="site_diaries">File Uploads</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="site_diaries[]" id="site_diaries" multiple>
                    <label class="custom-file-label" for="site_diaries">Choose file</label>
                </div>
            </div>
        </div>
        <!--<div class="col-md-6">
            <div class="form-group">
                <label for="images">Images</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="images[]" id="images" multiple>
                    <label class="custom-file-label" for="images">Choose file</label>
                </div>
            </div>
        </div>-->
    </div>
    <!--<div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="person_photos">Person Photos</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="person_photos[]" id="person_photos" multiple>
                    <label class="custom-file-label" for="person_photos">Choose file</label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="drawings">Drawings</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="drawings[]" id="drawings" multiple>
                    <label class="custom-file-label" for="drawings">Choose file</label>
                </div>
            </div>
        </div>
    </div>-->
	<h4>CO<sub>2</sub> operational record </h4>
	 <table class="table table-bordered">
    <thead>
        <tr class="table-success">
		    <th>Work arrangement</th>
			<th></th>
            <th>Commuting Option</th>
			<th></th>
			<th>Hrs</th>
           
            
        </tr>
    </thead>
            <tr>
			 <td>Home<br>Office<br>Hybrid</td> 
			  <td><input type="checkbox" name="home" id="home" value="1"><br><input type="checkbox" name="office" id="office"  value="1"><br><input type="checkbox" name="hybrid" id="hybrid"  value="1"></td>
              <td>walking<br>cycling <br>public transport<br>car<br>Hybrid</td> 
              <td><input type="checkbox" name="walking" id="walking"  value="1"><br><input type="checkbox" name="cycling" id="cycling"  value="1"><br><input type="checkbox" name="public_transport" id="public_transport"  value="1"><br><input type="checkbox" name="car" id="car"  value="1"><br><input type="checkbox" name="hybrid_commute" id="hybrid_commute" value="1"></td>
			  <td><input type="number" name="walking_text" id="walking_text"><br><input type="number" name="cycling_text" id="cycling_text"><br><input type="number" name="public_transport_text" id="public_transport_text"><br><input type="number" name="car_transport_text" id="car_transport_text"><br><input type="number" name="hybrid_text" id="hybrid_text"></td>
            </tr>
</table>
<div class="form-group">
        <label for="notes">Notes</label>
        <textarea class="form-control" rows="2" name="notes" id="notes"></textarea>
    </div>
<h6><b>Energy consumption</b></h6>
	<table class="table table-bordered">
    <thead>
        <tr class="table-success">
		    <th>Work arrangement</th>
			<th></th>
        </tr>
    </thead>
            <tr>
			 <td>Home<br>Office<br>Hybrid</td> 
			  <td><input type="checkbox" name="home_energy" id="home_energy" value="1"><br><input type="checkbox" name="office_energy" id="office_energy" value="1"><br><input type="checkbox" name="hybrid_energy" id="hybrid_energy" value="1"></td>
            </tr>
</table>
<h6><b>Home</b></h6>
	<table class="table table-bordered">
    <thead>
        <tr class="table-success">
		    <th></th>
			<th>Kwh</th>
        </tr>
    </thead>
            <tr>
			 <td>Electricity<br>Gas</td> 
			  <td><input type="number" name="electricity" id="electricity"><br><input type="number" name="gas" id="gas"></td>
            </tr>
</table>
<h6><b>Equipment Used</b></h6>
 <table class="table table-bordered">
    <thead>
        <tr class="table-success">
			<th></th>
			<th></th>
			<th>energy Kwh</th>
        </tr>
    </thead>
            <tr>
              <td>Laptop<br>Desk Top <br>Others</td> 
              <td><input type="checkbox" name="laptop" id="laptop" value="1"><br><input type="checkbox" name="desktop" id="desktop" value="1"><br><input type="checkbox" name="others" id="others"value="1"></td>
			  <td><input type="number" name="laptop_kwh" id="laptop_kwh"><br><input type="number" name="desktop_kwh" id="desktop_kwh"><br><input type="number" name="others_kwh" id="others_kwh"></td>
            </tr>
</table>
@endif
<script type="text/javascript">
    bsCustomFileInput.init();
</script>