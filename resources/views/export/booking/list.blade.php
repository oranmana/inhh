@php( $ofm = $yymm.'01' )
@php( $books = \App\Models\mkbooking::YYMM($yymm)->orderBy('bookdate','desc')->get() )

@foreach($books as $book) 
  <tr class="bookrow" data-id="{{ $book->id }}">
    <td>{{ edate($book->bookdate,13) }}</td>
    <td>{{ $book->code }}</td>
    <td>{{ ($book->qlcl ? $book->qlcl : '-') }}</td>
    <td>{{ ($book->q20 ? $book->q20 : '-') }}</td>
    <td>{{ ($book->q20h ? $book->q20h : '-') }}</td>
    <td>{{ ($book->q40 ? $book->q40 : '-') }}</td>
    <td>{{ ($book->q40h ? $book->q40h : '-') }}</td>
    <td>{{ $book->agent->nm > '' ? $book->agent->nm : substr($book->agent->name, 0, 15) }}</td>
    <td>{!! $book->FeederName !!}</td>
    <td>{{ cpday($ofm, $book->receivedate) }}</td>
    <td>{{ cpday($ofm, $book->returndate) }}</td>
    <td>{{ edate($book->etddate,13) }}</td>
    <td>{{ $book->inv->invnum }}</td>
    <td>{{ $book->inv->pic->name }}</td>
  </tr>
@endforeach
