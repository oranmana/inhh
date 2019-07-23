@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div>
                <div>{!!( $pars ? "<a href=\"/" . $pars[0]->par . "/commons\">" .$pars[0]->name . "</a>" : 'Common') !!}</div>
                <div>
                    <ol id=treeview>
                    @foreach($commons as $common)
    <li class="list-number{!! ($common->group ? ' haschild' : '') !!}" id={{ $common->id }} >
                            <a>{!! $common->name . ($common->group ? ' >>' : '') !!} </a>
                        </li>
                    @endforeach
</ol>
                </div>
            </div>
        </div>
        <div class="col-md-8 pull-right">
            @include('commons.form')
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $("li.haschild").on("click", function(event) {
        event.stopPropagation();
        var id = $(event.target).attr('id');
        var location = "{{ URL::to('/') }}/" + id + "/commons/";
        window.location = location;
    });
    $('#treeview li a').on("click", function(event) {
        event.stopPropagation();
        var id = $(event.target).closest('li').attr('id');
        $.get("/commons/"+id)
            .done(function(data){
                  eval(data);
            });
    });
</script>
@endsection
