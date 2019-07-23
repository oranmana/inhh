@extends('layouts.app')

@section('content')
@php( $cookie['dirgrp'] = Cookie::get('dir_grp') ?? 0)
@php( $cookie['dirtext'] = Cookie::get('dir_text') ?? '')
@php( $dirgrp = ($_GET['dirgroup'] ?? 0) )
@php( $dirtext = ($_GET['searchtext'] ?? '') )

@php( $grps = App\Models\dir::External()->where('eby','!-',9)->groupBy('grp')->pluck('grp') )
@php( $dirgrps = App\Models\common::whereIn('id', $grps)->orderBy('par')->orderBy('num')->get() )

<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="btn"><a href="/dirs">Directory</a></button>
        <div class="collapse navbar-collapse pull-right">
            <form class="form-inline my-2 my-lg-0" id="dirmenu">
                <div class="form-group">
                    <label class="btn">Group</label>
                    <select name="dirgroup" id="dirgroup" class="form-control" value="{{ $cookie['dirgrp'] ?? 0 }}">
                        @foreach($dirgrps as $dgrp)
                        <option value="{{ $dgrp->id }}"{!! ($dgrp->id == $dirgrp ? " SELECTED" : '') !!}>{{ $dgrp->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <input class="form-control ml-sm-2" type="search" id="searchtext" name="searchtext" placeholder="Search" aria-label="Search" value="{{ $dirtext ?? '' }}">
                    <button id="gosearch" class="btn btn-outline-success ml-2 my-sm-0" type="submit">Search</button>
                </div>
            </form>
        </div>
    </nav>
    <table class="table table-stripped">
        <thead>
            <tr>
                <th class="w-33 text-center">Name</th>
                <th class="w-33 text-center">ชื่อ</th>
                <th class="w-33 text-center">Tel</th>
            </tr>
        </thead>
        <tbody id="tbody">
            @php( $dirs = \App\Models\Dir::OfGroup($dirgrp)->NameIn($dirtext)->orderBy('name')->get() )
            @include('dirs.dirlist', compact('dirs'))
        </tbody>
    </table>
    {{-- $dirs->appends(request()->query())->links() --}}
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#search').on('click', function() {
            var dir_grp = $('#dirgroup option:selected').val();
            var dir_text = $('#searchtext').val();
            document.cookie = "dir_grp="+ (dir_grp ? dir_grp : 0) +";";
            document.cookie = "dir_text="+ (dir_text > '' ? dir_text : "''") +";";
        });
    });
</script>    
@endsection