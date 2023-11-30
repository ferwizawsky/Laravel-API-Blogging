<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        // $search  = $request->search ?? null;
        $results = Booking::orderBy("created_at", "desc");
        if ($request->event_id) {
            $results = $results->where('event_id', $request->event_id);
        }
        if ($request->user_id) {
            $results = $results->where('user_id', $request->user_id);
        }
        $results = $results
            ->paginate($request->limit ?? 10);
        return BookingResource::collection($results);
    }

    public function store(Request $request)
    {
        $request->validate([
            "event_id" => "required",
        ]);
        $tmp = Event::find($request->event_id);
        if (!$tmp)
            return response()->json([
                "message" => "Event not found!"
            ], 404);
        if (count($tmp->booking) >= $tmp->slot)
            return response()->json([
                "message" => "Slot Event is Full!"
            ], 422);
        $tmp = Booking::where('user_id', $request->user()?->role_id == 0 ? $request->user()?->id : $request->user_id)
            ->whereHas('event', function ($query) {
                $query->whereDate('time', '>', today());
            })
            ->get();

        if (count($tmp))
            return response()->json([
                "message" => "User already book Event in present!"
            ], 422);

        $data = Booking::create([
            "event_id" => $request->event_id,
            "user_id" => $request->user()?->role_id == 0 ? $request->user()?->id : $request->user_id,
        ]);

        return response()->json([
            "message" => "Success",
            "data" => new BookingResource($data)
        ], 200);
    }


    public function get($id)
    {
        $data = Booking::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Booking not found!"
            ], 404);
        }
        return new BookingResource($data);
    }



    public function destroy(Request $request, $id)
    {
        $data = Booking::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Booking not found!"
            ], 404);
        }
        if ($request->user()?->role_id == 0 && $data->user_id != $request->user()?->id)
            return response()->json([
                "message" => "Booking not found!"
            ], 404);

        $data->delete();
        return response()->json([
            "message" => "Success delete Booking"
        ]);
    }
}
