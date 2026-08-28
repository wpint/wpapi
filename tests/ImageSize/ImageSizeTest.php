<?php
namespace Wpint\WPAPI\Tests\ImageSize;

use Brain\Monkey\Functions;
use Closure;
use Mockery;
use Wpint\WPAPI\ImageSize\ImageSize;
use Wpint\WPAPI\Tests\TestCase;

/**
 * @covers \Wpint\WPAPI\ImageSize\ImageSize
 */
class ImageSizeTest extends TestCase
{

    public function test_register_passes_expected_args_to_add_image_size() : void
    {
        Functions\expect('add_action')
            ->once()
            ->with('after_setup_theme', Mockery::type(Closure::class))
            ->andReturnUsing(fn($hook, $callback) => $callback());

        Functions\expect('add_image_size')->once()->with('wpint-card', 400, 300, true);

        (new ImageSize())->name('wpint-card')->width(400)->height(300)->crop(true)->register();
    }

    public function test_show_in_dropdown_adds_the_size_label_via_the_choose_filter() : void
    {
        Functions\expect('add_action')->once()->andReturnUsing(fn($hook, $cb) => $cb());
        Functions\expect('add_image_size')->once();

        $filterCallback = null;
        Functions\expect('add_filter')
            ->once()
            ->with('image_size_names_choose', Mockery::type(Closure::class))
            ->andReturnUsing(function ($hook, $callback) use (&$filterCallback) {
                $filterCallback = $callback;
                return null;
            });

        (new ImageSize())
            ->name('wpint-card')
            ->width(400)
            ->height(300)
            ->label('Card')
            ->showInDropdown(true)
            ->register();

        $this->assertSame(
            ['thumbnail' => 'Thumbnail', 'wpint-card' => 'Card'],
            $filterCallback(['thumbnail' => 'Thumbnail'])
        );
    }

}
