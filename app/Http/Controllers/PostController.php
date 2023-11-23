<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'picture' => 'mimes:jpeg,jpg,png',
            "title" => "required",
            "content" => "required",
        ]);
        $img = $this->uploadFile($request->picture, $request->user()?->username ?? "tester");
        $data = Post::create([
            "title" => $request->title,
            "content" => $request->content,
            "tag" => $request->tag,
            "status" => $request->status,
            "picture" => $img['path'],
            "user_id" => $request->user()?->id ?? 1,
        ]);

        return response()->json([
            "message" => "Success",
            "data" => $data
        ], 200);
    }



    public function uploadFile($file, $user)
    {
        $filename = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();
        $newFilename = 'img-' . rand() . '-' . time() . '.' . $ext;
        // Image::configure(['driver' => 'gd']);
        // $foto = Image::make($file);
        // $foto->resize(480, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // });
        $path = Storage::disk('local')->put('image/' . $user . '/' . $newFilename, $file);

        return  [
            "path" =>  $path,
            "name" => $newFilename
        ];
    }
}
