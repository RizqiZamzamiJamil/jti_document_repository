<?php

namespace App\Models\Setting;


use App\Models\Master\DosenModel;
use App\Models\Master\MahasiswaModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class UserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 's_user';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username',
        'name',
        'password',
        'group_id',
        'is_active',
        'avatar_dir',
        'avatar_url',
        'hp',
        'email',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function group()
    {
        return $this->belongsTo(GroupModel::class, 'group_id', 'group_id');
    }

    public function role()
    {
        return $this->hasOne(GroupModel::class, 'group_id', 'group_id');
    }

    public function getRole()
    {
        return $this->role->group_code;
    }

    public function getRoleName()
    {
        return $this->role->group_name;
    }

    public function hasRole($role)
    {
        return ($this->role->group_code === $role);
    }

    public function getRoute()
    {
        return strtolower($this->role->group_code);
    }

    public function isSuperAdmin()
    {
        return ($this->role->group_code == 'SPR' && $this->role->group_id == 1);
    }

    public function isAdmin()
    {
        return ($this->role->group_code == 'ADM' && $this->role->group_id == 2);
    }

    public function isDosen()
    {
        return ($this->role->group_code == 'DSN' && $this->role->group_id == 3);
    }

    public function isMahasiswa()
    {
        return ($this->role->group_code == 'MHS' && $this->role->group_id == 4);
    }


    /*
        Relasi terhadap data GURU
    */
    public function getUserDosen()
    {
        return $this->hasOne(DosenModel::class, 'user_id', 'user_id');
    }



    /*
        Relasi terhadap data SISWA
    */
    public function getUserMahasiswa()
    {
        return $this->hasOne(MahasiswaModel::class, 'user_id', 'user_id');
    }



    public static function insertData($request)
    {
        $data = $request->except(['_token', '_method']);
        $data['created_by'] = Auth::user()->user_id;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['password'] = Hash::make($data['password']);

        return self::insert($data);     // return status insert data
    }

    public static function updateData($id, $request)
    {
        $data = $request->except(['_token', '_method']);
        $data['updated_by'] = Auth::user()->user_id;
        $data['updated_at'] = date('Y-m-d H:i:s');

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return self::where('user_id', $id)
            ->update($data);
    }

    public static function deleteData($id)
    {
        $data['deleted_by'] = Auth::user()->user_id;
        $data['deleted_at'] = date('Y-m-d H:i:s');

        return self::where('user_id', $id)
            ->update($data);
    }
}
