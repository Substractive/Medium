<?php
/**
 * Created by PhpStorm.
 * User: substractive
 * Date: 2019-07-07
 * Time: 17:28
 */

namespace IdeaVerum\Medium\Components;


use Cms\Classes\ComponentBase;
use IdeaVerum\Medium\Models\Article;
use IdeaVerum\Medium\Models\Author;

class ArticleList extends ComponentBase{

    public $articles;
    protected $properties;
    private $orderByColumn;
    private $orderByValue;

    public static $allowedSortingOptions = [
        'created_at asc' => 'Created (ascending)',
        'created_at desc' => 'Created (descending)'
    ];


    public function onRun()
    {
        $this->properties = $this->getProperties();
        //echo "<pre>";print_r($this->properties);exit;

        $this->orderByColumn = explode(" ",$this->properties["sortOrder"])[0];
        $this->orderByValue = explode(" ",$this->properties["sortOrder"])[1];

        //if($this->properties["author"])
        if(is_null($this->properties["author"]))
            $this->articles = $this->getArticles();
        else
            $this->articles = $this->getArticlesByAuthor($this->properties["author"]);
        if( $this->properties["dev"] == "1")
        {
            echo "<pre>";print_r($this->articles);exit;
        }

        $this->addCss("/plugins/ideaverum/medium/assets/css/component/style.css");

    }

    public function componentDetails()
    {
        return [
            'name'        => 'Article RSS',
            'description' => 'Display articles'
        ];
    }

    public function defineProperties()
    {
        return[
            'author' => [
                'title' => 'Display articles by author',
                'description' => 'Use author name if you want to filter articles by author',
                'default' => '{{:author_name}}',
                'type' => 'string'
            ],
            'dev'=>[
                'title' => 'Development',
                'description' => 'Enable dev to print values on screen',
                'default'=> 0,
                'type'=> 'checkbox'
            ],
            'displayNumber' => [
                'title'             => 'ideaverum.medium::lang.settings.displayNumber',
                'description' => 'ideaverum.medium::lang.settings.displayDescription',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'rainlab.blog::lang.settings.posts_per_page_validation',
                'default'           => '10',
            ],
            'sortOrder' => [
                'title'       => 'ideaverum.medium::lang.settings.order',
                'description' => 'ideaverum.medium::lang.settings.article_order_desc',
                'type'        => 'dropdown',
                'default'     => 'created_at desc'
            ],
        ];
    }

    public function getSortOrderOptions()
    {
        return ArticleList::$allowedSortingOptions;
    }

    private function getArticles(){
        $number_displayed = $this->properties["displayNumber"];
        if($number_displayed == 0)
            $number_displayed = 10; // defaulting
        $articles = Article::take($number_displayed)->orderBy($this->orderByColumn,$this->orderByValue)->get();
        return $articles;
    }

    private function getArticlesByAuthor($author){
        $author_obj = Author::where('name','=',$author)->first();
        $number_displayed = $this->properties["displayNumber"];
        if($number_displayed == 0)
            $number_displayed = 10; // defaulting
        $articles = Article::where('author_id','=',$author_obj->id)->take($number_displayed)->orderBy($this->orderByColumn,$this->orderByValue)->get();
        return $articles;
    }

}
