@php ( $assetlogs = $asset->logs )
<div class=h5>{{ $asset->des }}</div>
<table class="table table-sm table-hover">
  <thead>
    <tr class="bg-primary text-center">
      <th>Date</th>
      <th>Approval</th>
      <th>Action</th>
      <th>From</th>
      <th>To</th>
    </tr>
  </thead>
  <tbody>
  @foreach($assetlogs as $log)
    <tr data-id="{{ $log->id }}"></tr>
      <td>{{ edate($log->actiondate) ?? '' }}</td>
      <td>{{ $log->doc->doccode ?? '' }}</td>
      <td>{{ $log->actionname[0] }}</td>
      <td>{{ $log->actionname[1]->nm ?? '' }}</td>
      <td>{{ $log->actionname[2]->nm ?? '' }}</td>
      <td></td>
  @endforeach
  </tbody>
</table>