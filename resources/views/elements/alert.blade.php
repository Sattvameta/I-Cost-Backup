@push('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			@if(session('status') && session('message'))
				@if(session('status') == 'success')
				    SYSAPI.Message.toastrSuccess("{{ session('message') }}");
				@else
				    SYSAPI.Message.toastrError("{{ session('message') }}");
				@endif
			@endif
		});
	</script>
@endpush