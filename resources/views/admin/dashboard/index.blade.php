{{-- resources/views/admin/dashboard/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')
@section('page-subtitle', 'Ringkasan statistik sekolah')

@section('content')
<div class="row">
    <!-- Stat Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card classes h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #1a237e;">
                            Total Kelas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['total_classes'] }}
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-chalkboard-teacher me-1"></i>
                                {{ $stats['total_teachers'] }} Guru
                            </small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chalkboard stat-icon" style="color: #1a237e;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card students h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Siswa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['total_students'] }}
                        </div>
                        <div class="mt-2">
                            <small class="text-success">
                                <i class="fas fa-user-check me-1"></i>
                                Aktif: {{ $stats['total_students'] - $stats['inactive_students'] }}
                            </small>
                            <small class="text-danger ms-2">
                                <i class="fas fa-user-times me-1"></i>
                                Nonaktif: {{ $stats['inactive_students'] }}
                            </small>
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
        <div class="card stat-card teachers h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Bendahara
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['total_treasurers'] }}
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('admin.students.index') }}?role=treasurer" class="text-decoration-none">
                                <small class="text-info">
                                    <i class="fas fa-hand-holding-usd me-1"></i>
                                    Kelola Bendahara
                                </small>
                            </a>
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
        <div class="card stat-card cash h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Kas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($stats['total_cash'], 0, ',', '.') }}
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="fas fa-receipt me-1"></i>
                                {{ $stats['total_transactions'] }} Transaksi
                            </small>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave stat-icon text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Popular Classes -->
    <div class="col-lg-6 mb-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-trophy me-2"></i>
                    Kelas Terpopuler
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Nama Kelas</th>
                                <th>Wali Kelas</th>
                                <th>Jumlah Siswa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popularClasses as $class)
                            <tr>
                                <td>
                                    <strong>{{ $class->full_name }}</strong>
                                    @if($class->treasurer)
                                    <div class="small">
                                        <span class="badge bg-info">Bendahara: {{ $class->treasurer->name }}</span>
                                    </div>
                                    @endif
                                </td>
                                <td>{{ $class->teacher->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $class->active_students_count }} aktif</span>
                                    <span class="badge bg-secondary">{{ $class->students_count - $class->active_students_count }} nonaktif</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-sm btn-admin">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-chalkboard-teacher fa-2x mb-3"></i>
                                    <p>Belum ada kelas</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="col-lg-6 mb-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-history me-2"></i>
                    Transaksi Terbaru
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $transaction->user->name }}</div>
                                    <small class="text-muted">
                                        {{ $transaction->created_at->format('d/m H:i') }}
                                    </small>
                                </td>
                                <td>{{ $transaction->class->name ?? '-' }}</td>
                                <td class="fw-bold">
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if($transaction->status == 'success')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i> Berhasil
                                    </span>
                                    @elseif($transaction->status == 'pending')
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock me-1"></i> Pending
                                    </span>
                                    @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times me-1"></i> Gagal
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="fas fa-receipt fa-2x mb-3"></i>
                                    <p>Belum ada transaksi</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Students Haven't Paid -->
    <div class="col-lg-8 mb-4">
        <div class="admin-card">
            <div class="admin-card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Siswa Belum Bayar Bulan Ini ({{ $unpaidCount }})
                </h6>
                <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-admin">
                    <i class="fas fa-list me-1"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($unpaidStudents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Wali Kelas</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unpaidStudents as $student)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $student->name }}</div>
                                    <small class="text-muted">{{ $student->email }}</small>
                                </td>
                                <td>{{ $student->nisn }}</td>
                                <td>{{ $student->class->full_name ?? '-' }}</td>
                                <td>{{ $student->class->teacher->name ?? '-' }}</td>
                                <td>
                                    @if($student->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                    @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.students.show', $student) }}" 
                                       class="btn btn-sm btn-admin">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="text-success">Selamat!</h5>
                    <p class="text-muted">Semua siswa sudah membayar iuran bulan ini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-bolt me-2"></i>
                    Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('admin.classes.create') }}" class="btn btn-admin">
                        <i class="fas fa-plus me-2"></i> Tambah Kelas
                    </a>
                    <a href="{{ route('admin.students.create') }}" class="btn btn-success">
                        <i class="fas fa-user-plus me-2"></i> Tambah Siswa
                    </a>
                    <a href="{{ route('admin.classes.index') }}" class="btn btn-info">
                        <i class="fas fa-chalkboard me-2"></i> Kelola Kelas
                    </a>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-warning">
                        <i class="fas fa-users me-2"></i> Kelola Siswa
                    </a>
                </div>
                
                <div class="mt-4">
                    <h6 class="text-muted mb-3">Statistik Bulanan</h6>
                    @if($monthlyIncome->count() > 0)
                    <div class="list-group">
                        @foreach($monthlyIncome as $income)
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-calendar me-2 text-primary"></i>
                                {{ DateTime::createFromFormat('!m', $income->month)->format('F') }}
                            </div>
                            <span class="badge bg-success rounded-pill">
                                Rp {{ number_format($income->total, 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                        <p>Belum ada data pemasukan</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection