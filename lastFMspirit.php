<?php


/*
    Plugin Name: lastFMspirit
    Plugin URI: http://acegiak.net
    Description: Gives you access to the last.fm api through shortcodes. Uses 
    Version: 1.1.1
    Author: Ashton McAllan
    Author URI: http://acegiak.net
    License: GPLv2
*/

/*  Copyright 2011 Ashton McAllan (email : acegiak@machinespirit.net)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/
    include( plugin_dir_path(__FILE__) . 'LastFm.php');

$LastFmSpirit = new LastFm(get_option("LastFmKey"),get_option("LastFmSecret"));
function last_fm_code( $atts ,$content=null) {
    global $LastFmSpirit;
    $a = shortcode_atts( array(
        'user' => null,
        'period' => null,
	'artist'=>null,
	'album'=>null,
	'autocorrect'=>null,
	'page'=>null,
	'limit'=>null,
	'country'=>null,
	'lang'=>null,
	'username'=>null,
	'event'=>null,
	'long'=>null,
	'lat'=>null,
	'location'=>null,
	'distance'=>null,
	'tag'=>null,
	'festivalsonly'=>null,
	'metro'=>null,
	'Group'=>null,
	'from'=>null,
	'to'=>null,
	'track'=>null

    ), $atts );
	if ( "" == get_option( "LastFmKey", "" ) || "" == get_option( "LastFmSecret", "" ) ) {
		return "*LASTFMSPIRIT ERROR: YOU MUST ENTER YOU API KEY AND SECRET ON THE OPTIONS PAGE*";
	}
    $table = $LastFmSpirit->doCall($content,$a);
    recursive_unset($table,"`duration|mbid|streamable|\@attr|#text|image`");
    return arrayTable($table,"lastFM".$content,25);
}

function recursive_unset(&$array, $unwanted_key) {
    foreach($array as $k=>$v){
        if(preg_match($unwanted_key,$k)){
		unset($array[$k]);
	}
    }
    foreach ($array as &$value) {
        if (is_array($value)) {
            recursive_unset($value, $unwanted_key);
        }
    }
}
function is_assoc($array) {
  return (bool)count(array_filter(array_keys($array), 'is_string'));
}
function arrayTable($arr,$tableClass="",$cursor=5){
	$tableClass = preg_replace("`[^A-Za-z0-9]+`","",$tableClass);
	if($cursor <= 0){
		return "too deep";
	}
	if(!is_array($arr)){
		return $arr;
	}
	$out = "";
	if(is_assoc($arr)){
		$row1 = "<tr>";
		$row2 = "<tr>";
		$rowcount = 0;
		foreach($arr as $key=>$val){
			if($key != "url"){
				$rowcount++;
				$row1 .= "<th>".$key."</th>";
				if(is_string($val) && isset($arr['url'])){
					$row2 .= "<td><a href=\"".$arr['url']."\">".$val."</a></td>";
				}else{
					$row2 .= "<td>".arrayTable($val,$tableClass.$key,$cursor-1)."</td>";
				}
			}
		}
		if($rowcount == 1){
			foreach($arr as $key=>$val){
                        	if($key != "url"){
				 if(is_string($val) && isset($arr['url'])){
                                        $out .= "<a href=\"".$arr['url']."\">".$val."</a>";
                                }else{
                                        $out .= arrayTable($val,$tableClass.$key,$cursor-1);
                                }}
			}
		}else{
			$out .= "<table class=\"".$tableClass."\">".$row1.$row2."</table>";
		}
	}else{
		$header = true;
		$out .= "<table class=\"".$tableClass."\">";
		foreach($arr as $key=>$row){
			if(is_array($row)){
				if($header && is_assoc($row)){
					$out .="<tr>";
					foreach($row as $key2=>$row2){
						if($key2 != "url"){
							$out .="<th>".$key2."</th>";
						}
					}
					$out .= "</tr>";
					$header = false;
				}
				$out .= "<tr>";
				foreach($row as $key2=>$row2){
					if(is_string($row2) && preg_match("`^https?\:.*?\.(png|jpg|gif|bmp|jpeg)`i",$row2)){
						$row2 = "<img src=\"".$row2."\">";
					}
					if($key2 != "url"){
						if(isset($row['url']) && !is_array($row2)){
							$out .= "<td><a href=\"".$row['url']."\">".$row2."</a></td>";
						}else{
							$out .= "<td>".arrayTable($row2,$tableClass.$key2,$cursor-1)."</td>";
						}
					}
				}
				$out .= "</tr>";
			}else{
				$out .= "<tr><td>".arrayTable($row,$tableclass.$key,$cursor-1)."</tr></td>";
			}
		}
		$out .= "</table>";
	}
	return $out."<!--".print_r($arr,true)."-->";
}

add_shortcode( 'LastFmChart', 'last_fm_code' );


function last_fm_spirit_options()
{
?>
    <div class="wrap">
        <h2>Last.Fm Spirit Options</h2>
	
        <form method="post" action="options.php">
            <?php wp_nonce_field('update-options') ?>
            <p><strong>Last.Fm Api Key:</strong><br />
                <input type="text" name="LastFmKey" value="<? echo get_option('LastFmKey'); ?>"/><br>
            </p>
            <p><strong>Last.Fm Api Secret:</strong><br />
                <input type="text" name="LastFmSecret" value="<? echo get_option('LastFmSecret'); ?>"/><br>
            </p>

            <p><input type="submit" name="Submit" value="Store Options" /></p>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="LastFmKey,LastFmSecret" />
        </form>
		<h2>Readme:</h2>
		<p><?php echo preg_replace('`==+(.*?)==+`','<strong>$1</strong>',preg_replace('`\n`','<br/>',file_get_contents(plugin_dir_path(__FILE__) . 'README.txt'))); ?></p>
    </div>
<?php
}

function add_last_fm_spirit_options_to_menu(){
	add_options_page( 'LastFmSpirit', 'LastFmSpirit', 'manage_options', 'LastFmSpirit', 'last_fm_spirit_options');
}

add_action('admin_menu', 'add_last_fm_spirit_options_to_menu');



?>

