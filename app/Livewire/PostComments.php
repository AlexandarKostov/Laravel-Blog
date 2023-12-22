<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class PostComments extends Component
{
    use WithPagination;
    public Post $post;

    #[Rule('required|min:3|max:100')]
    public string $comment;
    public function postComment(): void
    {
        if (auth()->guest())
        {
            return;
        }
        $this->validateOnly('comment');
        $this->post->comments()->create([
            'comment' => $this->comment,
            'user_id' => auth()->id(),
        ]);

        $this->reset('comment');
    }

    #[Computed()]
    public function comments(): ?\Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
      return $this?->post?->comments()->with('user')->latest()->paginate(5);
    }

    public function render()
    {
        return view('livewire.post-comments');
    }
}
