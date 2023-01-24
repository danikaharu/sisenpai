@extends('layouts.admin')

@section('title')
    Absen
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Dashboard /</span> Absen
        </h4>

        <ul class="nav nav-pills flex-column flex-md-row mb-3">
            <li class="nav-item"><a class="nav-link active" href="{{ route('attendance.index') }}"><i
                        class="bx bx-arrow-back me-1"></i>
                    Kembali</a></li>
        </ul>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="form-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="nama_pegawai"><b>Nama Pegawai</b></label>
                                    <p>{{ $attendance->user->name }}</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="latitude"><b>Latitude </b></label>
                                    <input class="form-control" type="text" name="latitude"
                                        value="{{ isset($attendance) ? $attendance->latitude : old('latitude') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="longitude"><b>Longitude </b></label>
                                    <input class="form-control" type="text" name="longitude"
                                        value="{{ isset($attendance) ? $attendance->longitude : old('longitude') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="time"><b>Waktu Absen</b></label>
                                    <input class="form-control" type="text" name="time"
                                        value="{{ isset($attendance) ? $attendance->time : old('time') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="type"><b>Jenis Absen</b></label>
                                    <fieldset class="form-group">
                                        <select name="type" class="form-select" id="select_type">
                                            <option selected>-- Pilih jenis absen --</option>
                                            <option value="1"
                                                {{ isset($attendance) && $attendance->type == 1 ? 'selected' : (old('type') == 1 ? 'selected' : '') }}>
                                                Absen Masuk</option>
                                            <option value="2"
                                                {{ isset($attendance) && $attendance->type == 2 ? 'selected' : (old('type') == 2 ? 'selected' : '') }}>
                                                Absen Pulang</option>
                                            <option value="3"
                                                {{ isset($attendance) && $attendance->type == 3 ? 'selected' : (old('type') == 3 ? 'selected' : '') }}>
                                                Absen Penugasan Masuk</option>
                                            <option value="4"
                                                {{ isset($attendance) && $attendance->type == 4 ? 'selected' : (old('type') == 4 ? 'selected' : '') }}>
                                                Absen Penugasan Pulang</option>
                                        </select>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" id="alertApproval" class="btn btn-primary me-1 mb-1">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
