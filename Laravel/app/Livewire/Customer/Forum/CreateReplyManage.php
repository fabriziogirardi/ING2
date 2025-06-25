<?php

namespace App\Livewire\Customer\Forum;

use App\Models\ForumReply;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class CreateReplyManage extends Component implements HasForms
{
    use InteractsWithForms, ReplyManage;

    public $discussion;

    public $content = '';

    public function form(Form $form): Form
    {
        return $this->replyForm($form);
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        ForumReply::create([
            'content'             => $data['content'],
            'person_id'           => auth()->user()->person_id,
            'forum_discussion_id' => $this->discussion->id,
        ]);

        redirect()->route('forum.discussions.show', ['discussion' => $this->discussion->id])->with(['toast' => 'success', 'message' => 'Respuesta creada con éxito.']);
    }

    public function render()
    {
        return view('livewire.forum.reply-form');
    }
}
