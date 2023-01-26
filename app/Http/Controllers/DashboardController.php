<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todayAttendance = Attendance::whereDate('created_at', Carbon::today())->latest()->get();
        $checkinRegular = Attendance::checkAttendance(1)->first();
        $checkoutRegular = Attendance::checkAttendance(2)->first();
        $checkinAssignment = Attendance::checkAttendance(3)->first();
        $checkoutAssignment = Attendance::checkAttendance(4)->first();

        return view('admin.dashboard.index', compact('todayAttendance', 'checkinRegular', 'checkoutRegular', 'checkinAssignment', 'checkoutAssignment'));
    }
}
