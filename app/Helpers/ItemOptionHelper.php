<?php

namespace App\Helpers;

use App\Models\ItemOptionTemplate;

class ItemOptionHelper
{
    /**
     * Cache for option templates.
     */
    private static $optionTemplates = null;

    /**
     * Get all option templates from database.
     */
    private static function getOptionTemplates()
    {
        if (self::$optionTemplates === null) {
            self::$optionTemplates = ItemOptionTemplate::all()->keyBy('id');
        }
        return self::$optionTemplates;
    }
    /**
     * Get option text and color based on option ID and value.
     */
    public static function getOptionInfo($optionId, $value)
    {
        // Try to get from database first
        $templates = self::getOptionTemplates();
        if (isset($templates[$optionId])) {
            $template = $templates[$optionId];
            return [
                'text' => $template->name,
                'color' => $template->getColor(),
                'formatted_value' => self::formatValue($value, 'number')
            ];
        }

        // Fallback to hardcoded options
        $options = [
            // Basic Stats
            0 => ['text' => 'Sức mạnh yêu cầu', 'color' => 'danger', 'format' => 'number'],
            1 => ['text' => 'Sức mạnh', 'color' => 'primary', 'format' => 'number'],
            2 => ['text' => 'HP/30s', 'color' => 'success', 'format' => 'number'],
            3 => ['text' => 'MP/30s', 'color' => 'info', 'format' => 'number'],
            4 => ['text' => 'Giảm thời gian hồi chiêu', 'color' => 'warning', 'format' => 'percent'],
            5 => ['text' => 'Tăng EXP', 'color' => 'success', 'format' => 'percent'],
            6 => ['text' => 'HP', 'color' => 'success', 'format' => 'number'],
            7 => ['text' => 'Sức đánh', 'color' => 'danger', 'format' => 'number'],
            8 => ['text' => 'Giáp', 'color' => 'primary', 'format' => 'number'],
            9 => ['text' => 'Chí mạng', 'color' => 'warning', 'format' => 'number'],
            10 => ['text' => 'Tốc độ di chuyển', 'color' => 'info', 'format' => 'percent'],

            // Advanced Stats
            14 => ['text' => 'Chí mạng', 'color' => 'warning', 'format' => 'number'],
            15 => ['text' => 'Né đòn', 'color' => 'info', 'format' => 'percent'],
            16 => ['text' => 'Xuyên giáp', 'color' => 'danger', 'format' => 'percent'],
            17 => ['text' => 'Hút HP', 'color' => 'success', 'format' => 'percent'],
            18 => ['text' => 'Hút MP', 'color' => 'info', 'format' => 'percent'],
            19 => ['text' => 'Phản sát thương', 'color' => 'warning', 'format' => 'percent'],
            20 => ['text' => 'Kháng băng', 'color' => 'info', 'format' => 'percent'],
            21 => ['text' => 'Kháng lửa', 'color' => 'danger', 'format' => 'percent'],
            22 => ['text' => 'Kháng sét', 'color' => 'warning', 'format' => 'percent'],

            // MP and HP
            27 => ['text' => 'MP', 'color' => 'info', 'format' => 'number'],
            28 => ['text' => 'Giáp', 'color' => 'primary', 'format' => 'number'],
            29 => ['text' => 'Chí mạng', 'color' => 'warning', 'format' => 'number'],
            30 => ['text' => 'Hiệu ứng đặc biệt', 'color' => 'secondary', 'format' => 'special'],

            // Set bonuses and special effects
            47 => ['text' => 'Tăng sát thương', 'color' => 'danger', 'format' => 'percent'],
            48 => ['text' => 'Tăng EXP', 'color' => 'success', 'format' => 'percent'],
            49 => ['text' => 'Tăng vàng', 'color' => 'warning', 'format' => 'percent'],
            50 => ['text' => 'Sức mạnh', 'color' => 'primary', 'format' => 'number'],

            // Stats bonuses
            77 => ['text' => 'Sức mạnh', 'color' => 'primary', 'format' => 'number'],
            81 => ['text' => 'Tăng sát thương', 'color' => 'danger', 'format' => 'number'],
            83 => ['text' => 'Hiệu ứng', 'color' => 'secondary', 'format' => 'special'],
            85 => ['text' => 'Hiệu ứng', 'color' => 'secondary', 'format' => 'special'],

            // Elemental damages
            95 => ['text' => 'Sát thương băng', 'color' => 'info', 'format' => 'number'],
            96 => ['text' => 'Sát thương lửa', 'color' => 'danger', 'format' => 'number'],
            97 => ['text' => 'Sát thương sét', 'color' => 'warning', 'format' => 'number'],
            98 => ['text' => 'Sát thương độc', 'color' => 'success', 'format' => 'number'],
            99 => ['text' => 'Sát thương ánh sáng', 'color' => 'light', 'format' => 'number'],
            100 => ['text' => 'Sát thương tối', 'color' => 'dark', 'format' => 'number'],
            101 => ['text' => 'Sát thương vật lý', 'color' => 'primary', 'format' => 'number'],
            103 => ['text' => 'Sức mạnh', 'color' => 'primary', 'format' => 'number'],

            // Advanced stats
            127 => ['text' => 'Chỉ số nâng cao', 'color' => 'secondary', 'format' => 'special'],
            128 => ['text' => 'Chỉ số nâng cao', 'color' => 'secondary', 'format' => 'special'],
            129 => ['text' => 'Chỉ số nâng cao', 'color' => 'secondary', 'format' => 'special'],
            139 => ['text' => 'Chỉ số nâng cao', 'color' => 'secondary', 'format' => 'special'],
            140 => ['text' => 'Chỉ số nâng cao', 'color' => 'secondary', 'format' => 'special'],
            141 => ['text' => 'Chỉ số nâng cao', 'color' => 'secondary', 'format' => 'special'],
            154 => ['text' => 'Hiệu ứng đặc biệt', 'color' => 'secondary', 'format' => 'special'],

            // Set effects
            245 => ['text' => 'Set Óc tiêu', 'color' => 'warning', 'format' => 'special'],
            246 => ['text' => 'Set Óc tiêu', 'color' => 'warning', 'format' => 'special'],
            247 => ['text' => 'Set Óc tiêu', 'color' => 'warning', 'format' => 'special'],
            248 => ['text' => 'Set Óc tiêu', 'color' => 'warning', 'format' => 'special'],
        ];

        $option = $options[$optionId] ?? [
            'text' => "Option {$optionId}",
            'color' => 'secondary',
            'format' => 'number'
        ];

        return [
            'text' => $option['text'],
            'color' => $option['color'],
            'formatted_value' => self::formatValue($value, $option['format']),
            'raw_value' => $value
        ];
    }

