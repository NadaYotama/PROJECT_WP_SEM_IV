<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Menu;
use App\Models\Order;

class StockSyncTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function placing_an_order_decrements_menu_stock()
    {
        // create a customer
        $user = User::factory()->create(['role' => 'customer']);

        // create menu with stock 100
        $menu = Menu::create([
            'name' => 'Test Nasi Goreng',
            'description' => 'Lorem',
            'price' => 10000,
            'category' => 'Makanan',
            'stock' => 100,
            'is_available' => true,
        ]);

        // act as the customer and post an order
        $items = [
            [
                'menu_id' => $menu->id,
                'quantity' => 1,
            ]
        ];

        $response = $this->actingAs($user)
            ->post(route('orders.store'), [
                'order_type' => 'dine_in',
                'items' => $items,
            ]);

        $response->assertStatus(302); // redirect on success

        $menu->refresh();
        $this->assertEquals(99, $menu->stock);
        $this->assertTrue($menu->is_available);
    }

    /** @test */
    public function admin_can_decrease_stock_and_availability_updates()
    {
        // create admin
        $admin = User::factory()->create(['role' => 'admin']);

        $menu = Menu::create([
            'name' => 'Test Rendang',
            'description' => 'Lorem',
            'price' => 20000,
            'category' => 'Makanan',
            'stock' => 2,
            'is_available' => true,
        ]);

        // call adjustStock to decrease by 2
        $response = $this->actingAs($admin)
            ->postJson(route('admin.menu.adjust-stock'), [
                'menu_id' => $menu->id,
                'action' => 'decrease',
                'quantity' => 2,
            ]);

        $response->assertStatus(200)->assertJson(['success' => true]);

        $menu->refresh();
        $this->assertEquals(0, $menu->stock);
        $this->assertFalse((bool) $menu->is_available);
    }
}
