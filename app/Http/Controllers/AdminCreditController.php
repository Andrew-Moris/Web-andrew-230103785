<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminCreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'credit')->paginate(20);
        return view('admin.credits.index', compact('users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'credit' => 'required|numeric|min:0',
        ]);

        $user->credit = $request->credit;
        $user->save();

        return back()->with('success', 'تم تحديث رصيد المستخدم بنجاح.');
    }
}