    /**
     * Format value based on type.
     */
    private static function formatValue($value, $format)
    {
        switch ($format) {
            case 'percent':
                return "+{$value}%";
            case 'number':
                return $value == 0 ? 'Kích hoạt' : '+' . number_format($value);
            case 'special':
                return $value == 0 ? 'Kích hoạt' : $value;
            default:
                return $value;
        }
    }

    /**
     * Get set information based on options and item info.
     */
    public static function getSetInfo($options, $itemId = null, $template = null)
    {
        $sets = [];
        $optionIds = array_column($options, 0);
        $templates = self::getOptionTemplates();

        // Check for Set Óc tiêu based on item IDs (245-249 are Set Óc tiêu items)
        if ($itemId && in_array($itemId, [245, 246, 247, 248, 249])) {
            // Get set bonus description from database (option 143)
            $setBonusText = '5 món +100% sát thương Liên hoàn';
            if (isset($templates[143])) {
                $setBonusText = str_replace('$(', '', str_replace(')', '', $templates[143]->name));
            }

            $sets[] = [
                'name' => 'Set Óc tiêu',
                'description' => $setBonusText,
                'color' => 'warning'
            ];
        }

        // Check for Set Óc tiêu based on options (backup method)
        elseif (array_intersect([245, 246, 247, 248], $optionIds)) {
            $setBonusText = '5 món +100% sát thương Liên hoàn';
            if (isset($templates[143])) {
                $setBonusText = str_replace('$(', '', str_replace(')', '', $templates[143]->name));
            }

            $sets[] = [
                'name' => 'Set Óc tiêu',
                'description' => $setBonusText,
                'color' => 'warning'
            ];
        }

        // Check for Set Óc tiêu based on advanced stats pattern (131, 143 are common in Set Óc tiêu)
        elseif (array_intersect([131, 143], $optionIds) && count($optionIds) >= 4) {
            // Get set name from database (option 131)
            $setName = 'Set Óc tiêu';
            if (isset($templates[131])) {
                $setName = $templates[131]->name;
            }

            // Get set bonus description from database (option 143)
            $setBonusText = '5 món +100% sát thương Liên hoàn';
            if (isset($templates[143])) {
                $setBonusText = str_replace('$(', '', str_replace(')', '', $templates[143]->name));
            }

            $sets[] = [
                'name' => $setName,
                'description' => $setBonusText,
                'color' => 'warning'
            ];
        }

        // Check for high HP items (likely set items)
        foreach ($options as $option) {
            $optionId = $option[0];
            $value = $option[1];

            if ($optionId == 6 && $value >= 15000 && !in_array($itemId, [245, 246, 247, 248, 249])) { // High HP items (not Set Óc tiêu)
                $sets[] = [
                    'name' => 'Trang bị cao cấp',
                    'description' => 'HP cao: +' . number_format($value),
                    'color' => 'success'
                ];
            }

            if ($optionId == 7 && $value >= 10000) { // High damage items
                $sets[] = [
                    'name' => 'Vũ khí mạnh',
                    'description' => 'Sát thương cao: +' . number_format($value),
                    'color' => 'danger'
                ];
            }
        }

        // Check for multiple advanced stats (likely set bonus)
        $advancedStats = array_intersect([127, 128, 129, 139, 140, 141, 154], $optionIds);
        if (count($advancedStats) >= 2 && !in_array($itemId, [245, 246, 247, 248, 249])) {
            $sets[] = [
                'name' => 'Set nâng cao',
                'description' => 'Nhiều chỉ số đặc biệt (' . count($advancedStats) . ' options)',
                'color' => 'info'
            ];
        }

        return array_unique($sets, SORT_REGULAR);
    }

