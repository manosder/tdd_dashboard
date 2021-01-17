@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between w-full items-start">
            <p class="text-grey text-sm font-normal">
                <a class="text-grey text-sm font-normal no-underline" href="/projects">My Projects</a> /
                {{ $project->title }}
            </p>

            <a href="/projects/create" class="button">Add project</a>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <h2 class="text-lg text-grey font-normal mb-3">Tasks</h2>
                    {{-- Tasks--}}
                    @foreach ($project->tasks as $task)
                        <div class="card mb-3">

                            <form action="{{ $task->path() }}" method="post">
                                @method('PATCH')
                                @csrf

                                <div class="flex">
                                    <input type="text" name="body" id="" value="{{ $task->body }}"
                                        class="w-full {{ $task->completed ? 'text-grey' : '' }}"
                                        style="border-style: none;">
                                    <input type="checkbox" name="completed" id="" onchange="this.form.submit()"
                                        {{ $task->completed ? 'checked' : '' }}>
                                </div>
                            </form>

                        </div>
                    @endforeach

                    <div class="card mb-3">
                        <form action="{{ $project->path() . '/tasks' }}" method="post">
                            @csrf
                            <input type="text" name="body" placeholder="Add a task..." class="w-full"
                                style="border-style: none;">
                        </form>
                    </div>
                </div>

                <div class="w-5/6">
                    <h2 class="text-lg text-grey font-normal mb-3">General Notes</h2>
                    {{-- General Notes--}}

                    <form action="{{ $project->path() }}" method="post">
                        @csrf
                        @method('PATCH')
                        <textarea name="notes" class="card w-full mb-4" style="min-height: 200px;border-style: none;"
                            placeholder="Anything special that you want to make a note of?">{{ $project->notes }}</textarea>

                        <button class="button" type="submit">Save</button>
                    </form>

                </div>

            </div>
            <div class="lg:w-1/4 px-3">


                @include('projects.card')


            </div>
        </div>

    </main>

@endsection
