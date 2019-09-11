/*
========================================================
Slick slider configuration
========================================================
*/
(function($){
	$(document).ready(function(){
		$('.moviepro-list').slick({
		  dots: false,
		  infinite: true,
		  speed: 300,
		  slidesToShow: 4,
		  slidesToScroll: 1,
		  centerMode: true,
		  centerPadding: '0px',
		  variableWidth: true,
		  prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left fa-2x"></i></button>',
		  nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right fa-2x"></i></button>',
		  responsive: [
		    {
		      breakpoint: 1024,
		      settings: {
		        slidesToShow: 3,
		        slidesToScroll: 3,
		        infinite: true,
		      }
		    },
		    {
		      breakpoint: 600,
		      settings: {
		        slidesToShow: 2,
		        slidesToScroll: 1
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    }]
		});

		$('.moviepro-listItem').click(function(e){
			let popup = '.popup-info-' + e.id;
			if ($(popup).hasClass('.show-overlay')) {
				$(popup).removeClass('.show-overlay');
			} else {
				$(popup).addClass('.show-overlay');
			}
		});
	});
})(jQuery)