<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "ventes";
    protected $fillable = ["amount", "quantity", "id_sub_category"];
    protected $softDelete = true;

    public function __construct($quantity = null, $amount = null, $id_sub_category = null)
    {
        $this->quantity = $quantity;
        $this->amount = $amount;
        $this->id_sub_category = $id_sub_category;
    }

    public function sub_category()
    {
        return $this->BelongsTo(SubCategory::class, 'id_sub_category', 'id');
    }
}
