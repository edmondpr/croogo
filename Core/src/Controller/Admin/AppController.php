<?php

namespace Croogo\Core\Controller\Admin;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Event\Event;
use Croogo\Core\Croogo;
use Croogo\Core\Controller\AppController as CroogoAppController;

/**
 * Croogo App Controller
 *
 * @category Croogo.Controller
 * @package  Croogo.Croogo.Controller
 * @version  1.5
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class AppController extends CroogoAppController {

/**
 * Helpers
 *
 * @var array
 * @access public
 */
	public $helpers = array(
		'Croogo/Core.Croogo',
	);

	/**
	 * Load the theme component with the admin theme specified
	 *
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();

		$this->Theme->config('theme', Configure::read('Site.admin_theme'));
	}

	/**
	 * Change the admin layout
	 *
	 * @param Event $event The event that's handled
	 */
	public function beforeRender(Event $event)
	{
		parent::beforeRender($event);

		$this->viewBuilder()->layout('Croogo/Core.admin');
	}

	/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter(Event $event) {
		parent::beforeFilter($event);

		if (
			Configure::read('Site.status') == 0 &&
			$this->Auth->user('role_id') != 1
		) {
			if (!$this->request->is('whitelisted')) {
				$this->layout = 'Croogo/Core.maintenance';
				$this->response->statusCode(503);
				$this->set('title_for_layout', __d('croogo', 'Site down for maintenance'));
				$this->viewPath = 'Maintenance';
				$this->render('Croogo/Core.blank');
			}
		}

		Croogo::dispatchEvent('Croogo.beforeSetupAdminData', $this);
	}
}
