<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $currentMonth = $today->format('Y-m');

        return response()->json([
            'active_members' => Member::where('status', 'active')->count(),
            'overdue_members' => Member::where('status', 'overdue')->count(),
            'monthly_revenue' => Payment::where('reference_month', $currentMonth)->sum('amount'),
            'members_due_today' => Member::where('due_day', $today->day)->count(),
        ]);
    }
}
