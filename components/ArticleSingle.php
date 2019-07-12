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

class ArticleSingle extends ComponentBase{

    public $article;
    protected $properties;

    public function onRun()
    {
        $this->properties = $this->getProperties();
        //echo "<pre>";print_r($this->properties);exit;
        $this->article = $this->getArticle();
        if( $this->properties["dev"] == "1")
        {
            echo "<pre>";print_r($this->article);exit;
        }

        $this->addCss("/plugins/ideaverum/medium/assets/css/component/style.css");

    }

    public function componentDetails()
    {
        return [
            'name'        => 'Article single',
            'description' => 'Display article'
        ];
    }

    public function defineProperties()
    {
        return[
            'authorUrl' => [
                'title' => 'Article author',
                'description' => 'Author name of the article',
                'default' => '{{ :author_name }}',
                'type' => 'string'
            ],
            'articleUrl'=>[
                'title' => 'Article local url',
                'description' => 'Local url of article',
                'default' => '{{ :local_url }}',
                'type' => 'string'
            ],
            'dev'=>[
                'title' => 'Development',
                'description' => 'Enable dev to print value on screen',
                'default'=> 0,
                'type'=> 'checkbox'
            ]
        ];
    }

    private function getArticle(){
        $local_url = $this->properties['articleUrl'];
        $article = Article::where('local_url','=',$local_url)->first();
        return $article;
    }

}
