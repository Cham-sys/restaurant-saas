<?php

use App\Models\User;
use App\Providers\Filament\RestaurantPanelProvider;
use Filament\Panel;
use Illuminate\Container\Container;

it('registers a dedicated restaurant panel with the expected route', function () {
    $app = Container::getInstance();
    $provider = new RestaurantPanelProvider($app);

    $panel = $provider->panel(Panel::make());

    expect($panel->getId())->toBe('restaurant')
        ->and($panel->getPath())->toBe('restaurant');
});

it('loads the custom restaurant dashboard page for authenticated users', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/restaurant');

    $response->assertOk();
    $response->assertSee('لوحة التحكم');
});
