<?php

namespace App\Http\Controllers;

use App\Http\Resources\AbsensiResource;
use App\Http\Resources\KelasResource;
use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $groupedData = Absensi::where("kelas_id", $request->kelas_id ?? 3)->selectRaw('*, COUNT(*) as total')
            ->groupBy('kelas_id', 'user_id')
            ->get();
        $kelas = Kelas::find($request->kelas_id ?? 3);
        // return $groupedData;
        return response()->json([
            "kelas" => new KelasResource($kelas),
            // "kelas" => $kelas,
            "absensi" => AbsensiResource::collection($groupedData)
        ]);
    }

    public function indexDetail(Request $request)
    {
        $results = Absensi::where("kelas_id", $request->kelas_id ?? 3);


        if ($request->date) {
            $results = $results->whereDate('created_at', '=', $request->date);
        }


        if ($request->user_id) {
            $results = $results->where('user_id', $request->user_id);
        }

        $results = $results->get();
        return response()->json([
            "absensi" => AbsensiResource::collection($results)
        ]);
    }


    public function store(Request $request)
    {
        $credentials = $request->validate([
            'user' => 'required|array',
            'kelas_id' => 'required'
        ]);

        foreach ($request->user as $user) {
            $data = Absensi::create([
                "status" => $user['status'] ?? null,
                "kelas_id" => $request->kelas_id,
                "user_id" => $user['id'],
            ]);
        }

        return response()->json([
            "message" => "Success",
        ], 200);
    }



    public function cukupkan(Request $request)
    {
        $credentials = $request->validate([
            'user' => 'required|array',
            'kelas_id' => 'required'
        ]);

        $data = Kelas::with('absensis')->find($request->kelas_id);
        if (!$data) {
            return response()->json([
                "message" => "Kelas not found!"
            ], 404);
        }
        // $tmp = [];
        foreach ($request->user as $user) {
            $kali = $request->kali ?? $data->min - count($data->absensis->where('user_id', $user['id']));
            for ($x = 0; $x < $kali; $x++) {
                Absensi::create([
                    "status" => $user['status'] ?? null,
                    "kelas_id" => $request->kelas_id,
                    "user_id" => $user['id'],
                ]);
            }
        }
        return response()->json([
            "message" => "Success",
        ], 200);
    }

    public function edit(Request $request, $id)
    {
        $credentials = $request->validate([
            "status" => "required",
        ]);

        $data = Absensi::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Absensi not found!"
            ], 404);
        }

        if ($data->user_id != $request->user()?->id)
            return response()->json([
                "message" => "Absensi not found!"
            ], 404);

        $data->update([
            "status" => $request->status,
        ]);

        return response()->json([
            "message" => "Success edit Absensi"
        ]);
    }


    public function destroy(Request $request, $id)
    {
        $data = Absensi::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Absensi not found!"
            ], 404);
        }
        if ($request->user()?->role_id == 1 && $data->user_id != $request->user()?->id)
            return response()->json([
                "message" => "Absensi not found!"
            ], 404);

        $data->delete();
        return response()->json([
            "message" => "Success delete Absensi"
        ]);
    }
}
