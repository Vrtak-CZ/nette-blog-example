<?php

namespace App\Presenters;

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
	 * @param string $slug
	 */
	public function renderDetail($slug)
	{
		$article = $this->database->table('articles')->where('slug = ?', $slug)->fetch();
		if ($article === false) {
			$this->error('Article not found');
		}

		$this->template->article = $article;
		$this->template->title = $article->name;
	}

	public function renderList()
	{
		$this->template->articles = $this->database->table('articles')->order('published DESC');
	}

	/**
	 * @param int $id
	 */
	public function actionEdit($id)
	{
		$article = $this->database->table('articles')->where('id = ?', $id)->fetch();
		if ($article === false) {
			$this->error('Article not found');
		}

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

		$form->onSuccess[] = function(Form $form, \Nette\Utils\ArrayHash $values) {
			$articleId = $this->getParameter('id', NULL);
			if ($articleId !== NULL) {
				$article = $this->database->table('articles')->where('id = ?', $articleId)->fetch();
			}

			$slug = $form->getComponent('slug')->isFilled() ? $values->slug : Strings::webalize($values->name);
			if (isset($article) && $article !== false) {
				$article->update(array(
					'name' => $values->name,
					'slug' => $slug,
					'text' => $values->text,
				));
			} else {
				$this->database->table('articles')->insert(array(
					'name' => $values->name,
					'slug' => $slug,
					'text' => $values->text,
					'published' => new \DateTimeImmutable(),
				));
			}

			$this->flashMessage(sprintf('Article %s saved', $values->name), 'success');
			$this->redirect('list');
		};

		return $form;
	}
}
