<?php

namespace App\Presenters;

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
		$article = $this->loadArticleBySlug($slug);

		$this->template->article = $article;
		$this->template->title = $article->name;
	}

	/**
	 * @return \App\Components\CommentsControl
	 */
	protected function createComponentComments()
	{
		$article = $this->loadArticleBySlug($this->getParameter('slug', NULL));

		$control = new \App\Components\CommentsControl($this->database, $article->id);
		return $control;
	}

	/**
	 * @param $slug string
	 * @return \Nette\Database\Table\ActiveRow
	 */
	private function loadArticleBySlug($slug)
	{
		$article = $this->database->table('articles')->where('slug = ?', $slug)->fetch();
		if ($article === FALSE) {
			$this->error('Article not found');
		}
		return $article;
	}
}
