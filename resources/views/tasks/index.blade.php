@extends('layouts.app')

@section('content')
<div class="container">
    <trello :initial-data="{{ $tasks }}" ></trello>
    teste
</div>
@endsection