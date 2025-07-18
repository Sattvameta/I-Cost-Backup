@if($timesheets->isNotEmpty())
    @foreach($timesheets as $timesheet)
        <tr class="expandable">
            <td><input type="button" class="btn btn-primary btn-sm expandable-input" value="+"></td>
            <td>{{ $timesheet->sub_code }}</td>
            <td>{{ $timesheet->timesheet_date }}</td>
            <td>{{ $timesheet->item_code }}</td>
            <td>{{ $timesheet->activity }}</td>
            <td>{{ $timesheet->peoples }}</td>
            <td>{{ $timesheet->allocated_hour }}</td>
            <td>{{ $timesheet->spent_hour }}</td>
            <td>{{ $timesheet->total_spent_hour }}</td>
            <td>
                @php
                    $tsh = strtok($timesheet->total_spent_hour, ':');
                    $ah = strtok($timesheet->allocated_hour, ':');
                @endphp
                @if($tsh > $ah)
                    <font style="color: red;">{{ $timesheet->remaining_hour }}</font>
                @else
                    {{ $timesheet->remaining_hour }}
                @endif
            </td>
            <td width="14%">
                @if(auth()->user()->can('access', 'timesheets add'))
                    <a title="Edit time sheet" href="{{ route('timesheets.labour.edit', $timesheet->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a title="Delete time sheet" href="{{ route('timesheets.labour.delete', $timesheet->id) }}" onclick="return confirm('Are you sure want to remove the timesheet?')" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i>
                    </a>
                @endif
                <a title="Print time sheet" href="{{ route('timesheets.labour.print', $timesheet->id) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-print"></i>
                </a>
            </td>
        </tr>
        <tr>
            <td colspan="11" class="expandable">
                <table class="table-responsive table table-bordered" style="width:90%;margin: auto">
                    <thead class="table-info">
                        <tr>
                            <th>Lab Code</th>
                            <th>Operative</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Hours</th>
                            <th>Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($timesheet->timesheetMaterials->isNotEmpty())
                            @foreach($timesheet->timesheetMaterials as $timesheetMaterial)
                                <tr>
                                    <td>{{ $timesheetMaterial->lab_code }}</td>
                                    <td>{{ $timesheetMaterial->operative }}</td>
                                    <td>{{ $timesheetMaterial->start_time }}</td>
                                    <td>{{ $timesheetMaterial->end_time }}</td>
                                    <td>{{ $timesheetMaterial->hours }}</td>
                                    <td>{{ $timesheetMaterial->rate }}</td>
                                </tr>
                            @endforeach
                        @endif
                        @isset($timesheet->notes)
                            <tr>
                                <td colspan="6" class="text-center">Note:{{ $timesheet->notes }}</td>
                            </tr>
                        @endisset
                        @if($timesheet->timesheetFiles->isNotEmpty())
                            <tr>
                                <td colspan="5">
                                    Files:
                                </td>
                                <td>
                                    <a title="View file" href="{{ route('timesheets.gallery.labour', $timesheet->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </td>
        </tr>
    @endforeach
@endif