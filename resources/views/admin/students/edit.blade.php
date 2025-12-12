{{-- resources/views/teacher/students/edit.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Edit Siswa')
@section('page-title', 'Edit Siswa')
@section('page-subtitle', 'Perbarui data siswa')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit me-2"></i>
                    Form Edit Siswa
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('teacher.students.update', $student) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $student->name) }}" 
                                   placeholder="Nama lengkap siswa" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nisn') is-invalid @enderror" 
                                   id="nisn" name="nisn" value="{{ old('nisn', $student->nisn) }}" 
                                   placeholder="Nomor Induk Siswa Nasional" required>
                            @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $student->email) }}" 
                                   placeholder="email@contoh.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $student->phone) }}" 
                                   placeholder="081234567890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="class_id" class="form-label">Kelas</label>
                            <select class="form-select @error('class_id') is-invalid @enderror" 
                                    id="class_id" name="class_id">
                                <option value="">Tanpa Kelas</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" 
                                        {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->full_name }}
                                    @if($class->teacher)
                                    - Wali: {{ $class->teacher->name }}
                                    @endif
                                </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" name="role" required>
                                <option value="student" {{ old('role', $student->role) == 'student' ? 'selected' : '' }}>Siswa Biasa</option>
                                <option value="treasurer" {{ old('role', $student->role) == 'treasurer' ? 'selected' : '' }}>Bendahara</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="is_active" id="is_active" value="1" 
                                   {{ old('is_active', $student->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Akun aktif
                            </label>
                        </div>
                        <small class="text-muted">Jika tidak dicentang, siswa tidak dapat login</small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                        <div>
                            <a href="{{ route('teacher.students.show', $student) }}" 
                               class="btn btn-info me-2">
                                <i class="fas fa-eye me-1"></i> Lihat Detail
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-user-circle me-2"></i>
                    Profil Siswa
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-initials bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                         style="width: 60px; height: 60px; font-size: 1.5rem;">
                        {{ substr($student->name, 0, 1) }}
                    </div>
                    <h5 class="mt-2">{{ $student->name }}</h5>
                    <div class="text-muted">{{ $student->nisn }}</div>
                </div>
                
                <div class="mb-3">
                    <h6>Status Saat Ini</h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Kelas:</strong></td>
                            <td>
                                @if($student->class)
                                <span class="badge bg-primary">{{ $student->class->full_name }}</span>
                                @else
                                <span class="badge bg-secondary">Tanpa kelas</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Role:</strong></td>
                            <td>
                                @if($student->role === 'treasurer')
                                <span class="badge bg-info">Bendahara</span>
                                @else
                                <span class="badge bg-secondary">Siswa</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Aktif:</strong></td>
                            <td>
                                @if($student->is_active)
                                <span class="badge bg-success">Ya</span>
                                @else
                                <span class="badge bg-danger">Tidak</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Bergabung:</strong></td>
                            <td>{{ $student->created_at->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i> Perhatian:</h6>
                    <ul class="mb-0 small">
                        <li>Mengubah role ke bendahara akan menghapus bendahara lama</li>
                        <li>Siswa tanpa kelas tidak bisa menjadi bendahara</li>
                        <li>Nonaktifkan akun untuk siswa yang sudah lulus</li>
                    </ul>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('teacher.students.show', $student) }}" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i> Lihat Detail Lengkap
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Show/hide class requirement for treasurer
    document.getElementById('role').addEventListener('change', function() {
        const classSelect = document.getElementById('class_id');
        if (this.value === 'treasurer' && !classSelect.value) {
            alert('Siswa bendahara harus memiliki kelas!');
        }
    });
</script>
@endpush