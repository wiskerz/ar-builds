<?php
require "lancers.php";

echo <<<eof
<!doctype html>
<html lang="en">
	<head>
		<title>Mod Builder</title>
		<link href="http://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Questrial" rel="stylesheet" type="text/css">
		<link href="http://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="./default.css?3" />
		<script src="./jquery.min.js"></script>
		<script>
			var tokens = [0,0,0,0,0];
			var state  = {
				lancer : "",
				mods   : ["-", "-", "-", "-", "-"]
			};

			var max = 10;
			function initState() {
				state  = {
					lancer : "",
					mods   : ["-", "-", "-", "-"]
				};
				$('#share-link').attr('href', '#');
			}
			function updateState() {
				var linkstate =  state.lancer + state.mods.reduce(function(x,y){return x + y}, ":");
				$('#share-link').attr('href', '#' + linkstate);
				$(location).attr('hash', linkstate)
			}
			function initTokens() {
				tokens = [0,0,0,0,0];
				$('#tokens-cur').text(0);
				state.mods =  ["-", "-", "-", "-", "-"];
			}
			function assignTokens(i, num) {
				if(i < 0 || i >= tokens.length) return false;
					i = parseInt(i);
				num = parseInt(num);
				var sum = tokens.reduce(function(x, y) { return x + y; }, 0);
				var diff= num - tokens[i];
				if(sum + diff <= max) {
					tokens[i] = num;
					$('#tokens-cur').text(sum + diff);
					return true;
				}
				return false;
			}
			function showLancer(lancer) {
					$('#footer').css('position', 'relative');
					$('#lancer-role').removeClass();
					$('#lancer-role').addClass(lancer.info.Role);
					$('#lancer-name').text(lancer.info.name)
					$('#lancer-wiki').attr('href', lancer.info.wiki);
					$('#ability-images').html("");
					$('#ability-names').html("");
					$('#ability-desc').html("");
					$('#ability-mods').html("");
					$('#ability-en').html("");
					var id = lancer.info.id;
					initState();
					state.lancer = id;
					initTokens();
					updateState();

					$('#ability-images').html();
					for(i in lancer.abilities) {
						var cd = lancer.abilities[i].Cooldown;
						if(parseInt(i) == 4) cd = "";
						else if(typeof(cd) == "undefined") cd = "";
						else if(cd == "None") cd ="x0";
						else if(cd == "") cd = "x0";
						else cd = "x"+cd;
						var img = $('<td><img class="phase phase-' + lancer.abilities[i].Phase + ' abilityicon" src="./content/Abilities/' + id + '/' + (parseInt(i) + 1) +'.png"/>' + cd + '</td>');
						$('#ability-images').append(img);
						$('#ability-names').append(('<td>' + lancer.abilities[i].Name + '</td>'));
						$('#ability-desc').append(('<td>' + lancer.abilities[i].Description + '</td>'));
						if(parseInt(i) == 4) {
							$('#ability-en').append(('<td>Ultimate Ability</td>'));
						}
						else {
							$('#ability-en').append(('<td>' + lancer.abilities[i]["Energy Gained"] + '</td>'));
						}
						var mods = $('<td class="mod-cell" id="mods-for-'+ i +'"></td>');
						for(j in lancer.abilities[i].mods) {
							(function(mi, mj, mod, parent) {
								var div = $('<div id="mod-'+ mi +'-'+ mj +'" class="mod"></div>');
								div.append('<div class="mod-cost">' + mod.Cost + '</div>');
								div.append('<div class="mod-name">' + mod.Name + '</div>');
								div.append('<div class="mod-description">' + mod.Description + '</div>');
								div.click(function(){
									if($(this).hasClass('selected')) {
										assignTokens(mi, 0);
										state.mods[mi] = "-";
										updateState();
										$(this).removeClass('selected');
									}
									else {
										if(assignTokens(mi, mod.Cost)) {
											parent.find(".selected").removeClass('selected');
											state.mods[mi] = mj;
											updateState();
											$(this).addClass("selected");
										}
									}

								});
								mods.append(div);
							})(i, j, lancer.abilities[i].mods[j], mods);
						}
						$('#ability-mods').append(mods);
					}

			}
			$(document).ready(function(){
					for(i in lancers) {
						var lancer = $('<div class="lancer"></div>');
						var url = './content/FL/FLpick_' + i + '.png';
						lancer.css('background-image', 'url(' + url + ')');
						(function(j) {
							lancer.click(function(){
								showLancer(lancers[j]);
							});
						})(i);

						$('#pick-lancer').append(lancer);
					}
					var recover = $(location).attr('hash');
					if(typeof(recover) == "string") {
						var re = new RegExp('#[a-z0-9]+:[0-9\-]{5}');
						var matches = recover.match(re);
						if(matches != null) {
								var split = matches[0].split(":");
								var lancer = split[0].substring(1);
								var mods = split[1];
								showLancer(lancers[lancer]);
								for(var i = 0; i < mods.length; i++) {
									$('#mod-'+ i +'-'+ mods.charAt(i)).click();
								}
						}
					}
			});
		</script>
	</head>
	<body>
	<div id="header">
		<img id="logo" src="./content/main.svg"/>
		Atlas Reactor : Builds
	</div>
	<div id="pick-lancer"></div>
	<div id="build">
			<div id="tokens-line">
				<span id="tokens-cur">0</span> / 10 - <a id="share-link" href="#" target="_blank">Share Link</a>
			</div>
			<div id="info">
				<div id="lancer-role"></div>
				<a href="" id="lancer-wiki" target="_blank"><div id="lancer-name"></div></a>
			</div>
			<table id="abilities">
				<tr><td style="width:20%"></td><td style="width:20%"></td><td style="width:20%"></td><td style="width:20%"></td><td style="width:20%"></td></tr>
				<tr id="ability-images"> </tr>
				<tr id="ability-names"> </tr>
				<tr id="ability-en"  ></tr>
				<tr id="ability-desc"></tr>
				<tr id="ability-mods"></tr>
			</table>
	</div>
eof;



echo "<script>\n";
echo "var lancers = {};\n";
foreach($lancers as $key => $lancer) {
	echo "lancers['$key'] = " . trim(file_get_contents("lancers/$key.js")) . ";\n";
	echo "lancers['$key']['info']['name'] = '" . str_replace("_", " ", $lancer) . "';\n";
	echo "lancers['$key']['info']['wiki'] = 'http://atlas-reactor.wikia.com/wiki/" . $lancer . "';\n";
	echo "lancers['$key']['info']['id'] = '" . $key. "';\n";
}
echo "\n</script>";

echo <<<EOF
	<div id="footer">
		Information grabbed from the <a href="http://atlas-reactor.wikia.com/wiki/Atlas_Reactor_Wiki" target="_blank">Atlas Reactor Wiki</a> - This site is &copy; Wiskerz 2017 - Licensed under <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/2.0/">Creative Commons Attribution-NonCommercial-ShareAlike 2.0 Generic License</a>
		<br/>
		 Trion, Trion Worlds, Atlas Reactor, Glyph, and their respective logos, are trademarks or registered trademarks of Trion Worlds, Inc. in the U.S. and other countries.
	</div>
	</body>
</html>
EOF;
