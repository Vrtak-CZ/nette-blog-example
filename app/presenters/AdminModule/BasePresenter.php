<?php

namespace App\AdminModule\Presenters;

abstract class BasePresenter extends \App\Presenters\BasePresenter
{
	/**
	 * @param mixed $element
	 */
	public function checkRequirements($element)
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect(':Login:default');
		}
	}

	public function handleLogout()
	{
		$this->getUser()->logout(TRUE);
		$this->redirect(':Article:default');
	}
}
