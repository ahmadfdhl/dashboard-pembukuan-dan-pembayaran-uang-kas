<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'student')
            ->with(['class' => function($q) {
                $q->with('teacher');
            }]);
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan kelas
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        $students = $query->orderBy('name')->paginate(20);
        $classes = Classes::all();
        
        // Stats for filter
        $totalStudents = User::students()->count();
        $activeStudents = User::students()->where('is_active', true)->count();
        $inactiveStudents = User::students()->where('is_active', false)->count();
        $treasurerStudents = User::treasurers()->count();
        
        return view('admin.students.index', compact(
            'students', 
            'classes', 
            'totalStudents',
            'activeStudents',
            'inactiveStudents',
            'treasurerStudents'
        ));
    }
    
    public function create()
    {
        $classes = Classes::all();
        
        return view('admin.students.create', compact('classes'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nisn' => 'required|string|max:20|unique:users',
            'phone' => 'nullable|string|max:15',
            'class_id' => 'nullable|exists:classes,id',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'boolean',
            'role' => ['required', Rule::in(['student', 'treasurer'])],
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nisn' => $validated['nisn'],
            'phone' => $validated['phone'],
            'class_id' => $validated['class_id'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'is_active' => $validated['is_active'] ?? true,
        ]);
        
        // Jika role treasurer, update class treasurer_id
        if ($validated['role'] === 'treasurer' && $validated['class_id']) {
            Classes::where('id', $validated['class_id'])
                ->update(['treasurer_id' => $user->id]);
        }
        
        return redirect()->route('admin.students.index')
            ->with('success', 'Siswa berhasil ditambahkan!');
    }
    
    public function show(User $student)
    {
        $student->load(['class.teacher', 'class.treasurer']);
        
        // Get student's transaction history
        $transactions = \App\Models\Transaction::where('user_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Get payment statistics
        $paymentStats = [
            'total_paid' => \App\Models\Transaction::where('user_id', $student->id)
                ->where('status', 'success')
                ->sum('amount'),
            'total_transactions' => \App\Models\Transaction::where('user_id', $student->id)->count(),
            'pending_transactions' => \App\Models\Transaction::where('user_id', $student->id)
                ->where('status', 'pending')
                ->count(),
            'success_transactions' => \App\Models\Transaction::where('user_id', $student->id)
                ->where('status', 'success')
                ->count(),
        ];
        
        return view('admin.students.show', compact('student', 'transactions', 'paymentStats'));
    }
    
    public function edit(User $student)
    {
        $classes = Classes::all();
        
        return view('admin.students.edit', compact('student', 'classes'));
    }
    
    public function update(Request $request, User $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->id,
            'nisn' => 'required|string|max:20|unique:users,nisn,' . $student->id,
            'phone' => 'nullable|string|max:15',
            'class_id' => 'nullable|exists:classes,id',
            'is_active' => 'boolean',
            'role' => ['required', Rule::in(['student', 'treasurer'])],
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nisn' => $validated['nisn'],
            'phone' => $validated['phone'],
            'class_id' => $validated['class_id'],
            'role' => $validated['role'],
            'is_active' => $validated['is_active'] ?? true,
        ];
        
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }
        
        // Handle treasurer assignment
        if ($validated['role'] === 'treasurer' && $validated['class_id']) {
            // Remove treasurer role from previous treasurer in same class
            User::where('class_id', $validated['class_id'])
                ->where('role', 'treasurer')
                ->where('id', '!=', $student->id)
                ->update(['role' => 'student']);
            
            // Update class treasurer
            Classes::where('id', $validated['class_id'])
                ->update(['treasurer_id' => $student->id]);
        } elseif ($student->role === 'treasurer' && ($validated['role'] !== 'treasurer' || $student->class_id != $validated['class_id'])) {
            // Remove from class treasurer if role changed or class changed
            if ($student->class_id) {
                Classes::where('id', $student->class_id)
                    ->update(['treasurer_id' => null]);
            }
        }
        
        $student->update($updateData);
        
        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Data siswa berhasil diperbarui!');
    }
    
    public function destroy(User $student)
    {
        // Check if student has transactions
        $hasTransactions = \App\Models\Transaction::where('user_id', $student->id)->exists();
        
        if ($hasTransactions) {
            return back()->with('error', 'Tidak dapat menghapus siswa yang sudah memiliki transaksi.');
        }
        
        // Remove from class treasurer if applicable
        if ($student->role === 'treasurer' && $student->class_id) {
            Classes::where('id', $student->class_id)
                ->update(['treasurer_id' => null]);
        }
        
        $student->delete();
        
        return redirect()->route('admin.students.index')
            ->with('success', 'Siswa berhasil dihapus!');
    }
    
    public function toggleActive(User $student)
    {
        $student->update([
            'is_active' => !$student->is_active
        ]);
        
        $status = $student->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return back()->with('success', "Siswa berhasil {$status}!");
    }
    
    public function toggleTreasurer(User $student)
    {
        if (!$student->class_id) {
            return back()->with('error', 'Siswa harus memiliki kelas terlebih dahulu');
        }
        
        if ($student->role === 'treasurer') {
            // Remove as treasurer
            $student->update(['role' => 'student']);
            Classes::where('id', $student->class_id)
            ->update(['treasurer_id' => null]);
            return back()->with('success', 'Siswa berhasil dihapus dari jabatan bendahara');
        } else {
            // Set as treasurer
            // Remove previous treasurer first
            $previousTreasurer = User::where('class_id', $student->class_id)
            ->where('role', 'treasurer')
            ->first();
            
            if ($previousTreasurer) {
                $previousTreasurer->update(['role' => 'student']);
            }

            $student->update(['role' => 'treasurer']);
            Classes::where('id', $student->class_id)
                ->update(['treasurer_id' => $student->id]);
            return back()->with('success', 'Siswa berhasil dijadikan bendahara');
        }
    }
    
    public function removeFromClass(Request $request, User $student)
    {
        // Check if student is treasurer
        if ($student->role === 'treasurer' && $student->class_id) {
            Classes::where('id', $student->class_id)
                ->update(['treasurer_id' => null]);
        }

        $student->update([
            'class_id' => null,
            'role' => 'student' // Reset role to student
        ]);

        return back()->with('success', 'Siswa berhasil dihapus dari kelas!');
    }
    
    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,assign_class,remove_class',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
            'class_id' => 'required_if:action,assign_class|exists:classes,id',
        ]);

        $studentIds = $request->student_ids;

        switch ($request->action) {
            case 'activate':
                User::whereIn('id', $studentIds)->update(['is_active' => true]);
                $message = 'Siswa berhasil diaktifkan!';
                break;

            case 'deactivate':
                User::whereIn('id', $studentIds)->update(['is_active' => false]);
                $message = 'Siswa berhasil dinonaktifkan!';
                break;

            case 'assign_class':
                User::whereIn('id', $studentIds)->update(['class_id' => $request->class_id]);
                $message = 'Siswa berhasil ditambahkan ke kelas!';
                break;

            case 'remove_class':
                // Remove treasurer role if any student is treasurer
                $treasurers = User::whereIn('id', $studentIds)
                    ->where('role', 'treasurer')
                    ->get();

                foreach ($treasurers as $treasurer) {
                    if ($treasurer->class_id) {
                        Classes::where('id', $treasurer->class_id)
                            ->update(['treasurer_id' => null]);
                    }
                }

                User::whereIn('id', $studentIds)->update([
                    'class_id' => null,
                    'role' => 'student'
                ]);
                $message = 'Siswa berhasil dihapus dari kelas!';
                break;

            case 'delete':
                // Check if any student has transactions
                $studentsWithTransactions = \App\Models\Transaction::whereIn('user_id', $studentIds)
                    ->pluck('user_id')
                    ->unique();

                if ($studentsWithTransactions->count() > 0) {
                    return back()->with('error', 'Tidak dapat menghapus siswa yang sudah memiliki transaksi.');
                }

                // Remove treasurer assignments
                $treasurers = User::whereIn('id', $studentIds)
                    ->where('role', 'treasurer')
                    ->get();

                foreach ($treasurers as $treasurer) {
                    if ($treasurer->class_id) {
                        Classes::where('id', $treasurer->class_id)
                            ->update(['treasurer_id' => null]);
                    }
                }

                User::whereIn('id', $studentIds)->delete();
                $message = 'Siswa berhasil dihapus!';
                break;
        }

        return back()->with('success', $message);
    }

    public function export(Request $request)
    {
        $query = User::where('role', 'student')
            ->with('class');

        // Apply filters if any
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $students = $query->orderBy('name')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="siswa_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($students) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // Header
            fputcsv($file, [
                'NISN',
                'Nama',
                'Email',
                'Telepon',
                'Kelas',
                'Status',
                'Role',
                'Tanggal Dibuat',
                'Terakhir Diupdate'
            ]);

            // Data
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->nisn,
                    $student->name,
                    $student->email,
                    $student->phone ?? '-',
                    $student->class ? $student->class->full_name : '-',
                    $student->is_active ? 'Aktif' : 'Nonaktif',
                    $student->role === 'treasurer' ? 'Bendahara' : 'Siswa',
                    $student->created_at->format('d/m/Y H:i'),
                    $student->updated_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}