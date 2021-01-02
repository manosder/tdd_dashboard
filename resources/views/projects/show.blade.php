@extends('layouts.app')

@section('content')
    <h1>Birdboard</h1>

    <h1>{{ $project->title }}</h1>
    <div class="description">{{ $project->description }}</div>

    <a href="/projects">Go Back</a>
@endsection
