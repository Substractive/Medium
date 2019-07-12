<?php
/**
 * Created by PhpStorm.
 * User: substractive
 * Date: 2019-07-02
 * Time: 19:23
 */

namespace IdeaVerum\Medium\Controllers;


use Backend\Classes\Controller;
use Backend\Facades\BackendAuth;
use Backend\Models\User;
use IdeaVerum\Medium\Models\Article;
use IdeaVerum\Medium\Models\Author;
use IdeaVerum\Medium\Models\MediumSettings;
use IdeaVerum\Medium\SDK\MediumAPI;
use Illuminate\Http\Request;
use Carbon\Carbon;

use BackendMenu;
use Flash;

class Articles extends Controller
{
    public $pageTitle = "Articles";
    private $_api;

    public $requiredPermissions = ['ideaverum.medium.plugin'];
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('IdeaVerum.Medium', 'settings','articles'); // has to be uppercase plugin name always

        $this->initAPI();

        $this->addCss("/plugins/ideaverum/medium/assets/css/backend/style.css");
        $this->addCss("/plugins/ideaverum/medium/assets/css/toastr.css");
        $this->addJs("/plugins/ideaverum/medium/assets/js/toastr.min.js");
        $this->addJs("/plugins/ideaverum/medium/assets/js/main.js");

    }

    public function index() // folder of the views has to have same name as this class in order to work
    {
        $this->vars["articles"] = $this->getArticles();
    }

    public function onSaveSettings(){
        $token = input('token');
        $settings = MediumSettings::where('type','=','token')->first();
        $settings->value = $token;
        $settings->save();
    }

    public function onGetData(){
        $token = MediumSettings::where('type','=','token')->first();
        $api = new MediumAPI($token->value);
        $user = $api->getUser();

        $publications = $api->getPublications();

        $feed = $api->getArticles(str_slug($publications->data[0]->name));
    }

    public function preview($id){
        $this->vars['article'] = Article::find($id);
    }

    public function feed(){
        $authors = Author::all();
        $this->vars["authors"] = $authors;
    }

    public function onArticleFetch(){
        $name = input('name');
        if($name != ""){
            $author = Author::where('name','=',str_slug($name))->first();
            if(is_null($author)){
                $author = new Author();
                $author->name = str_slug($name);
                $author->save();
            }
            $response = $this->getArticleData($author->name);
            if($response["status"]){
                Article::where('author_id','=',$author->id)->delete();
                foreach ($response["data"] as $article){
                    $exploded_link = explode('/',$article["link"]);
                    $article_name = explode('?',$exploded_link[4])[0];
                    $local_url = $article_name;
                    $publish_date = Carbon::parse($article["date"]);

                    $article_obj = new Article();
                    $article_obj->author_id = $author->id;
                    $article_obj->title = $article["title"];
                    $article_obj->local_url = $local_url;
                    $article_obj->link = $article["link"];
                    $article_obj->content = $article["content"];
                    $article_obj->publish_date = $publish_date;
                    $article_obj->save();
                }
            }else{
                $author->delete();
            }
            return[
                "response" => $response
            ];
        }
        return[
            "response" => ["status"=>0,"error"=>"Empty name"]
        ];
    }

    public function onRefreshArticles(){
        $name = input('authorName');
        $author = Author::where("name","=",$name)->first();
        Article::where('author_id','=',$author->id)->delete();
        $response = $this->getArticleData($author->name);

        if($response["status"]){
            foreach ($response["data"] as $article){
                $exploded_link = explode('/',$article["link"]);
                $article_name = explode('?',$exploded_link[4])[0];
                $local_url = $article_name;
                $publish_date = Carbon::parse($article["date"]);

                $article_obj = new Article();
                $article_obj->author_id = $author->id;
                $article_obj->title = $article["title"];
                $article_obj->link = $article["link"];
                $article_obj->local_url = $local_url;
                $article_obj->content = $article["content"];
                $article_obj->publish_date = $publish_date;
                $article_obj->save();
            }
        }
        return[
            "response" => $response
        ];
    }


    private function getArticles(){
        $articles = Article::all();
        return $articles;
    }

    private function getArticleData($name){
        if(!is_null($this->_api)){
            $feed = $this->_api->getArticles(str_slug($name));
            if($feed['status'])
                return ["status"=>1,"data"=>$feed['articles']];
            else
                return ["status" => 0, "error"=> $feed['error']];
        }
        else
            return ["status"=>0,"error"=>"API is not set"];
    }

    private function initAPI(){
        $token = MediumSettings::where('type','=','token')->first();
        if(!empty($token->value))
            $this->_api = new MediumAPI($token->value);
        else
            $this->_api = null;
    }
}
