<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->search ?? null;
        $results = Event::where('title', 'LIKE', '%' . $search . '%');
        if ($request->user_id) {
            $results = $results->where('user_id', $request->user_id);
        }
        if ($request->type == "past") {
            $results = $results->whereDate('time', '<', today());
        }
        if ($request->type == "present") {
            $results = $results->whereDate('time', '>=', today());
        }

        // if ($request->type == 'range') {
        //     $results = $results->whereBetween('created_at', [$request->form, $request->to]);
        // }
        if ($request->date) {
            $results = $results->whereDate('time', '=', $request->date);
        }
        if ($request->month) {
            $results = $results->whereMonth('time', '=', $request->month);
        }
        if ($request->year) {
            $results = $results->whereYear('time', $request->year);
        }

        $results = $results
            ->orderBy("created_at", "desc")
            ->paginate($request->limit ?? 10);
        return EventResource::collection($results);
    }

    public function store(Request $request)
    {
        $request->validate([
            "title" => "required",
            "description" => "required",
            "time" => "required",
            "location" => "required",
            "slot" => "required",
        ]);
        $data = Event::create([
            "title" => $request->title,
            "description" => $request->description,
            "time" => $request->time,
            "location" => $request->location,
            "slot" => $request->slot,
            "user_id" => $request->user()?->id ?? 1,
        ]);

        return response()->json([
            "message" => "Success",
            "data" => new EventResource($data)
        ], 200);
    }


    public function get($id)
    {
        $data = Event::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Event not found!"
            ], 404);
        }
        return new EventResource($data);
    }


    public function edit(Request $request, $id)
    {
        $request->validate([
            "title" => "required",
            "description" => "required",
            "time" => "required",
            "location" => "required",
            "slot" => "required",
        ]);

        $data = Event::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Event not found!"
            ], 404);
        }

        if ($data->user_id != $request->user()?->id && $request->user()?->role_id == 0)
            return response()->json([
                "message" => "Event not found!"
            ], 404);

        $data->update([
            "title" => $request->title,
            "description" => $request->description,
            "time" => $request->time,
            "location" => $request->location,
            "slot" => $request->slot,
        ]);

        return response()->json([
            "message" => "Success edit Event"
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $data = Event::find($id);
        if (!$data) {
            return response()->json([
                "message" => "Event not found!"
            ], 404);
        }
        if ($request->user()?->role_id == 0 && $data->user_id != $request->user()?->id)
            return response()->json([
                "message" => "Event not found!"
            ], 404);

        $data->delete();
        return response()->json([
            "message" => "Success delete Event"
        ]);
    }
}
