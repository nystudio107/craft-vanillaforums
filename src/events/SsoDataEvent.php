<?php
/**
 * Vanillaforums plugin for Craft CMS 3.x
 *
 * Single Sign On plugin for VanillaForums/jsConnect and CraftCMS
 *
 * @link      https://nystudio107.com/
 * @copyright Copyright (c) 2019 nystudio107
 */

namespace nystudio107\vanillaforums\events;

use craft\elements\User;
use nystudio107\vanillaforums\models\SsoData;

use yii\base\ModelEvent;

/**
 * @author    nystudio107
 * @package   Vanillaforums
 * @since     3.0.0
 */
class SsoDataEvent extends ModelEvent
{
    // Properties
    // =========================================================================

    /**
     * @var User|null The user associated with this SSO data (usually the currently logged in user)
     */
    public $user;

    /**
     * @var SsoData|null the SsoData model
     */
    public $data;
}
