<?php
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $classes = Classes::with(['teacher', 'treasurer'])->get();
        } else {
            $classes = Classes::where('teacher_id', auth()->id())
                ->with(['teacher', 'treasurer'])
                ->get();
        }
        
        return view('teacher.classes.index', compact('classes'));
    }
    
    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')->whereNull('class_id')->get();
        
        return view('teacher.classes.create', compact('teachers', 'students'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'grade' => 'required|string|max:10',
            'major' => 'nullable|string|max:50',
            'teacher_id' => 'required|exists:users,id',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);
        
        $class = Classes::create([
            'name' => $validated['name'],
            'grade' => $validated['grade'],
            'major' => $validated['major'],
            'teacher_id' => $validated['teacher_id'],
        ]);
        
        if (!empty($validated['student_ids'])) {
            User::whereIn('id', $validated['student_ids'])
                ->update(['class_id' => $class->id]);
        }
        
        return redirect()->route('teacher.classes.index')
            ->with('success', 'Kelas berhasil dibuat!');
    }
    
    public function show(Classes $class)
    {
        $this->authorizeClassAccess($class);
        
        $class->load(['teacher', 'treasurer', 'students']);
        $availableStudents = User::where('role', 'student')
            ->where(function($query) use ($class) {
                $query->whereNull('class_id')
                      ->orWhere('class_id', $class->id);
            })
            ->get();
        
        // Untuk sekarang, hitung semua siswa aktif sebagai belum bayar
        // Nanti akan diganti dengan logika pembayaran
        $unpaidCount = $class->students()
            ->where('is_active', true)
            ->count();
        
        return view('teacher.classes.show', compact('class', 'availableStudents', 'unpaidCount'));
    }
    
    public function edit(Classes $class)
    {
        $this->authorizeClassAccess($class);
        
        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')
            ->where(function($query) use ($class) {
                $query->whereNull('class_id')
                      ->orWhere('class_id', $class->id);
            })
            ->get();
        
        return view('teacher.classes.edit', compact('class', 'teachers', 'students'));
    }
    
    public function update(Request $request, Classes $class)
    {
        $this->authorizeClassAccess($class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'grade' => 'required|string|max:10',
            'major' => 'nullable|string|max:50',
            'teacher_id' => 'required|exists:users,id',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
            'treasurer_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);
        
        $class->update([
            'name' => $validated['name'],
            'grade' => $validated['grade'],
            'major' => $validated['major'],
            'teacher_id' => $validated['teacher_id'],
            'treasurer_id' => $validated['treasurer_id'] ?? null,
        ]);
        
        // Update student assignments
        if (isset($validated['student_ids'])) {
            // Remove all students first
            User::where('class_id', $class->id)->update(['class_id' => null]);
            
            // Assign selected students
            if (!empty($validated['student_ids'])) {
                User::whereIn('id', $validated['student_ids'])
                    ->update(['class_id' => $class->id]);
            }
        }
        
        // Update treasurer role
        if ($validated['treasurer_id']) {
            // Remove treasurer role from previous treasurer
            User::where('class_id', $class->id)
                ->where('role', 'treasurer')
                ->where('id', '!=', $validated['treasurer_id'])
                ->update(['role' => 'student']);
            
            // Set new treasurer
            User::where('id', $validated['treasurer_id'])
                ->update(['role' => 'treasurer']);
        }
        
        // Handle status
        if ($validated['status'] === 'inactive' && !$class->deleted_at) {
            $class->delete();
        } elseif ($validated['status'] === 'active' && $class->deleted_at) {
            $class->restore();
        }
        
        return redirect()->route('teacher.classes.show', $class)
            ->with('success', 'Kelas berhasil diperbarui!');
    }
    
    public function destroy(Classes $class)
    {
        $this->authorizeClassAccess($class);
        
        // Detach students from class
        User::where('class_id', $class->id)->update(['class_id' => null]);
        
        $class->delete();
        
        return redirect()->route('teacher.classes.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }
    
    public function addStudent(Request $request, Classes $class)
    {
        $this->authorizeClassAccess($class);
        
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);
        
        $student = User::find($validated['student_id']);
        $student->update(['class_id' => $class->id]);
        
        return redirect()->route('teacher.classes.show', $class)
            ->with('success', 'Siswa berhasil ditambahkan ke kelas!');
    }
    
    public function removeStudent(Request $request, Classes $class, User $student)
    {
        $this->authorizeClassAccess($class);
        
        if ($student->class_id !== $class->id) {
            return back()->with('error', 'Siswa tidak berada di kelas ini');
        }
        
        $student->update(['class_id' => null]);
        
        return back()->with('success', 'Siswa berhasil dihapus dari kelas!');
    }
    
    private function authorizeClassAccess(Classes $class)
    {
        if (auth()->user()->isAdmin()) {
            return true;
        }
        
        if (auth()->user()->isTeacher() && $class->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this class.');
        }
        
        if (auth()->user()->isStudent() || auth()->user()->isTreasurer()) {
            abort(403, 'Unauthorized access.');
        }
        
        return true;
    }
}