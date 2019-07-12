<?php
/**
 * Created by PhpStorm.
 * User: substractive
 * Date: 2019-07-04
 * Time: 18:59
 */
namespace IdeaVerum\Medium\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Article extends Model{

    public $table = 'ideaverum_medium_articles';

    public function getPublishDate($value){
        $value = Carbon::parse($value)->format('d.m.Y H:i');
        return $value;
    }

    public function author(){
        return $this->belongsTo('IdeaVerum\Medium\Models\Author');
    }

}
