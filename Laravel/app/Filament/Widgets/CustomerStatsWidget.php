<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Carbon\Carbon;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CustomerStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $this->checkOldDeletedUsers();

        return [
            Stat::make('Total de Usuarios', Customer::count())
                ->description('Usuarios activos en el sistema')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Usuarios Bloqueados', Customer::onlyTrashed()->count())
                ->description('Usuarios bloqueados')
                ->descriptionIcon('heroicon-m-lock-closed')
                ->color('warning'),
        ];
    }

    private function checkOldDeletedUsers(): void
    {
        $userId   = Auth::id();
        $cacheKey = "old_deleted_users_notification_{$userId}";

        // Verificar si ya se mostró la notificación en esta sesión
        if (Cache::has($cacheKey)) {
            return;
        }

        // Buscar usuarios eliminados hace más de 90 días
        $oldDeletedCount = Customer::onlyTrashed()
            ->where('deleted_at', '<', Carbon::now()->subDays(90))
            ->count();

        if ($oldDeletedCount > 0) {
            Notification::make()
                ->title('Usuarios antiguos eliminados')
                ->body("Hay {$oldDeletedCount} usuario(s) eliminado(s) hace más de 90 días que podrían requerir revisión.")
                ->warning()
                ->persistent()
                ->actions([
                    Action::make('revisar')
                        ->button()
                        ->url(route('filament.manager.resources.customers.index', ['tableFilters[status][status]' => 'restorable'])),
                ])
                ->send();

            // Marcar que se mostró la notificación en esta sesión
            Cache::put($cacheKey, true, now()->addHours(24));
        }
    }
}
