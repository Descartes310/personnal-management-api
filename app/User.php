<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;
use DB;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'deleted_at'
    ];


    /**
     *  Get query of permitted model for user
     * @param string $model the full model class name
     * @param string|array $permissions single permission or array of permission
     * @param string $keyColumn key column to match with team's name
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function getPermittedInstances(string $model, $permissions, string $keyColumn = 'slug')
    {
        if ($permissions instanceof string) {
            $permissions = [$permissions];
        }
        $teamIds1 = DB::table('role_user')->where('user_id', $this->id)->pluck('team_id')->toArray();
        $teamIds2 = DB::table('permission_user')->where('user_id', $this->id)->pluck('team_id')->toArray();
        $teamIds = array_merge($teamIds1, $teamIds2);
        $teamNames = Team::whereIn('id', array_unique($teamIds))->pluck('name');

        $tableName = eval('return (new ' . $model . ')->getTable();');

        $slugs = []; // Team name are also product slug
        foreach ($teamNames as $teamName) {
            if ($this->isAbleTo($permissions, $teamName, true)) {
                $slugs[] = str_replace("$tableName-", '', $teamName);
            }
        }

        return eval('return ' . $model . '::whereIn($keyColumn, $slugs);');
    }


    function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'getPermitted')) {
            $model = 'App\\' . str_replace('getPermitted', '', $method);
            return $this->getPermittedInstances($model, ...$parameters);
        }

        return parent::__call($method, $parameters);
    }
}
