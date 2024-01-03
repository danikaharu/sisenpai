<div class="flex">
    @can('edit attendance')
        <a class="btn btn-primary me-2 mt-2" href="{{ route('attendance.edit', $id) }}">
            Edit
        </a>
    @endcan

    @can('delete attendance')
        <form action="{{ route('attendance.destroy', $id) }}" method="POST" role="alert" alert-title="Hapus Absensi"
            alert-text="Yakin ingin menghapusnya?">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm me-2 mt-2"><i class="bx bx-trash me-1"></i>
                Hapus</button>
        </form>
    @endcan

    <a class="btn btn-secondary me-2 mt-2" href="{{ route('attendance.show', $id) }}">
        Detail
    </a>

</div>
