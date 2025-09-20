<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTemplate extends Model
{
    use HasFactory;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'ngocrong_data';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'itemtemplate';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'type',
        'gender',
        'name',
        'description',
        'level',
        'strRequire',
        'iconID',
        'part',
        'isUpToUp',
        'isNew',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'gender' => 'integer',
        'level' => 'integer',
        'strRequire' => 'integer',
        'iconID' => 'integer',
        'part' => 'integer',
        'isUpToUp' => 'boolean',
        'isNew' => 'boolean',
    ];

    /**
     * Get the type text attribute.
     */
    public function getTypeTextAttribute()
    {
        $types = [
            0 => 'Trang bị',
            1 => 'Vũ khí',
            2 => 'Giáp',
            3 => 'Phụ kiện',
            4 => 'Tiêu hao',
            5 => 'Đặc biệt',
            6 => 'Ngọc rồng',
            7 => 'Đậu thần',
            8 => 'Sách kỹ năng',
            9 => 'Thú cưng',
            10 => 'Trang sức',
            11 => 'Bùa',
            12 => 'Đá quý',
            13 => 'Vật liệu',
            14 => 'Nhiệm vụ',
            15 => 'Khác',
            16 => 'Capsule',
            17 => 'Thẻ',
            18 => 'Hộp',
            19 => 'Rương',
            20 => 'Túi',
            21 => 'Giấy',
            22 => 'Vé',
            23 => 'Chìa khóa',
            24 => 'Công thức',
            25 => 'Bản đồ',
            26 => 'Thư',
            27 => 'Sự kiện',
            28 => 'Quà tặng',
            29 => 'Đặc biệt 2',
            30 => 'Khác 2',
        ];

        return $types[$this->type] ?? "Type {$this->type}";
    }

    /**
     * Get the gender text attribute.
     */
    public function getGenderTextAttribute()
    {
        $genders = [
            0 => 'Nam',
            1 => 'Nữ',
            2 => 'Trái Đất',
            3 => 'Chung',
        ];

        return $genders[$this->gender] ?? 'Không xác định';
    }

    /**
     * Get the rarity color based on item properties.
     */
    public function getRarityColorAttribute()
    {
        if ($this->isNew) {
            return 'text-warning'; // Gold for new items
        }

        if ($this->level >= 50) {
            return 'text-danger'; // Red for high level
        }

        if ($this->level >= 20) {
            return 'text-info'; // Blue for medium level
        }

        return 'text-success'; // Green for common
    }
}
