<?php

namespace App\Http\Livewire\Pengaturan;

use App\Models\MsElemenP5;
use App\Models\PrmProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class UsersManager extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $sortColumn = "users.name";
    public $sortOrder = "asc";
    public $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';
    public $searchKeyword = '';
    public $roleFilter = '';
    public $set_id;

    public $nis;
    public $nisn;
    public $name;
    public $gender;
    public $angkatan;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $agama_id;
    public $address;
    public $phone;
    public $email;
    public $father_name;
    public $father_phone;
    public $mother_name;
    public $mother_phone;
    public $wali_name;
    public $wali_phone;
    public $importFile;

    public function render()
    {
        $user = User::orderby($this->sortColumn,$this->sortOrder)
            ->leftJoin('prm_roles', 'prm_roles.id', '=', 'users.role')
            ->select('users.id', 'users.name', 'users.username', 'prm_roles.id as role_id', 'prm_roles.name as role_name');
        if(!empty($this->searchKeyword)){
            $user->orWhere('users.username','like',"%".$this->searchKeyword."%")->where('is_active', '1');
            $user->orWhere('users.name','like',"%".$this->searchKeyword."%")->where('is_active', '1');
            $user->orWhere('prm_roles.name','like',"%".$this->searchKeyword."%")->where('is_active', '1');
        }
        $users = $user->where('is_active', '1')->paginate($this->perPage);

        return view('livewire.pengaturan.users-manager', [ 'users' => $users ]);
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function sortOrder($columnName="")
    {
        $caretOrder = "up";
        if($this->sortOrder == 'asc'){
            $this->sortOrder = 'desc';
            $caretOrder = "down";
        }else{
            $this->sortOrder = 'asc';
            $caretOrder = "up";
        }
        $this->sortLink = '<i class="sorticon fa-solid fa-caret-'.$caretOrder.'"></i>';
        $this->sortColumn = $columnName;
    }

    public function closeModal()
    {
        $this->formReset();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatchBrowserEvent('close-modal');
    }

    public function formReset()
    {
        $this->set_id = null;
        $this->nis = null;
        $this->nisn = null;
        $this->name = null;
        $this->gender = null;
        $this->angkatan = null;
        $this->tempat_lahir = null;
        $this->tanggal_lahir = null;
        $this->agama_id = null;
        $this->address = null;
        $this->email = null;
        $this->phone = null;
        $this->father_name = null;
        $this->father_phone = null;
        $this->mother_name = null;
        $this->mother_phone = null;
        $this->wali_name = null;
        $this->wali_phone = null;
        $this->importFile = null;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        $rules = [
            'nis'  => [
                'required',
                'max:20',
                Rule::unique('ms_siswa', 'nis')->where('is_active', '1'),
            ],
            'nisn'  => [
                'required',
                'max:20',
                Rule::unique('ms_siswa', 'nisn')->where('is_active', '1'),
            ],
            'name'  => 'required|max:100',
            'gender'  => 'required',
            'angkatan'  => 'max:4',
            'tempat_lahir'  => '',
            'tanggal_lahir'  => 'date|nullable',
            'agama_id'  => '',
            'address'  => '',
            'email'  => '',
            'phone'  => '',
            'father_name'  => '',
            'father_phone'  => '',
            'mother_name'  => '',
            'mother_phone'  => '',
            'wali_name'  => '',
            'wali_phone'  => '',
        ];

        if(empty($this->set_id))
        {
            $valid = $this->validate($rules);

            $userData = new User();
            $userData->name = $valid['name'];
            $userData->username = $valid['nis'];
            $userData->email = $valid['email'];
            $userData->password = Hash::make($valid['nis']);
            $userData->role = 3;
            $userData->save();
            $userId = $userData->id;

            $valid['user_id'] = $userId;
            $valid['created_by'] = Auth::user()->id;
            $valid['updated_by'] = Auth::user()->id;

            MsSiswa::create($valid);
        }
        else
        {
            $rules['nis'] = ['required', 'max:20', Rule::unique('ms_siswa', 'nis')->where('is_active', '1')->ignore($this->set_id, 'id')];
            $rules['nisn'] = ['required', 'max:20', Rule::unique('ms_siswa', 'nisn')->where('is_active', '1')->ignore($this->set_id, 'id')];
            $valid = $this->validate($rules);
            $valid['updated_by'] = Auth::user()->id;

            $siswa = MsSiswa::find($this->set_id);
            $siswa->update($valid);
        }

        $this->formReset();
        session()->flash('success','Saved.');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function edit($id)
    {
        $siswa = MsSiswa::find($id);
        $this->set_id = $id;
        $this->nis = $siswa->nis;
        $this->nisn = $siswa->nisn;
        $this->name = $siswa->name;
        $this->gender = $siswa->gender;
        $this->angkatan = $siswa->angkatan;
        $this->tempat_lahir = $siswa->tempat_lahir;
        $this->tanggal_lahir = $siswa->tanggal_lahir;
        $this->agama_id = $siswa->agama_id;
        $this->address = $siswa->address;
        $this->email = $siswa->email;
        $this->phone = $siswa->phone;
        $this->father_name = $siswa->father_name;
        $this->father_phone = $siswa->father_phone;
        $this->mother_name = $siswa->mother_name;
        $this->mother_phone = $siswa->mother_phone;
        $this->wali_name = $siswa->wali_name;
        $this->wali_phone = $siswa->wali_phone;
    }

    public function delete($id)
    {
        $this->set_id = $id;
    }

    public function destroy()
    {
        $valid = [
            'is_active' => '0',
            'deleted_at' => Carbon::now()->toDateTimeString(),
            'deleted_by' => Auth::user()->id
        ];
        $tp = MsSiswa::find($this->set_id);
        $tp->update($valid);
        $this->formReset();
        session()->flash('success','Deleted.');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function import()
    {
        $valid = $this->validate([
            'importFile' => 'required|max:2048|mimes:xls,xlsx',
        ]);

        $file = $this->importFile->store('/', 'local');
        $tmp = storage_path('app').'/'.$file;
        $import = new SiswaImport();
        $import->import($tmp);

        $this->formReset();
        session()->flash('success','Saved.');
        $this->dispatchBrowserEvent('close-modal');
    }

    public function export()
    {
        return Excel::download(new SiswaExport(), 'Data Siswa.xlsx');
    }
}
