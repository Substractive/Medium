<?php
/**
 * Created by PhpStorm.
 * User: substractive
 * Date: 2019-07-01
 * Time: 19:07
 */

namespace IdeaVerum\Medium\SDK;

use DOMDocument;
use GuzzleHttp\Client as Guzzle;

class MediumHttpClient{


    private $_client;
    private $_mediumBaseApiUrl = "https://api.medium.com/v1/";
    private $_mediumArticleUrl = "https://api.medium.com/feed/";
    /**
     * Auth Medium client for authenticated API requests
     * @param $token
     */
    public function setClient($token){
        $this->_client = new Guzzle([
            'base_url' => $this->_mediumBaseApiUrl,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Accept-Charset' => 'utf-8',
                'Authorization' => 'Bearer ' . $token,
            ]
        ]);
    }

    public function getRequest($endpoint,$data = []){
        $res = $this->_client->request("GET",$this->_mediumBaseApiUrl . $endpoint,$data);
        return json_decode($res->getBody());
    }

    public function postRequest($endpoint,$data){
        $res = $this->_client->request("POST",$endpoint,$data);
        return json_decode($res->getBody());
    }

    public function getArticles($name){
        return $this->parseArticleRss($name);
    }

    /**
     * Grabs RSS feed from medium and parses it into array
     * @param $name
     * @return array
     */
    private function parseArticleRss($name){
        try{
            $rss = new DOMDocument();
            $rss->load($this->_mediumArticleUrl . $name);
            $articles = [];
            foreach ($rss->getElementsByTagName('item') as $node){
                $item = array (
                    'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                    'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                    'content'=> $node->getElementsByTagName('encoded')->item(0)->nodeValue,
                    'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
                );
                $content_exploded = explode("<p>",$item['content']);
                $content_exploded[count($content_exploded) - 1] = $content_exploded[count($content_exploded ) - 1] . "<p> Free <a href='https://octobercms.com/plugin/ideaverum-medium'>Medium plugin</a> brought to you by <a href='https://www.ideaverum.hr/'>Idea Verum</a>.</p>";
                $item['content'] = implode("<p>",$content_exploded);
                array_push($articles, $item);
            }
            return ['status'=>1,'articles'=>$articles];
        }catch (\Exception $exception){
            return ['status'=>0,'error'=>'Fetch failed. Check your author name or try again later'];
        }

    }

}
