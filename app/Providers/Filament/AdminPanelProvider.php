<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\StatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Blade;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('60px')
            ->colors([
                'primary' => Color::hex('#2e5f99'),
            ])
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): string => Blade::render('
                    <style>
                        .fi-simple-layout {
                            background: linear-gradient(135deg, #0f2744 0%, #1a3d6b 40%, #2e5f99 100%) !important;
                            min-height: 100vh;
                        }
                        .fi-simple-main {
                            box-shadow: 0 25px 60px rgba(0,0,0,0.35) !important;
                            border-radius: 1.5rem !important;
                            border: 1px solid rgba(255,255,255,0.1) !important;
                            background: #ffffff !important;
                            backdrop-filter: blur(10px);
                        }
                        .fi-simple-main * {
                            color: #1a1a2e !important;
                        }
                        .fi-simple-main h1,
                        .fi-simple-main .fi-auth-heading {
                            color: #1a3d6b !important;
                            font-weight: 700 !important;
                        }
                        .fi-simple-main label,
                        .fi-simple-main .fi-fo-field-wrp label {
                            color: #374151 !important;
                            font-weight: 500 !important;
                        }
                        .fi-simple-main input {
                            color: #111827 !important;
                            background: #f9fafb !important;
                            border-color: #d1d5db !important;
                        }
                        .fi-simple-main input:focus {
                            border-color: #2e5f99 !important;
                            ring-color: #2e5f99 !important;
                        }
                        .fi-btn-primary {
                            background: #2e5f99 !important;
                            color: #ffffff !important;
                        }
                        .fi-btn-primary:hover {
                            background: #1a3d6b !important;
                        }
                        .fi-checkbox-label,
                        .fi-fo-checkbox label {
                            color: #6b7280 !important;
                        }
                        .fi-logo img {
                            max-height: 60px;
                            width: auto;
                        }
                    </style>
                '),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                StatsOverview::class,
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
