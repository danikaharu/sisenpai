<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\{StoreAttendanceRequest, UpdateAttendanceRequest};
use App\Models\{Attendance, Employee};
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendances = Attendance::all();
        return response()->json($attendances);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAttendanceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAttendanceRequest $request)
    {
        $radius = 1; //100 meter
        $attr = $request->validated();
        dd($attr);

        // Get auth user
        $user = Auth::user();
        $userInfo = Employee::where('nip', $user->username)->first();

        // Check location
        $latitude = $userInfo->agency->latitude;
        $longitude = $userInfo->agency->longitude;
        $distance = $this->haversineDistance($attr['latitude'], $attr['longitude'], $latitude, $longitude);
        $convertDistance = number_format($distance, 2);

        if ($convertDistance >= $radius) {
            return response()->json(['message' => 'Anda tidak berada di lokasi presensi.'], 400);
        } else {
            return response()->json(['message' => 'Anda berada di lokasi presensi.'], 200);
        }

        // Create a new attendance record
        $attendance = new Attendance;
        $attendance->user_id = $user->id;
        $attendance->type = $attr['type'];
        $attendance->latitude = $attr['latitude'];
        $attendance->longitude = $attr['longitude'];
        $attendance->time = $attr['time'];
        $attendance->photo = $attr['photo']->store('selfies');
        $attendance->save();

        // Return a success response
        return response()->json(['message' => 'Absen berhasil'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        return response()->json($attendance);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAttendanceRequest  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
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
}
