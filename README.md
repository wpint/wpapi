# wpint/wpapi

A fluent PHP API for registering WordPress hooks, objects, and endpoints — post types, taxonomies, meta boxes, post meta, cron, settings, shortcodes, REST routes, AJAX handlers, script/style enqueueing, sidebars, nav menus, roles, and generic actions/filters — without hand-writing `add_action`/`register_*` boilerplate.

```
composer require wpint/wpapi
```

## How it works

Every object is a chainable builder ending in `->register()`:

```php
use Wpint\WPAPI\WPAPI;

WPAPI::postType()
    ->name('book')
    ->public(true)
    ->hasArchive(true)
    ->register();
```

`register()` defers itself into whatever WordPress lifecycle hook it actually needs (`init`, `admin_init`, `widgets_init`, ...), so it's safe to call at any point during your plugin's bootstrap.

Every security-sensitive default (metabox nonce/capability checks, REST route permission callbacks, AJAX nonce verification, settings sanitizers) is documented per class in [Documentation](https://github.com/wpint/wpapi/wiki/Documenation), which has one complete, runnable example for every object.

## License

MIT
