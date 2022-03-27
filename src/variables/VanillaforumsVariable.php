<?php
/**
 * Vanillaforums plugin for Craft CMS 3.x
 *
 * Single Sign On plugin for VanillaForums/jsConnect and CraftCMS
 *
 * @link      https://nystudio107.com/
 * @copyright Copyright (c) 2019 nystudio107
 */

namespace nystudio107\vanillaforums\variables;

use craft\helpers\Template;
use nystudio107\vanillaforums\Vanillaforums;
use yii\base\InvalidConfigException;

/**
 * @author    nystudio107
 * @package   Vanillaforums
 * @since     3.0.0
 */
class VanillaforumsVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Generate an SSO string suitable for passing in the url for embedded SSO
     *
     * @param int $userId
     *
     * @return string
     * @throws InvalidConfigException
     */
    public function embeddedOutput(int $userId = 0): string
    {
        return Template::raw(
            Vanillaforums::$plugin->sso->embeddedOutput($userId)
        );
    }

    /**
     * Return whether we are running Craft 3.1 or later
     *
     * @return bool
     */
    public function craft31(): bool
    {
        return true;
    }
}
