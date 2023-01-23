<div class="flex">

    @can('show application')
        <a class="btn btn-primary btn-sm me-2" href="{{ route('application.show', $id) }}">
            Detail</a>
    @endcan

    @can('edit application')
        <a class="btn btn-warning btn-sm me-2" href="{{ route('application.edit', $id) }}">
            Edit</a>
    @endcan

    @can('delete application')
        <form action="{{ route('application.destroy', $id) }}" method="POST" role="alert" alert-title="Hapus pengajuan"
            alert-text="Yakin ingin menghapusnya?">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm me-2 mt-2">
                Hapus</button>
        </form>
    @endcan

</div>
