{{-- resources/views/admin/students/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manajemen Siswa')
@section('page-title', 'Manajemen Siswa')
@section('page-subtitle', 'Kelola semua siswa sekolah')

@section('content')
<div class="row mb-4">
    <!-- Stats Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card students h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Siswa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $totalStudents }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users stat-icon text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card classes h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Siswa Aktif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $activeStudents }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-check stat-icon text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card treasurers h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Bendahara
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $treasurerStudents }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-cash-register stat-icon text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card inactive h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                            Siswa Nonaktif
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $inactiveStudents }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-times stat-icon text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-list me-2"></i>
                    Daftar Siswa
                </h6>
                <div>
                    <a href="{{ route('admin.students.create') }}" class="btn btn-sm btn-admin">
                        <i class="fas fa-plus me-1"></i> Tambah Siswa
                    </a>
                    <a href="{{ route('admin.students.export') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-download me-1"></i> Export CSV
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('admin.students.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <input type="text" class="form-control" name="search" 
                                   value="{{ request('search') }}" placeholder="Cari nama/NISN...">
                        </div>
                        <div class="col-md-2 mb-2">
                            <select class="form-select" name="class_id">
                                <option value="">Semua Kelas</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->full_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <select class="form-select" name="role">
                                <option value="">Semua Role</option>
                                <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Siswa</option>
                                <option value="treasurer" {{ request('role') == 'treasurer' ? 'selected' : '' }}>Bendahara</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 d-flex gap-2">
                            <button type="submit" class="btn btn-admin">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Students Table -->
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Status</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $loop->iteration + (($students->currentPage() - 1) * $students->perPage()) }}</td>
                                <td>
                                    <div class="fw-bold">{{ $student->name }}</div>
                                    <small class="text-muted">{{ $student->email }}</small>
                                    <div class="small">
                                        <i class="fas fa-phone"></i> {{ $student->phone ?? '-' }}
                                    </div>
                                </td>
                                <td>{{ $student->nisn }}</td>
                                <td>
                                    @if($student->class)
                                    <span class="badge bg-primary">{{ $student->class->full_name }}</span>
                                    <div class="small text-muted">
                                        Wali: {{ $student->class->teacher->name ?? '-' }}
                                    </div>
                                    @else
                                    <span class="badge bg-secondary">Tanpa Kelas</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                    @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->role === 'treasurer')
                                    <span class="badge bg-info">Bendahara</span>
                                    @else
                                    <span class="badge bg-secondary">Siswa</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.students.show', $student) }}" 
                                           class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.students.edit', $student) }}" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.students.toggle-active', $student) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-{{ $student->is_active ? 'warning' : 'success' }}" 
                                                    title="{{ $student->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="fas fa-{{ $student->is_active ? 'ban' : 'check' }}"></i>
                                            </button>
                                        </form>
                                        @if($student->class_id)
                                        <form action="{{ route('admin.students.toggle-treasurer', $student) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-{{ $student->role === 'treasurer' ? 'secondary' : 'primary' }}" 
                                                    title="{{ $student->role === 'treasurer' ? 'Hapus Bendahara' : 'Jadikan Bendahara' }}">
                                                <i class="fas fa-{{ $student->role === 'treasurer' ? 'user-minus' : 'user-check' }}"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $students->firstItem() }} - {{ $students->lastItem() }} dari {{ $students->total() }} siswa
                    </div>
                    {{ $students->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('.datatable').DataTable({
            "paging": false, // We use Laravel pagination
            "searching": false,
            "ordering": true,
            "info": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>
@endpush