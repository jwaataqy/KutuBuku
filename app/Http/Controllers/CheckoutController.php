<?php

namespace App\Http\Controllers;

use App\Models\{Order, OrderItem};
use App\Support\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Http};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $r)
    {
        return view('orders.index', ['orders' => $r->user()->orders()->withCount('items')->latest()->get()]);
    }
    public function show(Request $r, Order $order)
    {
        $user = $r->user();

        abort_unless($order->user_id === $user->id, 403);

        return view('orders.show', [
            'order' => $order->load('items.product')
        ]);
    }
    public function store(Request $r)
    {
        $s = Cart::summary();
        if ($s['items']->isEmpty()) return redirect()->route('cart');
        $order = DB::transaction(function () use ($s, $r) {
            $o = Order::create([
                'user_id' => $r->user()->id,
                'invoice' => 'INV-' . now()->format('ymd') . strtoupper(Str::random(5)),
                'subtotal' => $s['subtotal'],
                'discount' => $s['discount'],
                'total' => $s['total'],
                'voucher_code' => $s['voucher']?->code
            ]);
            foreach ($s['items'] as $p) OrderItem::create(['order_id' => $o->id, 'product_id' => $p->id, 'price' => $p->final_price]);
            return $o;
        });
        session()->forget(['cart', 'voucher']);
        if ($order->total == 0) {
            $order->markPaid('gratis');
            return redirect()->route('library')->with('ok', 'E-book sudah masuk ke perpustakaanmu!');
        }
        $base = config('midtrans.production') ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';
        $res = Http::withBasicAuth((string)config('midtrans.server_key'), '')->post("$base/snap/v1/transactions", [
            'transaction_details' => ['order_id' => $order->invoice, 'gross_amount' => $order->total],
            'customer_details' => ['first_name' => $r->user()->name, 'email' => $r->user()->email]
        ]);
        $order->update(['snap_token' => $res->json('token')]);
        return redirect()->route('orders.show', $order);
    }
    public function simulate(Order $order)
    {
        abort_unless(
            app()->isLocal() && $order->user_id === Auth::id(),
            404
        );

        $order->markPaid('simulasi');

        return back()->with('ok', 'Pembayaran simulasi berhasil.');
    }
    public function notification(Request $r)
    {
        $sig = hash('sha512', $r->order_id . $r->status_code . $r->gross_amount . config('midtrans.server_key'));
        abort_unless(hash_equals($sig, (string)$r->signature_key), 403);
        $o = Order::where('invoice', $r->order_id)->firstOrFail();
        match ($r->transaction_status) {
            'settlement', 'capture' => $o->markPaid($r->payment_type),
            'expire' => $o->update(['status' => 'expired']),
            'cancel', 'deny' => $o->update(['status' => 'failed']),
            default => null
        };
        return response()->json(['ok' => true]);
    }
}
