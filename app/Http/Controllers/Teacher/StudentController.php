<?php
 
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        // Filter berdasarkan kelas yang diajar (untuk teacher)
        $query = User::where('role', 'student')
            ->with('class');
        
        if (auth()->user()->isTeacher() && !auth()->user()->isAdmin()) {
            $teacherClasses = Classes::where('teacher_id', auth()->id())
                ->pluck('id');
            $query->whereIn('class_id', $teacherClasses);
        }
        
        $students = $query->paginate(20);
        $classes = Classes::all();
        
        return view('teacher.students.index', compact('students', 'classes'));
    }
    
    public function create()
    {
        $classes = Classes::all();
        return view('teacher.students.create', compact('classes'));
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
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nisn' => $validated['nisn'],
            'phone' => $validated['phone'],
            'class_id' => $validated['class_id'],
            'role' => 'student',
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('teacher.students.index')
            ->with('success', 'Siswa berhasil ditambahkan!');
    }
    
    public function edit(User $student)
    {
        // Authorization check
        $this->authorizeStudentAccess($student);
        
        $classes = Classes::all();
        return view('teacher.students.edit', compact('student', 'classes'));
    }
    
    public function update(Request $request, User $student)
    {
        // Authorization check
        $this->authorizeStudentAccess($student);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $student->id,
            'nisn' => 'required|string|max:20|unique:users,nisn,' . $student->id,
            'phone' => 'nullable|string|max:15',
            'class_id' => 'nullable|exists:classes,id',
            'is_active' => 'boolean',
        ]);
        
        $student->update($validated);
        
        return redirect()->route('teacher.students.index')
            ->with('success', 'Data siswa berhasil diperbarui!');
    }
    
    public function toggleTreasurer(User $student)
    {
        // Authorization check
        $this->authorizeStudentAccess($student);
        
        if ($student->role !== 'student') {
            return back()->with('error', 'Hanya siswa yang bisa dijadikan bendahara');
        }
        
        if (!$student->class_id) {
            return back()->with('error', 'Siswa harus memiliki kelas terlebih dahulu');
        }
        
        $class = Classes::find($student->class_id);
        
        if ($student->role === 'student' && $student->id === $class->treasurer_id) {
            // Remove as treasurer
            $class->update(['treasurer_id' => null]);
            return back()->with('success', 'Siswa berhasil dihapus dari jabatan bendahara');
        } else {
            // Set as treasurer
            $class->update(['treasurer_id' => $student->id]);
            return back()->with('success', 'Siswa berhasil dijadikan bendahara');
        }
    }
    
    private function authorizeStudentAccess(User $student)
    {
        if (auth()->user()->isAdmin()) {
            return true;
        }
        
        if (auth()->user()->isTeacher()) {
            $teacherClasses = Classes::where('teacher_id', auth()->id())->pluck('id');
            if (!$teacherClasses->contains($student->class_id)) {
                abort(403, 'Unauthorized access.');
            }
        }
    }
}