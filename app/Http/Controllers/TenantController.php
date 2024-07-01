<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TenantController extends Controller
{
    public function indexTenant(Request $request)
    {
        $user_id = $request->user()->id;
        $item = Item::where('user_id', $user_id)->get();
        return $this->success($item);
    }

    public function tambahPemeilharaan(Request $request) {
        $validator = Validator::make($request->all(), [
            ''
        ]);
    }
}
