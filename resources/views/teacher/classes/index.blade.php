@extends('layouts.teacher')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Daftar Kelas</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kelas
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Tingkat</th>
                            <th>Jurusan</th>
                            <th>Wali Kelas</th>
                            <th>Bendahara</th>
                            <th>Jumlah Siswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                        <tr>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->grade }}</td>
                            <td>{{ $class->major ?? '-' }}</td>
                            <td>{{ $class->teacher->name ?? '-' }}</td>
                            <td>{{ $class->treasurer->name ?? 'Belum ditentukan' }}</td>
                            <td>{{ $class->students->count() }}</td>
                            <td>
                                @if ($class->teacher_id === auth()->id())
                                    <form action="{{ route('teacher.classes.destroy', $class) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('teacher.classes.show', $class) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('teacher.classes.edit', $class) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada kelas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection