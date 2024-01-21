<?php

namespace App\Http\Controllers;

use App\Http\Resources\KelasResource;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\UserKelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search  = $request->search ?? null;
        $results = Kelas::with('students')->where('title', 'LIKE', '%' . $search . '%');

        // if ($request->tag) {
        //     $results = $results->where('tag', $request->tag);
        // }
        // if ($request->status) {
        //     $results = $results->where('status', $request->status);
        // }

        // if ($request->user()?->role_id > 1) {
        //     if ($request->user_id) {
        //         $results = $results->where('user_id', $request->user_id);
        //     }
        // } else {
        //     $results = $results->where('user_id', $request->user()?->user_id);
        // }


        if ($request->kelas_id) {
            $results = $results->where('kelas_id', $request->kelas_id);
        }
        // if ($request->type == 'range') {
        //     $results = $results->whereBetween('created_at', [$request->form, $request->to]);
        // }
        // if ($request->date) {
        //     $results = $results->whereDate('created_at', '=', $request->date);
        // }
        // if ($request->month) {
        //     $results = $results->whereMonth('created_at', '=', $request->month);
        // }
        // if ($request->year) {
        //     $results = $results->whereYear('created_at', $request->year);
        // }

        $results = $results
            ->orderBy("created_at", "desc")
            ->paginate($request->limit ?? 4);
        return KelasResource::collection($results);
    }


    public function store(Request $request)
    {
        $credentials = $request->validate([
            "title" => "required",
        ]);
        $data = Kelas::create([
            "title" => $request->title,
            "code" => $this->generateCode(2),
            "min" => $request->min ?? 0,
            "day" => $request->day ?? "Senin",
            "isUnactive" => $request->isUnactive ?? 0,
            "user_id" => $request->user()?->id ?? 2,
        ]);

        if ($request->students) {
            foreach ($request->students as $student) {
                UserKelas::create([
                    "kelas_id" => $data->id,
                    "user_id" => $student
                ]);
            }
        }
        return new KelasResource($data);
    }

    public function get($id)
    {
        $data = Kelas::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Kelas not found!"
            ], 404);
        }
        return new KelasResource($data);
    }

    public function edit(Request $request, $id)
    {
        $credentials = $request->validate([
            "title" => "required",
            "min" => "required"
        ]);

        $data = Kelas::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Kelas not found!"
            ], 404);
        }
        if ($data->user_id != $request->user()?->id)
            return response()->json([
                "message" => "Kelas not found!"
            ], 404);

        if ($request->students) {
            foreach ($request->students as $student) {
                if (!UserKelas::where("kelas_id", $id)->where('user_id', $student)->first()) {
                    UserKelas::create([
                        "kelas_id" => $id,
                        "user_id" => $student
                    ]);
                }
            }
        }
        $data->update([
            "title" => $request->title,
            "min" => $request->min ?? 0,
            "day" => $request->day ?? "Senin",
            "isUnactive" => $request->isUnactive ?? 0,
        ]);

        return response()->json([
            "message" => "Success edit Kelas"
        ]);
    }


    public function remove(Request $request, $id)
    {
        if ($request->students) {
            foreach ($request->students as $student) {
                $data = UserKelas::where("kelas_id", $id)->where('user_id', $student)->first();
                if ($data) {
                    $data->delete();
                }
            }
        }
        return response()->json([
            "message" => "Success Delete Student from class"
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $data = Kelas::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Kelas not found!"
            ], 404);
        }
        if ($request->user()?->role_id == 1 && $data->user_id != $request->user()?->id)
            return response()->json([
                "message" => "Kelas not found!"
            ], 404);

        $data->delete();
        return response()->json([
            "message" => "Success delete Kelas"
        ]);
    }


    public function generateCode($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        $code = Carbon::now()->timestamp;
        return $code . $randomString;
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
