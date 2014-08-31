<?php

namespace App\Components;

use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

class CommentsControl extends \Nette\Application\UI\Control
{
	/** @var \Nette\Database\Context */
	private $database;

	/** @var int */
	private $articleId;

	/**
	 * @param \Nette\Database\Context $database
	 * @param int $articleId
	 */
	public function __construct(\Nette\Database\Context $database, $articleId)
	{
		parent::__construct();

		$this->database = $database;
		$this->articleId = $articleId;
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{
		$form = new Form();

		$form->addText('name', 'Name')->setRequired();
		$form->addText('email', 'Email')->setRequired()
			->addRule(Form::EMAIL, 'Is not valid e-mail');
		$form->addText('web', 'Web')
			->addCondition(Form::FILLED)
				->addRule(Form::URL, 'Is not valid URL');
		$form->addTextArea('text', 'Text')->setRequired();

		$form->addSubmit('save', 'Add comment');

		$form->onSuccess[] = function(Form $form, ArrayHash $values) {
			$this->database->table('comments')->insert(array(
				'article_id' => $this->articleId,
				'name' => $values->name,
				'email' => $values->email,
				'web' => $form->getComponent('web')->isFilled() ? $values->web : NULL,
				'text' => $values->text,
				'published' => new \DateTimeImmutable(),
			));

			$this->flashMessage('Comment successfully added');
			$this->redirect('this');
		};

		return $form;
	}

	public function render()
	{
		$this->template->comments = $this->database->table('comments')
			->where('article_id = ?', $this->articleId)
			->order('published DESC');

		$this->template->setFile(__DIR__ . '/CommentsControl.latte');
		$this->template->render();
	}
}
