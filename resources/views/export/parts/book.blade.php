<button class="btn btn-secondary col mb-2 text-left text-white" type="button" data-toggle="collapse" data-target="#partbook-div" aria-expanded="true" aria-controls="partbook-div">
    Booking Reference
</button>

<div id="partbook-div" class="col collapse show">

  <div class="form-group row">
    <label for="book.num" class="col-sm-2 col-form-label">No.</label>
    <div class="col-sm-5">
      <input class="form-control"{!! ($inv->bookid ? '' : " id='newbook'") !!} type="text" name="book.num" value="{{ $book->code ?? ''}}">
    </div>

    <label for="book.date" class="col-sm-2 col-form-label">Date</label>
    <div class="col-sm-3">
      <input class="form-control" type="text" name="book.date" value="{{ edate($book->date ?? 0) }}">
    </div>

  </div>

  <div class="form-group row">
    <label for="book.feed" class="col-sm-2 col-form-label">Feeder</label>

    <div class="col-sm-4">
      <input class="form-control" type="text" value="{{ $book->feeder->name ?? '' }}">  
    </div>

    <div class="col-sm-2">
      <input class="form-control" type="text" name="book.feedv" value="{{ $book->feedervoy ?? '' }}" placeholder="Voyage">
    </div>

    <label for="book.feeddate" class="col-sm-1 col-form-label">ETD</label>
    <div class="col-sm-3">
      <input class="form-control" type="text" name="book.feeddate" value="{{ edate($book->etddate ?? $inv->bldate) }}">
    </div>

  </div>

  <div class="form-group row">
    <label for="book.vessel" class="col-sm-2 col-form-label">Carrier</label>
    <div class="col-sm-4">
      <input class="form-control" type="text" name="book.vessel" value="{{ $book->carrier->name ?? '' }}">  
    </div>
    <div class="col-sm-2">
      <input class="form-control" type="text" name="book.vesselv" value="{{ $book->carriervoy ?? '' }}" placeholder="Voyage">
    </div>
    <label for="book.vesseldate" class="col-sm-1 col-form-label">ETA</label>
    <div class="col-sm-3">
      <input class="form-control" type="text" name="book.vesseldate" value="{{ edate($book->etadate ?? 0) }}">
    </div>
  </div>

  <div class="form-group row">
    <label for="book.agent" class="col-4 col-form-label">Booking Agent / Forward</label>
    <div class="col-8">
      <input class="form-control" type="text" name="book.agent" value="{{ $book->agent->name ?? '' }}">  
    </div>
  </div>

</div>
