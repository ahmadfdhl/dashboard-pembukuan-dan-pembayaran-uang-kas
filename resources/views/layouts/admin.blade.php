{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Admin') - Sistem Kas Sekolah</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --admin-primary: #1a237e;
            --admin-secondary: #283593;
            --admin-accent: #3949ab;
            --admin-light: #e8eaf6;
            --admin-dark: #0d47a1;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f5f7fb;
        }
        
        .admin-sidebar {
            background: linear-gradient(180deg, var(--admin-primary) 10%, var(--admin-dark) 100%);
            min-height: 100vh;
            position: fixed;
            width: 250px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
        }
        
        .admin-brand {
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
            text-decoration: none;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        
        .admin-brand i {
            font-size: 1.8rem;
            margin-right: 10px;
        }
        
        .sidebar-divider {
            border-top: 1px solid rgba(255,255,255,.15);
            margin: 1rem 1rem;
        }
        
        .nav-item .nav-link {
            color: rgba(255,255,255,.9);
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-radius: 5px;
            margin: 2px 10px;
        }
        
        .nav-item .nav-link i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .nav-item .nav-link:hover {
            color: white;
            background-color: var(--admin-accent);
        }
        
        .nav-item .nav-link.active {
            color: white;
            background-color: var(--admin-accent);
            box-shadow: 0 2px 5px rgba(0,0,0,.2);
        }
        
        .admin-main {
            margin-left: 250px;
            padding: 20px;
        }
        
        .admin-topbar {
            background-color: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .admin-card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.15);
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .admin-card-header {
            background-color: white;
            border-bottom: 1px solid #e3e6f0;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
            font-weight: 600;
        }
        
        .stat-card {
            border-left: 5px solid;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.classes {
            border-left-color: var(--admin-primary);
        }
        
        .stat-card.students {
            border-left-color: #1cc88a;
        }
        
        .stat-card.teachers {
            border-left-color: #36b9cc;
        }
        
        .stat-card.cash {
            border-left-color: #f6c23e;
        }
        
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.7;
        }
        
        .btn-admin {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
            color: white;
        }
        
        .btn-admin:hover {
            background-color: var(--admin-secondary);
            border-color: var(--admin-secondary);
            color: white;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #5a5c69;
            background-color: #f8f9fc;
        }
        
        .badge-admin {
            background-color: var(--admin-accent);
            color: white;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            
            .admin-main {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Admin Sidebar -->
    <div class="admin-sidebar">
        <a class="admin-brand" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-user-shield"></i>
            <span>Admin Panel</span>
        </a>
        
        <!-- Navigation -->
        <div class="sidebar-nav pt-3">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}" 
                       href="{{ route('admin.classes.index') }}">
                        <i class="fas fa-chalkboard"></i>
                        <span>Manajemen Kelas</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" 
                       href="{{ route('admin.students.index') }}">
                        <i class="fas fa-users"></i>
                        <span>Manajemen Siswa</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-user-tie"></i>
                        <span>Manajemen Guru</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Transaksi</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-cogs"></i>
                        <span>Pengaturan</span>
                    </a>
                </li>
                
                <li class="nav-item mt-4">
                    <div class="sidebar-divider"></div>
                    <a class="nav-link text-warning" href="{{ route('teacher.dashboard') }}">
                        <i class="fas fa-exchange-alt"></i>
                        <span>Switch to Teacher</span>
                    </a>
                </li>
                
                <li class="nav-item mt-auto">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="nav-link text-danger" href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="admin-main">
        <!-- Topbar -->
        <div class="admin-topbar">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0 text-dark">@yield('page-title', 'Dashboard')</h4>
                    <small class="text-muted">@yield('page-subtitle', 'Administrator Panel')</small>
                </div>
                <div class="col-md-6 text-end">
                    <div class="dropdown">
                        <button class="btn btn-admin dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown">
                            <i class="fas fa-user-cog me-2"></i>
                            {{ auth()->user()->name }}
                            <span class="badge bg-light text-dark ms-1">Admin</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-user-circle me-2"></i> Profil
                            </a></li>
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i> Pengaturan
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-info" href="{{ route('teacher.dashboard') }}">
                                    <i class="fas fa-exchange-alt me-2"></i> Mode Guru
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <main>
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            
            <!-- Page Content -->
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="mt-5 text-center text-muted">
            <hr>
            <p class="small mb-0">
                &copy; {{ date('Y') }} Sistem Pembukuan Kas Sekolah - Admin Panel v1.0
            </p>
        </footer>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                },
                "order": [[0, 'desc']]
            });
        });
        
        // SweetAlert Confirm Delete
        function confirmDelete(formId, message = 'Data yang dihapus tidak dapat dikembalikan.') {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>