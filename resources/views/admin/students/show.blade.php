{{-- resources/views/teacher/students/show.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Detail Siswa')
@section('page-title', 'Detail Siswa')
@section('page-subtitle', 'Informasi lengkap siswa')

@section('content')
<div class="row">
    <!-- Student Information -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-circle me-2"></i>
                    Profil Siswa
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-circle mb-3">
                        <div class="avatar-initials bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ substr($student->name, 0, 1) }}
                        </div>
                    </div>
                    <h4>{{ $student->name }}</h4>
                    <div class="text-muted">{{ $student->nisn }}</div>
                </div>
                
                <div class="mb-3">
                    <h6>Informasi Kontak</h6>
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $student->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Telepon:</strong></td>
                            <td>{{ $student->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kelas:</strong></td>
                            <td>
                                @if($student->class)
                                <span class="badge bg-primary">{{ $student->class->full_name }}</span>
                                @else
                                <span class="badge bg-secondary">Belum ada kelas</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Wali Kelas:</strong></td>
                            <td>{{ $student->class->teacher->name ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="mb-3">
                    <h6>Status Akun</h6>
                    <table class="table table-sm">
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
                            <td><strong>Status:</strong></td>
                            <td>
                                @if($student->is_active)
                                <span class="badge bg-success">Aktif</span>
                                @else
                                <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Bergabung:</strong></td>
                            <td>{{ $student->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Terakhir Update:</strong></td>
                            <td>{{ $student->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('teacher.students.edit', $student) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit Profil
                    </a>
                    <form action="{{ route('teacher.students.toggle-active', $student) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-{{ $student->is_active ? 'danger' : 'success' }} w-100">
                            <i class="fas fa-{{ $student->is_active ? 'ban' : 'check' }} me-1"></i>
                            {{ $student->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Akun
                        </button>
                    </form>
                    @if($student->class_id)
                    <form action="{{ route('teacher.students.toggle-treasurer', $student) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-{{ $student->role === 'treasurer' ? 'secondary' : 'info' }} w-100">
                            <i class="fas fa-{{ $student->role === 'treasurer' ? 'user-minus' : 'user-check' }} me-1"></i>
                            {{ $student->role === 'treasurer' ? 'Hapus Bendahara' : 'Jadikan Bendahara' }}
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('teacher.students.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Payment Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-chart-pie me-2"></i>
                    Statistik Pembayaran
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="h4">Rp {{ number_format($paymentStats['total_paid'], 0, ',', '.') }}</div>
                        <small class="text-muted">Total Dibayar</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4">{{ $paymentStats['total_transactions'] }}</div>
                        <small class="text-muted">Total Transaksi</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4 text-success">{{ $paymentStats['success_transactions'] }}</div>
                        <small class="text-success">Berhasil</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h4 text-warning">{{ $paymentStats['pending_transactions'] }}</div>
                        <small class="text-warning">Pending</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Transaction History -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>
                    Riwayat Transaksi
                </h6>
                @if($student->class_id)
                <a href="" 
                   class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Transaksi
                </a>
                @endif
            </div>
            <div class="card-body">
                @if($transactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Tanggal</th>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                            <tr>
                                <td>
                                    <strong>{{ $transaction->invoice_number }}</strong>
                                </td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td class="fw-bold">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($transaction->payment_method == 'cash')
                                    <span class="badge bg-secondary">Tunai</span>
                                    @elseif($transaction->payment_method == 'transfer')
                                    <span class="badge bg-info">Transfer</span>
                                    @else
                                    <span class="badge bg-primary">Online</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->status == 'success')
                                    <span class="badge bg-success">Berhasil</span>
                                    @elseif($transaction->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @else
                                    <span class="badge bg-danger">Gagal</span>
                                    @endif
                                </td>
                                <td>{{ $transaction->notes ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada transaksi</h5>
                    <p class="text-muted">Siswa ini belum melakukan pembayaran</p>
                    @if($student->class_id)
                    <a href="" 
                       class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Catat Pembayaran Pertama
                    </a>
                    @endif
                </div>
                @endif
                
                @if($transactions->count() > 0)
                <div class="text-center mt-3">
                    <a href="" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-list me-1"></i> Lihat Semua Transaksi
                    </a>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Class Information -->
        @if($student->class)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-chalkboard me-2"></i>
                    Informasi Kelas
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Detail Kelas</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Nama Kelas:</strong></td>
                                <td>{{ $student->class->full_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Wali Kelas:</strong></td>
                                <td>{{ $student->class->teacher->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Bendahara:</strong></td>
                                <td>{{ $student->class->treasurer->name ?? 'Belum ditentukan' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Total Siswa:</strong></td>
                                <td>{{ $student->class->students->count() }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Aksi Kelas</h6>
                        <div class="d-grid gap-2">
                            <a href="{{ route('teacher.classes.show', $student->class) }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> Lihat Detail Kelas
                            </a>
                            <a href="{{ route('teacher.classes.edit', $student->class) }}" 
                               class="btn btn-outline-warning">
                                <i class="fas fa-edit me-1"></i> Edit Kelas
                            </a>
                            @if($student->role !== 'treasurer')
                            <form action="{{ route('teacher.students.remove-from-class', $student) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-outline-danger" 
                                        onclick="return confirm('Hapus siswa dari kelas?')">
                                    <i class="fas fa-sign-out-alt me-1"></i> Keluarkan dari Kelas
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection