<?php

class NP_NCTagConv extends NucleusPlugin
{
    function getName()           {return 'NP_NCTagConv';}
    function getAuthor()         {return "yama";}
    function getURL()            {return 'http://japan.nucleuscms.org/wiki/plugins:nctagconv';}
    function getVersion()        {return '0.1.1';}
    function getDescription()    {return "tagconv";}
    function supportsFeature($w) {return (int)($w=='SqlTablePrefix');}
    function getEventList()      {return array('PrepareItemForEdit');}

    var $curItem;

    function event_PrepareItemForEdit($data) {
        $this->curItem =  &$data["item"];
        $pattern = '/<%image\((.*?)\|(.*?)\|(.*?)\|(.*?)\)%>/';
        $this->curItem['body'] = preg_replace_callback($pattern, array(&$this, '_replaceImageCode'), $this->curItem['body']);
        $this->curItem['more'] = preg_replace_callback($pattern, array(&$this, '_replaceImageCode'), $this->curItem['more']);
    }

    function _replaceImageCode ($matches) {
    global $CONF, $DIR_MEDIA;
        $filename = $matches[1];
        $width  = sprintf('width="%s"', $matches[2]);
        $height = sprintf('height="%s"', $matches[3]);
        $alttext  = ($matches[4]==='') ? sprintf('alt="%s"',$matches[4]):'';
        $authorid = $this->curItem['authorid'];
        $imagepath  = (strpos($filename,'/')!==false) ? $filename : "{$authorid}/{$filename}";
        $imagepath = $CONF['MediaURL'].$imagepath;
        
        return sprintf('<img src="%s" %s %s %s />', $imagepath, $width, $height. $alttext);
    }
}
