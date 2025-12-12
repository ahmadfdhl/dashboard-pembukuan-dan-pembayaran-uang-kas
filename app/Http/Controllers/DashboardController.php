<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect berdasarkan role
        switch ($user->role) {
            case 'admin':
            case 'teacher':
                return redirect()->route('teacher.dashboard');

            case 'treasurer':
                return redirect()->route('treasurer.dashboard');

            case 'student':
                return redirect()->route('student.dashboard');

            default:
                return redirect('/');
        }
    }
}
