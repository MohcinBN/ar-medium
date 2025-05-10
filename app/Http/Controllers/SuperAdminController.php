<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function getAllUsers() {
        return Inertia::render('Admin/Users', [
             'users' => User::with('roles')->paginate(10),
            'tags' => Tag::all(['id', 'name']),
            'success' => session('success')
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if($user->id !== Auth::id()) {
            $user->delete();
            return redirect()->back()->with('success', 'تم حذف المستخدم بنجاح');
        }
        return redirect()->back()->with('error', 'لا يمكنك حذف حسابك');
    }

    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);
        
        $user->syncRoles([$request->role]);
        return redirect()->back()->with('success', 'تم تحديث دور المستخدم بنجاح');
    }
}
