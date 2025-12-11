{{-- resources/views/teacher/dashboard/index.blade.php --}}
@extends('layouts.teacher')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan aktivitas dan statistik')

@section('content')
    <div class="row">
        <!-- Stat Cards -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card classes h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Kelas Saya
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['my_classes'] }}
                            </div>
                            <div class="mt-2">
                                <span class="badge bg-primary">
                                    <i class="fas fa-chalkboard-teacher me-1"></i>
                                    Wali Kelas
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard stat-icon text-primary"></i>
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
                                {{ $stats['my_students'] }}
                            </div>
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-user-check me-1"></i>
                                    Aktif: {{ $stats['active_students'] }}
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
            <div class="card stat-card treasurers h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Bendahara
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['my_treasurers'] }}
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('teacher.students.index') }}" class="text-decoration-none">
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
            <div class="card stat-card inactive h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Total Transaksi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_transactions'] }}
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('teacher.classes.index') }}" class="text-decoration-none">
                                    <small class="text-secondary">
                                        <i class="fas fa-history me-1"></i>
                                        Lihat Riwayat
                                    </small>
                                </a>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt stat-icon text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if (isset($unpaidCount) && $unpaidCount > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-danger">
                    <div class="card-body text-center">
                        <div class="text-danger mb-2">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                        <h4 class="text-danger font-weight-bold">
                            {{ $unpaidCount }} Siswa Belum Bayar Bulan Ini
                        </h4>
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Klik tombol di bawah untuk melihat detail siswa yang belum bayar
                        </p>
                        <a href="#unpaid-students-section" class="btn btn-danger btn-sm mt-2">
                            <i class="fas fa-arrow-down me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- My Classes -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chalkboard me-2"></i>
                        Kelas Saya
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Kelas</th>
                                    <th>Bendahara</th>
                                    <th>Siswa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myClasses as $class)
                                    <tr>
                                        <td>
                                            <strong>{{ $class->full_name }}</strong>
                                            <div class="small text-muted">
                                                Wali: {{ $class->teacher->name ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($class->treasurer)
                                                <span class="badge bg-info">
                                                    {{ $class->treasurer->name }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning">Belum ada</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ $class->active_student_count }} aktif
                                            </span>
                                            <span class="badge bg-secondary ms-1">
                                                {{ $class->student_count - $class->active_student_count }} nonaktif
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('teacher.classes.show', $class) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teacher.classes.edit', $class) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-chalkboard-teacher fa-2x mb-3"></i>
                                                <p>Belum ada kelas yang diajar</p>
                                                <a href="{{ route('teacher.classes.create') }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus me-1"></i> Buat Kelas Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if ($myClasses->isNotEmpty())
                        <div class="text-center mt-3">
                            <a href="{{ route('teacher.classes.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-list me-1"></i> Lihat Semua Kelas
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
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
                                            @if ($transaction->status == 'success')
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

    <!-- Unpaid Students Section -->
    <div class="row" id="unpaid-students-section">
        <!-- Students Haven't Paid -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Siswa Belum Bayar Bulan Ini
                    </h6>
                </div>
                <div class="card-body">
                    @if ($unpaidStudents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>NISN</th>
                                        <th>Kelas</th>
                                        <th>No. Telepon</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($unpaidStudents as $student)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $student->name }}</div>
                                                <small class="text-muted">{{ $student->email }}</small>
                                            </td>
                                            <td>{{ $student->nisn }}</td>
                                            <td>{{ $student->class->name ?? '-' }}</td>
                                            <td>{{ $student->phone ?? '-' }}</td>
                                            <td>
                                                @if ($student->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('teacher.students.edit', $student) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="tel:{{ $student->phone }}"
                                                    class="btn btn-sm btn-info {{ $student->phone ? '' : 'disabled' }}">
                                                    <i class="fas fa-phone"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="text-success">Selamat!</h5>
                            <p class="text-muted">Semua siswa sudah membayar iuran bulan ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>
                        Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.classes.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-2"></i> Tambah Kelas
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.students.create') }}" class="btn btn-success w-100">
                                <i class="fas fa-user-plus me-2"></i> Tambah Siswa
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.classes.index') }}" class="btn btn-info w-100">
                                <i class="fas fa-list me-2"></i> Lihat Semua Kelas
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('teacher.students.index') }}" class="btn btn-warning w-100">
                                <i class="fas fa-users me-2"></i> Kelola Siswa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
