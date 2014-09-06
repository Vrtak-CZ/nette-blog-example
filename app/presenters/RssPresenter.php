<?php

namespace App\Presenters;

class RssPresenter extends BasePresenter
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
	 * @param $slug string
	 */
	public function renderComments($slug)
	{
		$article = $this->loadArticleBySlug($slug);

		$this->template->article = $article;
		$this->template->comments = $article->related('comments')->order('published DESC');
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
