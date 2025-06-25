<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\ForumDiscussion;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index()
    {
        if (Auth::guard('customer')->check() || Auth::guard('employee')->check() || Auth::guard('manager')->check()) {
            $discussions = ForumDiscussion::all();

            return view('forum.index', compact('discussions'));
        }

        return redirect(route('customer.login'));
    }
}
