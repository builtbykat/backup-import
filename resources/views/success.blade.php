@extends('layout')

@section('header')
    <h1>Successful upload!</h1>
@stop

@section('content')
    <p>{{ count($rows) }} records inserted.</p>
    <pre>
          @foreach ($sliced as $index=>$value)
            {{ json_encode($value, JSON_PRETTY_PRINT) }}
          @endforeach
          <p>+{{ count($rows) - count($sliced) }} more...</p>
    </pre>
@stop