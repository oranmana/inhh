<div dtaa-id="{{ $doc->id }}" class="docuploadarea">
  <form action="">
    <table class="table table-sm  table-borderless">
      <tr>
        <td class="text-info">Number</td>
        <td>{{ $doc->doccode }}</td>
        <td class="text-info">Date</td>
        <td>{!! edate($doc->indate) !!}</td>
      </tr>
      <tr>
        <td class="text-info">Under</td>
        <td>{{ $doc->org->name }}</td>
        <td class="text-info">Type</td>
        <td>{{ $doc->doctype->name }}</td>
      </tr>
      <tr>
        <td class="text-info">Subject</td>
        <td colspan=3>{!! $doc->name !!}
      </tr>
    </table>
  </form>
  <table class="table table-sm">
    <thead>
      <tr class="bg-info">
        <th>No.</th>
        <th>Subject</th>
      </tr>
    </thead>
    <tbody>
    @php($ln=1)
    @php($files = $doc->FilesUploaded)
    @if($files)
      @foreach($files as $file)
        <tr>
          <td>{{ $ln++ }}</td>
          <td><a href="{{ $file->fullname }}" target="_blank">{{ $file->name }}</td>
        </tr>
      @endforeach
    @endif
    </tbody>
  </table>
  <div>
    <form id="uploadform" method="POST" action="{{ url('doc/upload') }}" enctype="multipart/form-data">
      {{ csrf_field() }}
      <input type="hidden" name="docid" id="docid" value="{{ $doc->id }}">
      Select File to upload :<br>
      <input type="file" name="filedata">
      <input type="submit" value="Upload">
    </form>
  </div>

</div>
