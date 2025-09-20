<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'ngocrong_option';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'options';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'playerId';

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
        'playerId',
        'cName',
        'cgender',
        'head',
        'ctaskId',
        'ctaskIndex',
        'ctaskCount',
        'cPower',
        'cPowerLimit',
        'mapTemplateId',
        'cx',
        'cy',
        'nClassId',
        'xu',
        'luong',
        'luongKhoa',
        'cHPGoc',
        'cMPGoc',
        'cHP',
        'cMP',
        'cDamGoc',
        'cDefGoc',
        'cCriticalGoc',
        'cTiemNang',
        'skills',
        'arrItemBody',
        'typeTeleport',
        'KSkill',
        'OSkill',
        'CSkill',
        'itemTimes',
        'cStamina',
        'cMaxStamina',
        'cspecialSkill',
        'clanId',
        'securityCode',
        'timeSecurity',
        'items',
        'lastTime',
        'pointEvent',
        'radas',
        'totalGold',
        'isCan',
        'yesterday',
        'timeReceiveNamek',
        'clanPoint',
        'pointVip',
        'pointEventVIP',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'playerId' => 'integer',
        'cgender' => 'integer',
        'head' => 'integer',
        'ctaskId' => 'integer',
        'ctaskIndex' => 'integer',
        'ctaskCount' => 'integer',
        'cPower' => 'integer',
        'cPowerLimit' => 'integer',
        'mapTemplateId' => 'integer',
        'cx' => 'integer',
        'cy' => 'integer',
        'nClassId' => 'integer',
        'xu' => 'integer',
        'luong' => 'integer',
        'luongKhoa' => 'integer',
        'cHPGoc' => 'integer',
        'cMPGoc' => 'integer',
        'cHP' => 'integer',
        'cMP' => 'integer',
        'cDamGoc' => 'integer',
        'cDefGoc' => 'integer',
        'cCriticalGoc' => 'integer',
        'cTiemNang' => 'integer',
        'typeTeleport' => 'integer',
        'cStamina' => 'integer',
        'cMaxStamina' => 'integer',
        'clanId' => 'integer',
        'securityCode' => 'integer',
        'timeSecurity' => 'integer',
        'lastTime' => 'integer',
        'pointEvent' => 'integer',
        'totalGold' => 'integer',
        'isCan' => 'boolean',
        'yesterday' => 'integer',
        'timeReceiveNamek' => 'integer',
        'clanPoint' => 'integer',
        'pointVip' => 'integer',
        'pointEventVIP' => 'integer',
    ];

    /**
     * Get the user that owns the option.
     * Note: GameUser is in different database (ngocrong_game)
     * Mapping: Option.playerId -> GameUser.playerId (not GameUser.id)
     */
    public function user()
    {
        return $this->belongsTo(GameUser::class, 'playerId', 'playerId');
    }

    /**
     * Get the gender text attribute.
     */
    public function getGenderTextAttribute()
    {
        return $this->cgender == 1 ? 'Nữ' : 'Nam';
    }

    /**
     * Get the class text attribute.
     */
    public function getClassTextAttribute()
    {
        $classes = [
            0 => 'Trái Đất',
            1 => 'Namek',
            2 => 'Xayda'
        ];

        return $classes[$this->nClassId] ?? 'Không xác định';
    }

    /**
     * Get formatted power attribute.
     */
    public function getFormattedPowerAttribute()
    {
        if ($this->cPower >= 1000000000) {
            return number_format($this->cPower / 1000000000, 1) . 'B';
        } elseif ($this->cPower >= 1000000) {
            return number_format($this->cPower / 1000000, 1) . 'M';
        } elseif ($this->cPower >= 1000) {
            return number_format($this->cPower / 1000, 1) . 'K';
        }

        return number_format($this->cPower);
    }

    /**
     * Get formatted xu attribute.
     */
    public function getFormattedXuAttribute()
    {
        return number_format($this->xu);
    }

    /**
     * Get formatted luong attribute.
     */
    public function getFormattedLuongAttribute()
    {
        return number_format($this->luong);
    }

    /**
     * Scope a query to search by character name.
     */
    public function scopeSearchByName($query, $name)
    {
        return $query->where('cName', 'like', '%' . $name . '%');
    }

    /**
     * Scope a query to filter by class.
     */
    public function scopeFilterByClass($query, $classId)
    {
        return $query->where('nClassId', $classId);
    }

    /**
     * Scope a query to filter by gender.
     */
    public function scopeFilterByGender($query, $gender)
    {
        return $query->where('cgender', $gender);
    }
}
