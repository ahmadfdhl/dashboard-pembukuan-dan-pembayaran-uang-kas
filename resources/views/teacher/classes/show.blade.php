{{-- resources/views/teacher/classes/show.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Detail Kelas')
@section('page-title', 'Detail Kelas')
@section('page-subtitle', 'Kelola siswa dan informasi kelas')

@section('content')
<div class="row">
    <!-- Class Information -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chalkboard me-2"></i>
                    Informasi Kelas
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="display-6">{{ $class->full_name }}</div>
                    <div class="text-muted">Kode: CLS-{{ str_pad($class->id, 4, '0', STR_PAD_LEFT) }}</div>
                </div>
                
                <div class="mb-3">
                    <h6>Detail Kelas</h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Nama Kelas:</strong></td>
                            <td>{{ $class->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tingkat:</strong></td>
                            <td>{{ $class->grade }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jurusan:</strong></td>
                            <td>{{ $class->major ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Wali Kelas:</strong></td>
                            <td>{{ $class->teacher->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Bendahara:</strong></td>
                            <td>
                                @if($class->treasurer)
                                <span class="badge bg-info">{{ $class->treasurer->name }}</span>
                                @else
                                <span class="badge bg-warning">Belum ditentukan</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                @if($class->deleted_at)
                                <span class="badge bg-danger">Nonaktif</span>
                                @else
                                <span class="badge bg-success">Aktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat:</strong></td>
                            <td>{{ $class->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('teacher.classes.edit', $class) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit Kelas
                    </a>
                    <a href="{{ route('teacher.classes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistik
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="h4">{{ $class->students->count() }}</div>
                        <small class="text-muted">Total Siswa</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4">{{ $class->activeStudents->count() }}</div>
                        <small class="text-muted">Siswa Aktif</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4">
                            {{ $class->students->where('role', 'treasurer')->count() }}
                        </div>
                        <small class="text-muted">Bendahara</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4 text-danger">
                            {{ $unpaidCount }}
                        </div>
                        <small class="text-danger">Belum Bayar</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Student Management -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-users me-2"></i>
                    Daftar Siswa ({{ $class->students->count() }})
                </h6>
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <i class="fas fa-user-plus me-1"></i> Tambah Siswa
                </button>
            </div>
            <div class="card-body">
                <!-- Student Search -->
                <div class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="studentSearch" 
                               placeholder="Cari nama atau NISN...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Students Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="studentsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($class->students as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-bold">{{ $student->name }}</div>
                                    <small class="text-muted">{{ $student->phone ?? '-' }}</small>
                                </td>
                                <td>{{ $student->nisn }}</td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    @if($student->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                    @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->role == 'treasurer')
                                    <span class="badge bg-info">Bendahara</span>
                                    @else
                                    <span class="badge bg-secondary">Siswa</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('teacher.students.edit', $student) }}" 
                                           class="btn btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('teacher.students.toggle-treasurer', $student) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-info">
                                                @if($student->role == 'treasurer')
                                                <i class="fas fa-user-minus"></i>
                                                @else
                                                <i class="fas fa-user-check"></i>
                                                @endif
                                            </button>
                                        </form>
                                        <form action="{{ route('teacher.students.remove-from-class', $student) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="class_id" value="">
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Hapus siswa dari kelas?')">
                                                <i class="fas fa-user-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($class->students->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada siswa</h5>
                    <p class="text-muted">Tambahkan siswa ke kelas ini</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                        <i class="fas fa-user-plus me-1"></i> Tambah Siswa Pertama
                    </button>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Add Student Modal -->
        <div class="modal fade" id="addStudentModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-user-plus me-2"></i>
                            Tambah Siswa ke Kelas
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('teacher.classes.add-student', $class) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Pilih Siswa</label>
                                <select class="form-select" name="student_id" required>
                                    <option value="">Pilih siswa...</option>
                                    @foreach($availableStudents as $student)
                                    <option value="{{ $student->id }}">
                                        {{ $student->name }} ({{ $student->nisn }}) 
                                        @if($student->class_id == $class->id)
                                        <small class="text-muted">(Sudah di kelas ini)</small>
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Pilih siswa yang akan ditambahkan ke kelas</small>
                            </div>
                            
                            @if($availableStudents->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Tidak ada siswa yang tersedia. 
                                <a href="{{ route('teacher.students.create') }}" class="alert-link">
                                    Buat siswa baru
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" 
                                    {{ $availableStudents->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-plus me-1"></i> Tambah
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Student search functionality
    document.getElementById('studentSearch').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#studentsTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
    
    // Initialize DataTable
    $(document).ready(function() {
        $('#studentsTable').DataTable({
            "paging": true,
            "searching": false, // We use custom search
            "ordering": true,
            "info": false,
            "lengthChange": false,
            "pageLength": 10,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>
@endpush
@endsection