<html>
   <body>
      <form id="uploadform" method="POST" action="{{ url('/uploadfile') }}" accept-charset="UTF-8" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input name="des" type="hidden" value="1">
        Select the file to upload.
        <input name="text" type="file">
        <input type="submit" value="Upload File">
      </form>      
   </body>
</html>
