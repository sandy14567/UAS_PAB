<?php

namespace App\Http\Controllers\api;

use App\Models\card;
use App\Models\topup;
use App\Models\membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\belanja;
use Illuminate\Support\Facades\Auth;

class cardController extends Controller
{
    public function create()
    {
        $id = DB::table('cards')->insertGetId([
            'balance' => 0,
        ]);
        return response()->json([
            'id' => $id,
            'message' => 'Berhasil'
        ], 200);
    }

    public function list(Request $request)
    {
        $page = $request->input('page', 0);
        $page_size = $request->input('page_size', 10);
        return response()->json([
            'message' => 'Berhasil',
            'cards' => card::skip($page * $page_size)->take($page_size)->select('id')->get(),
        ], 200);
    }

    public function topup(Request $request)
    {
        $card_id = $request->input('card_id', 0);
        $nominal = $request->input('nominal', 0);
        if ($card_id == 0) {
            return response()->json([
                'message' => 'CardID tidak ditemukan',
            ], 422);
        }
        if ($nominal <= 0) {
            return response()->json([
                'message' => 'Nominal harus positif',
            ], 422);
        }
        $card = card::find($card_id);
        if ($card == null) {
            return response()->json([
                'message' => 'Card tidak ditemukan',
            ], 422);
        }
        $card->balance += $nominal;
        $card->save();
        $membership = membership::where('user_id', Auth::user()->id)->first();
        topup::insert([
            'topup_datetime' => date('Y-m-d H:i:s'),
            'membership_id' => $membership->id,
            'card_id' => $card->id,
            'nominal' => $nominal,
        ]);
        return response()->json([
            'message' => 'Topup berhasil',
            'saldo' => $card->balance,
        ], 200);
    }

    public function belanja(Request $request)
    {
        $card_id = $request->input('card_id', 0);
        $nominal = $request->input('nominal', 0);
        if ($card_id == 0) {
            return response()->json([
                'message' => 'CardID tidak ditemukan',
            ], 422);
        }
        if ($nominal <= 0) {
            return response()->json([
                'message' => 'Nominal harus positif',
            ], 422);
        }
        $card = card::find($card_id);
        if ($card == null) {
            return response()->json([
                'message' => 'Card tidak ditemukan',
            ], 422);
        }
        if ($card->balance < $nominal) {
            return response()->json([
                'message' => 'Saldo tidak mencukupi',
            ], 422);
        }
        $card->balance -= $nominal;
        $card->save();
        $membership = membership::where('user_id', Auth::user()->id)->first();
        belanja::insert([
            'belanja_datetime' => date('Y-m-d H:i:s'),
            'membership_id' => $membership->id,
            'card_id' => $card->id,
            'nominal' => $nominal,
        ]);
        return response()->json([
            'message' => 'Payment berhasil',
            'saldo' => $card->balance,
        ], 200);
    }

    public function balance($card_id)
    {
        $card = card::find($card_id);
        if ($card == null) {
            return response()->json([
                'message' => 'Card tidak ditemukan',
            ], 422);
        }
        return response()->json([
            'message' => 'Berhasil',
            'saldo' => $card->balance,
        ], 200);
    }
}
