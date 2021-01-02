<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        //persist
        $projects = auth()->user()->projects;

        //redirect
        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }

        //redirect
        return view('projects.show', compact('project'));
    }

    public function store()
    {
        //validate
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        //persist
        auth()->user()->projects()->create($attributes);

        //redirect
        return redirect('/projects');
    }

    public function create()
    {
        return view('projects.create');
    }
}
