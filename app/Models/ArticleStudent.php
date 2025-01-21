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
 * @method static \Database\Factories\ArticleStudentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleStudent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleStudent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleStudent query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleStudent whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleStudent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleStudent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleStudent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleStudent whereUserId($value)
 * @mixin \Eloquent
 */
class ArticleStudent extends Model
{
    use HasFactory;
}
