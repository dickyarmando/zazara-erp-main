<?php

namespace App\Http\Livewire\Config;

use App\Models\PrmConfig;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class PoConfigComponent extends Component
{
    use WithFileUploads;

    public $configs = [];

    public function mount()
    {
        $idGeneral = ['2', '14'];
        $configs = PrmConfig::where('is_status', '1')
            ->whereIn('id', $idGeneral)
            ->select('id', 'code', 'name', 'type', 'value')
            ->orderBy('name', 'asc')
            ->get()->toArray();

        $this->configs = $configs;
    }

    public function render()
    {
        return view('livewire.config.po-config-component');
    }

    public function store()
    {
        foreach ($this->configs as $k => $v) {
            $values = $v['value'];
            if ($v['type'] == 'file') {
                if ($this->configs[$k]['file']) {
                    if (!empty($v['value'])) {
                        Storage::disk('config_disk')->delete($v['value']);
                    }

                    $this->validate([
                        'configs.' . $k . '.file' => 'required|mimes:png,jpg|max:2048',
                    ]);

                    $filename = $this->configs[$k]['file']->store('/', 'config_disk');
                    $values = $filename;
                }
            }

            $configs = PrmConfig::find($v['id']);
            $configs->update([
                'value' => $values
            ]);
        }

        session()->flash('success', 'Saved');
    }

    public function updatedConfigs($value, $key)
    {
        if (str_contains($key, 'file')) {
            $index = explode('.', $key)[0];
            $this->validate([
                "configs.$index.file" => 'required|mimes:png,jpg|max:2048',
            ]);
        }
    }
}
