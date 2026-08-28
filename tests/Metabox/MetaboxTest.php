<?php
namespace Wpint\WPAPI\Tests\Metabox;

use Brain\Monkey\Functions;
use Closure;
use Mockery;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use stdClass;
use Wpint\WPAPI\Metabox\Metabox;
use Wpint\WPAPI\Tests\TestCase;

/**
 * Regression suite for the security-critical save_post handler (missing
 * nonce/capability/autosave/sanitization checks). The add_meta_boxes
 * render callback itself is intentionally not invoked here — it calls
 * through to the external Wpint\Support\CallbackResolver, which is
 * already alias-mocked once in Support/RegistrableTest.php; Mockery
 * can't redefine that alias again in the same process. Wiring of the
 * add_meta_boxes hook is still asserted structurally below.
 *
 * @covers \Wpint\WPAPI\Metabox\Metabox
 */
class MetaboxTest extends TestCase
{

    /**
     * @return Closure the closure passed to add_action('save_post', ...)
     */
    private function registerAndCaptureSavePostCallback(Metabox $metabox) : Closure
    {
        Functions\expect('add_action')->once()->with('add_meta_boxes', Mockery::type(Closure::class));

        $captured = null;
        Functions\expect('add_action')
            ->once()
            ->with('save_post', Mockery::type(Closure::class))
            ->andReturnUsing(function ($hook, $callback) use (&$captured) {
                $captured = $callback;
                return null;
            });

        $metabox->register();

        return $captured;
    }

    private function baseMetabox() : Metabox
    {
        return (new Metabox())
            ->id('wpint_subtitle')
            ->title('Subtitle')
            ->screen('post')
            ->metaKey('wpint_subtitle')
            ->postKey('wpint_subtitle_field');
    }

    #[RunInSeparateProcess]
    public function test_save_post_bails_during_autosave() : void
    {
        define('DOING_AUTOSAVE', true);

        $savePost = $this->registerAndCaptureSavePostCallback($this->baseMetabox());

        Functions\expect('update_post_meta')->never();

        $savePost(123);
    }

    public function test_save_post_bails_when_post_type_not_in_screens() : void
    {
        $savePost = $this->registerAndCaptureSavePostCallback($this->baseMetabox());

        Functions\expect('get_post_type')->once()->with(123)->andReturn('page');
        Functions\expect('update_post_meta')->never();

        $savePost(123);
    }

    public function test_save_post_bails_when_nonce_field_missing() : void
    {
        $_POST = [];

        $savePost = $this->registerAndCaptureSavePostCallback($this->baseMetabox());

        Functions\expect('get_post_type')->once()->with(123)->andReturn('post');
        Functions\expect('update_post_meta')->never();

        $savePost(123);
    }

    public function test_save_post_bails_when_nonce_is_invalid() : void
    {
        $_POST = ['wpint_subtitle_wpint_nonce' => 'bad-nonce'];

        $savePost = $this->registerAndCaptureSavePostCallback($this->baseMetabox());

        Functions\expect('get_post_type')->once()->with(123)->andReturn('post');
        Functions\when('wp_unslash')->returnArg(1);
        Functions\when('sanitize_text_field')->returnArg(1);
        Functions\expect('wp_verify_nonce')->once()->with('bad-nonce', 'wpint_subtitle_wpint_metabox')->andReturn(false);
        Functions\expect('update_post_meta')->never();

        $savePost(123);
    }

    public function test_save_post_bails_when_user_lacks_capability() : void
    {
        $_POST = [
            'wpint_subtitle_wpint_nonce' => 'good-nonce',
            'wpint_subtitle_field'       => 'New subtitle',
        ];

        $savePost = $this->registerAndCaptureSavePostCallback($this->baseMetabox());

        Functions\expect('get_post_type')->twice()->with(123)->andReturn('post');
        Functions\when('wp_unslash')->returnArg(1);
        Functions\when('sanitize_text_field')->returnArg(1);
        Functions\expect('wp_verify_nonce')->once()->andReturn(true);

        $postType = new stdClass();
        $postType->cap = new stdClass();
        $postType->cap->edit_post = 'edit_post';
        Functions\expect('get_post_type_object')->once()->with('post')->andReturn($postType);
        Functions\expect('current_user_can')->once()->with('edit_post', 123)->andReturn(false);

        Functions\expect('update_post_meta')->never();

        $savePost(123);
    }

    public function test_save_post_sanitizes_and_saves_when_all_checks_pass() : void
    {
        $_POST = [
            'wpint_subtitle_wpint_nonce' => 'good-nonce',
            'wpint_subtitle_field'       => '<b>New subtitle</b>',
        ];

        // Custom sanitizeCallback so its call is unambiguous from the
        // nonce value's own (always sanitize_text_field) sanitization.
        $savePost = $this->registerAndCaptureSavePostCallback(
            $this->baseMetabox()->sanitizeCallback(fn($value) => strtoupper(strip_tags($value)))
        );

        Functions\expect('get_post_type')->twice()->with(123)->andReturn('post');
        Functions\when('wp_unslash')->returnArg(1);
        Functions\when('sanitize_text_field')->returnArg(1);
        Functions\expect('wp_verify_nonce')->once()->andReturn(true);

        $postType = new stdClass();
        $postType->cap = new stdClass();
        $postType->cap->edit_post = 'edit_post';
        Functions\expect('get_post_type_object')->once()->with('post')->andReturn($postType);
        Functions\expect('current_user_can')->once()->with('edit_post', 123)->andReturn(true);

        Functions\expect('update_post_meta')->once()->with(123, 'wpint_subtitle', 'NEW SUBTITLE');

        $savePost(123);
    }

}
