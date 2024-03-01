<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            $data = User::with(['group', 'skpd'])
            ->where('id', '!=', auth()->user()->id)->get();
            return DataTables::of($data)
                ->make(true);
        }

        return view('setting.pengguna.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'group_id' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'group_id' => $request->group_id,
            'skpd_id' => $request->skpd_id,
            'password' => bcrypt($request->password),
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Data berhasil ditambahkan!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->group_id = $request->group_id;
        $user->skpd_id = $request->skpd_id;
        if($request->password) $user->password = bcrypt($request->password);
        $user->status = $request->status;
        $user->save();

        return response()->json([
            'message' => 'Data berhasil diubah!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        User::destroy($id);

        return response()->json([
            'message' => 'Data berhasil dihapus!',
        ]);
    }
}
