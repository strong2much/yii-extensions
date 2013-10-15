<?php
/**
 * File for MediaElementWidget
 *
 * @package  Ext.widgets
 * @author   Denis Tatarnikov <tatarnikovda@gmail.com>
 */

/**
 * MediaElementWidget is a widget for playing video using
 * html5, flash and silverlight technology. Support almost
 * all systems and browsers.
 *
 * @package  Ext.widgets
 * @author   Denis Tatarnikov <tatarnikovda@gmail.com>
 */
class MediaElementWidget extends CWidget
{
    const MEDIA_TYPE_VIDEO = 'video';
    const MEDIA_TYPE_AUDIO = 'audio';

    const MODE_AUTO = 'auto';               //attempts to detect what the browser can do
    const MODE_AUTO_PLUGIN = 'auto_plugin'; //prefer plugins and then attempt native HTML5
    const MODE_NATIVE = 'native';           //forces HTML5 playback
    const MODE_SHIM = 'shim';               //disallows HTML5, will attempt either Flash or Silverlight
    const MODE_NONE = 'none';               //forces fallback view

    /**
     * @var bool Enable or disable widget
     */
    public $enabled = true;

    /**
     * @var string Media type: video or audio.
     */
    public $mediaType = self::MEDIA_TYPE_VIDEO;

    /**
     * @var string Mode of player. Allows testing on HTML5, flash, silverlight
     */
    public $mode = self::MODE_AUTO_PLUGIN;

    /**
     * @var array|string Url to source file (string) or files (array of strings).
     */
    public $src;

    /**
     * @var string Url for poster. Only for video media type
     */
    public $poster = '';

    /**
     * @var integer Width. Only for video media type
     */
    public $width = 320;

    /**
     * @var integer Height. Only for video media type
     */
    public $height = 240;

    /**
     * @var string Css for player skin.
     */
    public $cssSkinFile = 'mejs-skins.css';

    /**
     * @var array additional HTML options to be rendered in the container tag.
     */
    public $htmlOptions = array();

    /**
     * @var array additional Javascript options to be put in widget.
     * For more information (@see http://mediaelementjs.com)
     */
    public $options = array();

    /**
     * @see CWidget::init()
     */
    public function init()
    {
        if($this->enabled) {
            parent::init();
            $assetPath = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, 1, YII_DEBUG);

            $cs=Yii::app()->getClientScript();
            //$cs->registerCoreScript('jquery');
            $cs->registerScriptFile($assetPath . '/mediaelement-and-player'.(YII_DEBUG ? '' : '.min').'.js');
            $cs->registerCssFile($assetPath . '/mediaelementplayer'.(YII_DEBUG ? '' : '.min').'.css');
            if (!empty($this->cssSkinFile)) {
                $cs->registerCssFile($assetPath . $this->cssSkinFile);
            }
        }
    }

    /**
     * @see CWidget::run()
     */
    public function run()
    {
        if (!$this->enabled) {
            return;
        }

        if($this->src===null) {
            throw new CException('Url to source file(s) is required.');
        }

        if(!in_array($this->mediaType, $this->getMediaTypes())) {
            $this->mediaType = self::MEDIA_TYPE_VIDEO;
        }
        if(!in_array($this->mode, $this->getModes())) {
            $this->mode = self::MODE_AUTO;
        }

        $htmlOptions=$this->htmlOptions;
        if(!isset($htmlOptions['id']))
            $htmlOptions['id']=$this->getId();

        $id = $htmlOptions['id'];

        echo CHtml::openTag('div', $htmlOptions);
        $this->_renderBody();
        echo CHtml::closeTag('div');

        $this->options['mode'] = $this->mode;
        $jsOptions = CJavaScript::encode($this->options);

        //Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $id, "$('#{$id} {$this->mediaType}').mediaelementplayer($jsOptions);", CClientScript::POS_READY);
        Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $id, "var mePlayer = new MediaElementPlayer('#{$id} {$this->mediaType}', $jsOptions);", CClientScript::POS_READY);
    }

    /**
     * Return array of available media types
     * @return array media types
     */
    public function getMediaTypes()
    {
        return array(
            self::MEDIA_TYPE_VIDEO,
            self::MEDIA_TYPE_AUDIO
        );
    }

    /**
     * Return array of available modes
     * @return array media types
     */
    public function getModes()
    {
        return array(
            self::MODE_AUTO,
            self::MODE_AUTO_PLUGIN,
            self::MODE_NATIVE,
            self::MODE_SHIM,
            self::MODE_NONE
        );
    }

    /**
     * Render body of the widget
     */
    private function _renderBody()
    {
        $id = 'meJS';
        if(is_string($this->src)) {
            $opt = array();
            if($this->mediaType == self::MEDIA_TYPE_AUDIO) {
                $opt = array(
                    'id' => $id,
                    'src' => $this->src
                );
            } elseif($this->mediaType == self::MEDIA_TYPE_VIDEO) {
                $opt = array(
                    'id' => $id,
                    'src' => $this->src,
                    'poster' => $this->poster,
                    'width' => $this->width,
                    'height' => $this->height
                );
            }
            echo CHtml::tag($this->mediaType, $opt);
        } elseif(is_array($this->src)) {
            $opt = array(
                'id' => $id,
            );
            if($this->mediaType == self::MEDIA_TYPE_VIDEO) {
                $opt = array(
                    'id' => $id,
                    'poster' => $this->poster,
                    'width' => $this->width,
                    'height' => $this->height
                );
            }

            echo CHtml::openTag($this->mediaType, $opt);
            foreach($this->src as $src) {
                echo Chtml::tag('source', array(
                    'src' => $src,
                    'type' => $this->_mimeContentType($src)
                ));
            }
            echo CHtml::closeTag($this->mediaType);
        }
    }

    /**
     * Returns mime type of the given file
     * @param $filename file name to check mime type
     * @return string mime type
     */
    private function _mimeContentType($filename)
    {
        $mime_types = array(
            'mp3' => 'audio/mp3',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'ogv' => 'video/ogg',
            'flv' => 'video/flv',
            'mp4' => 'video/mp4',
        );

        $extArray = explode('.',$filename);
        $ext = strtolower(array_pop($extArray));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}