<form method="POST">

<label>Relationship</label>
<select name='relate' class="form-control">
@php ($relatives = Common::Relatives()->get() )
<option value="0">- Select Relationship -</option>
@foreach($relatives as $relative)
    <option value="{{ $relative->id }}">{{ $relative->name }}</option>
@endforeach
</select>

<label>ID Card</label>
<input type='text' name='idnum' value="{{ dir->code ?? '' }}" class="form-control">

<label>Name</label>
<input type='text' name='name' value="{{ dir->name ?? '' }}" class="form-control">

<label>Birthday</label>
<input type='date' name='bdate' value="{{ dir->bdate ?? '' }}" class="form-control">

</form>
