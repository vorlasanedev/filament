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
                    .fi-modal-window .fi-sc,
                    .fi-modal-window .fi-sc-has-gap {
                        gap: 1rem !important;
                    }
                    .fi-header {
                        position: relative !important;
                        padding-bottom: 0 !important;
                        margin-bottom: 4px !important;
                    }
                    .fi-header-search-ctn {
                        position: absolute;
                        left: 50%;
                        transform: translateX(-50%);
                        width: 360px;
                        max-width: calc(100% - 380px);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 5;
                    }
                    .fi-header-search-ctn .fi-ta-search-field,
                    .fi-header-search-ctn .fi-input-wrp {
                        width: 100%;
                    }
                    .fi-ta-header-toolbar .fi-ta-search-field-ctn {
                        display: contents;
                    }
                    .fi-ta-view-toggle {
                        display: inline-flex !important;
                        flex-direction: row !important;
                        align-items: center !important;
                        gap: 4px !important;
                        white-space: nowrap !important;
                    }
                    .fi-ta-view-toggle button,
                    .fi-ta-view-toggle .fi-icon-btn {
                        display: inline-flex !important;
                    }
                    .is-grid-view .fi-ta-col-manager-dropdown,
                    .fi-ta-ctn:has(.fi-user-grid-content) .fi-ta-col-manager-dropdown,
                    .is-grid-view .fi-ta-content-header,
                    .fi-ta-ctn:has(.fi-user-grid-content) .fi-ta-content-header {
                        display: none !important;
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
                        .fi-header-search-ctn {
                            position: static;
                            transform: none;
                            width: 100%;
                            max-width: 100%;
                            margin-top: 0.5rem;
                        }
                    }
                </style>'),
            )
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
