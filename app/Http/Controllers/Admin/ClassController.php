<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Classes::with(['teacher', 'treasurer', 'students'])
            ->withCount(['students', 'activeStudents'])
            ->get();
        
        return view('admin.classes.index', compact('classes'));
    }
    
    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')->whereNull('class_id')->get();
        
        return view('admin.classes.create', compact('teachers', 'students'));
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
        
        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dibuat!');
    }
    
    public function show(Classes $class)
    {
        $class->load(['teacher', 'treasurer', 'students']);
        $availableStudents = User::where('role', 'student')
            ->where(function($query) use ($class) {
                $query->whereNull('class_id')
                      ->orWhere('class_id', $class->id);
            })
            ->get();
        
        $unpaidCount = $class->students()
            ->where('is_active', true)
            ->count();
        
        return view('admin.classes.show', compact('class', 'availableStudents', 'unpaidCount'));
    }
    
    public function edit(Classes $class)
    {
        $class->load(['teacher', 'treasurer', 'students']);
        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')
            ->where(function($query) use ($class) {
                $query->whereNull('class_id')
                      ->orWhere('class_id', $class->id);
            })
            ->get();
        
        return view('admin.classes.edit', compact('class', 'teachers', 'students'));
    }
    
    public function update(Request $request, Classes $class)
    {
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
        
        return redirect()->route('admin.classes.show', $class)
            ->with('success', 'Kelas berhasil diperbarui!');
    }
    
    public function destroy(Classes $class)
    {
        // Detach students from class
        User::where('class_id', $class->id)->update(['class_id' => null]);
        
        $class->delete();
        
        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }
    
    public function addStudent(Request $request, Classes $class)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);
        
        $student = User::find($validated['student_id']);
        $student->update(['class_id' => $class->id]);
        
        return redirect()->route('admin.classes.show', $class)
            ->with('success', 'Siswa berhasil ditambahkan ke kelas!');
    }
    
    public function removeStudent(Request $request, Classes $class, User $student)
    {
        if ($student->class_id !== $class->id) {
            return back()->with('error', 'Siswa tidak berada di kelas ini');
        }
        
        $student->update(['class_id' => null]);
        
        return back()->with('success', 'Siswa berhasil dihapus dari kelas!');
    }
}