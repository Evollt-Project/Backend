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
 * @method static \Database\Factories\ArticleAdminFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleAdmin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleAdmin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleAdmin query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleAdmin whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleAdmin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleAdmin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleAdmin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleAdmin whereUserId($value)
 * @mixin \Eloquent
 */
class ArticleAdmin extends Model
{
    use HasFactory;
}
