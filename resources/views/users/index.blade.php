@extends('layouts.app')

@section('content')
        <div class="col-md-3 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Users</div>
                <div class="panel-body">
                    <ul id=treeview class="list-group">
                    @php ($n=0)
                    @foreach($users as $user)
                        <li id={{ $user->id }} class="list-group-item">{{ ++$n }}) {{ $user->username }}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-7 pull-right">
          <form id=rec style=display:none;>
            <div class="form-group">
              <label for="id">Record ID</label>
              <input type="text" class="form-control" id="id" placeholder="Record ID" readonly>
            </div>
            <div class="form-group">
              <label for="name">Parent ID</label>
              <input type="text" class="form-control" id="name" placeholder="Display Name">
            </div>
            <div class="form-group">
              <label for="email">Main Group ID</label>
              <input type="email" class="form-control" id="email" placeholder="Enter Main Group ID">
            </div>
            <div class="form-group">
              <label for="dir_id">Serial Number</label>
              <input type="text" class="form-control" id="dir_id" placeholder="Directory ID">
            </div>
            <div class="form-group">
              <label for="sapid">Code</label>
              <input type="text" class="form-control" id="sapid" placeholder="SAP User ID">
            </div>
            <div class="form-group">
              <label for="eagle">Name</label>
              <input type="text" class="form-control" id="eagle" placeholder="Eagle User ID">
            </div>
            <div class="form-group">
              <label for="expiredate">Expired Date</label>
              <input type="date" class="form-control" id="expiredate" placeholder="Active User" readonly>
            </div>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="state">
              <label class="form-check-label" for="state">Deactivate</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>

@endsection

@section('script')
<script>
    $('#treeview li').on("click", function(event) {
        event.stopPropagation();
        var id = $(event.target).attr('id');
        $.get("/users/"+id)
            .done(function(data){
                  alert(data);
                  eval(data);
            });
    });
</script>
@endsection