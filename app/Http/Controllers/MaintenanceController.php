<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\maintenance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $maintenance = maintenance::where('user_id', $user_id)->get();
        return $this->success($maintenance);
    }

    public function createMaintenance(Request $request, $item_id)
    {
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid field', 'errors' => $validator->errors()], 422);
        }

        $dateNow = Carbon::now('Asia/Jakarta');

        // Cek apakah item ada
        $item = Item::find($item_id);
        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        // Cek apakah ada maintenance yang sedang berlangsung
        $maintenance = maintenance::where('item_id', $item_id)->where('status', '!=', 'selesai')->first();
        if ($maintenance) {
            return response()->json(['message' => 'Maintenance sudah dijadwalkan atau sedang berlangsung untuk item ini'], 400);
        }

        // Buat maintenance baru
        $newMaintenance = maintenance::create([
            'item_id' => $item_id,
            'user_id' => $request->user()->id,
            'tanggal_dijadwalkan' => $dateNow,
            'deskripsi' => $request->deskripsi,
            'status' => 'dijadwalkan',
        ]);

        // Update status item menjadi 'maintenance'
        $item->update(['status' => 'maintenance']);

        return response()->json(['message' => 'Berhasil membuat maintenance', 'data' => $newMaintenance], 200);
    }

    public function processMaintenance($maintenance_id)
    {
        $maintenance = maintenance::find($maintenance_id);
        if (!$maintenance) {
            return response()->json(['message' => 'Maintenance tidak ditemukan'], 404);
        }

        if ($maintenance->status !== 'dijadwalkan') {
            return response()->json(['message' => 'Maintenance tidak dapat diproses'], 400);
        }

        $dateNow = Carbon::now('Asia/Jakarta');
        $maintenance->update([
            'status' => 'proses',
            'tanggal_proses' => $dateNow
        ]);

        return response()->json(['message' => 'Maintenance sedang diproses']);
    }

    public function completeMaintenance($maintenance_id)
    {
        $maintenance = maintenance::find($maintenance_id);
        if (!$maintenance) {
            return response()->json(['message' => 'Maintenance tidak ditemukan'], 404);
        }

        if ($maintenance->status !== 'proses') {
            return response()->json(['message' => 'Maintenance tidak dapat diselesaikan'], 400);
        }

        $dateNow = Carbon::now('Asia/Jakarta');
        $maintenance->update([
            'status' => 'selesai',
            'tanggal_selesai' => $dateNow,
        ]);

        // Update status item menjadi 'tersedia'
        $item = Item::find($maintenance->item_id);
        $item->update(['status' => 'tersedia']);

        return response()->json(['message' => 'Maintenance selesai']);
    }
}
