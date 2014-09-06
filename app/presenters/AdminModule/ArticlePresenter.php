<?php

namespace App\AdminModule\Presenters;

use Nette\Application\UI\Form;
use Nette\Utils\Strings;

class ArticlePresenter extends BasePresenter
{
	/** @var \Nette\Database\Context */
	private $database;

	public function __construct(\Nette\Database\Context $database)
	{
		parent::__construct();

		$this->database = $database;
	}

	public function renderDefault()
	{
		$this->template->articles = $this->database->table('articles')->order('published DESC');
	}

	/**
	 * @param int $id
	 */
	public function actionEdit($id)
	{
		$article = $this->loadArticleById($id);

		$this->getComponent('form')->setDefaults(array(
			'name' => $article->name,
			'slug' => $article->slug,
			'text' => $article->text,
		));
	}

	/**
	 * @return \Nette\Application\UI\Form
	 */
	protected function createComponentForm()
	{
		$form = new Form();

		$form->addText('name', 'Name')->setRequired();
		$form->addText('slug', 'Slug');
		$form->addTextArea('text', 'Text')->setRequired();

		$form->addSubmit('save', 'Save');

		$form->onSuccess[] = function (Form $form, \Nette\Utils\ArrayHash $values) {
			$articleId = $this->getParameter('id', null);
			if ($articleId !== null) {
				$article = $this->loadArticleById($articleId);
			}

			$slug = $form->getComponent('slug')->isFilled() ? $values->slug : Strings::webalize($values->name);
			$data = array(
				'name' => $values->name,
				'slug' => $slug,
				'text' => $values->text,
			);
			if (isset($article)) {
				$article->update($data);
			} else {
				$data['published'] = new \DateTimeImmutable();
				$this->database->table('articles')->insert($data);
			}

			$this->flashMessage(sprintf('Article %s saved', $values->name), 'success');
			$this->redirect('default');
		};

		return $form;
	}

	/**
	 * @param $id int
	 * @return \Nette\Database\Table\ActiveRow
	 */
	private function loadArticleById($id)
	{
		$article = $this->database->table('articles')->get($id);
		if ($article === FALSE) {
			$this->error('Article not found');
		}
		return $article;
	}
}
