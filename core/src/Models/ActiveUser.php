<?php namespace EvolutionCMS\Models;

use Illuminate\Database\Eloquent;

/**
 * @property string $sid
 * @property int $internalKey
 * @property string $username
 * @property int $lasthit
 * @property string $action
 * @property int $id
 */
class ActiveUser extends Eloquent\Model
{
	protected $primaryKey = 'sid';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'internalKey' => 'int',
		'lasthit' => 'int',
		'id' => 'int'
	];

	protected $fillable = [
		'internalKey',
		'username',
		'lasthit',
		'action',
		'id'
	];

	public function scopeLocked(Eloquent\Builder $builder, $id, $userId = null)
    {
        if ($userId === null) {
            $userId = evolutionCMS()->getLoginUserID();
        }

        return $builder->where('action', '=', (int)$id)
            ->where('internalKey', '!=', $userId)
            ->orderBy('lasthit', 'DESC');
    }
}
