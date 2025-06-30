<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\ForumDiscussion;

class ForumController extends Controller
{
    public function index()
    {
        $sectionId   = request('section');
        $discussions = ForumDiscussion::when($sectionId, function ($query, $sectionId) {
            return $query->where('forum_section_id', $sectionId);
        })->get();

        return view('forum.index', compact('discussions'));
    }
}
