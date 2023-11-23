<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search  = $request->search ?? null;
        $results = Post::where('title', 'LIKE', '%' . $search . '%')->orderBy("created_at", "desc")
            ->paginate(10);
        return PostResource::collection($results);
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'picture' => 'mimes:jpeg,jpg,png',
            "title" => "required",
            "content" => "required",
        ]);
        $img = null;
        // if ($request->picture) {
        //     $img = $this->uploadFile($request->picture, $request->user()?->username ?? "tester");
        // }
        $data = Post::create([
            "title" => $request->title,
            "content" => $request->content,
            "tag" => $request->tag,
            "status" => $request->status,
            "picture" => $img['path'] ?? null,
            "user_id" => $request->user()?->id ?? 1,
        ]);

        return response()->json([
            "message" => "Success",
            "data" => $data
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        $credentials = $request->validate([
            'picture' => 'mimes:jpeg,jpg,png',
            "title" => "required",
            "content" => "required",
        ]);
        $img = null;
        // if ($request->picture) {
        //     $img = $this->uploadFile($request->picture, $request->user()?->username ?? "tester");
        // }
        $data = Post::find($id);
        $data->update([
            "title" => $request->title,
            "content" => $request->content,
            "tag" => $request->tag,
            "status" => $request->status,
        ]);

        return response()->json([
            "message" => "Success edit Post"
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $data = Post::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Post not found!"
            ], 404);
        }
        if ($request->user()?->role_id == 0 && $data->user_id != $request->user()?->id)
            return response()->json([
                "message" => "Post not found!"
            ], 404);

        $data->delete();
        return response()->json([
            "message" => "Success delete Post"
        ]);
    }

    // public function uploadFile($file, $user)
    // {
    //     $filename = $file->getClientOriginalName();
    //     $ext = $file->getClientOriginalExtension();
    //     $newFilename = 'img-' . rand() . '-' . time() . '.' . $ext;
    //     // Image::configure(['driver' => 'gd']);
    //     // $foto = Image::make($file);
    //     // $foto->resize(480, null, function ($constraint) {
    //     //     $constraint->aspectRatio();
    //     // });
    //     $path = Storage::disk('local')->put('image/' . $user . '/' . $newFilename, $file);

    //     return  [
    //         "path" =>  $path,
    //         "name" => $newFilename
    //     ];
    // }
}
