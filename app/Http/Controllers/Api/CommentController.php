<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCommentRequest;
use App\Http\Resources\ShowCommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Post $post): JsonResponse
    {
        $user = Auth::guard('sanctum')->user();

        $comment = $post->comments()->create([
            'user_id' => $user?->id,
            'comment' => $request->get('comment')
        ]);

        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => new ShowCommentResource($comment)
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        if (! Gate::allows('delete-comment', $comment)) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You do not have permission to delete this comment.'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
