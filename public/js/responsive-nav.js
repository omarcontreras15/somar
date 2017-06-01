
var ww = document.body.clientWidth;

$(document).ready(function() {
	$(".navig li a").each(function() {
		if ($(this).next().length > 0) {
			$(this).addClass("parent");
		};
	})
	
	$(".toggleMenu").click(function(e) {
		e.preventDefault();
		$(this).toggleClass("sub");
		$(".navig").toggle();
	});
	adjustMenu();
})

$(window).bind('resize orientationchange', function() {
	ww = document.body.clientWidth;
	adjustMenu();
});

var adjustMenu = function() {
	if (ww < 800) {
		$(".toggleMenu").css("display", "inline-block");
		if (!$(".toggleMenu").hasClass("sub")) {
			$(".navig").hide();
		} else {
			$(".navig").show();
		}
		
	} 
	else if (ww >= 800) {
		$(".toggleMenu").css("display", "none");
		$(".navig").show();
		
	}
}

