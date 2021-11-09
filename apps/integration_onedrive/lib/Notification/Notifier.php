<?php
/**
 * Nextcloud - onedrive
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien Veyssier <eneiluj@posteo.net>
 * @copyright Julien Veyssier 2020
 */

namespace OCA\Onedrive\Notification;

use InvalidArgumentException;
use OCP\IURLGenerator;
use OCP\IUserManager;
use OCP\L10N\IFactory;
use OCP\Notification\IManager as INotificationManager;
use OCP\Notification\INotification;
use OCP\Notification\INotifier;
use OCA\Onedrive\AppInfo\Application;

class Notifier implements INotifier {

	/** @var IFactory */
	protected $factory;

	/** @var IUserManager */
	protected $userManager;

	/** @var INotificationManager */
	protected $notificationManager;

	/** @var IURLGenerator */
	protected $url;

	/**
	 * @param IFactory $factory
	 * @param IUserManager $userManager
	 * @param INotificationManager $notificationManager
	 * @param IURLGenerator $urlGenerator
	 */
	public function __construct(IFactory $factory,
								IUserManager $userManager,
								INotificationManager $notificationManager,
								IURLGenerator $urlGenerator) {
		$this->factory = $factory;
		$this->userManager = $userManager;
		$this->notificationManager = $notificationManager;
		$this->url = $urlGenerator;
	}

	/**
	 * Identifier of the notifier, only use [a-z0-9_]
	 *
	 * @return string
	 * @since 17.0.0
	 */
	public function getID(): string {
		return 'integration_onedrive';
	}
	/**
	 * Human readable name describing the notifier
	 *
	 * @return string
	 * @since 17.0.0
	 */
	public function getName(): string {
		return $this->factory->get('integration_onedrive')->t('Onedrive');
	}

	/**
	 * @param INotification $notification
	 * @param string $languageCode The code of the language that should be used to prepare the notification
	 * @return INotification
	 * @throws InvalidArgumentException When the notification was not prepared by a notifier
	 * @since 9.0.0
	 */
	public function prepare(INotification $notification, string $languageCode): INotification {
		if ($notification->getApp() !== 'integration_onedrive') {
			// Not my app => throw
			throw new InvalidArgumentException();
		}

		$l = $this->factory->get('integration_onedrive', $languageCode);

		switch ($notification->getSubject()) {
		case 'import_onedrive_finished':
			$p = $notification->getSubjectParameters();
			$nbImported = (int) ($p['nbImported'] ?? 0);
			$targetPath = $p['targetPath'];
			$content = $l->n('%s file was imported from Onedrive storage.', '%s files were imported from Onedrive storage.', $nbImported, [$nbImported]);

			$notification->setParsedSubject($content)
				->setIcon($this->url->getAbsoluteURL($this->url->imagePath(Application::APP_ID, 'app-dark.svg')))
				->setLink($this->url->linkToRouteAbsolute('files.view.index', ['dir' => $targetPath]));
			return $notification;

		default:
			// Unknown subject => Unknown notification => throw
			throw new InvalidArgumentException();
		}
	}
}
