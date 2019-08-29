@extends('layouts.app')

@section('content')
    @php( $leave = App\Models\Leave::find($lvid) )
<div class="container">
    <div class="h3"><a href="{{ url('leaveitem') }}">Leave Profile Edit</a> </div>
    <form method="POST" action="{{ url('leaveitem/update')}}">
        <label>ID</label>
        <input type="textbox" class="form-control" name="id" value="{{ $leave->id ?? '' }}" readonly>
        <label>Code</label>
        <input type="textbox" class="form-control" name="code" value="{{ $leave->code ?? '' }}" >
        <label>Name</label>
        <input type="textbox" class="form-control" name="ename" value="{{ $leave->name ?? '' }}" >
        <label>ชื่อ</label>
        <input type="textbox" class="form-control" name="tname" value="{{ $leave->tname ?? '' }}" placeholder="Name in Thai" >
        <label>Pre-Notice for Leave Request (Days)</label>
        <input type="number" class="form-control" name="notice" value="{{ $leave->des ?? 0 }}" placeholder="Before noon of the date return to work" >
        <label>Limit Number of Day per Year</label>
        <input type="textbox" class="form-control" name="limit" value="{{ $leave->sub ?? 0 }}" >
        <label>Maximum Paid Days per Year</label>
        <input type="textbox" class="form-control" name="paid" value="{{ $leave->dir ?? 0 }}" >
        <input type="checkbox" name="holiday" {{ $leave->gl ? ' checked' : '' }}>
        <label>Consecutively with Holiday</label>
        <div class="text-right">
        <input type="button" id="btncancel" class="pl-3 pr-3 btn-danger" value="Cancel" >
        <input type="submit" id="btnsave" class="pl-3 pr-3 btn-primary" value="Update">
        </div>
    </form>
</div>
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#btncancel').on('click', function() {
            window.location="{{ url('leaveitem') }}";
        });
        $('#btnsave').on('click', function() {
            if (confirm('Are you sure to update above detail ?'))
                return true;
        });
    });
</script>
@endsection