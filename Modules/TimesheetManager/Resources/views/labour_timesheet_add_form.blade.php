<table class="table-responsive table table-bordered">
    <thead>
        <tr class="table-success">
            <th>Activity Code</th>
            <th>Activity</th>
            <th width="15%">Site Operative</th>
            <th>Allocated/hr</th>
            <th>Spent/hr</th>
            <th>Total Spent/hr</th>
            <th>Remaining/hr</th>
            <th width="10%">
                <input type="checkbox" class="select_all" id="select_all" checked>
            </th>
        </tr>
    </thead>
    @if($activities->isNotEmpty())
        @foreach($activities as $activity)
            @php
                $allocated_hour = $activity->quantity*$activity->subActivity->quantity*$activity->subActivity->mainActivity->quantity;
                $total_spent_hour = "00:00";	
                $remaining_hour = "00:00";
                $timesheet = \Modules\TimesheetManager\Entities\LabourTimesheet::where('project_id', $activity->subActivity->mainActivity->project->id)
                                ->where('activity_id', $activity->id)->first();
                if($timesheet){
                    $total_spent_hour = $timesheet->total_spent_hour ?? "00:00";
                    $remaining_hour = $timesheet->remaining_hour ?? "00:00";	
                }else{
                    $remaining_hour = $allocated_hour;
                }
                $tt = $remaining_hour;
                $a = $remaining_hour;
                if (strpos($a, '.') !== false) {
                    $h = strtok($a, '.');
                    $m = substr(strstr($a, '.'), 1);
                    $tt = str_pad($h, 2, '0', STR_PAD_LEFT).':'.str_pad($m, 2, '0', STR_PAD_LEFT);
                    $a = $tt;
                }  
                if (strpos($a, ':') !== false) {
                    
                }else{
                    $tt = str_pad($tt, 2, '0', STR_PAD_LEFT).':00';	
                }	 
                    
                $remaining_hour = $tt;		
                    
                $aa = $allocated_hour;	 
                $ah = $allocated_hour;
                if (strpos($ah, '.') !== false) {
                    $h = strtok($ah, '.');
                    $m = substr(strstr($ah, '.'), 1);
                    $aa = str_pad($h, 2, '0', STR_PAD_LEFT).':'.str_pad($m, 2, '0', STR_PAD_LEFT);
                    $ah = $aa;
                }  
                if (strpos($ah, ':') !== false) {
                    
                }else{
                    $aa = str_pad($aa, 2, '0', STR_PAD_LEFT).':00';	
                }	 
                    
                $allocated_hour = $aa;		
	
            @endphp
            <tr>
                <input type="hidden" name="activities[{{ $activity->id }}][project_id]" value="{{ $activity->subActivity->mainActivity->project->id }}">
                <input type="hidden" name="activities[{{ $activity->id }}][activity_id]" value="{{ $activity->id }}">
                <td class="tr{{ $activity->id }}">
                    <input type="hidden" name="activities[{{ $activity->id }}][item_code]" value="{{ $activity->item_code }}">
                    {{ $activity->item_code }}
                </td>
                <td class="tr{{ $activity->id }}">
                    <input type="hidden" name="activities[{{ $activity->id }}][activity]" value="{{ $activity->activity }}">
                    {{ $activity->activity }}
                </td>
                <td class="tr{{ $activity->id }}">
                    {{ Form::number('activities['.$activity->id.'][peoples]', 0, [
                        'class' => "form-control peoples",
                        'id' => "peoples".$activity->id,
                        'data-id' => $activity->id,
                        'onkeypress'=>'javascript:return isNumber(event)',
                        'onchange'=> 'prepareLaboursForm(this.value, "'.$activity->item_code.'", '.$activity->id.')',
                    ]) }}
                </td>
                <td class="tr{{ $activity->id }}">
                    <input type="hidden" name="activities[{{ $activity->id }}][allocated_hour]" value="{{ $allocated_hour }}" data-id="{{ $activity->id }}" id="allocated_hour{{ $activity->id }}"  class="allocated_hour">
                    {{ $allocated_hour }}
                </td>
                <td class="tr{{ $activity->id }}">
                    <input type="hidden" name="activities[{{ $activity->id }}][spent_hour]" value="0" data-id="{{ $activity->id }}" id="spent_hour{{ $activity->id }}"  class="spent_hour">
                    <span class="spent_hour_font" id="spent_hour_font{{ $activity->id }}">00:00</span>
                </td>
                <td class="tr{{ $activity->id }}">
                    <input type="hidden" value="{{ $total_spent_hour }}" name="activities[{{ $activity->id }}][real_total_spent_hour]" data-id="{{ $activity->id }}" id="real_total_spent_hour{{ $activity->id }}"  class="real_total_spent_hour">
                    <input type="hidden" name="activities[{{ $activity->id }}][total_spent_hour]'" value="{{ $total_spent_hour }}" data-id="{{ $activity->id }}" id="total_spent_hour{{ $activity->id }}"  class="total_spent_hour">
                    <span class="total_spent_hour_font" id="total_spent_hour_font{{ $activity->id }}">{{ $total_spent_hour }}</span>
                </td>
                <td class="tr{{ $activity->id }}">
                    <input type="hidden" name="activities[{{ $activity->id }}][remaining_hour]'" value="{{ $remaining_hour }}" data-id="{{ $activity->id }}" id="remaining_hour{{ $activity->id }}"  class="remaining_hour">
                    <input type="hidden" name="activities[{{ $activity->id }}][total_hours]'" value="0.0" data-id="{{ $activity->id }}" id="total_hours{{ $activity->id }}"  class="total_hours">
                    <span class="remaining_hour_font" id="remaining_hour_font{{ $activity->id }}">{{ $remaining_hour }}</span>
                </td>
                <td class="tr{{ $activity->id }}">
                    <input name="selected_rows[]" type="checkbox" class="checkbox selected_rows" data-id="{{ $activity->id }}" id="selected_rows{{ $activity->id }}" value="{{ $activity->id }}" checked>
                </td>
            </tr>
            <tr class="labour_row{{ $activity->id }}">
                <td colspan="7" class="labour_table{{ $activity->id }} tr{{ $activity->id }}"></td>
            </tr>
            <tr class="notes_row{{ $activity->id }}" style="display:none">
                <td colspan="7">
                    <div class="form-group">
                        {{ Form::label('notes', 'Notes') }}
                        {{ Form::textarea('activities['.$activity->id.'][notes]', null, [
                            'class' => "form-control",
                            'id' => "notes",
                            'rows'=> 2
                        ]) }}
                    </div>
                </td>
            </tr>
            <tr class="files_row{{ $activity->id }}" style="display:none">
                <td colspan="7">
                    <div class="form-group">
                        <label for="customFile">Files</label>
                        <div class="custom-file">
                            {{ Form::file('activities['.$activity->id.'][files][]', [
                                'class' => "custom-file-input",
                                'id' => "files",
                                'multiple'=> 'multiple'
                            ]) }}
                            <label class="custom-file-label" for="files"></label>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    @endif
</table>
<script type="text/javascript">
    bsCustomFileInput.init();
</script>