<?php

namespace Shebaoting\FilamentPlugins\Models;

use Illuminate\Database\Eloquent\Model;
use Shebaoting\FilamentPlugins\Services\CRUDGenerator;
use App\Models\Team;

/**
 * @property integer $id
 * @property string $module
 * @property string $name
 * @property string $comment
 * @property boolean $timestamps
 * @property boolean $soft_deletes
 * @property boolean $migrated
 * @property boolean $generated
 * @property string $created_at
 * @property string $updated_at
 * @property TableCol[] $tableCols
 */
class Table extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['module', 'name', 'comment', 'timestamps', 'soft_deletes', 'migrated', 'generated', 'created_at', 'updated_at'];

    protected $casts = [
        'timestamps' => 'boolean',
        'soft_deletes' => 'boolean',
        'migrated' => 'boolean',
        'generated' => 'boolean'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tableCols()
    {
        return $this->hasMany('Shebaoting\FilamentPlugins\Models\TableCol');
    }

    public function migrate()
    {
        $generator = new CRUDGenerator(table: $this, migration: true);
        $generator->generate();
    }

    public function getTable()
    {
        return config('filament-plugins.database_prefix') ? config('filament-plugins.database_prefix') . '_tables' : 'tables';
    }

    public function team()
    {
        return $this->belongsTo(Team::class); // 假设你的租户模型是 Team
    }
}
