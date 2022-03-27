<?php
/**
 * Vanillaforums plugin for Craft CMS 3.x
 *
 * Single Sign On plugin for VanillaForums/jsConnect and CraftCMS
 *
 * @link      https://nystudio107.com/
 * @copyright Copyright (c) 2019 nystudio107
 */

namespace nystudio107\vanillaforums\services;

use Craft;
use craft\base\Component;
use craft\helpers\App;
use nystudio107\vanillaforums\events\SsoDataEvent;
use nystudio107\vanillaforums\models\Settings;
use nystudio107\vanillaforums\models\SsoData;
use nystudio107\vanillaforums\Vanillaforums;
use Vanilla\JsConnect\JsConnect;
use yii\base\ExitException;
use yii\base\InvalidConfigException;

/** @noinspection MissingPropertyAnnotationsInspection */

/**
 * @author    nystudio107
 * @package   Vanillaforums
 * @since     3.0.0
 */
class Sso extends Component
{
    // Constants
    // =========================================================================

    /**
     * @event SsoDataEvent The event that is triggered before the SSO data is used,
     * you may modify the [[SsoDataEvent::data]] as you see fit. You may set
     * [[SsoDataEvent::isValid]] to `false` to prevent SSO data from being used.
     *
     * ```php
     * use nystudio107\vanillaforums\services\Sso;
     * use nystudio107\vanillaforums\events\SsoDataEvent;
     *
     * Event::on(Sso::class,
     *     SsoDataEvent::EVENT_SSO_DATA,
     *     function(SsoDataEvent $event) {
     *         // potentially set $event->isValid or modify $event->data
     *     }
     * );
     * ```
     */
    public const EVENT_SSO_DATA = 'vanillaForumsSsoData';

    // Public Methods
    // =========================================================================

    /**
     * Generate the jsConnect string for single sign on
     *
     * @param string $jwt
     *
     * @throws ExitException|InvalidConfigException
     */
    public function output(string $jwt): void
    {
        $settings = $this->getPluginSettings();
        $ssoData = $this->getSsoData($jwt);
        $jsConnect = new JsConnect();
        $jsConnect->setSigningCredentials($settings->vanillaForumsClientID, $settings->vanillaForumsSecret);
        // If they are signed in to Craft
        if ($ssoData !== null) {
            $jsConnect
                ->setUniqueID($ssoData->uniqueid)
                ->setName($ssoData->name)
                ->setEmail($ssoData->email)
                ->setPhotoUrl($ssoData->photourl);
        } else {
            // They are not signed in to Craft
            $jsConnect->setGuest(true);
        }
        $request = Craft::$app->getRequest();
        // And away we go
        $jsConnect->handleRequest($request->get());
        Craft::$app->end();
    }

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
        $result = '';
        $settings = $this->getPluginSettings();
        $ssoData = $this->getSsoData($userId);
        if ($ssoData !== null) {
            $jsConnect = new JsConnect();
            $jsConnect->setSigningCredentials($settings->vanillaForumsClientID, $settings->vanillaForumsSecret);
            $jsConnect
                ->setUniqueID($ssoData->uniqueid)
                ->setName($ssoData->name)
                ->setEmail($ssoData->email)
                ->setPhotoUrl($ssoData->photourl);
            // @TODO unclear how to return a string using the new library
            // https://github.com/vanilla/jsConnectPHP
        }

        return $result;
    }

    // Private Methods
    // =========================================================================

    /**
     * Return an SSOData object filled in with the current user's info, or null
     *
     * @param int $userId
     *
     * @return ?SsoData
     * @throws InvalidConfigException
     */
    private function getSsoData(int $userId = 0): ?SsoData
    {
        $data = null;
        // Assume the currently logged in user if no $userId is passed in
        if ($userId === 0) {
            $user = Craft::$app->getUser()->getIdentity();
        } else {
            $users = Craft::$app->getUsers();
            $user = $users->getUserById($userId);
        }
        if ($user) {
            $generalConfig = Craft::$app->getConfig()->getGeneral();
            $name = $generalConfig->useEmailAsUsername ? $user->fullName : $user->username;
            $photoUrl = '';
            $photo = $user->getPhoto();
            if ($photo !== null) {
                $photoUrl = $photo->getUrl();
            }
            // Fill in the initial data
            $data = new SsoData([
                'uniqueid' => $user->id,
                'name' => $name ?? '',
                'email' => $user->email ?? '',
                'photourl' => $photoUrl ?? '',
            ]);
        }
        // Give plugins a chance to modify it
        $event = new SsoDataEvent([
            'user' => $user,
            'ssoData' => $data,
        ]);
        $this->trigger(self::EVENT_SSO_DATA, $event);
        if (!$event->isValid) {
            return null;
        }

        return $event->ssoData;
    }

    /**
     * Get the plugin's settings, parsing any environment variables
     *
     * @return Settings
     */
    private function getPluginSettings(): Settings
    {
        /** @var Settings $settings */
        $settings = Vanillaforums::$plugin->getSettings();
        $settings->vanillaForumsClientID = App::parseEnv($settings->vanillaForumsClientID);
        $settings->vanillaForumsSecret = App::parseEnv($settings->vanillaForumsSecret);

        return $settings;
    }

    /**
     * Clear the output buffer to prevent corrupt downloads.
     *
     * Need to check the OB status first, or else some PHP versions will throw an E_NOTICE
     * since we have a custom error handler (http://pear.php.net/bugs/bug.php?id=9670).
     */
    private function _clearOutputBuffer(): void
    {
        // Turn off output buffering and discard OB contents
        while (ob_get_length() !== false) {
            // If ob_start() didn't have the PHP_OUTPUT_HANDLER_CLEANABLE flag, ob_get_clean() will cause a PHP notice
            // and return false.
            if (@ob_get_clean() === false) {
                break;
            }
        }
    }
}
