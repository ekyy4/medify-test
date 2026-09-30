<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function KategoriItems()
    {
        return $this->belongsToMany(KategoriItem::class, 'kategori_item_master_item', 'master_item_id', 'kategori_id');
    }
}
