<?php
    $city = 'Samara,ru';
    $link = 'http://api.openweathermap.org/data/2.5/weather';
    $appid = '7bb7c2254e638e89b5db741b79ab9f7f';
    if (isset($_GET['city']) && strlen($_GET['city']) > 3 && !is_numeric($_GET['city'])) {
        $city = $_GET['city'];
    }
    $api_query = "$link?q=$city&appid=$appid";
    $api_weather_data = file_get_contents($api_query);
    if ($api_weather_data === false) {
        exit('Не удалось получить данные');
    }
    $file_name = 'weather.json';
    $res_file = fopen($file_name, 'w+');
    fwrite($res_file, $api_weather_data);
    fclose($res_file);
    $res_file = fopen($file_name, 'r');
    $json_data = fread($res_file, filesize($file_name));
    fclose($res_file);
    $data = json_decode($json_data);
    if ($data === null) {
        exit('Ошибка декодирования json');
    }
    $mmrtst = 0.0075006375541921;
    $current_weather = $data->weather[0]->main;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://yastatic.net/bootstrap/3.3.6/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="css/style.css">
  <title>Погода</title>
</head>
<body>
<div class="row <?= strtolower($current_weather) ?> bg">
  <div class="container">
    <div class="col-md-8 col-md-offset-2">
      <h1 style="font-size: 3em; font-weight: 100; text-align: center;"><?= $data->name ?></h1>
      <p style="text-align: center; font-size: 1.2em; font-weight:100"><?= date('d/M/Y') ?></p> <br>
      <div>
        <div class="curr">
          <img class="weather-pic" src="http://openweathermap.org/img/w/<?= $data->weather[0]->icon ?>.png" alt="Погода">
          <h4 class="weather-condition"><?= ucfirst($data->weather[0]->description) ?></h4>
        </div>
        <div class="max-min-temp">
          <p>
            &#8593; <?= (!empty($data->main->temp_max)) ? round($data->main->temp_max - 273) : 'не удалось получить данные'; ?> </p>
          <p>
            &#8595; <?= (!empty($data->main->temp_min)) ? round($data->main->temp_min - 273) : 'не удалось получить данные'; ?> </p>
        </div>
        <p
          class="current-temp"><?= (!empty($data->main->temp)) ? round($data->main->temp - 273) : 'не удалось получить данные'; ?>
          &#176;</p>
        <div class="parameters">
          <div class="row">
            <div class="param-name">Видимость</div>
            <div
              class="param-data"><?= (!empty($data->visibility)) ? @ round($data->visibility / 1000) : 'не удалось получить данные'; ?>
              км
            </div>
            <br>
          </div>
          <div class="row">
            <div class="param-name">Влажность</div>
            <div class="param-data"><?= (!empty($data->main->humidity)) ? $data->main->humidity : 'не удалось получить данные'; ?>%</div>
            <br>
          </div>
          <div class="row">
            <div class="param-name">Давление</div>
            <div class="param-data"><?= (!empty($data->main->pressure)) ? round($data->main->pressure * 100 * $mmrtst) : 'не удалось получить данные'; ?> мм</div>
          </div>
          <div class="row">
            <div class="param-name">Скорость ветра</div>
            <div class="param-data"><?= (!empty($data->wind->speed)) ? $data->wind->speed : 'не удалось получить данные'; ?> м/с</div>
            <br>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-md-offset-4">
      <form class="form-inline">
        <div class="form-group">
          <div class="input-group">
            <input class="form-control" type="text" formmethod="GET" name="city" placeholder="Город" size="50"
                   formenctype="text/plain">
            <div class="input-group-addon button">
              <button class="btn btn-primary" type="submit">Выбрать</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
