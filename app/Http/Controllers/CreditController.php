<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    /**
     * Show the form for adding credit.
     */
    public function show()
    {
        return view('credits.add');
    }

    /**
     * Add credit to the user's account.
     */
    public function add(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $user->credit += $request->amount;
        $user->save();

        // Optional: Log this transaction

        return redirect()->route('credits.add')->with('success', 'تم شحن رصيدك بنجاح!');
    }
}
