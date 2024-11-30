<?php

namespace App\Http\Livewire\Auth;

use App\Models\PrmCompanies;
use Livewire\Component;
use Livewire\WithFileUploads;

class ChangeImages extends Component
{
    use WithFileUploads;

    public $avatar;
    public $picture;

    public function mount()
    {
        $prmCompanies = PrmCompanies::find(1);

        $this->picture = $prmCompanies->picture;
    }

    public function render()
    {
        return view('livewire.auth.change-images');
    }

    public function updatedAvatar()
    {
        $this->validate([
            'avatar' => 'required|image|max:2048|mimes:jpg,jpeg,png,webp,svg,gif',
        ]);

        $filename = $this->avatar->store('/', 'picture_disk');
        PrmCompanies::where('id', '1')->update(
            [
                'picture' => $filename
            ]
        );

        $this->avatar = NULL;
        $this->picture = $filename;
    }
}
