<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $module_id
 * @property int $completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleUser whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleUser whereModuleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ModuleUser whereUserId($value)
 * @mixin \Eloquent
 */
class ModuleUser extends Model
{
    use HasFactory;
}
