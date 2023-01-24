@extends('layouts.admin')

@section('title', 'Riwayat Absen')

@push('css')
    <link rel="stylesheet"
        href="{{ asset('template/admin') }}/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="{{ asset('template/admin') }}/css/pages/datatables.css" />
@endpush

@section('content')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Riwayat Absen</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="../index.html">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Riwayat Absen
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @hasrole('Super Admin|Admin OPD')
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-md mb-2" data-bs-toggle="modal"
                                            data-bs-target="#laporanAbsenModal"> Cetak Laporan
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="laporanAbsenModal" tabindex="-1"
                                            aria-labelledby="laporanAbsenModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="laporanAbsenModalLabel">
                                                            Cetak Laporan
                                                        </h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('attendance.exportAttendance') }}">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="">Tanggal Awal</label>
                                                                <input type="date" name="start_date" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Tanggal Selesai</label>
                                                                <input type="date" name="end_date" class="form-control">
                                                            </div>
                                                            <button type="submit" class="btn btn-primary mt-4">Cetak</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endhasrole

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="data-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Jenis Absen</th>
                                            <th>Waktu Absen</th>
                                            <th>Tanggal Absen</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
@endsection

@push('js')
    <script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
    <script>
        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url()->current() }}",
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    orderable: false
                }, {
                    data: 'nama',
                    name: 'user.name'
                },
                {
                    data: 'type'
                },
                {
                    data: 'time'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        if (data === 'Terlambat') {
                            return '<span class="badge bg-danger badge-md">' + data + '</span>'
                        } else {
                            return '<span class="badge bg-success badge-md">' + data + '</span>'
                        }

                    }
                },
                {
                    data: 'action',
                    searchable: false,
                    orderable: false
                },
            ],
        });
    </script>
@endpush
