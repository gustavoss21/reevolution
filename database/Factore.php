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
    $data     = ['name' => $name, 'slug' => $faker->slug(), 'theme_id' => $range_id[array_rand($range_id)], 'description' => $faker->paragraph(), 'end_date' => $faker->dateTimeBetween('2025/08/01', '2026/12/01')->format('Y/m/d h:i:s'), 'lot_to_discuss' => (int)$faker->boolean()];  //, 'can_explain' => (int)$faker->boolean(), 'started_study' => (int)$faker->boolean(), 'study_time' => $faker->numberBetween(1, 12), 'domain_week' => $faker->numberBetween(0, 365), 'priority' => $faker->numberBetween(1, 4), 'more_advanced' => (int)$faker->boolean(), 'status' => $faker->numberBetween(-1, 1)];
    $stage    = new TopicModel($data);
    $stage->insert();
  }

  function factoreStage()
  {

    $faker    = Factory::create('pt_BR');
    $name     = $faker->company();
    $mT       = (new TopicModel())->columns(self::col('id'))->all();
    $range_id = array_map(fn($data) => $data['id'], $mT);
    $data     = ['name' => $name, 'topic_id' =>  $range_id[array_rand($range_id)], 'slug' => $name, 'status' => $faker->numberBetween(-1, 1), 'domain_level' => $faker->numberBetween(0, 2), 'learning_stage' => $faker->numberBetween(1, 4), 'priority' => $faker->numberBetween(1, 4), 'more_advanced' => (int)$faker->boolean()];
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

    $mS = (new StageModel())->all();

    $range_id = array_map(fn($data) => $data['id'], $mS);

    $data  = ['name' => $font, 'stage_id' => $range_id[array_rand($range_id)], 'description' => $faker->paragraph()];
    $stage = new FontModel($data);
    $stage->insert();
  }

  function factoreTag()
  {

    $faker    = Factory::create('pt_BR');

    $data = ['name' => $faker->word(), 'description' => $faker->paragraph()];
    $tag  = new TagModel($data);
    $tag->insert();
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

  function factoreRelationshipTagTopic(){
    $faker = Factory::create('pt_BR');
    $mT    = (new TopicModel())->columns(self::col('id'))->all();
    $mTa   = (new TagModel())->columns(self::col('id'))->all();
    $range_id_topic = array_map(fn($data) => $data['id'], $mT);
    $range_id_tag = array_map(fn($data) => $data['id'], $mTa);

    for ($x = 0; $x < 20; $x++) {
      $topic_id = $range_id_topic[array_rand($range_id_topic)];
      $tag_id   = $range_id_tag[array_rand($range_id_tag)];
      TopicModel::connect_tags($topic_id, $tag_id);
    }
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
  //  $class->factoreTheme();
  //  $class->factoreTopic();
  //  $class->factoreStage();
  //  $class->factoreFont();
  //  $class->factoreTag();
  $class->factoreRelationshipTagTopic();
 }

   //criar relacionamento entre tag e topic
// (new Factore())->factoreRelationshipTagTopic();

   // for ($x = 0; $x < 20; $x++) {
    //     (new Factore)->factoreStage();
   // }

        // (new Factore)->teste();