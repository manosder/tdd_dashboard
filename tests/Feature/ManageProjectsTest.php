<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{

    use WithFaker, RefreshDatabase;


    /** @test */
    public function guest_cannot_manage_projects()

    {
        $project = Project::factory()->create();

        //guest cannot view projects
        $this->get('/projects')->assertRedirect('login');
        //guest cannot create projects
        $this->get('/projects/create')->assertRedirect('login');
        //guest cannot see a single project
        $this->get($project->path())->assertRedirect('login');
        //guest cannot create a project
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }


    /** @test */
    public function a_user_can_create_a_project()

    {
        $this->withoutExceptionHandling();

        $this->sighIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => 'General noted here.'
        ];

        $respone = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $respone->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $this->sighIn();

        $this->withoutExceptionHandling();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->patch($project->path(), [
            'notes' => 'Changed'
        ]);

        $this->assertDatabaseHas('projects', ['notes' => 'Changed']);
    }


    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->sighIn();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee((string) $project->title)
            ->assertSee((string) $project->description);
    }


    /** @test */
    public function an_authenticated_user_cannot_view_others_projects()
    {
        $this->sighIn();

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_others_projects()
    {
        $this->sighIn();

        $project = Project::factory()->create();

        $this->patch($project->path(), [])->assertStatus(403);
    }


    /** @test */
    public function a_project_requires_a_title()

    {
        $this->sighIn();

        $attributes = Project::factory()->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }


    /** @test */
    public function a_project_requires_a_description()

    {
        $this->sighIn();

        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
