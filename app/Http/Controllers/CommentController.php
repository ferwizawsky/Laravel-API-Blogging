<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->search ?? null;
        $results = Comment::where('content', 'LIKE', '%' . $search . '%');
        if ($request->post_id) {
            $results = $results->where('post_id', $request->post_id);
        }
        if ($request->user_id) {
            $results = $results->where('user_id', $request->user_id);
        }
        $results = $results
            ->orderBy("created_at", "desc")
            ->paginate($request->limit ?? 10);
        return CommentResource::collection($results);
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            "content" => "required",
            "post_id" => "required",
        ]);

        if (!Post::find($request->post_id)) {
            return response()->json([
                "message" => "Post not found!"
            ], 404);
        }
        $data = Comment::create([
            "content" => $request->content,
            "user_id" => $request->user()?->id ?? 0,
            "post_id" => $request->post_id
        ]);
        return new CommentResource($data);
    }

    public function edit(Request $request, $id)
    {
        $credentials = $request->validate([
            "content" => "required",
        ]);

        $data = Comment::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Comment not found!"
            ], 404);
        }
        if ($data->user_id != $request->user()?->id)
            return response()->json([
                "message" => "Comment not found!"
            ], 404);

        $data->update([
            "content" => $request->content,
        ]);
        return new CommentResource($data);
    }



    public function destroy(Request $request, $id)
    {
        $data = Comment::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Comment not found!"
            ], 404);
        }
        if ($request->user()?->role_id == 0 && $data->user_id != $request->user()?->id)
            return response()->json([
                "message" => "Comment not found!"
            ], 404);

        $data->delete();
        return response()->json([
            "message" => "Success delete Comment"
        ]);
    }
}
