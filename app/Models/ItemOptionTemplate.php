<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOptionTemplate extends Model
{
    protected $connection = 'ngocrong_data';
    protected $table = 'itemoptiontemplate';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name', 
        'type'
    ];

    /**
     * Get formatted option text with value replacement.
     */
    public function getFormattedText($value)
    {
        $text = $this->name;
        
        // Replace # placeholder with actual value
        if (strpos($text, '#') !== false) {
            $text = str_replace('#', number_format($value), $text);
        }
        
        // Handle special formatting for different types
        switch ($this->type) {
            case 0: // Basic options
                return $text;
            case 1: // Stat options
                return $text;
            case 9: // Set bonus descriptions
                return $text;
            default:
                return $text;
        }
    }

    /**
     * Get color based on option type and ID.
     */
    public function getColor()
    {
        // Set options (type 0, IDs 53, 127-135, 233, 237, 241, 245)
        if ($this->type == 0 && in_array($this->id, [53, 127, 128, 129, 130, 131, 132, 133, 134, 135, 233, 237, 241, 245])) {
            return 'warning'; // Yellow for set items
        }
        
        // Set bonus descriptions (type 9)
        if ($this->type == 9) {
            return 'warning'; // Yellow for set bonuses
        }
        
        // HP/MP options
        if (in_array($this->id, [6, 7])) {
            return $this->id == 6 ? 'success' : 'info'; // Green for HP, Blue for MP
        }
        
        // Damage/Defense options
        if (in_array($this->id, [0, 8, 28])) {
            return 'danger'; // Red for damage/defense
        }
        
        // Special options
        if (in_array($this->id, [30])) {
            return 'secondary'; // Gray for special effects
        }
        
        return 'secondary'; // Default gray
    }

    /**
     * Check if this is a set-related option.
     */
    public function isSetOption()
    {
        return $this->type == 0 && (
            strpos(strtolower($this->name), 'set') !== false ||
            in_array($this->id, [53, 127, 128, 129, 130, 131, 132, 133, 134, 135, 233, 237, 241, 245])
        );
    }

    /**
     * Check if this is a set bonus description.
     */
    public function isSetBonus()
    {
        return $this->type == 9 && (
            strpos($this->name, '5 món') !== false ||
            strpos($this->name, '100%') !== false
        );
    }

    /**
     * Get all set options.
     */
    public static function getSetOptions()
    {
        return static::where('type', 0)
            ->where(function($query) {
                $query->where('name', 'LIKE', '%Set%')
                      ->orWhereIn('id', [53, 127, 128, 129, 130, 131, 132, 133, 134, 135, 233, 237, 241, 245]);
            })
            ->get()
            ->keyBy('id');
    }

    /**
     * Get all set bonus descriptions.
     */
    public static function getSetBonuses()
    {
        return static::where('type', 9)
            ->where(function($query) {
                $query->where('name', 'LIKE', '%5 món%')
                      ->orWhere('name', 'LIKE', '%100%');
            })
            ->get()
            ->keyBy('id');
    }
}
