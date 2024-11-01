<?php
/*
Plugin Name: Smartphone News
Plugin URI: http://wordpress.org/extend/plugins/smartphone-news/
Description: Adds a customizeable widget which displays the latest smartphone news by http://www.simsalaring.eu/
Version: 1.0
Author: Markus Knopp
Author URI: http://www.simsalaring.eu/
License: GPL3
*/

function smartphonenews()
{
  $options = get_option("widget_smartphonenews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Smartphone News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Objekt erzeugen 
  $rss = simplexml_load_file( 
  'http://www.simsalaring.eu/blog/feed/'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximale Anzahl an News, wobei 0 (Null) alle anzeigt
  $max_news = $options['news'];
  // maximale Länge, auf die ein Titel, falls notwendig, gekürzt wird
  $max_length = $options['chars'];
  
  // RSS Elemente durchlaufen 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Titel in Zwischenvariable speichern
    $title = $i->title;
    // Länge des Titels ermitteln
    $length = strlen($title);
    // wenn der Titel länger als die vorher definierte Maximallänge ist,
    // wird er gekürzt und mit "..." bereichert, sonst wird er normal ausgegeben
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li>
    <li><a href="www.simsalaring.eu/blog/" target="_blank">Alle Nachrichten anzeigen</a></li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_smartphonenews($args)
{
  extract($args);
  
  $options = get_option("widget_smartphonenews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Smartphone News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  smartphonenews();
  echo $after_widget;
}

function smartphonenews_control()
{
  $options = get_option("widget_smartphonenews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Smartphone News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['smartphonenews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['smartphonenews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['smartphonenews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['smartphonenews-CharCount']);
    update_option("widget_smartphonenews", $options);
  }
?> 
  <p>
    <label for="smartphonenews-WidgetTitle">Widget Title: </label>
    <input type="text" id="smartphonenews-WidgetTitle" name="smartphonenews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="smartphonenews-NewsCount">Max. News: </label>
    <input type="text" id="smartphonenews-NewsCount" name="smartphonenews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="smartphonenews-CharCount">Max. Characters: </label>
    <input type="text" id="smartphonenews-CharCount" name="smartphonenews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="smartphonenews-Submit"  name="smartphonenews-Submit" value="1" />
  </p>
  
<?php
}

function smartphonenews_init()
{
  register_sidebar_widget(__('Smartphone News'), 'widget_smartphonenews');    
  register_widget_control('Smartphone News', 'smartphonenews_control', 300, 200);
}
add_action("plugins_loaded", "smartphonenews_init");
?>