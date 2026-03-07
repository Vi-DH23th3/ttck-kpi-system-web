<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DonVi;
class DonViController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DonVi::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('ten_don_vi', 'like', "%$search%");
        }
        $donvis = $query->get();
        return view('donvi.index', compact('donvis'));
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
        $donvi = DonVi::create([
            'ten_don_vi' => $request->name_donvi
        ]);
        return redirect()->back()->with('success', 'Thêm phòng ban thành công');
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
        $donvi = DonVi::findOrFail($id);
        return response()->json(['donvi'=>$donvi]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $donvi = DonVi::findOrFail($id);
        //dd($donvi);
        $donvi->update([
            'ten_don_vi' => $request->ten_don_vi
        ]);
        return response()->json([ 'success' => true, 'message' => 'Cập nhật phòng ban thành công']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $donvi = DonVi::findOrFail($id);
        $donvi->delete();
        return redirect()->back()->with('success', 'Xóa phòng ban thành công');
    }
}
