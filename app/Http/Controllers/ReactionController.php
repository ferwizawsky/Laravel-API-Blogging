<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{

    public function store(Request $request)
    {
        $credentials = $request->validate([
            "post_id" => "required",
        ]);
        $tmp1 = Post::find($request->post_id);
        if (!$tmp1) {
            return response()->json([
                "message" => "Post not found!"
            ], 404);
        }
        $tmp = Reaction::where("post_id", $request->post_id)->where("user_id", $request->user()->id)->first();
        if ($tmp) {
            $tmp->update(["status" => $request->status]);
        } else {
            $reaction = Reaction::create([
                "post_id" => $request->post_id,
                "user_id" => $request->user()->id,
                "status" => $request->status
            ]);
        }
        $data = Post::find($request->post_id);
        return new PostResource($data);
    }
}
