<?php
/**
 * Created by PhpStorm.
 * User: substractive
 * Date: 2018-12-21
 * Time: 20:52
 */



namespace IdeaVerum\Medium;

use IdeaVerum\Crm\Classes\CrmMailer;
use Illuminate\Support\Facades\Log;
use System\Classes\PluginBase;
use Backend;
use Event;
use App;
use Config;
use Illuminate\Foundation\AliasLoader;
class Plugin extends PluginBase{


    public function pluginDetails()
    {
        return [
            'name'        => 'Medium',
            'description' => 'Use Medium API with ease',
            'author'      => 'IDEA VERUM',
            'icon'        => 'icon-medium',
            'homepage'    =>  'https://www.ideaverum.hr/en/',
        ];
    }


    public function registerComponents()
    {
        return [
            'IdeaVerum\Medium\Components\ArticleList'       => 'articleList',
            'IdeaVerum\Medium\Components\ArticleSingle'       => 'articleSingle',
            'IdeaVerum\Medium\Components\AuthorList'       => 'authorList',
        ];
    }

    public function registerNavigation()
    {
        return [
            'settings' => [
                'label'=>'MEDIUM',
                'code'  => 'articles',
                'url'=>Backend::url('ideaverum/medium/articles'),
                'icon'=>'icon-medium',
                'order'=>400,
                'sideMenu' => [
                    'articles' => [
                        'label' => 'Articles',
                        'code' => 'articles',
                        'url' => Backend::url('ideaverum/medium/articles'),
                        'icon' => 'icon-newspaper-o',
                        'order' => 400
                    ],
                    'settings' => [
                        'label'=>'Settings',
                        'code'  => 'medium',
                        'url'=>Backend::url('ideaverum/medium/settings'),
                        'icon'=>'icon-cog',
                        'order'=>401,
                    ],
                ]
            ]
        ];
    }

    public function registerPermissions()
    {
        return [
            'ideaverum.medium.plugin' => [
                'label' => 'Manage the Medium plugin',
                'tab' => 'Social',
                'order' => 200,
            ],
        ];
    }

    public function Boot()
    {

    }
}
