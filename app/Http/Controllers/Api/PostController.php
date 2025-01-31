<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePostRequest;
use App\Http\Requests\Api\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\ShowPostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return PostResource::collection(Post::with('user')->latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = auth()->user()->posts()->create($request->validated());

        return response()->json(new PostResource($post), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): ShowPostResource
    {
        return new ShowPostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        if (!Gate::allows('update-or-delete-post', $post)) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You do not have permission to update this post.'
            ], 403);
        }

        $post->update($request->validated());

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => new PostResource($post)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        if (!Gate::allows('update-or-delete-post', $post)) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You do not have permission to delete this post.'
            ], 403);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }
}
