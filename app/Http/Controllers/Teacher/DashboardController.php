<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $unpaidCount = 0;
        $unpaidStudents = collect();

        // Stats for Admin
        if ($user->isAdmin()) {
            $stats = [
                'total_classes' => Classes::count(),
                'total_students' => User::students()->count(),
                'total_teachers' => User::teachers()->count(),
                'total_treasurers' => User::treasurers()->count(),
                'inactive_students' => User::students()->where('is_active', false)->count(),
                'total_transactions' => Transaction::count(),
                'total_cash' => Transaction::where('status', 'success')->sum('amount'),
            ];

            // Recent transactions
            $recentTransactions = Transaction::with(['user', 'class'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            // Classes with most students
            $popularClasses = Classes::withCount(['students', 'activeStudents'])
                ->orderBy('students_count', 'desc')
                ->take(5)
                ->get();

            // Monthly income chart data
            $monthlyIncome = Transaction::select(
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('YEAR(payment_date) as year'),
                DB::raw('SUM(amount) as total')
            )
                ->where('status', 'success')
                ->whereYear('payment_date', date('Y'))
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            $teacherClasses = Classes::where('teacher_id', $user->id)->pluck('id');
            if ($teacherClasses) {
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

                $unpaidCount = $unpaidStudents->count(); // Tambahkan ini
            }


            return view('teacher.dashboard.index', compact('stats', 'recentTransactions', 'popularClasses', 'monthlyIncome', 'unpaidStudents', 'unpaidCount'));
        }

        // Stats for Teacher
        $teacherClasses = Classes::where('teacher_id', $user->id)->pluck('id');

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

        return view('teacher.dashboard.index', compact('stats', 'myClasses', 'recentTransactions', 'unpaidStudents'));
    }
}
