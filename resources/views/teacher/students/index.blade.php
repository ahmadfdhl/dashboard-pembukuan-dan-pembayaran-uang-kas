{{-- resources/views/teacher/students/index.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Manajemen Siswa')
@section('page-title', 'Manajemen Siswa')
@section('page-subtitle', 'Kelola data siswa sekolah')

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
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list me-2"></i>
                    Daftar Siswa
                </h6>
                <div>
                    <a href="{{ route('teacher.students.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Siswa
                    </a>
                    <a href="{{ route('teacher.students.export') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-download me-1"></i> Export CSV
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('teacher.students.index') }}" class="mb-4">
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
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('teacher.students.index') }}" class="btn btn-secondary">
                                <i class="fas fa-sync"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Bulk Actions -->
                <form id="bulkForm" method="POST" action="{{ route('teacher.students.bulk-actions') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" name="action" id="bulkAction">
                                <option value="">Aksi Massal</option>
                                <option value="activate">Aktifkan</option>
                                <option value="deactivate">Nonaktifkan</option>
                                <option value="assign_class">Tambahkan ke Kelas</option>
                                <option value="remove_class">Hapus dari Kelas</option>
                                <option value="delete">Hapus</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="classSelectContainer" style="display: none;">
                            <select class="form-select" name="class_id">
                                <option value="">Pilih Kelas</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-warning" id="applyBulkAction">
                                <i class="fas fa-play"></i> Terapkan
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="selectAll">
                                <i class="fas fa-check-square"></i> Pilih Semua
                            </button>
                        </div>
                    </div>

                    <!-- Students Table -->
                    <div class="table-responsive">
                        <table class="table table-hover datatable">
                            <thead>
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAllCheckbox">
                                    </th>
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
                                    <td>
                                        <input type="checkbox" name="student_ids[]" 
                                               value="{{ $student->id }}" class="student-checkbox">
                                    </td>
                                    <td>{{ $loop->iteration + (($students->currentPage() - 1) * $students->perPage()) }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $student->name }}</div>
                                        <small class="text-muted">{{ $student->email }}</small>
                                    </td>
                                    <td>{{ $student->nisn }}</td>
                                    <td>
                                        @if($student->class)
                                        <span class="badge bg-primary">{{ $student->class->full_name }}</span>
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
                                            <a href="{{ route('teacher.students.show', $student) }}" 
                                               class="btn btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teacher.students.edit', $student) }}" 
                                               class="btn btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('teacher.students.toggle-active', $student) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-{{ $student->is_active ? 'warning' : 'success' }}" 
                                                        title="{{ $student->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas fa-{{ $student->is_active ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            @if($student->class_id)
                                            <form action="{{ route('teacher.students.toggle-treasurer', $student) }}" 
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
                </form>

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
        
        // HAPUS inisialisasi DataTables yang nested di sini.
        
        // PENTING: Sebelum menginisialisasi, cek apakah sudah ada instance
        // Jika ada, hancurkan (destroy) agar bisa diinisialisasi ulang
        if ($.fn.DataTable.isDataTable('.datatable')) {
            // Hancurkan instance lama
            $('.datatable').DataTable().destroy(); 
            // Hapus juga elemen DOM yang ditambahkan DataTables sebelumnya
            $('.datatable').empty(); 
        }

        // Re-inisialisasi DataTable
        // CATATAN: Karena Anda menggunakan Laravel pagination, pastikan 
        // DataTables ini hanya digunakan untuk 'ordering' (sorting) visual,
        // BUKAN untuk paging dan searching.
        $('.datatable').DataTable({
            "paging": false, 
            "searching": false,
            "ordering": true,
            "info": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
        
        // Bulk Actions
        $('#bulkAction').change(function() {
            if ($(this).val() === 'assign_class') {
                $('#classSelectContainer').show();
            } else {
                $('#classSelectContainer').hide();
            }
        });

        // Select All functionality
        $('#selectAllCheckbox').change(function() {
            $('.student-checkbox').prop('checked', $(this).prop('checked'));
        });

        $('#selectAll').click(function() {
            $('.student-checkbox').prop('checked', true);
            $('#selectAllCheckbox').prop('checked', true);
        });

        // Apply bulk action
        $('#applyBulkAction').click(function() {
            const action = $('#bulkAction').val();
            const checkedCount = $('.student-checkbox:checked').length;

            if (!action) {
                alert('Pilih aksi terlebih dahulu!');
                return;
            }

            if (checkedCount === 0) {
                alert('Pilih minimal satu siswa!');
                return;
            }

            // Confirm destructive actions
            if (action === 'delete') {
                if (!confirm('Apakah Anda yakin ingin menghapus ' + checkedCount + ' siswa?')) {
                    return;
                }
            }

            $('#bulkForm').submit();
        });
    }); // <--- Penutup tunggal untuk $(document).ready()
</script>
@endpush