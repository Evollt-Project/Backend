<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $article_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ArticleTeacherFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTeacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTeacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTeacher query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTeacher whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTeacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTeacher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTeacher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleTeacher whereUserId($value)
 * @mixin \Eloquent
 */
class ArticleTeacher extends Model
{
    use HasFactory;
}
