<?php

namespace App\Livewire\Customer\Forum;

use App\Models\ForumDiscussion;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class CreateDiscussionManage extends Component implements HasForms
{
    use DiscussionManage, InteractsWithForms;

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $this->discussionForm($form);
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $discussion = ForumDiscussion::create([
            'title'            => $data['title'],
            'content'          => $data['content'],
            'customer_id'      => auth()->id(),
            'forum_section_id' => $data['section'],
        ]);
        redirect()->route('forum.discussions.show', ['discussion' => $discussion->id])->with(['toast', 'success', 'message' => 'Discusión creada con éxito.']);
    }

    public function render()
    {
        return view('livewire.forum.discussion-form');
    }
}
