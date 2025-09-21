<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $connection = 'ngocrong_data';
    protected $table = 'task';
    protected $primaryKey = 'taskId';
    public $timestamps = false;

    protected $fillable = [
        'taskId',
        'name',
        'detail',
        'subNames',
        'tasks',
        'mapTasks',
        'contentInfo',
        'counts'
    ];

    protected $casts = [
        'name' => 'array',
        'detail' => 'array',
        'subNames' => 'array',
        'tasks' => 'array',
        'mapTasks' => 'array',
        'contentInfo' => 'array',
        'counts' => 'array'
    ];

    /**
     * Get formatted task name with ID for a specific planet (0=Trái Đất, 1=Namek, 2=Xayda)
     */
    public function getDisplayNameAttribute()
    {
        $name = is_array($this->name) ? ($this->name[0] ?? 'Không có tên') : $this->name;
        return "ID: {$this->taskId} - {$name}";
    }

    /**
     * Get task name for specific planet
     */
    public function getNameForPlanet($planet = 0)
    {
        return is_array($this->name) ? ($this->name[$planet] ?? $this->name[0] ?? 'Không có tên') : $this->name;
    }

    /**
     * Get task detail for specific planet
     */
    public function getDetailForPlanet($planet = 0)
    {
        return is_array($this->detail) ? ($this->detail[$planet] ?? $this->detail[0] ?? 'Không có mô tả') : $this->detail;
    }

    /**
     * Check if task has sub tasks for specific planet
     */
    public function hasSubTasks($planet = 0)
    {
        if (!is_array($this->subNames)) return false;
        $subNames = $this->subNames[$planet] ?? [];
        return !empty($subNames) && is_array($subNames) && count($subNames) > 0;
    }

    /**
     * Get sub tasks count for specific planet
     */
    public function getSubTasksCountAttribute()
    {
        // Get max sub tasks count across all planets
        if (!is_array($this->subNames)) return 0;

        $maxCount = 0;
        foreach ($this->subNames as $planetSubNames) {
            if (is_array($planetSubNames)) {
                $maxCount = max($maxCount, count($planetSubNames));
            }
        }
        return $maxCount;
    }

    /**
     * Get sub task names for specific planet
     */
    public function getSubNamesForPlanet($planet = 0)
    {
        return is_array($this->subNames) ? ($this->subNames[$planet] ?? []) : [];
    }

    /**
     * Get formatted detail
     */
    public function getFormattedDetailAttribute()
    {
        return $this->getDetailForPlanet(0);
    }
}
