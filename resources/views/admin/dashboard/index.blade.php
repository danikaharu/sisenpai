@extends('layouts.admin')

@section('title', 'Dashboard')

@push('css')
    <link rel="stylesheet"
        href="{{ asset('template/admin') }}/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="{{ asset('template/admin') }}/css/pages/datatables.css" />
@endpush

@section('content')
    <div class="page-title">
        <div class="container">
            <div class="col-12 col-md-6 order-md-1 order-last mb-2">
                <h4>Menu Absensi</h4>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-3">
                        <div class="row">
                            <h6 class="text-muted font-semibold text-center">
                                Absen Masuk
                            </h6>
                            <h5 class="font-extrabold mb-0 text-center">
                                @if ($checkinAttendance)
                                    {{ $checkinAttendance->time }}
                                @else
                                    Belum Absen
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-3">
                        <div class="row">
                            <h6 class="text-muted font-semibold text-center">
                                Absen Pulang
                            </h6>
                            <h5 class="font-extrabold mb-0 text-center">
                                @if ($checkoutAttendance)
                                    {{ $checkoutAttendance->time }}
                                @else
                                    Belum Absen
                                @endif
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-xl-12 col-md-12 col-sm-12">
                @can('create attendance')
                    <a href="{{ route('attendance.create') }}" class="btn rounded-pill btn-md btn-primary mb-2">
                        Absen Masuk
                    </a>
                    <a href="{{ route('attendance.createCheckout') }}" class="btn rounded-pill btn-md btn-primary mb-2">
                        Absen Pulang
                    </a>
                @endcan

                @can('create application')
                    <a href="{{ route('application.create') }}" class="btn rounded-pill btn-md btn-warning mb-2">
                        Pengajuan
                    </a>
                @endcan

            </div>
        </div>
    </section>

    @hasanyrole('Super Admin|Admin OPD')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Absen Harian {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Jenis Absen</th>
                                        <th>Waktu Absen</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($todayAttendance as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->user->name }}</td>
                                            <td>{{ $data->type() }}</td>
                                            <td>{{ $data->time }}</td>
                                            <td>
                                                @if ($data->type == 1 || $data->type == 3)
                                                    @if ($data->time > '09:00:00')
                                                        <span class="badge bg-danger">Terlambat</span>
                                                    @else
                                                        <span class="badge bg-success">Tepat Waktu</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-success">Tepat Waktu</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ asset('storage/upload/absen/' . $data->photo) }}"
                                                    class="btn btn-warning" target="_blank">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                Maaf, belum ada data
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endhasanyrole

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
    <script>
        $('#table1').DataTable();
    </script>
@endpush
