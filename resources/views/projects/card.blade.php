<div class="card" style="height: 200px">
    <h3 class="font-normal text-xl py-4 -ml-5 border-heading pl-4">
        <a href="{{ $project->path() }}" class="text-black no-underline">{{ $project->title }}</a>
    </h3>
    <div class="text-grey">{{ Str::limit($project->description) }}</div>
</div>
