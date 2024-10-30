var previousDivToToggle = false;
var previousMoreLink = false;
var previousPostId;

function toggleDiv(divToToggle,moreLink,postId) {

       if (previousMoreLink && previousDivToToggle && (previousDivToToggle != divToToggle)){
           var isPreviousOpen = (jQuery("#"+previousMoreLink).text() == 'Zobraz menej');
           if (isPreviousOpen){
               jQuery("#"+previousDivToToggle).hide("normal");
               jQuery("#"+previousMoreLink).text('Zobraz viac');
			jQuery('#thumbFor'+previousPostId).removeClass("lupa-visible"); //in case it has more thmubs, remove the hint class
           }
       }

       // continue - hide or show current link
       jQuery("#"+divToToggle).toggle('normal');
       var n = jQuery("#"+moreLink).text();
	if (n=="Zobraz viac") {
		// rozbalujeme ponuku
		jQuery("#"+moreLink).text("Zobraz menej");

		// pridaj lupu k obrazku ak je tam viac obrazkov

		var items = jQuery('a[rel="lightbox['+postId+']"]');
		if(items.length > 1){
			jQuery('#thumbFor'+postId).addClass("lupa-visible");
		}
		
   	}
	else 
	{
		jQuery("#"+moreLink).text("Zobraz viac"); 
		jQuery('#thumbFor'+postId).removeClass("lupa-visible");
	}

       previousDivToToggle = divToToggle;
	previousMoreLink    = moreLink;
	previousPostId      = postId;

}// function toggle div




jQuery(document).ready(function($) {
	
	//$(".navig label.prev, .navig-f-prev").click(function(){ 		prevImage();	});
	//$(".navig label.next, .navig-f-next, .slide-container .slide").click(function(){ 		nextImage();	});
	
	$(document).keydown(function(e){
	    if (e.keyCode == 37) { 
	       prevImage();
	       return false;
	    }
	    if (e.keyCode == 39) { 
	       nextImage();
	       return false;
	    }
	});
	
	
	
	
	
	
		  var tapCount=0;
	      var doubleTapCount=0;
	      var longTapCount=0;
	      var swipeCount=0;
	      var blackCount=0;
      //Enable swiping...
      $(".navig-f-swipe").swipe( {
		  tap:function(event, target) {
            tapCount++;
            msg(target);
          },
          doubleTap:function(event, target) {
            doubleTapCount++;
            msg(target);
          },
          longTap:function(event, target) {
            longTapCount++;
            msg(target);
          },
		  swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
			  swipeCount++;
			//alert(direction);
			if(direction=="left") { prevImage(); }
			if(direction=="right") { nextImage(); }
		  },
	   	  threshold:10
      });
      function msg(target) {
		  alert("You tapped " + tapCount +", double tapped " +  doubleTapCount + " and long tapped " +  longTapCount + " times on " +  $(target).attr("id"));
          //$("#textText").html("You tapped " + tapCount +", double tapped " +  doubleTapCount + " and long tapped " +  longTapCount + " times on " +  $(target).attr("id"));
      }

	
	
	function resizeFrame() 	{
		var windowHeight = $(window).height();
		var imageHeight = $(".slide img.active").height();
		var imagewidth = $(".slide img.active").width();
		var bodyImageHeight = windowHeight-330;
		
		$(".fotofolio .slides").css({"height":bodyImageHeight+100});
		$(".image-popiska").css({"top":bodyImageHeight+30});
		$(".slide-container .slide").css({"height":bodyImageHeight});
		
		//$(".fotofolio, .site-main").css({"width":imagewidth});
		
		}
	resizeFrame();	
	$.event.add(window, "load", resizeFrame);
	$.event.add(window, "resize", resizeFrame);


		
	function nextImage(){
		var kolko = $(".kolko").text();
		var thisIDecko = $(".active").attr("id");
		var nextIDecko = thisIDecko++;
		
		$(".slide img, .image-popiska").removeClass("active");
		
		if(thisIDecko <= kolko){}else{thisIDecko=1;}
		$(".kolky").text(thisIDecko);
		$("#"+thisIDecko).addClass("active").parent().next().addClass("active");
		
		resizeFrame(); 
		
	}
	function prevImage(){
		var kolko = $(".kolko").text();
		var thisIDecko = $(".active").attr("id");		
		var nextIDecko = thisIDecko--;
		
		$(".slide img, .image-popiska").removeClass("active");
		
		if(thisIDecko > 0){}else{thisIDecko=kolko;}
		$(".kolky").text(thisIDecko);
		$("#"+thisIDecko).addClass("active").parent().next().addClass("active");
		
		resizeFrame(); 
		
	}
});



