<?php

namespace App\Http\Controllers;

use App\Http\Resources\JadwalUjianResource;
use App\Http\Resources\KelasResource;
use App\Models\JadwalUjian;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $today = Carbon::now()->toDateString();

        // Query the model for records where the date is today or in the future
        // $futureRecords = YourModel::whereDate('your_date_column', '>=', $today)->get();

        $results = JadwalUjian::whereDate('day', '>=', $today);

        if ($request->user()?->role_id > 1) {
            if ($request->user_id) {
                $resultss = $results->where('user_id', $request->user_id);
            };
        } else {
            $results = $results->where('user_id', $request->user()?->user_id);
        }

        if ($request->kelas_id) {
            $results = $results->where('kelas_id', $request->kelas_id);
        }

        $results = $results->paginate($request->limit ?? 10);
        return  JadwalUjianResource::collection($results);
    }



    public function store(Request $request)
    {
        $credentials = $request->validate([
            'title' => 'required',
            'day' => 'required|date',
            'kelas_id' => "required"
        ]);

        $data = JadwalUjian::create([
            "day" => date('Y-m-d', strtotime('2024-01-15')),
            "title" => "UAS",
            "kelas_id" => $request->kelas_id,
            "user_id" => $request->user()?->id ?? 2
        ]);

        return response()->json([
            "message" => "Success",
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        $credentials = $request->validate([
            "status" => "required",
        ]);

        $data = JadwalUjian::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Jadwal Ujian not found!"
            ], 404);
        }

        if ($data->user_id != $request->user()?->id)
            return response()->json([
                "message" => "Jadwal Ujian not found!"
            ], 404);

        $data->update([
            "status" => $request->status,
        ]);

        return response()->json([
            "message" => "Success edit Jadwal Ujian"
        ]);
    }


    public function destroy(Request $request, $id)
    {
        $data = JadwalUjian::find($id);
        if (!$data) {
            return response()->json([
                "message" => "JadwalUjian not found!"
            ], 404);
        }
        if ($request->user()?->role_id == 1 && $data->user_id != $request->user()?->id)
            return response()->json([
                "message" => "Jadwal Ujian not found!"
            ], 404);

        $data->delete();
        return response()->json([
            "message" => "Success delete Jadwal Ujian"
        ]);
    }
}
