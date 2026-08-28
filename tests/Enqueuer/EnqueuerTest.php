<?php
namespace Wpint\WPAPI\Tests\Enqueuer;

use Brain\Monkey\Functions;
use Wpint\WPAPI\Enqueuer\Enqueuer;
use Wpint\WPAPI\Enqueuer\Enum\EnqueuerScopeEnum;
use Wpint\WPAPI\Tests\TestCase;

/**
 * @covers \Wpint\WPAPI\Enqueuer\Enqueuer
 */
class EnqueuerTest extends TestCase
{

    protected function setUp() : void
    {
        parent::setUp();

        Functions\when('config')->alias(fn($key) => match ($key) {
            'app.plugin_path' => '/plugin/',
            'app.plugin_uri'  => 'https://example.com/plugin/',
            default           => null,
        });
    }

    public function test_js_enqueuer_passes_deps_and_explicit_version_through() : void
    {
        Functions\when('file_exists')->justReturn(true);

        Functions\expect('wp_enqueue_script')->once()->with(
            'wpint_client_js_app',
            'https://example.com/plugin/assets/app.js',
            ['jquery'],
            '1.2.3',
            true
        );

        $enqueuer = (new Enqueuer())->scope(EnqueuerScopeEnum::CLIENT)->js('assets/app.js', ['jquery'], '1.2.3', true);
        $enqueuer->jsEnqueuer();
    }

    public function test_js_enqueuer_defaults_version_to_filemtime_for_cache_busting() : void
    {
        // Regression: previously wp_enqueue_script() was called with no
        // $ver argument at all, so WP fell back to its own core version,
        // causing stale-cache issues after deploys.
        Functions\when('file_exists')->justReturn(true);
        Functions\when('filemtime')->justReturn(1700000000);

        Functions\expect('wp_enqueue_script')->once()->with(
            'wpint_client_js_app',
            'https://example.com/plugin/assets/app.js',
            [],
            1700000000,
            true
        );

        $enqueuer = (new Enqueuer())->scope(EnqueuerScopeEnum::CLIENT)->js('assets/app.js');
        $enqueuer->jsEnqueuer();
    }

    public function test_css_enqueuer_passes_media_through() : void
    {
        Functions\when('file_exists')->justReturn(true);
        Functions\when('filemtime')->justReturn(1700000000);

        Functions\expect('wp_enqueue_style')->once()->with(
            'wpint_client_css_app',
            'https://example.com/plugin/assets/app.css',
            [],
            1700000000,
            'print'
        );

        $enqueuer = (new Enqueuer())->scope(EnqueuerScopeEnum::CLIENT)->css('assets/app.css', [], null, 'print');
        $enqueuer->cssEnqueuer();
    }

    public function test_missing_asset_file_is_skipped() : void
    {
        Functions\when('file_exists')->justReturn(false);
        Functions\expect('wp_enqueue_script')->never();

        $enqueuer = (new Enqueuer())->js('assets/missing.js');
        $enqueuer->jsEnqueuer();
    }

    public function test_register_wires_admin_enqueue_scripts_for_admin_scope() : void
    {
        Functions\expect('add_action')->once()->with('admin_enqueue_scripts', [\Mockery::type(Enqueuer::class), 'jsEnqueuer']);
        Functions\expect('add_action')->once()->with('admin_enqueue_scripts', [\Mockery::type(Enqueuer::class), 'cssEnqueuer']);

        (new Enqueuer())->register();
    }

    public function test_register_wires_wp_enqueue_scripts_for_client_scope() : void
    {
        Functions\expect('add_action')->once()->with('wp_enqueue_scripts', [\Mockery::type(Enqueuer::class), 'jsEnqueuer']);
        Functions\expect('add_action')->once()->with('wp_enqueue_scripts', [\Mockery::type(Enqueuer::class), 'cssEnqueuer']);

        (new Enqueuer())->scope(EnqueuerScopeEnum::CLIENT)->register();
    }

}
