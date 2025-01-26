<?php

namespace App\Http\Livewire\Masters;

use App\Models\PrmConfig;
use Livewire\Component;

class ConfigurationManager extends Component
{
    public $configs = [];

    public function mount()
    {
        $configs = PrmConfig::where('is_status', '1')
            ->select('id', 'code', 'name', 'type', 'value')
            ->orderBy('name', 'asc')
            ->get()->toArray();

        $this->configs = $configs;
    }

    public function render()
    {
        return view('livewire.masters.configuration-manager');
    }

    public function store()
    {
        foreach ($this->configs as $k => $v) {
            $configs = PrmConfig::find($v['id']);
            $configs->update([
                'value' => $v['value']
            ]);
        }

        session()->flash('success', 'Saved');
    }
}
