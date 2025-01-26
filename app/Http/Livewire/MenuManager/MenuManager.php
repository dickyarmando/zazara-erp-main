<?php

namespace App\Http\Livewire\MenuManager;

use Livewire\Component;
use App\Models\Menu;

class MenuManager extends Component
{
    public $set_id;
    public $title;
    public $parent_id = '0';
    public $url;
    public $scope;
    public $icon;

    public function render()
    {
        $menuLists = Menu::where('parent_id','=','0')->orderBy('ord','asc')->get();
        return view('livewire.menu-manager.menu-manager',[ 'menuLists' => $menuLists ]);
    }

    public function formReset()
    {
        $this->set_id = null;
        $this->title = null;
        $this->parent_id = '0';
        $this->url = null;
        $this->scope = null;
        $this->icon = null;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function edit($id)
    {
        $menu = Menu::find($id);

        $this->set_id = $menu->id;
        $this->title = $menu->title;
        $this->parent_id = $menu->parent_id;
        $this->url = $menu->url;
        $this->scope = $menu->scope;
        $this->icon = $menu->icon;
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|max:100',
            'parent_id' => 'required|integer',
        ]);

        if( empty($this->set_id) )
        {
            Menu::create([
                'title' => $this->title,
                'parent_id' => $this->parent_id,
                'url' => $this->url,
                'scope' => $this->scope,
                'icon' => $this->icon,
            ]);
        }
        else
        {
            $menu = Menu::find($this->set_id);
            $menu->update([
                'title' => $this->title,
                'parent_id' => $this->parent_id,
                'url' => $this->url,
                'scope' => $this->scope,
                'icon' => $this->icon,
            ]);
        }

        //session()->flash('success','Menu saved.');
        $this->formReset();
    }

    public function delete()
    {
        Menu::destroy($this->set_id);
        //session()->flash('success','Menu deleted.');
        $this->formReset();
    }
}
