<?php

namespace Factore;

require dirname(__FILE__) . '/vendor/autoload.php';

echo dirname(__FILE__) . '/vendor/autoload.php';

use Faker\Factory;
use Models\StageModel;
use Models\ThemeModel;
use Models\TopicModel;

function factoreStage()
{
    $faker = Factory::create('pt_BR');
    $name = $faker->name();
    $mT = (new TopicModel())->all(null, ['id']);
    $range_id = array_map(fn($data) => $data['id'], $mT);
    $data = ['name' => $name, 'topic_id' =>  $range_id[array_rand($range_id)], 'slug' => $name, 'status' => $faker->numberBetween(0,2), 'domain_level' => $faker->numberBetween(0,2), 'learning_stage' => $faker->numberBetween(1,4), 'priority' => $faker->numberBetween(1,4)];
    $stage = new StageModel($data);
    $stage->insert();
}

function factoreTheme()
{
    $faker = Factory::create('pt_BR');
    $name = $faker->company();
    $data = ['name' => $name, 'slug' =>$faker->slug(), 'description'=>$faker->paragraph(),  'updated_at'=> $faker->dateTimeBetween('2025/08/01')->format('Y/m/d h:i:s')];
    $stage = new ThemeModel($data);
    $stage->insert();
}

function factoreTopic()
{

    $faker = Factory::create('pt_BR');
    $name = $faker->jobTitle();
    $mT = (new ThemeModel())->all(null,['id']);
    $range_id = array_map(fn($data)=>$data['id'],$mT);
    $data = ['name' => $name, 'slug' => $faker->slug(), 'theme_id'=> $range_id[array_rand($range_id)], 'description' => $faker->paragraph()];
    $stage = new TopicModel($data);
    $stage->insert();
}

for( $x=0; $x < 5; $x++){
    factoreTheme();
}

for ($x = 0; $x < 20; $x++) {
    factoreTopic();
}

for ($x = 0; $x < 20; $x++) {
    factoreStage();
}