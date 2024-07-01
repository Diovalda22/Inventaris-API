<?php

namespace App\Http\Controllers;

use App\Models\checkout;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function getAllPinjaman(Request $request)
    {
        $user_id = $request->user()->id;
        if (!$user_id) return $this->fail('user not found', 404);
        $checkout = checkout::where('user_id', $user_id)->get();
        return $this->success($checkout);
    }

    public function checkoutBarang(Request $request, $item_id)
    {
        $validator = Validator::make($request->all(), [
            'catatan' => 'required',
            'jadwal_kembali' => 'required|date_format:Y-m-d H:i:s',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'invalid field', 'error' => $validator->errors()], 422);
        }

        $item = Item::find($item_id);
        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        if ($item->status === 'tersedia') {
            $user = $request->user();
            $tanggalPinjam = Carbon::now('Asia/Jakarta');
            try {
                $jadwalKembali = Carbon::createFromFormat('Y-m-d H:i:s', $request->jadwal_kembali, 'Asia/Jakarta');
            } catch (\Exception $e) {
                return response()->json(['message' => 'Invalid date format for jadwal_kembali'], 422);
            }

            $checkout = checkout::create([
                'item_id' => $item_id,
                'user_id' => $user->id,
                'tanggal_pinjam' => $tanggalPinjam,
                'jadwal_kembali' => $jadwalKembali,
                'catatan' => $request->catatan,
            ]);

            if ($item) {
                $item->update(['status' => 'dipinjam']);
            } else {
                return response()->json(['message' => 'Item tidak ditemukan'], 404);
            }

            return $this->message('Berhasil meminjam barang');
        } else if ($item->status === 'maintenance') {
            return $this->message('Barang tidak tersedia (diperbaiki)', 400);
        } else {
            return $this->message('Barang tidak tersedia (dipinjam)', 400);
        }
    }

    // public function returnBarang(Request $request, $item_id)
    // {
    //     $item = Item::find($item_id);
    //     if (!$item) {
    //         return response()->json(['message' => 'Item tidak ditemukan'], 404);
    //     }
    //     $user_id = $request->user()->id;
    //     $tanggal_kembali = Carbon::now('Asia/Jakarta');

    //     if ($item->status === 'dipinjam') {
    //         $checkout = checkout::where('item_id', $item_id)->where('user_id', $user_id)->where('status', 'dipinjam')->first();
    //         $checkout->update([
    //             'status' => 'kembali',
    //             'tanggal_kembali' => $tanggal_kembali
    //         ]);
    //         $item->update(['status' => 'tersedia']);
    //         return $this->message('barang berhasil dikembalikan');
    //     } else {
    //         return $this->message('barang tidak dipinjam', 400);
    //     } 
    // }

    public function returnBarang(Request $request, $item_id)
    {
        $item = Item::find($item_id);
        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        $user_id = $request->user()->id;
        $tanggal_kembali = Carbon::now('Asia/Jakarta');

        if ($item->status === 'dipinjam') {
            $checkout = Checkout::where('item_id', $item_id)->where('user_id', $user_id)->where('status', 'dipinjam')->first();
            if ($checkout) {
                $checkout->update([
                    'status' => 'kembali',
                    'tanggal_kembali' => $tanggal_kembali
                ]);
                $item->update(['status' => 'tersedia']);
                return $this->message('Barang berhasil dikembalikan');
            } else {
                return $this->message('Record checkout tidak ditemukan atau sudah dikembalikan', 400);
            }
        }
    }
}
