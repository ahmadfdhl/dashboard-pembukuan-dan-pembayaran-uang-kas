{{-- resources/views/admin/classes/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Kelas')
@section('page-title', 'Manajemen Kelas')
@section('page-subtitle', 'Kelola semua kelas sekolah')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h5 class="text-dark">Daftar Kelas ({{ $classes->count() }})</h5>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('admin.classes.create') }}" class="btn btn-admin">
            <i class="fas fa-plus me-1"></i> Tambah Kelas
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-list me-2"></i>
                    Daftar Semua Kelas
                </h6>
            </div>
            <div class="card-body">
                @if($classes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Kelas</th>
                                <th>Wali Kelas</th>
                                <th>Bendahara</th>
                                <th>Siswa</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classes as $class)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-bold">{{ $class->full_name }}</div>
                                    <small class="text-muted">
                                        {{ $class->grade }} - {{ $class->major ?? 'Umum' }}
                                    </small>
                                </td>
                                <td>
                                    @if($class->teacher)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            <div class="avatar-initials bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                {{ substr($class->teacher->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $class->teacher->name }}</div>
                                            <small class="text-muted">{{ $class->teacher->email }}</small>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-danger">Belum ada wali kelas</span>
                                    @endif
                                </td>
                                <td>
                                    @if($class->treasurer)
                                    <span class="badge bg-info">
                                        <i class="fas fa-user-check me-1"></i>
                                        {{ $class->treasurer->name }}
                                    </span>
                                    @else
                                    <span class="badge bg-warning">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-success">
                                            {{ $class->active_students_count }} aktif
                                        </span>
                                        <span class="badge bg-secondary">
                                            {{ $class->students_count - $class->active_students_count }} nonaktif
                                        </span>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Total: {{ $class->students_count }} siswa
                                    </small>
                                </td>
                                <td>
                                    @if($class->deleted_at)
                                    <span class="badge bg-danger">Nonaktif</span>
                                    @else
                                    <span class="badge bg-success">Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.classes.show', $class) }}" 
                                           class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.classes.edit', $class) }}" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.classes.destroy', $class) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Hapus kelas ini? Semua siswa akan dikeluarkan.')"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada kelas</h5>
                    <p class="text-muted">Tambahkan kelas pertama untuk memulai</p>
                    <a href="{{ route('admin.classes.create') }}" class="btn btn-admin">
                        <i class="fas fa-plus me-1"></i> Buat Kelas Pertama
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-chart-pie me-2"></i>
                    Statistik Kelas
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    @php
                        $activeClasses = $classes->whereNull('deleted_at')->count();
                        $inactiveClasses = $classes->whereNotNull('deleted_at')->count();
                        $totalStudents = $classes->sum('students_count');
                        $averageStudents = $classes->count() > 0 ? round($totalStudents / $classes->count(), 1) : 0;
                    @endphp
                    
                    <div class="col-md-3 mb-3">
                        <div class="display-6 text-primary">{{ $classes->count() }}</div>
                        <small class="text-muted">Total Kelas</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="display-6 text-success">{{ $activeClasses }}</div>
                        <small class="text-success">Kelas Aktif</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="display-6 text-danger">{{ $inactiveClasses }}</div>
                        <small class="text-danger">Kelas Nonaktif</small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="display-6 text-info">{{ $averageStudents }}</div>
                        <small class="text-info">Rata-rata Siswa/Kelas</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "order": [[0, 'asc']]
        });
    });
</script>
@endpush