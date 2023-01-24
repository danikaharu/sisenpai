<div class="flex">
    <a class="btn btn-warning me-2" href="{{ asset('storage/upload/absen/' . $photo) }}" target="_blank">
        Lihat Gambar
    </a>

    @can('edit attendance')
        <a class="btn btn-primary me-2" href="{{ route('attendance.edit', $id) }}">
            Edit
        </a>
    @endcan
</div>
