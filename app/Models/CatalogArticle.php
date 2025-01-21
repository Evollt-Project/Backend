<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $catalog_id
 * @property int $article_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CatalogArticleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogArticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogArticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogArticle query()
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogArticle whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogArticle whereCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogArticle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogArticle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogArticle whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CatalogArticle extends Model
{
    use HasFactory;
}
