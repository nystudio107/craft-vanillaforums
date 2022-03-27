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

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\web\twig\variables\CraftVariable;
use nystudio107\vanillaforums\models\Settings;
use nystudio107\vanillaforums\services\Sso as SsoService;
use nystudio107\vanillaforums\variables\VanillaforumsVariable;
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
     * @var ?Vanillaforums
     */
    public static ?Vanillaforums $plugin = null;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public string $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public bool $hasCpSection = false;

    /**
     * @var bool
     */
    public bool $hasCpSettings = true;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        $config['components'] = [
            'sso' => SsoService::class,
        ];

        parent::__construct($id, $parent, $config);
    }

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            static function (Event $event) {
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
    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): ?string
    {
        // Set up the form controls for editing the connection.
        $hashTypes = hash_algos();
        $hashTypes = array_combine($hashTypes, $hashTypes);

        return Craft::$app->view->renderTemplate(
            'vanillaforums/settings',
            [
                'settings' => $this->getSettings(),
                'hashTypes' => $hashTypes,
            ]
        );
    }
}
