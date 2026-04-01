<?php

namespace Database;

require dirname(__FILE__, 2) . '/vendor/autoload.php';

use Models\GenerateColumn;
use DateTime;
use Faker\Factory;
use Models\StageModel;
use Models\ThemeModel;
use Models\TopicModel;
use Models\FontModel;
use Models\TagModel;
use Models\ColumnTrait;
use Services\ConsultService;

class Factore
{
    use GenerateColumn;

    function factoreTheme()
    {
        $faker = Factory::create('pt_BR');
        $name  = $faker->company();
        $data  = ['name' => $name, 'slug' => $faker->slug(), 'description' => $faker->paragraph(),  'updated_at' => $faker->dateTimeBetween('2025/08/01')->format('Y/m/d h:i:s')];
        $stage = new ThemeModel($data);
        $stage->insert();
    }

    function factoreTopic()
    {

        $faker    = Factory::create('pt_BR');
        $name     = $faker->jobTitle();
        $mT       = (new ThemeModel())->all(null, ['id']);
        $range_id = array_map(fn($data) => $data['id'], $mT);
        $data     = ['name' => $name, 'slug' => $faker->slug(), 'theme_id' => $range_id[array_rand($range_id)], 'description' => $faker->paragraph()];
        $stage    = new TopicModel($data);
        $stage->insert();
    }

    function factoreStage()
    {

        $faker    = Factory::create('pt_BR');
        $name     = $faker->name();
        $mT       = (new TopicModel())->columns(self::col('id'))->all();
        $range_id = array_map(fn($data) => $data['id'], $mT);
        $data     = ['name' => $name, 'topic_id' =>  $range_id[array_rand($range_id)], 'slug' => $name, 'status' => $faker->numberBetween(-1, 1), 'domain_level' => $faker->numberBetween(0, 2), 'learning_stage' => $faker->numberBetween(1, 4), 'priority' => $faker->numberBetween(1, 4)];
        $stage    = new StageModel($data);
        $stage->insert();
    }

    function factoreFont()
    {

        $faker = Factory::create('pt_BR');
          //get method font
        $fontOptions = ['url', 'fileExtension'];
        $fakeMethod  = $fontOptions[array_rand($fontOptions)];
          //get font
        $font = $faker->{$fakeMethod}();
        $font = $fakeMethod == $fontOptions[1] ? $faker->word() . '.' . $font : $font;
          //get stage_id
        $mS       = (new StageModel())->all(null, ['id']);
        $range_id = array_map(fn($data) => $data['id'], $mS);

        $data  = ['font' => $font, 'stage_id' => $range_id[array_rand($range_id)], 'description' => $faker->paragraph()];
        $stage = new FontModel($data);
        $stage->insert();
    }

    function factoreTag()
    {

        $faker    = Factory::create('pt_BR');
        $name     = $faker->name();
        $mT       = (new TagModel())->columns(self::col('id'))->all();
        $range_id = array_map(fn($data) => $data['id'], $mT);
        $data     = ['name' => $name, 'topic_id' =>  $range_id[array_rand($range_id)], 'slug' => $name, 'status' => $faker->numberBetween(-1, 1), 'domain_level' => $faker->numberBetween(0, 2), 'learning_stage' => $faker->numberBetween(1, 4), 'priority' => $faker->numberBetween(1, 4)];
        $stage    = new StageModel($data);
        $stage->insert();
    }

    function factoreDropAll($model)
    {
        $model->delete(false, true);
    }

    function teste()
    {

        $faker = Factory::create('pt_BR');
        $name  = $faker->name();
        $mT    = new TopicModel();
        $mTs   = $mT->columns(self::col('id'));
        $mTa   = $mT->columns(self::col('name'));
        print_r($mT);
    }
}
        // DROP ALL DATA
        // (new Factore())->factoreDropAll(new StageModel);
        // (new Factore())->factoreDropAll(new ThemeModel);
        // (new Factore())->factoreDropAll(new TopicModel);
        // (new Factore())->factoreDropAll(new FontModel);
        // (new Factore())->factoreDropAll(new TagModel);

  for ($x = 0; $x < 20; $x++) {
     $class = new Factore;
     $class->factoreStage();
      $class->factoreTopic();
      $class->factoreFont();
      $class->factoreTheme();
      $class->factoreTag();

}

     // for ($x = 0; $x < 20; $x++) {
     //     (new Factore)->factoreStage();
     // }

        // (new Factore)->teste();