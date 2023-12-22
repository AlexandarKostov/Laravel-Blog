<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class LikeButton extends Component
{
    #[Reactive]
    public Post $post;

    public function toggleLike(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        if (auth()->guest())
        {
            return $this->redirect(route('login'), true);
        }
        $user = auth()->user();
        if ($user->hasLiked($this->post))
        {
            $user->likes()->detach($this->post);
            return $user;
        }
        $user->likes()->attach($this->post);
        return $user;
    }

    public function render()
    {
        return view('livewire.like-button');
    }
}
