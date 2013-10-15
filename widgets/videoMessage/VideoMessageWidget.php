<?php
/**
 * File for VideoMessageWidget
 *
 * @package  Ext.widgets
 * @author   Denis Tatarnikov <tatarnikovda@gmail.com>
 */
/**
 * VideoMessageWidget is a widget for recording video messages using
 * Adobe Flash technology and Adobe Flash Media Server.
 *
 * @package  Ext.widgets
 * @author   Denis Tatarnikov <tatarnikovda@gmail.com>
 */
class VideoMessageWidget extends CWidget
{
    /**
     * Enable or disable widget
     * @var integer
     */
    public $enabled = true;

    /**
     * Flash screen width
     * @var integer
     */
    public $width = 320;

    /**
     * Flash screen height
     * @var integer
     */
    public $height = 240;

    /**
     * SWF file URL
     * @var string
     */
    public $swfFile;
    
    /**
     * Quality property
     * @var integer
     */
    public $quality = 75;
    
    /**
     * Whether needed to switch off auto show/hide behavior
     * @var bool
     */
    public $switchOffAutoHide = false;

    /**
     * Visibility flash movie mode. Use 'transparent'
     * if you need to overlay a piece of html over
     * a flash animation.
     * @var string
     */
    public $wmode;

    /**
     * Record time limit for video message
     * @var integer
     */
    public $recordTime = 30;

    /**
     * Url for media server
     * @var string
     */
    public $mediaServer = "rtmp://localhost/vm";

    /**
     * Format of recording video
     * @var string
     */
    public $streamType = "flv";

    /**
     * Template for stream name. Recommended to use following template
     * {userID}/{messageID}. {userID} will be the folder, {messageID}
     * will be the recorded video file name.
     * @var string
     */
    public $streamTemplate = "{userID}/{messageID}";

    /**
     * UserID for using in stream template
     * @var string
     */
    public $userID = "admin";

    /**
     * MessageID for using in stream template
     * @var string
     */
    public $messageID = "message";

    /**
     * Switch to debug mode
     * @var bool
     */
    public $debug = false;

    /**
     * @var array additional HTML options to be rendered in the container tag.
     */
    public $htmlOptions = array();

    /**
     * @see CWidget::init()
     */
    public function init()
    {
        if($this->enabled) {
            parent::init();
            $assetPath = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, 1, YII_DEBUG);
            Yii::app()->getClientScript()->registerScriptFile($assetPath . '/jquery.vm.js');
            if (!isset($this->swfFile)) {
                $this->swfFile = $assetPath . '/vm.swf';
            }
        }
    }
    
    /**
     * Returns client options map
     * 
     * @return array client options
     */
    public function getClientOptions()
    {
        $streamName = str_replace('{userID}', $this->userID, $this->streamTemplate);
        $streamName = str_replace('{messageID}', $this->messageID, $streamName);

        $swf = JavaScript::registerSWFObject();
        $ret = array(
            'width' => $this->width,
            'height' => $this->height,
            'swfFile' => $this->swfFile,
            'quality' => $this->quality,
            'wmode' => $this->wmode,
            'swfObject' => $swf,
            'switchOffAutoHide' => $this->switchOffAutoHide,
            'remoteServer' => $this->mediaServer,
            'recordTime' => $this->recordTime,
            'stream' => $streamName,
            'streamType' => $this->streamType,
            'debug' => $this->debug
        );

        return $ret;
    }
    
    /**
     * @see CWidget::run()
     */
    public function run()
    {
        if (!$this->enabled) {
            return;
        }

        $htmlOptions=$this->htmlOptions;
        if(!isset($htmlOptions['id']))
            $htmlOptions['id']=$this->getId();

        $id = $htmlOptions['id'];

        echo CHtml::openTag('div', $htmlOptions);
        JavaScript::renderFlashPlayerInstallButton('vmObject');
        echo CHtml::closeTag('div');
        
        $options = CJavaScript::encode($this->getClientOptions());
        Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $id, "$('#{$id}').vm($options);", CClientScript::POS_READY);
    }
}