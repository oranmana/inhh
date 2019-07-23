@extends('layouts.app')

@section('content')
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <form class="navbar-form navbar-left my-2 my-lg-0 form-inline">
            <div class="form-group">
                <button class="btn"><a href="/emps">Employees</a></button>
                <select id=activeemp class="form-control" >
                    <option value=0{!! $quit==0 ? " SELECTED" : '' !!}>Active</option>
                    <option value=1{!! $quit==1 ? " SELECTED" : '' !!}>Inactive</option>
                </select> 
                <select id=cmemp class="form-control">
                    <option value=0>-All Group-</option>
                    <option value=553{!! $cm==553 ? " SELECTED" : '' !!}>KR</option>
                    <option value=554{!! $cm==554 ? " SELECTED" : '' !!}>Of</option>
                    <option value=3186{!! $cm==3186 ? " SELECTED" : '' !!}>Wf</option>
                    <option value=9637{!! $cm==9637 ? " SELECTED" : '' !!}>Cn</option>
                </select>
            </div>
        </form>
        <div class="collapse navbar-collapse pull-right col-3">
                <input class="form-control mr-sm-2" type="search" id="searchemp" name="searchtext" placeholder="Search" aria-label="Search" value="{{ $searchtxt ? $searchtxt : '' }}">
        </div>
    </nav>
    @if (count($emps))
    <table class="table table-stripped table-sm">
        <thead>
            <th>Name</th>
            <th>ชื่อ</th>
            <th>Employed</th>
            <th>Org</th>
            <th>Job Title</th>
        </thead>
        @foreach($emps as $emp)
        <tr class="row_dir" >
            <td>
                <a href="/emps/{{ $emp->id }}">{{ $emp->name }} </a>
            </td>
            <td>
                <a>{{ $emp->thname }} </a>
            </td>
            <td>
                <a>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $emp->indate)->format('d-M-Y') }}</a>
            </td>
            <td>
                <a>{{ $emp->orgcode }} </a>
            </td>
            <td>
                <a>{{ $emp->jobname }} </a>
            </td>
            <td>
                <a>{{ $emp->tel }} </a>
            </td>
        </tr>
        @endforeach
    </table>
    {{ $emps->appends(request()->query())->links() }}
    @else
    <center><h3>No Data Found</h3></center>
    @endif
</div>
@endsection

@section('script')
<script>
    $("#activeemp, #cmemp").on("change", function (e) {
        window.location.href = '/emps/' + $('#activeemp').val() + '/' + $('#cmemp').val();
    });    
    $('#searchemp').on('change',function() {
        var stxt = $(this).val();
        var url = "{{ url('/emps')}}" + "/" + {{ $quit }} + "/" + {{ $cm }} + "/" + stxt;
        window.location.href = url;
    })
</script>
@endsection
