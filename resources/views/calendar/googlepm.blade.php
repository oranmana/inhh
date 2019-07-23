@extends('layouts.app')
@section('style')
<!-- <link rel="stylesheet" href="https://fullcalendar.io/releases/4.0.0-beta.2/packages/core/main.css">
<link rel="stylesheet" href="https://fullcalendar.io/releases/4.0.0-beta.2/packages/daygrid/main.css">
<link rel="stylesheet" href="https://fullcalendar.io/releases/4.0.0-beta.2/packages/list/main.css"> -->

<!-- <link ref="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.css"> -->

<!-- <script src="https://fullcalendar.io/releases/4.0.0-beta.2/packages/core/main.js"></script>
<script src="https://fullcalendar.io/releases/4.0.0-beta.2/packages/daygrid/main.js"></script>
<script src="https://fullcalendar.io/releases/4.0.0-beta.2/packages/list/main.js"></script>
<script src="https://fullcalendar.io/releases/4.0.0-beta.2/packages/google-calendar/main.js"></script> -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.0.0-beta.2/packages/core/main.css">

<style>
html, body {
  margin: 0;
  padding: 0;
  font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
  font-size: 14px;
}

#calendar {
  max-width: 900px;
  margin: 40px auto;
}
</style>
@endsection

@section('content')

<div id='calendar'></div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.0/fullcalendar.min.js"></script>
<script>

document.addEventListener('DOMContentLoaded', function() {

  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    plugins: [ 'dayGrid', 'list', 'googleCalendar' ],
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'month,listYear'
    },

    displayEventTime: false, 
    //googleCalendarApiKey: 'aGFud2hhdGguY29tX3Zsc3JiNGdoY3MzZjNvcTQ4bzh0Y3NvOWJzQGdyb3VwLmNhbGVuZGFyLmdvb2dsZS5jb20',
    googleCalendarApiKey: 'AIzaSyBb0gqg2jXrNrNLYDdOLjZ42uHyISpEekY',
    events: 'hanwhath.com_vlsrb4ghcs3f3oq48o8tcso9bs@group.calendar.google.com',

    eventClick: function(event) {
      window.open(event.url, '_blank', 'width=700,height=600');
      return false;
    }

  });

  calendar.render();
});

</script>
@endsection
