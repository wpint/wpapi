<?php
namespace Wpint\WPAPI\Enqueuer;

use Wpint\WPAPI\Enqueuer\Enum\EnqueuerScopeEnum;
use Wpint\WPAPI\Support\Registrable;

/**
 * @method \Wpint\WPAPI\Enqueuer\Enqueuer scope()
 * @method \Wpint\WPAPI\Enqueuer\Enqueuer css()
 * @method \Wpint\WPAPI\Enqueuer\Enqueuer js()
 * @method void register()
 *
 * @see \Wpint\WPAPI\Enqueuer\Enqueuer
 */
class Enqueuer extends Registrable
{

    /**
     * $css
     *
     * @var array
     */
    private array $css = [];

    /**
     * $js
     *
     * @var array
     */
    private array $js = [];

    /**
     * $scope
     *
     * @var string|EnqueuerScopeEnum
     */
    private  string|EnqueuerScopeEnum $scope = EnqueuerScopeEnum::ADMIN;

    /**
     * Register the scripts
     *
     * @return void
     */
    public function register()
    {
        if($this->scope == EnqueuerScopeEnum::ADMIN)
        {
            add_action( 'admin_enqueue_scripts', [$this, 'jsEnqueuer'] );
            add_action( 'admin_enqueue_scripts', [$this, 'cssEnqueuer'] );
        }
        else
        {
            add_action( 'wp_enqueue_scripts', [$this, 'jsEnqueuer'] );
            add_action( 'wp_enqueue_scripts', [$this, 'cssEnqueuer'] );
        }
    }

    /**
     * set enqueuer scope admin|client
     *
     * @param string|EnqueuerScopeEnum $scope
     * @return self
     */
    public function scope(string|EnqueuerScopeEnum $scope) : self
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * queue a script for enqueueing
     *
     * @param string $path relative to config('app.plugin_path')/config('app.plugin_uri')
     * @param string[] $deps
     * @param string|null $version defaults to the file's mtime for cache-busting
     * @param bool $inFooter
     * @return self
     */
    public function js(string $path, array $deps = [], ?string $version = null, bool $inFooter = true) : self
    {
        $this->js[] = [
            'path'      => $path,
            'deps'      => $deps,
            'version'   => $version,
            'in_footer' => $inFooter,
        ];
        return $this;
    }

    /**
     * queue a stylesheet for enqueueing
     *
     * @param string $path relative to config('app.plugin_path')/config('app.plugin_uri')
     * @param string[] $deps
     * @param string|null $version defaults to the file's mtime for cache-busting
     * @param string $media
     * @return self
     */
    public function css(string $path, array $deps = [], ?string $version = null, string $media = 'all') : self
    {
        $this->css[] = [
            'path'    => $path,
            'deps'    => $deps,
            'version' => $version,
            'media'   => $media,
        ];
        return $this;
    }

    /**
     * Execute wp method wp_enqueue_script
     *
     * @return void
     */
    public function jsEnqueuer()
    {
        foreach ($this->js as $asset)
        {
            $actualPath = config('app.plugin_path') . $asset['path'];

            if ( ! ( file_exists($actualPath) && pathinfo($actualPath, PATHINFO_EXTENSION) == 'js' ) ) continue;

            wp_enqueue_script(
                'wpint_' . $this->scope . '_js_' . basename($asset['path'], '.js'),
                config('app.plugin_uri') . $asset['path'],
                $asset['deps'],
                $asset['version'] ?? filemtime($actualPath),
                $asset['in_footer']
            );
        }
    }

    /**
     * Execute wp method wp_enqueue_style
     *
     * @return void
     */
    public function cssEnqueuer()
    {
        foreach ($this->css as $asset)
        {
            $actualPath = config('app.plugin_path') . $asset['path'];

            if ( ! ( file_exists($actualPath) && pathinfo($actualPath, PATHINFO_EXTENSION) == 'css' ) ) continue;

            wp_enqueue_style(
                'wpint_' . $this->scope . '_css_' . basename($asset['path'], '.css'),
                config('app.plugin_uri') . $asset['path'],
                $asset['deps'],
                $asset['version'] ?? filemtime($actualPath),
                $asset['media']
            );
        }
    }


}
