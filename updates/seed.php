<?php namespace IdeaVerum\Medium\Updates;

use Seeder;
use  IdeaVerum\Medium\Models\MediumSettings;

class Seed extends Seeder
{
    public function run()
    {
        MediumSettings::create([
           'type' => 'token',
           'value' => '-'
        ]);
    }
}
