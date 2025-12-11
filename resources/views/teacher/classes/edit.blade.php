{{-- resources/views/teacher/classes/edit.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Edit Kelas')
@section('page-title', 'Edit Kelas')
@section('page-subtitle', 'Perbarui data kelas')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>
                        Form Edit Kelas
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.classes.update', $class) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Kelas <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $class->name) }}"
                                    placeholder="Contoh: 1, A, Alpha" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="grade" class="form-label">Tingkat <span class="text-danger">*</span></label>
                                <select class="form-select @error('grade') is-invalid @enderror" id="grade"
                                    name="grade" required>
                                    <option value="">Pilih Tingkat</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}"
                                            {{ old('grade', $class->grade) == $i ? 'selected' : '' }}>
                                            Kelas {{ $i }}
                                        </option>
                                    @endfor
                                    <option value="TK" {{ old('grade', $class->grade) == 'TK' ? 'selected' : '' }}>TK
                                    </option>
                                    <option value="PAUD" {{ old('grade', $class->grade) == 'PAUD' ? 'selected' : '' }}>
                                        PAUD</option>
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
                                    id="major" name="major" value="{{ old('major', $class->major) }}"
                                    placeholder="Contoh: IPA, IPS, Bahasa">
                                @error('major')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="teacher_id" class="form-label">Wali Kelas <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('teacher_id') is-invalid @enderror" id="teacher_id"
                                    name="teacher_id" required>
                                    <option value="">Pilih Wali Kelas</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}"
                                            {{ old('teacher_id', $class->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }} ({{ $teacher->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="treasurer_id" class="form-label">Bendahara Kelas</label>
                                <select class="form-select @error('treasurer_id') is-invalid @enderror" id="treasurer_id"
                                    name="treasurer_id">
                                    <option value="">Pilih Bendahara</option>
                                    @foreach ($students as $student)
                                        @if ($student->class_id == $class->id)
                                            <!-- Hanya siswa di kelas ini -->
                                            <option value="{{ $student->id }}"
                                                {{ old('treasurer_id', $class->treasurer_id) == $student->id ? 'selected' : '' }}>
                                                {{ $student->name }} ({{ $student->nisn }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('treasurer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Pilih salah satu siswa sebagai bendahara</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label d-block">Status Kelas</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="status_active"
                                        value="active" {{ $class->deleted_at ? '' : 'checked' }}>
                                    <label class="form-check-label" for="status_active">
                                        <span class="badge bg-success">Aktif</span>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="status_inactive"
                                        value="inactive" {{ $class->deleted_at ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_inactive">
                                        <span class="badge bg-danger">Nonaktif</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.classes.show', $class) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <div>
                                <a href="{{ route('teacher.classes.show', $class) }}" class="btn btn-info me-2">
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
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Kelas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Detail Kelas</h6>
                        <ul class="list-unstyled">
                            <li><strong>Nama Lengkap:</strong> {{ $class->full_name }}</li>
                            <li><strong>Wali Kelas:</strong> {{ $class->teacher->name ?? '-' }}</li>
                            <li><strong>Bendahara:</strong> {{ $class->treasurer->name ?? 'Belum ditentukan' }}</li>
                            <li><strong>Jumlah Siswa:</strong> {{ $class->students->count() }}</li>
                            <li><strong>Dibuat:</strong> {{ $class->created_at->format('d/m/Y H:i') }}</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i> Perhatian:</h6>
                        <ul class="mb-0 ps-3 small">
                            <li>Nonaktifkan kelas jika sudah tidak digunakan</li>
                            <li>Bendahara harus siswa dari kelas ini</li>
                            <li>Pastikan data sudah benar sebelum disimpan</li>
                        </ul>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('teacher.classes.show', $class) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-users me-1"></i> Kelola Siswa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
