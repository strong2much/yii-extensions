/**
 * @license jQuery video message plugin v0.1 23/06/2013
 * @author Denis Tatarnikov <tatarnikov@softlogicgroup.com>
 **/

(function ($) {

    var VM = {
        container: null,
        selector: null,
        options: {}
    };
    window.VM = VM;

    $.fn.vm = function(options) {

        VM.container = $(this);
        options = $.extend({}, $.fn.vm.defaults, options);
        VM.options = options;

        if ($('#' + options.containerId).length == 0) {
            var source = '<div id="' + options.containerId + '"></div>';
            VM.container.html(source);
        }

        var flashVars = {'mode': options.mode, 'quality': options.quality};
        var attributes = {'type': "application/x-shockwave-flash", data: options.swfFile};
        var params = {
            'AllowScriptAccess': 'always',
            'wmode': options.wmode,
            'movie': options.swfFile
        };

        if (options.switchOffAutoHide) {
            swfobject.switchOffAutoHideShow();
        }

        swfobject.embedSWF(options.swfFile, options.containerId,
            options.width, options.height, options.minPlayerVersion,
            options.swfObject.expressInstallUrl,
            flashVars, params, attributes, function(){
                VM.selector = document.getElementById(options.containerId);
            }
        );
    }

    $.fn.vm.defaults = {
        width: 320,
        height: 240,
        swfFile: "VM.swf",
        swfObject: {}, //swfobject.js library options
        minPlayerVersion: '11.1.0', //minimal flash player version
        quality: 85,
        wmode: null,
        containerId: 'vmObject',
        switchOffAutoHide: false
    };

    VM.setRemoteServer = function(url) {
        try {
            return VM.selector.setRemoteServer(url);
        } catch(e) {
            console.log(e.message);
        }
    }

    VM.setStream = function(name) {
        try {
            return VM.selector.setStream(name);
        } catch(e) {
            console.log(e.message);
        }
    }

    VM.setStreamType = function(type) {
        try {
            return VM.selector.setStreamType(type);
        } catch(e) {
            console.log(e.message);
        }
    }

    VM.setRecordTime = function(time) {
        try {
            return VM.selector.setRecordTime(time);
        } catch(e) {
            console.log(e.message);
        }
    }

    VM.setDebug = function(value) {
        try {
            return VM.selector.setDebug(value);
        } catch(e) {
            console.log(e.message);
        }
    }

    VM.getStreamUrl = function() {
        try {
            return VM.selector.getStreamUrl();
        } catch(e) {
            console.log(e.message);
        }
    }

    VM.isRecordAvailable = function() {
        try {
            return VM.selector.isRecordAvailable();
        } catch(e) {
            console.log(e.message);
        }
    }

    VM.startRecord = function() {
        try {
            VM.selector.startRecord();
        } catch(e) {
            console.log(e.message);
        }
    }

    VM.stopRecord = function() {
        try {
            VM.selector.stopRecord();
        } catch(e) {
            console.log(e.message);
        }
    }

    VM.startPlay = function() {
        try {
            VM.selector.startPlay();
        } catch(e) {
            console.log(e.message);
        }
    }

    VM.stopPlay = function() {
        try {
            VM.selector.stopPlay();
        } catch(e) {
            console.log(e.message);
        }
    }

    VM.close = function() {
        try {
            VM.selector.close();
        } catch(e) {
            console.log(e.message);
        }
    }

    VM.onInit = function() {
        if(VM.selector.setRemoteServer !== undefined) {
            if(!VM.isNullorEmpty(VM.options.remoteServer)) {
                VM.setRemoteServer(VM.options.remoteServer);
            }
            if(!VM.isNullorEmpty(VM.options.stream)) {
                VM.setStream(VM.options.stream);
            }
            if(!VM.isNullorEmpty(VM.options.streamType)) {
                VM.setStreamType(VM.options.streamType);
            }
            if(!VM.isNullorEmpty(VM.options.recordTime)) {
                VM.setRecordTime(VM.options.recordTime);
            }
            if(!VM.isNullorEmpty(VM.options.debug)) {
                VM.setDebug(VM.options.debug);
            }
        }
    }

    VM.onClose = function() {
        VM.container.html("");
    }

    VM.isNullorEmpty = function(param) {
        return param==null || param==undefined || param=="";
    }

})(jQuery);
