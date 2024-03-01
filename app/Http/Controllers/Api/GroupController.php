<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    //
    public function index()
    {
        $groups = Group::all();

        return response()->json([
            'message' => 'success',
            'data' => $groups,
        ]);
    }

    public function show(string $id)
    {
        $group = Group::findOrFail($id);

        return response()->json([
            'message' => 'success',
            'data' => $group,
        ]);
    }
}
