<?php
/**
 * Created by PhpStorm.
 * User: Dino
 * Date: 11.7.2019.
 * Time: 13:38
 */


namespace IdeaVerum\Medium\Components;


use Cms\Classes\ComponentBase;
use IdeaVerum\Medium\Models\Article;
use IdeaVerum\Medium\Models\Author;

class AuthorList extends ComponentBase{

    public $authors;
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

        $this->orderByColumn = explode(" ",$this->properties["sortOrder"])[0];
        $this->orderByValue = explode(" ",$this->properties["sortOrder"])[1];

        //if($this->properties["author"])
        $this->authors = $this->getAuthors();

        if( $this->properties["dev"] == "1")
        {
            echo "<pre>";print_r($this->authors);exit;
        }

        $this->addCss("/plugins/ideaverum/medium/assets/css/component/style.css");

    }

    public function componentDetails()
    {
        return [
            'name'        => 'Author list',
            'description' => 'List authors'
        ];
    }

    public function defineProperties()
    {
        return[
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

    private function getAuthors(){
        $number_displayed = $this->properties["displayNumber"];
        if($number_displayed == 0)
            $number_displayed = 10; // defaulting
        $authors = Author::take($number_displayed)->orderBy($this->orderByColumn,$this->orderByValue)->get();
        return $authors;
    }
}
