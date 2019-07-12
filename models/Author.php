<?php
/**
 * Created by PhpStorm.
 * User: substractive
 * Date: 2019-07-04
 * Time: 19:00
 */
namespace IdeaVerum\Medium\Models;
use Illuminate\Database\Eloquent\Model;

class Author extends Model{

    public $table = 'ideaverum_medium_authors';

    public function articles(){
        return $this->hasMany('IdeaVerum\Medium\Models\Article');
    }

}
