<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 1. Danh sách người dùng
    public function index()
    {
        $users = User::paginate(10); // Phân trang 10 người/trang
        return view('admin.users.index', compact('users'));
    }

    // 2. Hiển thị form sửa (để cấp quyền Admin/Khách hàng)
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // 3. Xử lý cập nhật thông tin
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user', // Chỉ chấp nhận admin hoặc user
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Nếu có nhập mật khẩu mới thì cập nhật, không thì giữ nguyên
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    // 4. Xóa người dùng
    public function destroy(User $user)
    {
        // Không cho phép tự xóa chính mình
        if (Auth::id() == $user->id) {
            return redirect()->back()->with('error', 'Bạn không thể xóa chính tài khoản mình đang đăng nhập!');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Đã xóa người dùng!');
    }
}