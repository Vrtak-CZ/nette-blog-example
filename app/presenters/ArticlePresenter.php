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
		$article = $this->database->table('articles')->where('slug = ?', $slug)->fetch();
		if ($article === false) {
			$this->error('Article not found');
		}

		$this->template->article = $article;
		$this->template->title = $article->name;
	}
}
