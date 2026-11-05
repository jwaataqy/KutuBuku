<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        return view('admin.vouchers', ['vouchers' => Voucher::latest()->get()]);
    }

    public function store(Request $r)
    {
        $r->merge(['code' => strtoupper(trim((string) $r->code))]);
        $d = $r->validate([
            'code'    => 'required|alpha_dash|max:30|unique:vouchers,code',
            'percent' => 'required|integer|between:1,100',
        ]);
        Voucher::create($d);
        return back()->with('ok', 'Voucher dibuat.');
    }

    public function update(Voucher $voucher)
    {
        $voucher->update(['active' => !$voucher->active]);
        return back()->with('ok', 'Status voucher diubah.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return back()->with('ok', 'Voucher dihapus.');
    }
}