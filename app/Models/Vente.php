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
    protected $fillable = ["amount", "quantity", "id_sub_category", "id_user"];
    protected $softDelete = true;

    public function __construct($amount = null, $quantity = null, $id_sub_category = null)
    {
        $this->amount = $amount;
        $this->quantity = $quantity;
        $this->id_sub_category = $id_sub_category;
        // $this->id_user = $id_user;
    }

    public function sub_category()
    {
        return $this->BelongsTo(SubCategory::class, 'id_sub_category', 'id');
    }

    public function user()
    {
        return $this->BelongsTo(User::class, 'id_user', 'id');
    }
}
