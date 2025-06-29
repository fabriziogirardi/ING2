<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\ForumReply;

class ForumReplyController extends Controller
{
    public function edit(ForumReply $reply)
    {
        return view('forum.discussions.reply.edit', [
            'reply' => $reply,
        ]);
    }

    public function destroy(ForumReply $reply)
    {
        $reply->delete();

        return redirect()->back()->with(['toast' => 'success', 'message' => 'Respuesta eliminada con exito.']);
    }
}
