<?php

declare(strict_types=1);

namespace App\Filament\Widgets\Concerns;

/**
 * Provides consistent color palettes for chart widgets.
 */
final class ChartColors
{
    /**
     * Default color palette for bar and doughnut charts.
     *
     * @var array<int, string>
     */
    public const DEFAULT = [
        'rgba(59, 130, 246, 0.8)',   // Blue
        'rgba(16, 185, 129, 0.8)',   // Green
        'rgba(245, 158, 11, 0.8)',   // Amber
        'rgba(239, 68, 68, 0.8)',    // Red
        'rgba(139, 92, 246, 0.8)',   // Purple
        'rgba(236, 72, 153, 0.8)',   // Pink
        'rgba(20, 184, 166, 0.8)',   // Teal
        'rgba(249, 115, 22, 0.8)',   // Orange
        'rgba(99, 102, 241, 0.8)',   // Indigo
        'rgba(34, 197, 94, 0.8)',    // Emerald
    ];

    /**
     * Primary color for single-dataset charts.
     */
    public const PRIMARY = 'rgba(59, 130, 246, 0.8)';

    public const PRIMARY_BORDER = 'rgba(59, 130, 246, 1)';

    public const PRIMARY_FILL = 'rgba(59, 130, 246, 0.1)';

    /**
     * Success color for positive metrics.
     */
    public const SUCCESS = 'rgba(16, 185, 129, 0.8)';

    public const SUCCESS_BORDER = 'rgba(16, 185, 129, 1)';

    public const SUCCESS_FILL = 'rgba(16, 185, 129, 0.1)';

    /**
     * Danger color for negative metrics.
     */
    public const DANGER = 'rgba(239, 68, 68, 0.8)';

    public const DANGER_BORDER = 'rgba(239, 68, 68, 1)';

    public const DANGER_FILL = 'rgba(239, 68, 68, 0.1)';
}
