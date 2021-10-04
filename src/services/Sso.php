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

use nystudio107\vanillaforums\models\Settings;
use nystudio107\vanillaforums\Vanillaforums;
use nystudio107\vanillaforums\events\SsoDataEvent;
use nystudio107\vanillaforums\models\SsoData;

use Craft;
use craft\base\Component;
use craft\web\Response;

use Vanilla\JsConnect\JsConnect;

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
    const EVENT_SSO_DATA = 'vanillaForumsSsoData';

    // Public Methods
    // =========================================================================

    /**
     * Generate the jsConnect string for single sign on
     *
     * @param int $userId
     *
     * @throws \yii\base\ExitException
     */
    public function output(string $jwt)
    {
        $result = '';
        $settings = $this->getPluginSettings();
        $ssoData = $this->getSsoData($jwt);
        if ($ssoData !== null) {
            $jsConnect = new JsConnect();
            $jsConnect->setSigningCredentials($settings->vanillaForumsClientID, $settings->vanillaForumsSecret);
            $jsConnect
                ->setUniqueID($ssoData->uniqueid)
                ->setName($ssoData->name)
                ->setEmail($ssoData->email)
                ->setPhotoUrl($ssoData->photourl)
            ;
            // Clear any headers that have been set
            header_remove();
            // Clear any output buffering that may be processed
            $this->_clearOutputBuffer();
            $request = Craft::$app->getRequest();
            // And away we go
            $jsConnect->handleRequest($request->get());
        }
    }

    /**
     * Generate an SSO string suitable for passing in the url for embedded SSO
     *
     * @param int $userId
     *
     * @return string
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
                ->setPhotoUrl($ssoData->photourl)
            ;
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
     * @return SsoData|null
     */
    private function getSsoData(string $jwt)
    {
        $data = null;

        $userId = 0;
        // Assume the currently logged in user if no $userId is passed in
        if ($userId === 0) {
            $user = Craft::$app->getUser()->getIdentity();
        } else {
            $users = Craft::$app->getUsers();
            $user = $users->getUserById($userId);
        }
        if ($user) {
            $generalConfig = Craft::$app->getConfig()->getGeneral();
            $name = $generalConfig->useEmailAsUsername ? $user->getFullName() : $user->username;
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
        if (Vanillaforums::$craft31) {
            $settings->vanillaForumsClientID = Craft::parseEnv($settings->vanillaForumsClientID);
            $settings->vanillaForumsSecret = Craft::parseEnv($settings->vanillaForumsSecret);
        }

        return $settings;
    }

    /**
     * Clear the output buffer to prevent corrupt downloads.
     *
     * Need to check the OB status first, or else some PHP versions will throw an E_NOTICE
     * since we have a custom error handler (http://pear.php.net/bugs/bug.php?id=9670).
     */
    private function _clearOutputBuffer()
    {
        if (ob_get_length() !== false) {
            // If zlib.output_compression is enabled, then ob_clean() will corrupt the results of output buffering.
            // ob_end_clean is what we want.
            ob_end_clean();
        }
    }
}
