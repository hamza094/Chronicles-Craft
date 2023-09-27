<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\BlogController
 */
class BlogControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view(): void
    {
        $blogs = Blog::factory()->count(3)->create();

        $response = $this->get(route('blog.index'));

        $response->assertOk();
        $response->assertViewIs('blog.index');
        $response->assertViewHas('blogs');
    }


    /**
     * @test
     */
    public function create_displays_view(): void
    {
        $response = $this->get(route('blog.create'));

        $response->assertOk();
        $response->assertViewIs('blog.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BlogController::class,
            'store',
            \App\Http\Requests\BlogStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name;
        $content = $this->faker->paragraphs(3, true);
        $visits = $this->faker->word;
        $status = $this->faker->boolean;

        $response = $this->post(route('blog.store'), [
            'name' => $name,
            'content' => $content,
            'visits' => $visits,
            'status' => $status,
        ]);

        $blogs = Blog::query()
            ->where('name', $name)
            ->where('content', $content)
            ->where('visits', $visits)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $blogs);
        $blog = $blogs->first();

        $response->assertRedirect(route('blog.index'));
        $response->assertSessionHas('blog.id', $blog->id);
    }


    /**
     * @test
     */
    public function show_displays_view(): void
    {
        $blog = Blog::factory()->create();

        $response = $this->get(route('blog.show', $blog));

        $response->assertOk();
        $response->assertViewIs('blog.show');
        $response->assertViewHas('blog');
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $blog = Blog::factory()->create();

        $response = $this->get(route('blog.edit', $blog));

        $response->assertOk();
        $response->assertViewIs('blog.edit');
        $response->assertViewHas('blog');
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\BlogController::class,
            'update',
            \App\Http\Requests\BlogUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects(): void
    {
        $blog = Blog::factory()->create();
        $name = $this->faker->name;
        $content = $this->faker->paragraphs(3, true);
        $visits = $this->faker->word;
        $status = $this->faker->boolean;

        $response = $this->put(route('blog.update', $blog), [
            'name' => $name,
            'content' => $content,
            'visits' => $visits,
            'status' => $status,
        ]);

        $blog->refresh();

        $response->assertRedirect(route('blog.index'));
        $response->assertSessionHas('blog.id', $blog->id);

        $this->assertEquals($name, $blog->name);
        $this->assertEquals($content, $blog->content);
        $this->assertEquals($visits, $blog->visits);
        $this->assertEquals($status, $blog->status);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $blog = Blog::factory()->create();

        $response = $this->delete(route('blog.destroy', $blog));

        $response->assertRedirect(route('blog.index'));

        $this->assertModelMissing($blog);
    }
}