    /**
     * Calculate total power from options.
     */
    public static function calculateTotalPower($options, $baseStats = [])
    {
        $totalPower = 0;

        // Add base stats
        $totalPower += $baseStats['damage'] ?? 0;
        $totalPower += $baseStats['defense'] ?? 0;
        $totalPower += ($baseStats['hp'] ?? 0) / 10; // HP contributes less to power
        $totalPower += ($baseStats['mp'] ?? 0) / 10; // MP contributes less to power

        // Add options power
        foreach ($options as $option) {
            $optionId = $option[0];
            $value = $option[1];

            switch ($optionId) {
                case 6: // HP
                    $totalPower += $value / 10;
                    break;
                case 7: // Damage
                    $totalPower += $value;
                    break;
                case 8: // Defense
                case 28: // Defense
                    $totalPower += $value;
                    break;
                case 27: // MP
                    $totalPower += $value / 10;
                    break;
                case 50: // Power
                case 77: // Power
                case 103: // Power
                    $totalPower += $value;
                    break;
                default:
                    $totalPower += $value / 100; // Other options contribute less
            }
        }

        return (int) $totalPower;
    }

    /**
     * Get requirement info from options.
     */
    public static function getRequirementInfo($options, $template = null)
    {
        $requirements = [];

        foreach ($options as $option) {
            if ($option[0] == 0) { // Power requirement
                $requirements[] = [
                    'type' => 'power',
                    'value' => $option[1],
                    'text' => 'Sức mạnh yêu cầu: ' . number_format($option[1]),
                    'color' => 'danger'
                ];
            }
        }

        // Add template requirements
        if ($template && $template->strRequire > 0) {
            $requirements[] = [
                'type' => 'strength',
                'value' => $template->strRequire,
                'text' => 'Sức mạnh yêu cầu: ' . number_format($template->strRequire),
                'color' => 'warning'
            ];
        }

        if ($template && $template->level > 1) {
            $requirements[] = [
                'type' => 'level',
                'value' => $template->level,
                'text' => 'Level yêu cầu: ' . $template->level,
                'color' => 'info'
            ];
        }

        return $requirements;
    }

}
