<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "categories";
    protected $fillable = ["name"];
    protected $softDelete = true;

    public function __construct($name = null)
    {
        $this->name = $name;
    }

    public function subCategories() {
        return $this->hasMany(SubCategory::class, 'id_category', 'id');
    }
}
