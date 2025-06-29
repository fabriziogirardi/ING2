<?php

namespace App\Livewire\Customer\Forum;

use App\Models\ForumReply;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class EditReplyManage extends Component implements HasForms
{
    use InteractsWithForms, ReplyManage;

    public $reply;

    public $content = '';

    public function form(Form $form): Form
    {
        return $this->replyForm($form);
    }

    public function mount(ForumReply $reply)
    {
        $this->reply   = $reply;
        $this->content = $reply->content;
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $this->reply->update([
            'content' => $data['content'],
        ]);

        redirect()->route('forum.discussions.show', ['discussion' => $this->reply->forum_discussion_id])->with(['toast' => 'success', 'message' => 'Respuesta actualizada con éxito.']);
    }

    public function render()
    {
        return view('livewire.forum.reply-form');
    }
}
