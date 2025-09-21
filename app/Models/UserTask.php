<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTask extends Model
{
    use HasFactory;

    protected $connection = 'ngocrong_player';
    protected $table = 'arrtasks';
    protected $primaryKey = 'playerId';
    public $timestamps = false;

    protected $fillable = [
        'playerId',
        'arrTask'
    ];

    protected $casts = [
        'playerId' => 'integer',
        'arrTask' => 'array'
    ];

    /**
     * Get the player/user
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'playerId', 'id');
    }

    /**
     * Get parsed task array
     */
    public function getParsedTasksAttribute()
    {
        if (empty($this->arrTask)) {
            return [];
        }

        // If arrTask is already an array, return it
        if (is_array($this->arrTask)) {
            return $this->arrTask;
        }

        // If it's a JSON string, decode it
        if (is_string($this->arrTask)) {
            // Handle double-encoded JSON (with extra quotes)
            $cleaned = trim($this->arrTask, '"');
            $decoded = json_decode($cleaned, true);

            // If first decode failed, try original string
            if (!is_array($decoded)) {
                $decoded = json_decode($this->arrTask, true);
            }

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    /**
     * Check if a specific task is completed
     * Format: [taskId, value, isCompleted, boolean4]
     */
    public function isTaskCompleted($taskId)
    {
        $tasks = $this->getParsedTasksAttribute();

        // Look for task in the array
        foreach ($tasks as $task) {
            if (is_array($task) && isset($task[0]) && $task[0] == $taskId) {
                // Check if task is completed (index 2 = true)
                return isset($task[2]) && $task[2] === true;
            }
        }

        return false;
    }

    /**
     * Get completed task IDs
     */
    public function getCompletedTaskIdsAttribute()
    {
        $tasks = $this->getParsedTasksAttribute();
        $completedIds = [];

        foreach ($tasks as $task) {
            if (is_array($task) && isset($task[0], $task[2]) && $task[2] === true) {
                $completedIds[] = (int)$task[0];
            }
        }

        return $completedIds;
    }

    /**
     * Get task progress value
     */
    public function getTaskProgress($taskId)
    {
        $tasks = $this->getParsedTasksAttribute();

        foreach ($tasks as $task) {
            if (is_array($task) && isset($task[0]) && $task[0] == $taskId) {
                return $task[1] ?? 0; // Return progress value
            }
        }

        return 0;
    }

    /**
     * Update user tasks with proper format
     * Format: [taskId, value, isCompleted, boolean4]
     */
    public function updateTasks($completedTaskIds)
    {
        $currentTasks = $this->getParsedTasksAttribute();
        $taskArray = [];

        // Create a map of existing tasks for easy lookup
        $existingTasks = [];
        foreach ($currentTasks as $task) {
            if (is_array($task) && isset($task[0])) {
                $existingTasks[$task[0]] = $task;
            }
        }

        // Get all possible task IDs (we need to maintain all tasks, not just completed ones)
        $allTaskIds = collect($currentTasks)->pluck(0)->unique()->merge($completedTaskIds)->unique()->sort()->values();

        foreach ($allTaskIds as $taskId) {
            $taskId = (int)$taskId;
            $isCompleted = in_array($taskId, $completedTaskIds);

            if (isset($existingTasks[$taskId])) {
                // Update existing task - keep original value but update completion status
                $existingTask = $existingTasks[$taskId];
                $taskArray[] = [
                    $taskId,
                    $existingTask[1] ?? 0, // Keep original progress value
                    $isCompleted,          // Update completion status
                    $existingTask[3] ?? false // Keep original 4th boolean
                ];
            } else {
                // New task - set default values
                $taskArray[] = [
                    $taskId,
                    $isCompleted ? 1 : 0, // Set progress to 1 if completed, 0 if not
                    $isCompleted,         // Set completion status
                    false                 // Default 4th boolean
                ];
            }
        }

        $this->update([
            'arrTask' => $taskArray  // Let Laravel's cast handle JSON encoding
        ]);
    }

    /**
     * Scope to get tasks for a specific player
     */
    public function scopeForPlayer($query, $playerId)
    {
        return $query->where('playerId', $playerId);
    }
}
