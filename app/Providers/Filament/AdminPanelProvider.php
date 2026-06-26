<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
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
            ->spa()
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('15rem')
            ->brandLogo(fn () => view('filament.brand'))
            ->login(\App\Filament\Pages\Auth\CustomLogin::class)
            ->favicon(asset('favicon.png'))
            ->globalSearch(false)
            ->breadcrumbs(false)
            ->profile()
            ->userMenuItems([
                'update_password' => \Filament\Actions\Action::make('update_password')
                    ->label('Update Password')
                    ->url('#')
                    ->icon('heroicon-o-key'),
            ])
            ->colors([
                'primary' => Color::Amber,
            ])
            ->font('Noto Sans Lao')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\Filament\Clusters')
            ->navigationGroups([
                'Overview',
                'Users',
                'Products',
                'Operations',
                'Report',
                'Configuration',
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->databaseNotifications()
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ])
            ->renderHook(
                \Filament\View\PanelsRenderHook::BODY_END,
                fn () => view('filament.hooks.viewer-js'),
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::TOPBAR_START,
                fn () => new \Illuminate\Support\HtmlString('<div id="topbar-sub-nav-target" style="position: absolute; left: 50%; transform: translateX(-50%); display: flex; align-items: center; justify-content: center; height: 100%; z-index: 10; width: max-content;"></div>'),
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::STYLES_AFTER,
                fn () => new \Illuminate\Support\HtmlString('<style>
                    .fi-topbar {
                        position: relative;
                    }
                    .fi-page-sub-navigation-tabs {
                        margin-top: 0 !important;
                        margin-bottom: 0 !important;
                        background: transparent !important;
                        box-shadow: none !important;
                    }
                    .fi-main {
                        padding-top: 3px !important;
                        margin-top: 0 !important;
                    }
                    .fi-page {
                        gap: 3px !important;
                    }
                    .fi-header {
                        padding-bottom: 0 !important;
                        margin-bottom: 0 !important;
                    }
                    h1.fi-header-heading {
                        font-size: 20px !important;
                    }
                    .fi-layout {
                        margin-top: 2px !important;
                    }
                    @media (max-width: 1024px) {
                        .fi-main {
                            padding-top: 3px !important;
                        }
                    }
                </style>'),
            )
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
