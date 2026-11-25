<?php

namespace App\Http\Controllers;

use App\Models\SerialCode;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $total = SerialCode::count();
        $used = SerialCode::used()->count();
        $unused = SerialCode::unused()->count();
        $recent = SerialCode::latest()->limit(6)->get();

        $daily = SerialCode::used()
            ->where('used_at', '>=', now()->subDays(13)->startOfDay())
            ->selectRaw('DATE(used_at) as day, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(used_at)'))
            ->pluck('total', 'day');
        $trend = collect(range(13, 0))->map(function ($daysAgo) use ($daily) {
            $date = now()->subDays($daysAgo)->toDateString();
            return ['label' => now()->subDays($daysAgo)->format('d M'), 'value' => (int) ($daily[$date] ?? 0)];
        });

        return view('dashboard.index', compact('total', 'used', 'unused', 'recent', 'trend'));
    }
}
