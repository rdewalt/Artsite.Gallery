<?php
// A VERY basic bbcode parser. I don't want to go hog wild on bbcodes now do we?
function bb_parse($string) {
        $string=str_replace("\r\n","<br>",$string);
        $tags = 'b|i|u|size|color|center|quote|url';
        while (preg_match_all('`\[('.$tags.')=?(.*?)\](.+?)\[/\1\]`', $string, $matches)) foreach ($matches[0] as $key => $match) {
            list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]);
            switch ($tag) {
                case 'b': $replacement = "<strong>$innertext</strong>"; break;
                case 'u': $replacement = "<u>$innertext</u>"; break;
                case 'i': $replacement = "<em>$innertext</em>"; break;
                case 'size': $replacement = "<span style=\"font-size: $param;\">$innertext</span>"; break;
                case 'color': $replacement = "<span style=\"color: $param;\">$innertext</span>"; break;
                case 'center': $replacement = "<div class=\"centered\">$innertext</div>"; break;
                case 'quote': $replacement = "<blockquote>$innertext</blockquote>" . $param? "<cite>$param</cite>" : ''; break;
                case 'url': $replacement = '<a href="' . ($param? $param : $innertext) . "\">$innertext</a>"; break;
                /*
                case 'img':
                    list($width, $height) = preg_split('`[Xx]`', $param);
                    $replacement = "<img src=\"$innertext\" " . (is_numeric($width)? "width=\"$width\" " : '') . (is_numeric($height)? "height=\"$height\" " : '') . '/>';
                break;
                case 'video':
                    $videourl = parse_url($innertext);
                    parse_str($videourl['query'], $videoquery);
                    if (strpos($videourl['host'], 'youtube.com') !== FALSE) $replacement = '<embed src="http://www.youtube.com/v/' . $videoquery['v'] . '" type="application/x-shockwave-flash" width="425" height="344"></embed>';
                    if (strpos($videourl['host'], 'google.com') !== FALSE) $replacement = '<embed src="http://video.google.com/googleplayer.swf?docid=' . $videoquery['docid'] . '" width="400" height="326" type="application/x-shockwave-flash"></embed>';
                break;
*/
            }
            $string = str_replace($match, $replacement, $string);
        }
        return $string;
    }

?>