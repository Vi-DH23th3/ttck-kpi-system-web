<?php

namespace App\Http\Controllers;

use App\Models\ChucVu;
use Illuminate\Http\Request;

class ChucVuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ChucVu::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('ten_chuc_vu', 'like', "%$search%");
        }
        $chucvu = $query->get();
        return view('chucvu.index', compact('chucvu'));
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
        $chucvu = ChucVu::create([
            'ten_chuc_vu' => $request->name_chucvu
        ]);
        return redirect()->back()->with('success', 'Thêm chức vụ thành công');
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
        $chucvu = ChucVu::findOrFail($id);
        return response()->json(['chucvu'=>$chucvu]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $chucvu = ChucVu::findOrFail($id);
        //dd($donvi);
        $chucvu->update([
            'ten_chuc_vu' => $request->ten_chuc_vu
        ]);
        return response()->json([ 'success' => true, 'message' => 'Cập nhật chức vụ thành công']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $chucvu = ChucVu::findOrFail($id);
        $chucvu->delete();
        return redirect()->back()->with('success', 'Xóa chức vụ thành công');
    }
}
