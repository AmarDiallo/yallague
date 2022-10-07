<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "sub_categories";
    protected $fillable = ["name", "quantity", "id_category"];
    protected $softDelete = true;

    public function __construct($name = null, $quantity = null, $id_category = null)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->id_category = $id_category;
    }

    public function category()
    {
        return $this->BelongsTo(Category::class, 'id_category', 'id');
    }

    public function ventes() 
    {
        return $this->hasMany(Vente::class, 'id_sub_category', 'id');
    }
}
