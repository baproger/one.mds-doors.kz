<?php

namespace App\Filament\Widgets;

use App\Models\Gate;
use App\Models\Lead;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalLeads = Lead::count();
        $newLeads   = Lead::where('status', 'new')->count();
        $todayLeads = Lead::whereDate('created_at', today())->count();

        $totalDoors = Gate::where('is_active', true)->count();

        $totalProjects = Project::whereNotNull('result_image')->count();
        $weekProjects  = Project::whereNotNull('result_image')
            ->where('created_at', '>=', now()->subDays(7))->count();

        return [
            Stat::make('Заявок всего', $totalLeads)
                ->description("Новых: {$newLeads} · Сегодня: {$todayLeads}")
                ->descriptionIcon('heroicon-m-envelope')
                ->color('warning')
                ->chart(
                    Lead::selectRaw('COUNT(*) as count')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->groupByRaw('DATE(created_at)')
                        ->orderByRaw('DATE(created_at)')
                        ->pluck('count')
                        ->toArray()
                ),

            Stat::make('Моделей дверей', $totalDoors)
                ->description('Активных моделей в каталоге')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('info'),

            Stat::make('Завершённых визуализаций', $totalProjects)
                ->description("За 7 дней: {$weekProjects}")
                ->descriptionIcon('heroicon-m-photo')
                ->color('primary'),
        ];
    }
}
