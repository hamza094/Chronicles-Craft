<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CommentController
 */
class CommentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CommentController::class,
            'store',
            \App\Http\Requests\CommentStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects(): void
    {
        $body = $this->faker->text;
        $approve = $this->faker->boolean;

        $response = $this->post(route('comment.store'), [
            'body' => $body,
            'approve' => $approve,
        ]);

        $comments = Comment::query()
            ->where('body', $body)
            ->where('approve', $approve)
            ->get();
        $this->assertCount(1, $comments);
        $comment = $comments->first();

        $response->assertRedirect(route('comment.index'));
        $response->assertSessionHas('comment.id', $comment->id);
    }


    /**
     * @test
     */
    public function edit_displays_view(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->get(route('comment.edit', $comment));

        $response->assertOk();
        $response->assertViewIs('comment.edit');
        $response->assertViewHas('comment');
    }


    /**
     * @test
     */
    public function destroy_deletes_and_redirects(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->delete(route('comment.destroy', $comment));

        $response->assertRedirect(route('comment.index'));

        $this->assertModelMissing($comment);
    }
}
