<?php

namespace App\Http\Controllers;

use App\Models\DonVi;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChucVu;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dsdonvi = DonVi::all();
        $dschucvu = ChucVu::all();

        $query = User::query();
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        }
        if($request->ajax()){
            $users = $query->with('donVi', 'chucVu')->paginate(5)->withQueryString();
            return view('users.table', compact('users'))->render(); // Trả về HTML đã được render cho phần table
        }
        if($request->has('filter_chucvu'))
        {
            $filter = $request->input('filter_chucvu');
            $query->where('chuc_vu_id', $filter);
        }
        if($request->has('filter_don_vi')) {
            $filterDonVi = $request->input('filter_don_vi');
            $query->where('don_vi_id', $filterDonVi);
        }
        if($request->filled('filter_trang_thai')) {
            $filterTrangThai = $request->input('filter_trang_thai');
            $query->where('trang_thai', $filterTrangThai);
        }
        if($request->has('filter_role')) {
            $filterRole = $request->input('filter_role');
            $query->where('role', $filterRole);
        }
        if($request->has('filter_change_password')){
            $filterchangepassword = $request->input('filter_change_password');
            $query->where('must_change_password', $filterchangepassword);
        }
        $users = $query->with('donVi', 'chucVu')->paginate(5)->withQueryString();
        return view('users.index', compact('users', 'dsdonvi', 'dschucvu'));
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
    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'chucvu' => $request->chucvu, 
            'role' => $request->role,
            'don_vi_id' => $request->don_vi_id,
            'password' => Hash::make($request->password), // Mã hóa mật khẩu trước khi lưu
        ]);
        
        return response()->json(['success' => true, 'message' => 'Người dùng đã được tạo thành công', 'user' => $user], 201);
    }
    public function import(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls',
        ]);
      //dd($request->import_file);
        try {
            $import = new \App\Imports\UsersImport;
            $import->import($request->file('import_file')); // Gọi trực tiếp từ object
            $stats = $import->getStats();
            
            // Nếu có lỗi, chúng ta báo chi tiết
            if ($stats['failed'] > 0) {
                $errorMsg = "Nạp xong! Thành công: {$stats['success']}, Thất bại: {$stats['failed']}. ";
                DB::rollBack();
                return back()->with('import_errors', $stats['details'], $errorMsg);
            }
            DB::commit();
            return back()->with('success', "Nạp thành công tất cả {$stats['success']} người dùng.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.index')->with('error', 'Có lỗi xảy ra khi nhập dữ liệu: ' . $e->getMessage());
        }
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
        $users = User::findOrFail($id);
        $donVis = DonVi::all() ?? [];
        $chucVu = ChucVu::all();
        return response()->json(['success' => true, 'user' => $users, 'donVis' => $donVis, 'chucVu' => $chucVu]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'chucvu' => $request->chuc_vu, // Lưu ý: Tên cột trong DB của bạn là chucvu
            'role' => $request->role,
            'trang_thai' => $request->trang_thai,
            'don_vi_id' => $request->don_vi_id,
        ]);
        return response()->json([ 'success' => true, 'message' => 'Cập nhật người dùng thành công', 'user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => true, 'message' => 'Người dùng đã được xóa thành công'], 200);
    }
}
