var name_prefix = "admin";
$(document).ready(function() 
{
    $('.attribute-6 input').change(function() {
        var value = $(this).val();
        
        if($(this).prop('checked') && (value == 'Male' || value == 'Transgender')) {
            $('.attribute-3 input').removeAttr('required');
            $('.attribute-3').hide();
        } else {
            $('.attribute-3 input').attr('required');
            $('.attribute-3').show();
        }
    });

    if($('.attribute-6 input').val() == 'Male' || $('.attribute-6 input').val()  == 'Transgender'){
    	$('.attribute-3 input').removeAttr('required');
        $('.attribute-3').hide();
    }
		
	// Responsive top navigation bar
		var $topBar = $('[data-responsive-hidden-nav]');
		var $button = $('[data-responsive-hidden-nav] .responsive-hidden-button');
		var $visibleLinks = $('[data-responsive-hidden-nav] .visible-links');
		var $hiddenLinks = $('[data-responsive-hidden-nav] .hidden-links');
		var responsiveBreaks = []; // Empty List (Array) on initialization

		function updateTopBar() {
		  
		  var availableSpace = $button.hasClass('hidden') ? $topBar.width() : $topBar.width() - $button.width() - 30; // Calculation of available space on the logic of whether button has the class `hidden` or not

		  if($visibleLinks.width() > availableSpace){ // Logic when visible list is overflowing the nav
			responsiveBreaks.push($visibleLinks.width()); // Record the width of the list
			$visibleLinks.children().last().prependTo($hiddenLinks); // Move item to the hidden list
			
			// Show the resonsive hidden button
			if($button.hasClass('hidden')) {
				$button.removeClass('hidden');
			}
		  } else { // Logic when visible list is not overflowing the nav
			if(availableSpace > responsiveBreaks[responsiveBreaks.length-1]) { // Logic when there is space for another item in the nav
				$hiddenLinks.children().first().appendTo($visibleLinks);
				responsiveBreaks.pop(); // Move the item to the visible list
			}
			// Hide the resonsive hidden button if list is empty
			if(responsiveBreaks.length < 1) {
				$button.addClass('hidden');
				$hiddenLinks.addClass('hidden');
			}
		  }

		  $button.attr("count", "+"+responsiveBreaks.length); // Keeping counter updated

		  if($visibleLinks.width() > availableSpace) { // Occur again if the visible list is still overflowing the nav
			updateTopBar();
		  } else if(availableSpace > responsiveBreaks[responsiveBreaks.length-1]){
			updateTopBar();  
		  }
		}

		// Window listeners
		$(window).resize(function() {
			setTimeout(function() {
				updateTopBar();
				console.log('its running');
			}, 100);
			
		});
		$button.on('click', function() {
			$hiddenLinks.toggleClass('hidden');
		});

		updateTopBar();
	// Responsive top navigation bar end
	
	if(document.getElementById("adminMenu")){
		var options = {};
		var elem1 = new Foundation.OffCanvas($('#adminMenu'), options);
		var elem2 = new Foundation.ResponsiveAccordionTabs($('#adminMenuPanel'), options);
		$('.off-canvas #closeAdminPanel').on('click', function() {
			$('.off-canvas').foundation('close');
		});
	}
	
	/*$('.datePicker').datepicker({
      showOtherMonths: true,
      selectOtherMonths: true
    });*/

    $(".side-bar").css('height',($("body").height() - $(".responsive-hidden-nav-container").outerHeight(true) - $(".fes-footer").outerHeight(true)));
    $(".side-bar").css('top', $(".responsive-hidden-nav-container").outerHeight(true));

   /* if($(".side-bar") !== undefined){
    	$(window).scroll(function() {
    		if ($('.innerDiv').isInViewport()) {
    			$(".side-bar").css('top', 'auto');
    		} else{
    			$(".side-bar").css('top', '0');
    		}	
    	});
    }

    $.fn.isInViewport = function() {
	    var elementTop = $(this).offset().top;
	    var elementBottom = elementTop + $(this).outerHeight();

	    var viewportTop = $(window).scrollTop();
	    var viewportBottom = viewportTop + $(window).height();

	    return elementBottom > viewportTop && elementTop < viewportBottom;
	};*/

	if($(".messages") !== undefined){
		$("#messageCloseBtn").on("click", function(){
			$(".messages").hide();
		});
	}

});

function changeAdminLangSwitch(elem){
	$('<form action="/Utils/changeLanguage" method="POST" autocomplete="off">')
	.append($('<input type="hidden" name="lang" value="' + elem.value + '">'))
	.appendTo($(document.body)).submit();
}