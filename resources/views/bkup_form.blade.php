@extends('layout')

@section('header')
    <h1>Backup Table</h1>
@stop

@section('content')

<div class="">
    <p>Select a table below to backup to your local machine as a <code>.csv</code> file.</p>
</div>

<form method="post" action="/backup-table/run">

    <div class="form-group">

        <label for="table_select">Select table:</label>
        <select class="form-control" id="table_select" name="table_chosen">
            <option></option>
            @foreach ($tables as $table)
            <option>{{ $table->Tables_in_homestead }}</option>
            @endforeach 
        </select>

    </div>

    <div class="form-group">

        {{ csrf_field() }}
        <button type="submit" class="btn btn-primary">Download</button>
        
    </div>
</form>

@if (count($errors))
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
@endif

@stop