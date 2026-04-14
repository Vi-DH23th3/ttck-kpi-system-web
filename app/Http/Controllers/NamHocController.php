<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NamHoc;
class NamHocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $namhoc = NamHoc::all();
        return view('namhoc.index', compact('namhoc'));
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
        $namHoc = NamHoc::create([
            'ten_nam_hoc' => $request->ten_nam_hoc,
            'ngay_bat_dau' => $request->ngay_bat_dau,
            'ngay_ket_thuc' => $request->ngay_ket_thuc,
        ]);
        return redirect()->back()->with('success', 'Thêm năm học thành công')->with('namHoc', $namHoc);
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
        $namHoc = NamHoc::findOrFail($id);
        $namHoc->update([
            'ten_nam_hoc' => $request->ten_nam_hoc,
            'ngay_bat_dau' => $request->ngay_bat_dau,
            'ngay_ket_thuc' => $request->ngay_ket_thuc,
        ]);
        return response()->json(['message' => 'Cập nhật năm học thành công']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $namHoc = NamHoc::findOrFail($id);
        $namHoc->delete();
        return redirect()->back()->with('success', 'Xóa năm học thành công');
    }
}
