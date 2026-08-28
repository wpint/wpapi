<?php
namespace Wpint\WPAPI\Tests\Setting;

use Brain\Monkey\Functions;
use Closure;
use Mockery;
use Wpint\WPAPI\Setting\Setting;
use Wpint\WPAPI\Tests\TestCase;

/**
 * @covers \Wpint\WPAPI\Setting\Setting
 */
class SettingTest extends TestCase
{

    public function test_register_setting_defaults_sanitize_callback_to_sanitize_text_field() : void
    {
        // Regression: register_setting() previously received no args at
        // all, so no sanitize_callback was ever applied.
        Functions\expect('add_action')->once()->with('admin_init', Mockery::type(Closure::class))->andReturnUsing(fn($h, $cb) => $cb());
        Functions\expect('register_setting')->once()->with('options', 'wpint_email', Mockery::on(
            fn(array $args) => ($args['sanitize_callback'] ?? null) === 'sanitize_text_field'
        ));
        Functions\expect('add_settings_section')->once();
        Functions\expect('add_settings_field')->once();

        (new Setting())
            ->name('wpint_email')
            ->sectionTitle('General')
            ->sectionCallback(fn() => null)
            ->fieldtitle('Email')
            ->fieldCallback(fn() => null)
            ->register();
    }

    public function test_register_setting_passes_through_type_default_and_show_in_rest() : void
    {
        Functions\expect('add_action')->once()->andReturnUsing(fn($h, $cb) => $cb());
        Functions\expect('register_setting')->once()->with('options', 'wpint_count', Mockery::on(
            fn(array $args) => $args['type'] === 'integer' && $args['default'] === 5 && $args['show_in_rest'] === true
        ));
        Functions\expect('add_settings_section')->once();
        Functions\expect('add_settings_field')->once();

        (new Setting())
            ->name('wpint_count')
            ->type('integer')
            ->default(5)
            ->showInRest(true)
            ->sectionTitle('General')
            ->sectionCallback(fn() => null)
            ->fieldtitle('Count')
            ->fieldCallback(fn() => null)
            ->register();
    }

    public function test_registers_section_and_field_with_slugified_titles() : void
    {
        Functions\expect('add_action')->once()->andReturnUsing(fn($h, $cb) => $cb());
        Functions\expect('register_setting')->once();
        Functions\expect('add_settings_section')->once()->with('general_settings', 'General Settings', Mockery::type(Closure::class), 'options');
        Functions\expect('add_settings_field')->once()->with('email_address', 'Email Address', Mockery::type(Closure::class), 'options', 'general_settings');

        (new Setting())
            ->name('wpint_email')
            ->sectionTitle('General Settings')
            ->sectionCallback(fn() => null)
            ->fieldtitle('Email Address')
            ->fieldCallback(fn() => null)
            ->register();
    }

}
