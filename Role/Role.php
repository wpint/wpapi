<?php
namespace Wpint\WPAPI\Role;

use Wpint\WPAPI\Support\Registrable;

/**
 * Fluent wrapper around add_role()/WP_Role::add_cap().
 *
 * Security note (not code-enforced): capability lists must always come
 * from a static, developer-defined whitelist — never build them from
 * unsanitized or user-controlled request data, since that's a direct
 * privilege-escalation vector.
 *
 * @method \Wpint\WPAPI\Role\Role name()
 * @method \Wpint\WPAPI\Role\Role displayName()
 * @method \Wpint\WPAPI\Role\Role capabilities()
 * @method \Wpint\WPAPI\Role\Role extends()
 * @method void register()
 *
 * @see \Wpint\WPAPI\Role\Role
 */
class Role extends Registrable
{

    /**
     * $name
     *
     * @var string
     */
    private string $name;

    /**
     * $displayName
     *
     * @var string
     */
    private string $displayName;

    /**
     * $capabilities
     *
     * @var string[]
     */
    private array $capabilities = [];

    /**
     * Existing role slug to seed capabilities from.
     *
     * @var string
     */
    private string $extendsRole;

    /**
     * Register (or extend) the role
     *
     * @return void
     */
    public function register() : void
    {
        add_action('init', function()
        {
            $role = get_role($this->name);

            if ($role === null)
            {
                add_role($this->name, $this->prop('displayName', $this->name), $this->buildCapabilities());
                return;
            }

            foreach ($this->capabilities as $capability)
            {
                $role->add_cap($capability);
            }
        });
    }

    /**
     * set $name
     *
     * @param string $name
     * @return self
     */
    public function name(string $name) : self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * set $displayName
     *
     * @param string $label
     * @return self
     */
    public function displayName(string $label) : self
    {
        $this->displayName = $label;
        return $this;
    }

    /**
     * set $capabilities. Must be a static, developer-defined list —
     * never derived from user-controlled input.
     *
     * @param string ...$caps
     * @return self
     */
    public function capabilities(string ...$caps) : self
    {
        $this->capabilities = $caps;
        return $this;
    }

    /**
     * seed this role's capabilities from an existing role before
     * applying capabilities()
     *
     * @param string $existingRole
     * @return self
     */
    public function extends(string $existingRole) : self
    {
        $this->extendsRole = $existingRole;
        return $this;
    }

    /**
     * build the initial capabilities map used by add_role()
     *
     * @return array<string, bool>
     */
    private function buildCapabilities() : array
    {
        $caps = [];

        if ($this->prop('extendsRole'))
        {
            $existing = get_role($this->extendsRole);
            if ($existing) $caps = $existing->capabilities;
        }

        foreach ($this->capabilities as $capability)
        {
            $caps[$capability] = true;
        }

        return $caps;
    }

}
