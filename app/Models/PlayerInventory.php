<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerInventory extends Model
{
    use HasFactory;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'ngocrong_player';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'arritembags';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'playerId';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

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
        'maxCount',
        'arrItemBag',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'playerId' => 'integer',
        'maxCount' => 'integer',
    ];

    /**
     * Get the parsed items from JSON.
     */
    public function getParsedItemsAttribute()
    {
        if (empty($this->arrItemBag)) {
            return [];
        }

        $items = json_decode($this->arrItemBag, true);
        if (!is_array($items)) {
            return [];
        }

        return collect($items)->map(function ($item, $index) {
            return $this->parseItem($item, $index);
        })->filter(function ($item) {
            return !empty($item['id']);
        });
    }

    /**
     * Parse individual item from JSON array.
     */
    private function parseItem($itemArray, $index)
    {
        if (!is_array($itemArray) || count($itemArray) < 30) {
            return null;
        }

        return [
            'slot' => $index,
            'id' => $itemArray[0] ?? 0,
            'position' => $itemArray[1] ?? 0,
            'type' => $itemArray[2] ?? 0,
            'quantity' => $itemArray[3] ?? 0,
            'isLock' => $itemArray[4] ?? false,
            'damage' => $itemArray[5] ?? 0,
            'defense' => $itemArray[6] ?? 0,
            'critical' => $itemArray[7] ?? 0,
            'hp' => $itemArray[8] ?? 0,
            'mp' => $itemArray[9] ?? 0,
            'options' => $itemArray[18] ?? [],
            'enchants' => array_slice($itemArray, 24, 6),
            'raw' => $itemArray
        ];
    }

    /**
     * Get the user that owns the inventory.
     */
    public function user()
    {
        return $this->belongsTo(GameUser::class, 'playerId', 'playerId');
    }
}
