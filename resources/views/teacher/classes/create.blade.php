{{-- resources/views/teacher/classes/create.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Tambah Kelas Baru')
@section('page-title', 'Tambah Kelas Baru')
@section('page-subtitle', 'Buat kelas baru untuk siswa')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-plus-circle me-2"></i>
                    Form Tambah Kelas
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('teacher.classes.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="Contoh: 1, A, Alpha" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Nama kelompok/rombongan belajar</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="grade" class="form-label">Tingkat <span class="text-danger">*</span></label>
                            <select class="form-select @error('grade') is-invalid @enderror" 
                                    id="grade" name="grade" required>
                                <option value="">Pilih Tingkat</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('grade') == $i ? 'selected' : '' }}>
                                        Kelas {{ $i }}
                                    </option>
                                @endfor
                                <option value="TK" {{ old('grade') == 'TK' ? 'selected' : '' }}>TK</option>
                                <option value="PAUD" {{ old('grade') == 'PAUD' ? 'selected' : '' }}>PAUD</option>
                            </select>
                            @error('grade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="major" class="form-label">Jurusan</label>
                            <input type="text" class="form-control @error('major') is-invalid @enderror" 
                                   id="major" name="major" value="{{ old('major') }}" 
                                   placeholder="Contoh: IPA, IPS, Bahasa">
                            @error('major')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kosongkan jika tidak ada jurusan</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="teacher_id" class="form-label">Wali Kelas <span class="text-danger">*</span></label>
                            <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                    id="teacher_id" name="teacher_id" required>
                                <option value="">Pilih Wali Kelas</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" 
                                            {{ (old('teacher_id') == $teacher->id || auth()->id() == $teacher->id) ? 'selected' : '' }}>
                                        {{ $teacher->name }} ({{ $teacher->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tambahkan Siswa ke Kelas</label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            @if($students->count() > 0)
                                @foreach($students as $student)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" 
                                           name="student_ids[]" value="{{ $student->id }}" 
                                           id="student_{{ $student->id }}"
                                           {{ in_array($student->id, old('student_ids', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="student_{{ $student->id }}">
                                        {{ $student->name }} 
                                        <small class="text-muted">({{ $student->nisn }})</small>
                                    </label>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="fas fa-user-slash fa-2x mb-2"></i>
                                    <p>Tidak ada siswa tanpa kelas</p>
                                    <a href="{{ route('teacher.students.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-1"></i> Tambah Siswa
                                    </a>
                                </div>
                            @endif
                        </div>
                        <small class="text-muted">Pilih siswa yang akan dimasukkan ke kelas ini</small>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('teacher.classes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Kelas
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
                    <i class="fas fa-lightbulb me-2"></i>
                    Petunjuk
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i> Cara Membuat Kelas:</h6>
                    <ol class="mb-0 ps-3">
                        <li>Isi nama kelas (misal: "1" untuk kelas 1A)</li>
                        <li>Pilih tingkat pendidikan</li>
                        <li>Isi jurusan jika diperlukan</li>
                        <li>Pilih wali kelas dari daftar guru</li>
                        <li>Pilih siswa yang akan dimasukkan ke kelas</li>
                        <li>Klik "Simpan Kelas"</li>
                    </ol>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i> Perhatian:</h6>
                    <ul class="mb-0 ps-3">
                        <li>Wali kelas dapat diubah nanti</li>
                        <li>Bendahara dapat ditentukan setelah kelas dibuat</li>
                        <li>Siswa dapat dipindahkan ke kelas lain</li>
                        <li>Pastikan data sudah benar sebelum disimpan</li>
                    </ul>
                </div>
                
                <div class="text-center">
                    <a href="{{ route('teacher.classes.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-list me-1"></i> Lihat Daftar Kelas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection