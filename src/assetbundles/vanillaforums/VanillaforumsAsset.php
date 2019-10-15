<?php
/**
 * Vanillaforums plugin for Craft CMS 3.x
 *
 * Single Sign On plugin for VanillaForums/jsConnect and CraftCMS
 *
 * @link      https://nystudio107.com/
 * @copyright Copyright (c) 2019 nystudio107
 */

namespace nystudio107\vanillaforums\assetbundles\Vanillaforums;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    nystudio107
 * @package   Vanillaforums
 * @since     3.0.0
 */
class VanillaforumsAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@nystudio107/vanillaforums/assetbundles/vanillaforums/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/Vanillaforums.js',
        ];

        $this->css = [
            'css/Vanillaforums.css',
        ];

        parent::init();
    }
}
