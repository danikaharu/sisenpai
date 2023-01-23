<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attendance\{StoreAttendanceRequest, UpdateAttendanceRequest};
use App\Models\{Attendance, Employee};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage};

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usernameAdmin = Auth::user()->username;
        $adminInfo = Employee::where('nip', $usernameAdmin)->first();

        $attendanceQuery = Attendance::query();

        $attendances = $attendanceQuery->with('user.employee')->latest();

        if (auth()->user()->roles->first()->name == 'Super Admin') {
            $attendances->get();
        } elseif (auth()->user()->roles->first()->name == 'Admin OPD') {
            $attendances->whereHas('user', function ($query) use ($adminInfo) {
                $query->whereHas('employee', function ($q) use ($adminInfo) {
                    $q->where('agency_id', $adminInfo->agency_id);
                });
            })->get();
        } else {
            $attendances->where('user_id', auth()->user()->id);
        }

        if (request()->ajax()) {
            return dataTables()->of($attendances)
                ->addIndexColumn()
                ->addColumn('nama', function ($row) {
                    return $row->user ? $row->user->name : '-';
                })
                ->addColumn('type', function ($row) {
                    return $row->type();
                })
                ->addColumn('time', function ($row) {
                    return $row->time;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->isoFormat('DD-MM-YYYY');
                })
                ->addColumn('status', function ($row) {
                    if ($row->type == 1 || $row->type == 3) {
                        if ($row->time > '09:00:00') {
                            return 'Terlambat';
                        } else {
                            return 'Tepat Waktu';
                        }
                    } else {
                        return 'Tepat Waktu';
                    }
                })
                ->addColumn('action', 'admin.attendance.include.action')
                ->toJson();
        }

        return view('admin.attendance.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.attendance.createCheckin');
    }

    public function createCheckout()
    {
        return view('admin.attendance.createCheckout');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttendanceRequest $request)
    {
        $radius = 1; //100 meter
        $attr = $request->validated();
        $checkinAttendance = Attendance::checkAttendance(1)->first();

        // Get auth user
        $user = Auth::user();
        $userInfo = Employee::where('nip', $user->username)->first();

        // Check location
        $latitude = $userInfo->agency->latitude;
        $longitude = $userInfo->agency->longitude;
        $distance = $this->haversineDistance($attr['latitude'], $attr['longitude'], $latitude, $longitude);
        $convertDistance = number_format($distance, 2);

        if ($convertDistance >= $radius) {
            return redirect()->back()
                ->with('toast_error', 'Anda tidak berada di lokasi presensi.');
        }

        if ($checkinAttendance) {
            return redirect()->route('dashboard.index')
                ->with('toast_error', 'Anda sudah melakukan absen masuk');
        }

        if (\Carbon\Carbon::now()->toTimeString() > '12:00:00' && !$checkinAttendance) {
            return redirect()->back()
                ->with('toast_error', 'Maaf sudah tidak bisa melakukan absen masuk');
        }

        // Create a new attendance record
        $attendance = new Attendance;
        $attendance->user_id = $user->id;
        $attendance->type = $attr['type'];
        $attendance->latitude = $attr['latitude'];
        $attendance->longitude = $attr['longitude'];
        $attendance->time = $attr['time'];
        $attendance->photo = $this->uploadPhoto();
        $attendance->save();

        // Return a success response
        return redirect()->route('dashboard.index')
            ->with('success', 'Absen Masuk Berhasil');
    }

    public function storeCheckout(StoreAttendanceRequest $request)
    {
        $radius = 1; //100 meter
        $attr = $request->validated();
        $checkInAttendance = Attendance::checkAttendance(1)->first();
        $checkOutAttendance = Attendance::checkAttendance(2)->first();

        // Get auth user
        $user = Auth::user();
        $userInfo = Employee::where('nip', $user->username)->first();

        // Check location
        $latitude = $userInfo->agency->latitude;
        $longitude = $userInfo->agency->longitude;
        $distance = $this->haversineDistance($attr['latitude'], $attr['longitude'], $latitude, $longitude);
        $convertDistance = number_format($distance, 2);

        if ($convertDistance >= $radius) {
            return redirect()->back()
                ->with('toast_error', 'Anda tidak berada di lokasi presensi.');
        }

        if ($checkOutAttendance) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Anda sudah melakukan absen pulang');
        }

        if (!$checkInAttendance) {
            return redirect()->route('dashboard.index')
                ->with('success', 'Maaf, anda belum melakukan absen masuk');
        }

        if (\Carbon\Carbon::now()->toTimeString() <= '16:00:00') {
            return redirect()->back()
                ->with('toast_error', 'Maaf, belum bisa melakukan absen pulang');
        }

        // Create a new attendance record
        $attendance = new Attendance;
        $attendance->user_id = $user->id;
        $attendance->type = $attr['type'];
        $attendance->latitude = $attr['latitude'];
        $attendance->longitude = $attr['longitude'];
        $attendance->time = $attr['time'];
        $attendance->photo = $this->uploadPhoto();
        $attendance->save();

        // Return a success response
        return redirect()->route('dashboard.index')
            ->with('success', 'Absen Pulang Berhasil');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }

    private function haversineDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
    {
        // convert from degrees to radians
        $latDelta = deg2rad($latitudeTo - $latitudeFrom);
        $lonDelta = deg2rad($longitudeTo - $longitudeFrom);

        $a = sin($latDelta / 2) * sin($latDelta / 2) + cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * asin(sqrt($a));

        return $earthRadius * $c;
    }

    private function uploadPhoto()
    {
        $photo = request()->photo;

        $image_parts = explode(";base64,", $photo);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];

        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';

        // $file = $folderPath . $fileName;
        Storage::disk('public')->put('upload/absen/' . $fileName, $image_base64);

        return $fileName;
    }
}
