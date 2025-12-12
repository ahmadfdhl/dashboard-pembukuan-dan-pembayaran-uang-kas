<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Teacher hanya bisa akses kelas mereka sendiri
        $teacherClasses = Classes::where('teacher_id', $user->id)->pluck('id');
        
        // Jika teacher belum punya kelas
        if ($teacherClasses->isEmpty()) {
            return view('teacher.dashboard.empty');
        }

        $stats = [
            'my_classes' => Classes::where('teacher_id', $user->id)->count(),
            'my_students' => User::students()->whereIn('class_id', $teacherClasses)->count(),
            'my_treasurers' => User::treasurers()->whereIn('class_id', $teacherClasses)->count(),
            'active_students' => User::students()
                ->whereIn('class_id', $teacherClasses)
                ->where('is_active', true)
                ->count(),
            'inactive_students' => User::students()
                ->whereIn('class_id', $teacherClasses)
                ->where('is_active', false)
                ->count(),
            'total_transactions' => Transaction::whereIn('class_id', $teacherClasses)->count(),
        ];

        // My classes
        $myClasses = Classes::with(['teacher', 'treasurer', 'students'])
            ->where('teacher_id', $user->id)
            ->get();

        // Recent transactions in my classes
        $recentTransactions = Transaction::with(['user', 'class'])
            ->whereIn('class_id', $teacherClasses)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Students who haven't paid this month
        $unpaidStudents = User::students()
            ->whereIn('class_id', $teacherClasses)
            ->where('is_active', true)
            ->whereDoesntHave('transactions', function ($query) {
                $query->where('status', 'success')
                    ->whereMonth('payment_date', date('m'))
                    ->whereYear('payment_date', date('Y'));
            })
            ->with('class')
            ->take(10)
            ->get();

        $unpaidCount = $unpaidStudents->count();

        return view('teacher.dashboard.index', compact(
            'stats', 
            'myClasses', 
            'recentTransactions', 
            'unpaidStudents',
            'unpaidCount'
        ));
    }
}