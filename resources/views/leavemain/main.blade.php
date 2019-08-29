@extends('layouts.app')

@section('content')
    @php( $leaves = App\Models\Leave::all()->sortBy('num')->sortBy('par') )
    <div class="container">
        <div class="h3">Standard Leave Profile</div>
        <table>
            <thead>
                <tr align=center>
                    <th>Code</th>
                    <th>Name</th>
                    <th>ชื่อ</th>
                    <th>Pre Notice<br>(Days)</th>
                    <th>Limit<br>(Days*)</th>
                    <th>Max Paid<br>(Days*)</th>
                    <th>Holiday<br>Included</th>
                </tr>
            </thead>
            <tbody>
            @php($par = 0)
            @foreach($leaves as $lv)
                @if($par != $lv->par)
                    <tr>
                        <td colspan=7 class="bg-info text-white">
                            {!! $lv->parent->name !!}
                        </td>
                    </tr>
                    @php( $par = $lv->par )
                @endif
                <tr align=center>
                    <td>{{ $lv->code }}</td>
                    <td align=left class="pr-3"><a href={{ url('leaveitem/edit/' . $lv->id) }}>{{ $lv->name }}</a></td>
                    <td align=left>{{ $lv->tname }}</td>
                    <td>{{ fnum($lv->DaysNotice) }}</td>
                    <td>{{ fnum($lv->DaysLimit) }}</td>
                    <td>{{ fnum($lv->DaysPaid) }}</td>
                    <td><input type="checkbox" {!! ($lv->Consecutive ? ' checked' : '') !!}></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div><small>* per annum</small></div>
    </div>
@endsection

@section('script')

@endsection