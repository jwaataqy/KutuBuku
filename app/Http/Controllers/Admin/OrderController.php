<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $r)
    {
        $orders = Order::with('user')->withCount('items')
            ->when($r->status, fn ($q, $v) => $q->where('status', $v))
            ->when($r->q, fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('invoice', 'like', "%$v%")
                ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%$v%")->orWhere('email', 'like', "%$v%"))))
            ->latest()->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', ['order' => $order->load('user', 'items.product')]);
    }

    public function update(Request $r, Order $order)
    {
        $d = $r->validate(['status' => 'required|in:paid,failed,expired']);
        abort_unless($order->status === 'pending', 422, 'Hanya pesanan pending yang bisa diubah.');
        if ($d['status'] === 'paid') {
            $order->markPaid('manual');
        } else {
            $order->update(['status' => $d['status']]);
        }
        return back()->with('ok', 'Status pesanan diperbarui.');
    }
}