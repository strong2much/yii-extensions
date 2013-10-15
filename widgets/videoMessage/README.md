VideoMessageWidget
==============

Yii widget for working with [video messages](https://github.com/strong2much/VideoMessage)

### Usage

Put the following code in your view file:

  ```php
  <?php 
  $this->widget('application.widgets.videoMessage.VideoMessageWidget', array(
  	'enabled' => true,
    'mediaServer' => 'rtmp://ec2-54-225-6-186.compute-1.amazonaws.com/vm',
	'userID' => 'admin',
    'messageID' => uniqid("vm")
	));
  ?>
  ```
  
### Available options

- **$enabled** *bool* Enable or disable widget
- **$width** *integer* Flash screen width
- **$height** *integer* Flash screen height
- **$swfFile** *string* SWF file URL
- **$quality** *integer* Quality property
- **$switchOffAutoHide** *bool* Whether needed to switch off auto show/hide behavior
- **$wmode** *string* Visibility flash movie mode. Use 'transparent' if you need to overlay a piece of html over a flash animation.
- **$recordTime** *integer* Record time limit for video message
- **$mediaServer** *string* Url for media server
- **$streamType** *string* Format of recording video: flv, mp4
- **$streamTemplate** *string* Template for stream name. Recommended to use following template {userID}/{messageID}. {userID} will be the folder, {messageID} will be the recorded video file name.
- **$userID** *string* UserID for using in stream template
- **$messageID** *string* MessageID for using in stream template
- **$debug** *bool* Switch to debug mode
- **$htmlOptions** *array* additional HTML options to be rendered in the container tag
  