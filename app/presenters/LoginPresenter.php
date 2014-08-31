<?php

namespace App\Presenters;

use Nette\Application\UI\Form;

class LoginPresenter extends BasePresenter
{
	/**
	 * @persistent
	 * @var string
	 */
	public $backlink;

	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{
		$form = new Form();

		$form->addText('username', 'Username')->setRequired();
		$form->addPassword('password', 'Password')->setRequired();
		$form->addSubmit('login', 'Login');

		$form->onSuccess[] = function(Form $form, \Nette\Utils\ArrayHash $values) {
			try {
				$this->getUser()->login(
					$values->username,
					$values->password
				);

				if ($this->backlink !== NULL) {
					$this->restoreRequest($this->backlink);
				}
				$this->redirect('Admin:Article:default');
			} catch (\Nette\Security\AuthenticationException $e) {
				$form->addError('Invalid credentials');
			}
		};

		return $form;
	}
}
