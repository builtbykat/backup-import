@extends('layout')

@section('header')
<h1>Import Table</h1>
@stop

@section('content')

<div class="">
    <p>Provide a table name and the <code>.csv</code> file you wish to upload as a database table.</p>
    <p>The first row of the <code>.csv</code> should be the column names of the table.</p>
</div>

<form method="post" action="/import-table/run" enctype="multipart/form-data">

    <div class="form-group">
         <label for="table_name">Table name (optional):</label>
         <input type="text" id="table_name" class="form-control" name="table_name" value="{{old('table_name')}}">
    </div>

     <div class="form-group">
         <label for="import_csv">Upload file:</label>
         <input type="file" id="import_csv" name="import_csv" placeholder="my_db_name">
     </div>

     <div class="form-group">
         {{ csrf_field() }}
         <button type="submit" class="btn btn-primary">Upload</button>
     </div>
     
</form>

@if (count($errors))
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
@endif

@stop