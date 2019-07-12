<?php


namespace IdeaVerum\Medium\SDK;


use IdeaVerum\Medium\SDK\MediumHttpClient as MediumClient;
class MediumAPI{

    private $_client;
    private $_selfIssuedAccessToken;
    private $_authUserID;


    public function __construct($token)
    {
        if($token != null){
            $this->_selfIssuedAccessToken = $token;
            $this->setMediumClient();
        }
    }

    public function getUser(){
        $user = $this->_client->getRequest('me');
        $this->_authUserID = $user->data->id;
        return $user;
    }

    public function getPublications(){
        $publications = $this->_client->getRequest('users/'.$this->_authUserID.'/publications');
        return $publications;
    }

    public function getArticles($publicationName){
        $articles = $this->_client->getArticles($publicationName);
        return $articles;
    }

    public function getContributors($publicationID){
        $contributors = $this->_client->getRequest('publications/'.$publicationID.'/contributors');
        return $contributors;
    }

    /**
     * Sets Client
     * uses self issued token
     */
    private function setMediumClient(){
        $this->_client = new MediumClient();
        $this->_client->setClient($this->_selfIssuedAccessToken);
    }


}
