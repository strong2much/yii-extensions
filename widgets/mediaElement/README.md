MediaElementWidget
==============

Yii widget for working with [mediaelement.js](http://mediaelementjs.com/)

### Usage

Put the following code in your view file:

  ```php
  <?php 
  $this->widget('application.widgets.mediaElement.MediaElementWidget', array(
  	'enabled' => true,
    'src' => 'http://tr.kinopoisk.ru/252667/kinopoisk.ru-Man-Steel-131530.mp4',
	));
  ?>
  ```
  
### Available options

- **$enabled** *bool* Enable or disable widget
- **$mediaType** *string* Media type: video or audio
- **$mode** *string* Mode of player. Allows testing on HTML5, flash, silverlight
- **$src** *array|string* Url to source file (string) or files (array of strings)
- **$poster** *string* Url for poster. Only for video media type
- **$width** *integer* Width. Only for video media type
- **$height** *integer* Height. Only for video media type
- **$cssSkinFile** *string* Css for player skin
- **$htmlOptions** *array* additional HTML options to be rendered in the container tag
- **$options** *array* additional Javascript options to be put in widget. For more information [see](http://mediaelementjs.com)
  