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
        //$this->actingAs(User::factory()->create());

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $respone = $this->post('/projects', $attributes);

        $respone->assertRedirect(Project::where($attributes)->first()->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }


    /** @test */
    public function a_user_can_view_their_project()
    {
        $this->sighIn();

        $this->withoutExceptionHandling();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee((string) $project->title)
            ->assertSee((string) $project->description);
    }


    /** @test */
    public function an_authenticated_user_cannot_view_others_projects()
    {
        $this->sighIn();

        // $this->withoutExceptionHandling();

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);
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
