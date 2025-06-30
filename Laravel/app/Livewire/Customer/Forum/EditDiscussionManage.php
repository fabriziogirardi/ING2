<?php

namespace App\Livewire\Customer\Forum;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class EditDiscussionManage extends Component implements HasForms
{
    use DiscussionManage, InteractsWithForms;

    public $discussion;

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $this->discussionForm($form);
    }

    public function mount($discussion)
    {
        $this->discussion = $discussion;
        $this->title      = $discussion->title;
        $this->content    = $discussion->content;
        $this->section    = $discussion->forum_section_id;
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $this->discussion->update([
            'title'            => $data['title'],
            'content'          => $data['content'],
            'forum_section_id' => $data['section'],
        ]);
        redirect()->route('forum.discussions.show', ['discussion' => $this->discussion->id])->with(['toast' => 'success', 'message' => 'Discusión actualizada con éxito.']);
    }

    public function render()
    {
        return view('livewire.forum.discussion-form');
    }
}
