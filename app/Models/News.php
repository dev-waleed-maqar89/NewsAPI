<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'article', 'image', 'published_at', 'admin_id'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function scopePublished(Builder $query)
    {
        $query->where('published_at', '!=', 'null');
    }
}