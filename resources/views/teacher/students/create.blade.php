{{-- resources/views/teacher/students/create.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Tambah Siswa Baru')
@section('page-title', 'Tambah Siswa Baru')
@section('page-subtitle', 'Tambahkan data siswa baru')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-plus me-2"></i>
                    Form Tambah Siswa
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('teacher.students.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="Nama lengkap siswa" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nisn') is-invalid @enderror" 
                                   id="nisn" name="nisn" value="{{ old('nisn') }}" 
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
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="email@contoh.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
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
                                <option value="">Pilih Kelas (Opsional)</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
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
                                <option value="student" {{ old('role', 'student') == 'student' ? 'selected' : '' }}>Siswa Biasa</option>
                                <option value="treasurer" {{ old('role') == 'treasurer' ? 'selected' : '' }}>Bendahara</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pilih "Bendahara" jika siswa akan mengelola keuangan kelas</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="is_active" id="is_active" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Aktifkan akun siswa
                            </label>
                        </div>
                        <small class="text-muted">Jika tidak dicentang, siswa tidak dapat login</small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('teacher.students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-lightbulb me-2"></i> Tips:</h6>
                    <ul class="mb-0">
                        <li>Gunakan email valid untuk notifikasi</li>
                        <li>NISN harus unik untuk setiap siswa</li>
                        <li>Password minimal 8 karakter</li>
                        <li>Pilih kelas jika siswa sudah ditempatkan</li>
                        <li>Bendahara dapat diubah nanti</li>
                    </ul>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i> Perhatian:</h6>
                    <ul class="mb-0">
                        <li>Pastikan data sudah benar sebelum disimpan</li>
                        <li>Siswa bendahara harus memiliki kelas</li>
                        <li>Password akan dikirim ke email siswa</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-bolt me-2"></i>
                    Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-list me-1"></i> Lihat Daftar Siswa
                </a>
                <a href="{{ route('teacher.classes.index') }}" class="btn btn-outline-info w-100 mb-2">
                    <i class="fas fa-chalkboard me-1"></i> Kelola Kelas
                </a>
                <a href="{{ route('teacher.classes.create') }}" class="btn btn-outline-success w-100">
                    <i class="fas fa-plus me-1"></i> Buat Kelas Baru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection