<?php

$bpixels = 100;

$alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

$words = array(
  "HAPPY",
  "MOMMA",
  "DADA",
  "KHALEN",
  "TREE",
  "DOG",
  "BUFFY",
  "DUKE",
  "JUSTINE",
  "CAT",
  "BRIDGER",
  "CHRISTMAS",
  "H E B",
  "ELEPHANT",
  "POPCORN",
  "COW",
  "DOOR BELL",
  "DING DONG",
  "BELL",
  "BALL",
);

$pick = array_rand($words);
$word = $words[$pick];

$alphaX = array (
'A' => 10,
'B' => 107,
'C' => 195,
'D' => 279,
'E' => 373,
'F' => 451,
'G' => 523,
'H' => 620,
'I' => 719,
'J' => 760,
'K' => 833,
'L' => 922,
'M' => 993,
'N' => 1117,
'O' => 1210,
'P' => 1309,
'Q' => 1392,
'R' => 1491,
'S' => 1576,
'T' => 1652,
'U' => 1737,
'V' => 1824,
'W' => 1914,
'X' => 2038,
'Y' => 2116,
'Z' => 2200,
);
$alphaW = array (
'A' => 74,
'B' => 68,
'C' => 62,
'D' => 70,
'E' => 53,
'F' => 51,
'G' => 70,
'H' => 69,
'I' => 23,
'J' => 44,
'K' => 69,
'L' => 52,
'M' => 98,
'N' => 69,
'O' => 75,
'P' => 65,
'Q' => 79,
'R' => 68,
'S' => 61,
'T' => 64,
'U' => 67,
'V' => 78,
'W' => 112,
'X' => 71,
'Y' => 74,
'Z' => 62,
);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="mobile-web-app-capable" content="yes" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
  <link rel="shortcut icon" href="images/apple_icon.png" />
  <link rel="apple-touch-icon" href="images/apple_icon.png" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
	<title>Lettery Words</title>

	<link href="css/app.css" rel="stylesheet" />

	<style>
	html, body {
		margin: 0px;
		padding: 0px;
	}

	#main {
		background: #DBD8B8;
		position: relative;
		top: 0px;
		bottom: 0px;
		left: 0px;
		right: 0px;
		width: 100%;
		height: 100%;
	}

	.letterDrag {
		position: absolute;
		top: 0;
		left: 0;
	}

	.letterDrop {
		display: inline-block;
		width: <?=$bpixels?>px;
		height: <?=$bpixels?>px;
    margin-right: 20px;
	}

	.trayContainer {
		background: #E0DFB6;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	#trayCLeft {
		position: absolute;
		top: 20px;
		left: 20px;
		width: <?=$bpixels?>px;
	}

	#trayCRight {
		position: absolute;
		top: 20px;
		right: 20px;
		width: <?=$bpixels?>px;
	}

	.tray {
		background: #eee;
		width: <?=$bpixels?>px;
		height: <?=$bpixels?>px;
		margin-bottom: 20px;
	}

	#alphaBoard {
		position: absolute;
		top: 0px;
		bottom: 0px;
		left: 0px;
		right: 0px;
    width: 100%;
    display: -webkit-flex;
    display: flex;
    -webkit-justify-content: center;
    justify-content: center;
    -webkit-align-items: center;
		align-items: center;
		text-align: center;
	}

	#alphaPieces {
		margin: auto 0px;
	}

  #refresh {
    width: 40px;
    height: 40px;
    background: url(images/refresh.png);
    z-index: 10000;
    position: absolute;
    top: 0;
    left: 0;
  }

  <?
  foreach (str_split($alphabet) as $alpha) {
    ?>
    .drags .letter<?=$alpha?> {
      background-image: url(images/alpha-teal.png);
      background-repeat: no-repeat;
      background-position: -<?=$alphaX[$alpha]?>px 0px;
      width: <?=$alphaW[$alpha]?>px;
      height: 150px;
    }

    #alphaBoard .letter<?=$alpha?> {
      background-image: url(images/alphab.png);
      background-repeat: no-repeat;
      background-position: -<?=$alphaX[$alpha]?>px 0px;
      width: <?=$alphaW[$alpha]?>px;
      height: 150px;
    }
    <?
  }
  ?>
	</style>
  </head>
  <body>
  <div id="main">
  	<div id="alphaBoard">
    	<div id="alphaPieces">
        <div id="alphaRow">
          <?
          $c = 1;
          foreach (str_split($word) as $letter) {
            if ($letter == " ") { continue; }
            ?><div class="drop letterDrop letter<?=$letter?>" data-letter="<?=$letter?>" id="drop<?=$c?>"></div><?
            $c++;
          }
          ?>
        </div>
    	</div>
  	</div>

    <div class="drags">
      <?
      $c = 1;
      foreach (str_split($word) as $letter) {
        if ($letter == " ") { continue; }
        ?><div class="drag letterDrag letter<?=$letter?>" data-letter="<?=$letter?>" data-hue="<?=intval(rand(0, 359))?>" id="drag<?=$c?>"></div><?
        $c++;
      }
      ?>
    </div>

    <div id="players"></div>

  </div>

  <div id="refresh"></div>
	<script src="js/zepto.min.js"></script>
	<script src="js/interact.min.js"></script>

	<script>
		var x, y, players = {};
    var word = "<?=strtolower($word)?>";
    var define = "";

    function getRandomInt(min, max) {
      return Math.floor(Math.random() * (max - min + 1) + min);
    }

		function figureHeights() {
			$("#main").css({height: $(window).height() + "px"});
		}

		function isAccepted(event) {
			var drag = event.relatedTarget;
			var drop = event.target;

      /*
      console.log($(drag).attr("id"));
			console.log($(drop).attr("id"));
			console.log("drag: " + $(drop).data("drag"));
      */

      if ($(drag).data("letter") != $(drop).data("letter")) {
        return false;
      } else if ($(drop).data("drag") == $(drag).attr("id")) {
        //console.log("drag == id: TRUE");
        return true;
      } else if ($(drop).data("drag")) {
        //console.log("has a drag: FALSE");
        return false;
      } else {
        //console.log("TRUE");
        return true;
      }
      //console.log("---");
    }

    function checkWordComplete() {
      var complete = true;

      $(".drop").each(function() {
        if (!$(this).data("drag")) {
          complete = false;
        }
      });

      if (complete) {
        setTimeout(function () {
          var playerName = "complete";
          players[playerName] = new Audio();
          $(players[playerName]).on("ended", function() {
            delete players[playerName];
          });
          players[playerName].src = "say.php?say=" + encodeURIComponent(word + ". " + define);
          players[playerName].play();
        }, 3000);
      }
    }

		$(document).ready(function() {
			figureHeights();

			interact(".drag").draggable({
				max: Infinity,
				onstart: function (event) {
					var target = event.target;

          if ($(target).data("locked")) {
            return;
          }

					var pos = $(target).offset();
					$(target)
						.data("startX", pos['left'])
						.data("startY", pos['top']);

          $(target).animate({
            "-webkit-filter": "hue-rotate(100deg)",
            "filter": "hue-rotate(100deg)",
            "-webkit-transform": "scale(1.1)",
            "transform": "scale(1.1)",
            "z-index": 5000,
          });

          var playerName = $(target).attr("id");
          players[playerName] = new Audio();
          players[playerName].src = "sounds/" + $(target).data("letter") + ".mp3";
          players[playerName].loop = true;
          players[playerName].play();
				},

				onmove: function (event) {
					var target = event.target;

          if ($(target).data("locked")) {
            return;
          }

					x = (parseFloat($(target).data("x")) || 0) + event.dx;
					y = (parseFloat($(target).data("y")) || 0) + event.dy;

					$(target).css({top: y + "px", left: x + "px"});

					$(target)
            .data("x", x)
            .data("y", y);
				},

				onend: function (event) {
					var target = event.target;

          if ($(target).data("locked")) {
            return;
          }

          var playerName = $(target).attr("id");
          players[playerName].pause();
          players[playerName].loop = false;
          players[playerName].autoplay = false;
          delete players[playerName];

          /*
          // Rejected drag goes back to start.
					if (!$(target).data("drop")) {
						x = $(target).data("startX");
						y = $(target).data("startY");
						$(target)
							.data("x", x)
							.data("y", y)
							.animate({top: y, left: x});
					}
          */

          var hue = $(target).data("hue");
          $(target).animate({
            "-webkit-filter": "hue-rotate(" + hue + "deg)",
            "filter": "hue-rotate(" + hue + "deg)",
            "-webkit-transform": "scale(1.0)",
            "transform": "scale(1.0)",
            "z-index": 1000,
          });

          // If dropped on target, lock it.
          if ($(target).data("drop")) {
            $(target).data("locked", 1);
            /*$(target).animate({
              "-webkit-filter": "hue-rotate(46deg)",
              "filter": "hue-rotate(46deg)"
            });*/

            var playerName = "full" + $(target).attr("id");
            players[playerName] = new Audio();
            $(players[playerName]).on("ended", function() {
              delete players[playerName];
            });
            players[playerName].src = "sounds/f" + $(target).data("letter") + ".mp3";
            players[playerName].play();

            checkWordComplete();

            //$(target).animate({"background-image": "url(images/alpha-blue.png)"});
          }
				}

			})
			.inertia(true)
			.restrict({
				drag: "#main",
				endOnly: true,
				elementRect: { top: 0, left: 0, bottom: 1, right: 1 }
			});

			interact.maxInteractions(Infinity);

			interact('.drop').dropzone({
				overlap: 0.5,
				accept: ".drag",
				ondropactivate: function (event) {
				},
				ondropdeactivate: function (event) {
				},
				ondragleave: function (event) {
          if ($(event.relatedTarget).data("locked")) {
            return;
          }

					var drag = event.relatedTarget;
					var drop = event.target;
					if ($(drop).data("drag") == $(drag).attr("id")) {
						$(drop).removeData("drag");
					}
					if ($(drag).data("drop") == $(drop).attr("id")) {
						$(drag).removeData("drop");
					}
				},
				ondrop: function (event) {
          if ($(event.relatedTarget).data("locked")) {
            return;
          }

					if (isAccepted(event)) {
  					var drag = event.relatedTarget;
  					var drop = event.target;
  					var pos = $(drop).offset();
  					x = pos['left'];
  					y = pos['top'];

  					$(drop).data("drag", $(drag).attr("id"));
  					$(drag).data("drop", $(drop).attr("id"));
  					$(drag).animate({top: y + "px", left: x + "px"});
					}
				}
			});

			//$('#main').disableSelection().css('webkit-user-select','none');

			//var trayPos = $(".tray").first().offset();
			//$("#cir_light").css({top: trayPos['top'], left: trayPos['left']});

      var rowPos = $("#alphaRow").offset();
      var rowTop = rowPos['top'];
      var rowLeft = rowPos['left'];
      var rowWidth = $("#alphaRow").width();
      var rowHeight = $("#alphaRow").height();

      var randY = [[10, rowTop - 150], [rowTop + rowHeight, $("#alphaBoard").height() - 150 - 10]];
      var randX = [[10, $("#alphaBoard").width() - 112 - 10]];

      $(".drag").each(function() {
        var x, y;

        var pickY = getRandomInt(0, 1);

        var rMinX = randX[0][0];
        var rMaxX = randX[0][1];

        var rMinY = randY[pickY][0];
        var rMaxY = randY[pickY][1];

        x = getRandomInt(rMinX, rMaxX);
        y = getRandomInt(rMinY, rMaxY);

        var hue = $(this).data("hue");

        $(this)
          .css({
            top: y + "px",
            left: x + "px",
            "-webkit-filter": "hue-rotate(" + hue + "deg)",
            "filter": "hue-rotate(" + hue + "deg)",
            "z-index": 1000,
          })
          .data("x", x)
          .data("y", y);
      });

      $("#refresh").on("click", function(){
        console.log("test");
        location.reload(true);
      });
		});

		/*$("#main").on("touchstart", function(event) {
			event.preventDefault();
		});*/

		$(window).on("resize", function() {
			figureHeights();

      var winHeight = $("#main").height();
      var winWidth = $("#main").width();

      $(".drag").each(function() {
        var dragPos = $(this).offset();
        x = dragPos['left'];
        y = dragPos['top'];

        if (y > winHeight - 150) {
          y = winHeight - 150;
        }

        if (x > winWidth - 112) {
          x = winWidth - 112;
        }

        if ($(this).data("drop")) {
          var pos = $("#" + $(this).data("drop")).offset();
          x = pos['left'];
          y = pos['top'];
          //$(this).css({top: y + "px", left: x + "px"});
        }

        $(this).css({top: y + "px", left: x + "px"});
      });
		});
	</script>
  </body>
</html>
