<?php
/**
 * Vanillaforums plugin for Craft CMS 3.x
 *
 * Single Sign On plugin for VanillaForums/jsConnect and CraftCMS
 *
 * @link      https://nystudio107.com/
 * @copyright Copyright (c) 2019 nystudio107
 */

namespace nystudio107\vanillaforums\controllers;

use nystudio107\vanillaforums\Vanillaforums;

use craft\web\Controller;

/**
 * @author    nystudio107
 * @package   Vanillaforums
 * @since     3.0.0
 */
class SsoController extends Controller
{
    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected array|bool|int $allowAnonymous = ['output'];

    // Public Methods
    // =========================================================================

    /**
     * Generate the jsConnect string for single sign on
     *
     * @param string $jwt
     *
     * @throws \yii\base\ExitException
     */
    public function actionOutput(string $jwt)
    {
        Vanillaforums::$plugin->sso->output($jwt);
    }
}
