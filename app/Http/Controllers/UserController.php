<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search  = $request->search ?? null;
        $results = User::where('title', 'LIKE', '%' . $search . '%')
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
        return UserResource::collection($results);
    }
}
