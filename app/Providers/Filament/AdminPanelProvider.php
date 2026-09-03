<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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
            ->passwordReset()
            ->favicon(asset('favicon.png'))
            ->globalSearch(false)
            ->breadcrumbs(true)
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
                'Users',
                'Operations',
                'Products',
                'Reports',
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
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ])
            ->renderHook(
                \Filament\Tables\View\TablesRenderHook::TOOLBAR_COLUMN_MANAGER_TRIGGER_AFTER,
                fn () => view('filament.resources.user-resource.components.view-toggle'),
                scopes: \App\Filament\Resources\UserResource\Pages\ListUsers::class,
            )
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
                        padding-top: 4px !important;
                        margin-top: 0 !important;
                    }
                    .fi-page-header-main-ctn {
                        padding-top: 4px !important;
                        padding-bottom: 4px !important;
                        gap: 4px !important;
                    }
                    .fi-page-main {
                        gap: 4px !important;
                    }
                    .fi-page {
                        gap: 4px !important;
                    }
                    .fi-page-content {
                        gap: 4px !important;
                    }
                    .fi-sc,
                    .fi-sc-has-gap {
                        gap: 4px !important;
                    }
                    .fi-header {
                        padding-bottom: 0 !important;
                        margin-bottom: 4px !important;
                    }
                    .fi-header .fi-breadcrumbs {
                        margin-bottom: 2px !important;
                    }
                    h1.fi-header-heading {
                        font-size: 20px !important;
                    }
                    .fi-layout {
                        margin-top: 2px !important;
                    }
                    @media (max-width: 1024px) {
                        .fi-main {
                            padding-top: 4px !important;
                        }
                    }
                </style>'),
            )
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
