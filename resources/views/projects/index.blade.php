@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between w-full items-start">
            <h2 class="text-grey text-sm font-normal">My Projects</h2>
            <a href="/projects/create" class="button">Add project</a>
        </div>
    </header>

    <main class="lg:flex flex-wrap -mx-3">

        @forelse ($projects as $project)
            <div class="lg:w-1/3 pr-1 pb-6">
                @include('projects.card')
            </div>
        @empty
            <div>No projects yet</div>
        @endforelse

    </main>
@endsection
