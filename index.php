<?php

$bpixels = 100;

?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="mobile-web-app-capable" content="yes" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<title>Drag</title>

	<link href="css/app.css" rel="stylesheet" />

	<style>
	html, body {
		margin: 0px;
		padding: 0px;
	}

	#main {
		background: #C7EBEA;
		position: relative;
		top: 0px;
		bottom: 0px;
		left: 0px;
		right: 0px;
		width: 100%;
		height: 100%;
	}

	.cir_box {
		background: #C3B6E0;
		width: <?=$bpixels?>px;
		height: <?=$bpixels?>px;
		position: absolute;
		top: 0;
		left: 0;
	}

	.cir_drop {
		display: inline-block;
		background: yellow;
		width: <?=$bpixels?>px;
		height: <?=$bpixels?>px;
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

	#circuitBoard {
		background: #A5F2AA;
		position: absolute;
		top: 0px;
		bottom: 0px;
		left: <?=$bpixels + 40?>px;
		right: <?=$bpixels + 40?>px;
		display: flex;
		justify-content: center;  
		align-items: center;
		text-align: center;
	}
	
	#circuitPieces {
		margin: auto 0px;
	}

	
	</style>
  </head>
  <body>
  <div id="main">
	<div id="trayCLeft" class="trayContainer">
	<div class="trayInner">
		<div class="tray drop"></div>
		<div class="tray drop"></div>
		<div class="tray drop"></div>
	</div>
	</div>
	
	<div id="trayCRight" class="trayContainer">
	<div class="trayInner">
		<div class="tray drop"></div>
		<div class="tray drop"></div>
		<div class="tray drop"></div>
	</div>
	</div>

	<div id="circuitBoard">
	<div id="circuitPieces">
		<div class="circuitRow">
			<div class="cir_drop drop" id="cp1">.</div><div class="cir_drop drop" id="cp2">.</div><div class="cir_drop drop" id="cp3">.</div>
		</div>
		<div class="circuitRow">
			<div class="cir_drop drop" id="cp4">.</div>
		</div>
		<div class="circuitRow">
			<div class="cir_drop drop" id="cp5">.</div>
		</div>
	</div>
	</div>

	<div id="cir_light" class="cir_box drag">.</div>
	<div id="cir_battery" class="cir_box drag">.</div>
  </div>

	<script src="js/zepto.min.js"></script>
	<script src="js/interact.min.js"></script>

	<script>
		var x, y;

		function figureHeights() {
			$("#main").css({height: $(window).height() + "px"});
		}

		function isAccepted(event) {
			var drag = event.relatedTarget;
			var drop = event.target;
			console.log($(drag).attr("id"));
			console.log($(drop).attr("id"));
			console.log("drag: " + $(drop).data("drag"));
                                        if ($(drop).data("drag") == $(drag).attr("id")) {
                                                console.log("drag == id: TRUE");
						return true;
                                        } else if ($(drop).data("drag")) {
						console.log("has a drag: FALSE");
                                                return false;
                                        } else {
						console.log("TRUE");
                                                return true;
                                        }
			console.log("---");
                                }

		$(document).ready(function() {
			figureHeights();
			
			interact(".drag").draggable({
				max: Infinity,
				onstart: function (event) {
					var target = event.target;
					var pos = $(target).offset();
					$(target)
						.data("startX", pos['left'])
						.data("startY", pos['top']);
				},

				onmove: function (event) {
					var target = event.target;
					x = (parseFloat($(target).data("x")) || 0) + event.dx;
					y = (parseFloat($(target).data("y")) || 0) + event.dy;

					$(target).css({top: y + "px", left: x + "px"});

					$(target).data("x", x).data("y", y);
				},
	
				onend: function (event) {
					var target = event.target;
					if (!$(target).data("drop")) {
						x = $(target).data("startX");
						y = $(target).data("startY");
						$(target)
							.data("x", x)
							.data("y", y)
							.animate({top: y, left: x});
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
				overlap: 0.75,
				accept: ".drag",
				ondropactivate: function (event) {
				},
				ondropdeactivate: function (event) {
				},
				ondragleave: function (event) {
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

			//$(".cir_box").draggable({revert: "invalid", containment: "#main", scroll: false});
			//$(".cir_drop").droppable({accept: dropAccept, drop: dropBox, out: dropOut});
			//$(".tray").droppable({accept: dropAccept, drop: dropBox, out: dropOut});
		
			var trayPos = $(".tray").first().offset();
			$("#cir_light").css({top: trayPos['top'], left: trayPos['left']});
		});

		/*$("#main").on("touchstart", function(event) {
			event.preventDefault();
		});*/

		$(window).on("resize", function() {
			figureHeights();
		});

		function dropAccept(draggable) {
			var holding = $(this).data("holding");
			if (holding == draggable.attr("id")) {
				//console.log($(this).data("name") + ": TRUE drag is hold");
				return true;
			}

			if (holding) {
				//console.log($(this).data("name") + ": FALSE holding not drag");
				return false;
			}

			if (draggable.hasClass("cir_box")) {
				//console.log($(this).data("name") + ": TRUE is box");
				return true;
			}

			//console.log($(this).data("name") + ": FALSE default");
			return false;
		}

		function dropBox(event, ui) {
			//console.log(this);
			//console.log(ui);
			var draggable = $(ui['draggable'][0]);
			$(this).data("holding", draggable.attr("id"));

			var dropPos = $(this).offset();
			var dragPos = draggable.offset();
			if (dragPos['top'] != dropPos['top'] || dragPos['left'] != dropPos['left']) {
				draggable.animate({"top": dropPos['top'], "left": dropPos['left']});
			}
		}

		function dropOut(event, ui) {
			var draggable = $(ui['draggable'][0]);

			if ($(this).data("holding") == draggable.attr("id")) {
				$(this).removeData("holding");
			}
		}
	</script>
  </body>
</html>
