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
        $results = Post::where('title', 'LIKE', '%' . $search . '%')
            ->orWhere('content', 'LIKE', '%' . $search . '%')
            ->orWhere('tag', 'LIKE', '%' . $search . '%');

        if ($request->tag) {
            $results = $results->where('tag', $request->tag);
        }
        if ($request->status) {
            $results = $results->where('status', $request->status);
        }
        if ($request->user_id) {
            $results = $results->where('user_id', $request->user_id);
        }
        // if ($request->type == 'range') {
        //     $results = $results->whereBetween('created_at', [$request->form, $request->to]);
        // }
        if ($request->date) {
            $results = $results->whereDate('created_at', '=', $request->date);
        }
        if ($request->month) {
            $results = $results->whereMonth('created_at', '=', $request->month);
        }
        if ($request->year) {
            $results = $results->whereYear('created_at', $request->year);
        }

        $results = $results
            ->orderBy("created_at", "desc")
            ->paginate($request->limit ?? 10);
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
        if (!$data) {
            return response()->json([
                "message" => "Post not found!"
            ], 404);
        }

        if ($data->user_id != $request->user()?->id)
            return response()->json([
                "message" => "Post not found!"
            ], 404);

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
