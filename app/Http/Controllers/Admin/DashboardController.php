<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Product, Review, User};

class DashboardController extends Controller
{
    public function index()
    {
        $paid = fn () => Order::where('status', 'paid');
        $thisMonth = $paid()->where('paid_at', '>=', now()->startOfMonth())->sum('total');
        $lastStart = now()->subMonthNoOverflow()->startOfMonth();
        $lastMonth = $paid()->whereBetween('paid_at', [$lastStart, $lastStart->copy()->endOfMonth()])->sum('total');

        $days = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->startOfDay());
        $byDay = $paid()->where('paid_at', '>=', $days->first())->get()
            ->groupBy(fn ($o) => $o->paid_at->format('Y-m-d'));
        $pick = fn ($d) => $byDay->get($d->format('Y-m-d'), collect());

        return view('admin.dashboard', [
            'revenue'   => $paid()->sum('total'),
            'thisMonth' => $thisMonth,
            'growth'    => $lastMonth > 0 ? round(($thisMonth - $lastMonth) / $lastMonth * 100) : null,
            'paidCount' => $paid()->count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'users'     => User::where('is_admin', false)->count(),
            'products'  => Product::count(),
            'sold'      => Product::sum('sold'),
            'reviews'   => Review::count(),
            'top'       => Product::orderByDesc('sold')->take(5)->get(),
            'recent'    => Order::with('user')->latest()->take(6)->get(),
            'chart'     => [
                'labels'  => $days->map(fn ($d) => $d->format('d/m'))->all(),
                'revenue' => $days->map(fn ($d) => (int) $pick($d)->sum('total'))->all(),
                'orders'  => $days->map(fn ($d) => $pick($d)->count())->all(),
            ],
        ]);
    }
}