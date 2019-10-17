<?php
/**
 * Vanillaforums plugin for Craft CMS 3.x
 *
 * Single Sign On plugin for VanillaForums/jsConnect and CraftCMS
 *
 * @link      https://nystudio107.com/
 * @copyright Copyright (c) 2019 nystudio107
 */

namespace nystudio107\vanillaforums\models;

use nystudio107\vanillaforums\Vanillaforums;

use craft\base\Model;
use craft\behaviors\EnvAttributeParserBehavior;

use yii\behaviors\AttributeTypecastBehavior;

/**
 * @author    nystudio107
 * @package   Vanillaforums
 * @since     3.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string Vanilla Forums jsConnect Client ID
     */
    public $vanillaForumsClientID = '';

    /**
     * @var string Vanilla Forums jsConnect Secret
     */
    public $vanillaForumsSecret = '';

    /**
     * @var string The hash algorithm to be ued when signing requests
     */
    public $hashAlgorithm = 'md5';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vanillaForumsClientID', 'vanillaForumsSecret'], 'string'],
            [['vanillaForumsClientID', 'vanillaForumsSecret'], 'default', 'value' => ''],
            ['hashAlgorithm', 'string'],
            ['hashAlgorithm', 'default', 'value' => 'md5'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        // Keep any parent behaviors
        $behaviors = parent::behaviors();
        // Add in the AttributeTypecastBehavior
        $behaviors['typecast'] = [
            'class' => AttributeTypecastBehavior::class,
            // 'attributeTypes' will be composed automatically according to `rules()`
        ];
        // If we're running Craft 3.1 or later, add in the EnvAttributeParserBehavior
        if (Vanillaforums::$craft31) {
            $behaviors['parser'] = [
                'class' => EnvAttributeParserBehavior::class,
                'attributes' => [
                    'vanillaForumsClientID',
                    'vanillaForumsSecret'
                ],
            ];
        }

        return $behaviors;
    }
}
