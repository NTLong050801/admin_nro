<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerBox extends Model
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
    protected $table = 'arritemboxs';

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
        'arrItemBox',
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
        if (empty($this->arrItemBox)) {
            return [];
        }

        $items = json_decode($this->arrItemBox, true);
        if (!is_array($items)) {
            return [];
        }

        $parsedItems = [];
        foreach ($items as $index => $item) {
            $parsedItem = $this->parseItem($item, $index);
            if ($parsedItem && !empty($parsedItem['id'])) {
                $parsedItems[$index] = $parsedItem;
            }
        }
        return $parsedItems;
    }

    /**
     * Parse individual item from JSON array.
     */
    private function parseItem($itemArray, $index)
    {
        if (!is_array($itemArray) || count($itemArray) < 30) {
            return null;
        }

        // Extract enchants from raw data (indices 24-29)
        $enchants = array_slice($itemArray, 24, 6);

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
            'enchants' => $enchants,
            'upgrade_level' => $enchants[0] ?? -1, // First enchant is upgrade level
            'raw' => $itemArray
        ];
    }

    /**
     * Get the user that owns the box.
     */
    public function user()
    {
        return $this->belongsTo(GameUser::class, 'playerId', 'playerId');
    }
}
