@extends('layouts.teacher')

@section('title', 'Dashboard Guru')
@section('page-title', 'Selamat Datang')
@section('page-subtitle', 'Anda belum memiliki kelas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-4"></i>
                <h3 class="text-muted mb-3">Anda Belum Memiliki Kelas</h3>
                <p class="text-muted mb-4">
                    Sebagai guru, Anda perlu memiliki kelas terlebih dahulu untuk mengelola siswa dan transaksi.
                </p>
                
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <i class="fas fa-plus-circle fa-2x text-primary mb-3"></i>
                                <h5>Buat Kelas Baru</h5>
                                <p class="text-muted">Buat kelas pertama Anda untuk mulai mengelola siswa</p>
                                <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary w-100">
                                    <i class="fas fa-plus me-1"></i> Buat Kelas
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <i class="fas fa-users fa-2x text-success mb-3"></i>
                                <h5>Kelola Siswa</h5>
                                <p class="text-muted">Kelola siswa yang belum memiliki kelas</p>
                                <a href="{{ route('teacher.students.index') }}" class="btn btn-success w-100">
                                    <i class="fas fa-list me-1"></i> Lihat Siswa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-4">
                    <h6><i class="fas fa-info-circle me-2"></i> Informasi:</h6>
                    <ul class="mb-0">
                        <li>Setelah membuat kelas, Anda dapat menambahkan siswa</li>
                        <li>Anda dapat menunjuk salah satu siswa sebagai bendahara</li>
                        <li>Setiap kelas memiliki bendahara yang mengelola keuangan</li>
                        <li>Admin dapat menugaskan kelas kepada guru lain</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection