<?php
/**
 * Created by PhpStorm.
 * User: substractive
 * Date: 2019-07-01
 * Time: 20:12
 */

namespace IdeaVerum\Medium\Controllers;


use Backend\Classes\Controller;
use Backend\Facades\BackendAuth;
use Backend\Models\User;
use IdeaVerum\Medium\Models\Article;
use IdeaVerum\Medium\Models\MediumSettings;
use IdeaVerum\Medium\SDK\MediumAPI;
use Illuminate\Http\Request;

use BackendMenu;
use Flash;

class Settings extends Controller
{
    public $pageTitle = "Settings";
    private $_api;
    public $requiredPermissions = ['ideaverum.medium.plugin'];
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('IdeaVerum.Medium', 'settings','settings');
        $this->initAPI();
        $this->addJs("/plugins/ideaverum/medium/assets/js/toastr.min.js");
        $this->addJs("/plugins/ideaverum/medium/assets/js/main.js");
    }

    public function index()
    {
        $settings = MediumSettings::all();
        foreach ($settings as $item){
            $this->vars[$item->type] = $item->value;
        }
        if(!is_null($this->_api)){
            $user = $this->getUser();
            $this->vars["user"] = $user["data"]->data->username;

        }
    }

    public function onSaveSettings(){
        $token = input('token');
        $settings = MediumSettings::where('type','=','token')->first();
        $settings->value = $token;
        $settings->save();
        if($token != "")
            $this->initAPI();
    }



    private function initAPI(){

        $token = MediumSettings::where('type','=','token')->first();
        if(!empty($token->value))
            $this->_api = new MediumAPI($token->value);
        else
            $this->_api = null;
    }


    private function getPublicationData(){
        if(!is_null($this->_api)){
            $publications = $this->_api->getPublications();
            return ["status"=>1,"data"=>$publications];
        }
        else
            return ["status"=>0,"error"=>"API is not set"];
    }

    private function getUser(){
        if(!is_null($this->_api)){
            $user = $this->_api->getUser();
            return ["status"=>1,"data"=>$user];
        }
        else
            return ["status"=>0,"error"=>"API is not set"];
    }
}
