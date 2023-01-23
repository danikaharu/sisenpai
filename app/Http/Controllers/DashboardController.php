<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todayAttendance = Attendance::whereDate('created_at', Carbon::today())->get();
        $checkinAttendance = Attendance::checkAttendance(1)->first();
        $checkoutAttendance = Attendance::checkAttendance(2)->first();

        return view('admin.dashboard.index', compact('todayAttendance', 'checkinAttendance', 'checkoutAttendance'));
    }
}
