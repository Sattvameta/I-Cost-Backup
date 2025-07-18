<script>
@if(session()->has('success'))
    $.alert({
        title: 'Success',
        icon: 'fa fa-checkmark',
        type: 'green',
        content: "{{session()->get('success')}}"
    });
@elseif(session()->has('error'))
    $.alert({
        title: 'Error',
        icon: 'fa fa-warning',
        type: 'red',
        content: "{{session()->get('error')}}"
    });
@endif
</script>