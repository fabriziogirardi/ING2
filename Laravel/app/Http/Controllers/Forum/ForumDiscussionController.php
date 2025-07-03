<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\ForumDiscussion;
use Illuminate\Support\Facades\Auth;

class ForumDiscussionController extends Controller
{
    public function create()
    {
        return view('forum.discussions.create');
    }

    public function show(ForumDiscussion $discussion)
    {
        $replies = $discussion->replies()->get();

        return view('forum.discussions.show', [
            'discussion' => $discussion,
            'replies'    => $replies,
        ]);
    }

    public function edit(ForumDiscussion $discussion)
    {
        return view('forum.discussions.edit', [
            'discussion' => $discussion,
        ]);
    }

    public function destroy(ForumDiscussion $discussion)
    {
        if (Auth::getCurrentGuard() === 'manager') {
            $discussion->delete();
            return redirect()->route('forum.index')->with(['toast' => 'success', 'message' => 'Discusión eliminada con exito.']);
        }

        return redirect()->back()->with(['toast' => 'error', 'message' => 'No tienes permiso para eliminar esta respuesta.']);
    }
}
