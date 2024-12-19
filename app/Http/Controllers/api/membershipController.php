<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class membershipController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users'],
            'address' => ['required', 'string', 'max:255'],
            'total_belanja' => ['required', 'boolean'],
            'total_topup' => ['required', 'boolean'],
        ]);
        $email = $request->email;
        $total_belanja = $request->get('total_belanja', 0);
        $total_topup = $request->get('total_topup', 0);
        if ($total_belanja == 0 && $total_topup == 0) {
            return response()->json([
                'message' => 'total_belanja dan total_topup tidak boleh 0'
            ], 422);
        }
        if ($total_belanja == 1 && $total_topup == 1) {
            return response()->json([
                'message' => 'total_belanja dan total_topup tidak boleh 1'
            ], 422);
        }
        $user = User::where('email', $email)->first();
        $membership = membership::where('user_id', $user->id)->first();
        if ($membership != null) {
            return response()->json([
                'message' => 'Email sudah dipakai di membership lain'
            ], 422);
        }
        DB::table('memberships')->insert([
            'user_id' => $user->id,
            'address' => $request->address,
            'total_belanja' => $total_belanja,
            'total_topup' => $total_topup,
        ]);
        return response()->json(['message' => 'Berhasil'], 200);
    }

    public function list(Request $request)
    {
        $page = $request->input('page', 0);
        $page_size = $request->input('page_size', 10);
        return response()->json([
            'message' => 'Berhasil',
            'memberships' => membership::skip($page * $page_size)->take($page_size)->get(),
        ], 200);
    }
}
