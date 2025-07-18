      <div class="modal" id="Logout">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h3 class="modal-title">Logout</h3>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
       Are you sure you want to logout? 
      </div>
       <form id="" action="{{ route('logout') }}" method="POST" style="">
                @csrf
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
         <button type="submit"  class="btn btn-danger" >Logout</button>
      </div>
      </form>

    </div>
  </div>
</div> 