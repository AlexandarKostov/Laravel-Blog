<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination;
    #[Url()]
    public string $sort = 'desc';

    #[Url()]
    public string $search = '';

    #[Url()]
    public string $category = '';

    public function setSort($sort): void
    {
        $this->sort = ($sort == 'desc') ? 'desc' : 'asc';
    }


    #[On('search')]
    public function updateSearch($search): void
    {
            $this->search = $search;
    }

    #[Computed()]
    public function posts()
    {
        return Post::where('published_at', '<=', Carbon::now())->with('author', 'categories')
            ->orderBy('published_at', $this->sort)
            ->when(Category::where('slug', $this->category)->first() ?? false, function ($query) {
                $query->category($this->category);
            })
            ->where('title', 'like', "%{$this->search}%")
            ->paginate(3);
    }

    public function render(): View
    {
        return view('livewire.post-list');
    }
}
