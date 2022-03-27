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
class SsoData extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var int Unique ID for this user
     */
    public $uniqueid;

    /**
     * @var string Display name for this user
     */
    public $name;

    /**
     * @var string Email address for this user
     */
    public $email;

    /**
     * @var string Ootional comma-delimited roles for this user
     */
    public $roles;

    /**
     * @var string URL to a photo for this user
     */
    public $photourl;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['uniqueid'], 'integer'],
            [['uniqueid'], 'default', 'value' => 0],
            [['name'], 'string'],
            [['name'], 'default', 'value' => ''],
            [['email'], 'email'],
            [['email'], 'default', 'value' => ''],
            [['roles'], 'string'],
            [['roles'], 'default', 'value' => ''],
            [['photourl'], 'url'],
            [['photourl'], 'default', 'value' => ''],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
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
                ],
            ];
        }

        return $behaviors;
    }
}
