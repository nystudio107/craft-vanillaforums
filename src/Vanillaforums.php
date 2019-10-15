<?php
/**
 * Vanillaforums plugin for Craft CMS 3.x
 *
 * Single Sign On plugin for VanillaForums/jsConnect and CraftCMS
 *
 * @link      https://nystudio107.com/
 * @copyright Copyright (c) 2019 nystudio107
 */

namespace nystudio107\vanillaforums;

use nystudio107\vanillaforums\services\Sso as SsoService;
use nystudio107\vanillaforums\variables\VanillaforumsVariable;
use nystudio107\vanillaforums\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

/**
 * Class Vanillaforums
 *
 * @author    nystudio107
 * @package   Vanillaforums
 * @since     3.0.0
 *
 * @property  SsoService $sso
 */
class Vanillaforums extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var Vanillaforums
     */
    public static $plugin;

    /**
     * @var bool
     */
    public static $craft31 = false;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '3.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        self::$craft31 = version_compare(Craft::$app->getVersion(), '3.1', '>=');

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('vanillaforums', VanillaforumsVariable::class);
            }
        );

        Craft::info(
            Craft::t(
                'vanillaforums',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'vanillaforums/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
