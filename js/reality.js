
function validateSubmissionForm( form ) {

	return true;

}

function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
    return false;

	return true;
}


jQuery(document).ready( function($) {

	$('#header-slider').flexslider({
		'manualControls'	:	".slider-nav ul li"
	});

	$('a[rel="fancybox"]').not('.video').fancybox();
	
	$('body.activity div.item-list-tabs#subnav ul li a').click( function(e) {
		
		e.preventDefault();
		$('div.item-list-tabs#subnav ul li.active,div.item-list-tabs#subnav ul li.current,div.item-list-tabs#subnav ul li.selected').removeClass('active').removeClass('current').removeClass('active');
		$(this).parent().addClass('active').addClass('current').addClass('selected');
		
		var filter = $(this).attr('class');
		
		$('div.item-list-tabs#subnav ul select option[value*="' + filter + '"]').attr('selected','selected');
		$('div.item-list-tabs#subnav ul select').change();
		
	});
	
	$('aside.quick-actions ul li a.submit-deal, aside.quick-actions ul li a.card-lookup').click( function(e){
	
		e.preventDefault();
		
		$('aside.quick-actions ul li.active').removeClass('active');
		$(this).parent().addClass('active');
		
	});
	
	// Populate dropdown with menu items
	$('.nav-holder a').each(function() {
		var el = $(this);

		if($(el).parents('.sub-menu .sub-menu').length >= 1) {
			$('<option />', {
			 'value'   : el.attr('href'),
			 'text'    : '-- ' + el.text()
			}).appendTo('.nav-holder select');
		}
		else if($(el).parents('.sub-menu').length >= 1) {
			$('<option />', {
			 'value'   : el.attr('href'),
			 'text'    : '- ' + el.text()
			}).appendTo('.nav-holder select');
		}
		else {
			if ( el.parent().hasClass('current-menu-item') ) {
				$('<option />', {
				 'value'   : el.attr('href'),
				 'text'    : el.text()
				}).attr('selected','selected').appendTo('.nav-holder select');
			} else {
				$('<option />', {
				 'value'   : el.attr('href'),
				 'text'    : el.text()
				}).appendTo('.nav-holder select');
			}
		}
	});

	$('.nav-holder select').change(function() {
		window.location = $(this).find('option:selected').val();
	});
	
	$('form.ac-form .reality-deal-audience-awards label').click( function() {
	
		$('form.ac-form .reality-deal-audience-awards label.selected').removeClass('selected');
		$(this).addClass('selected');
	
	});
	
	$('form#loginform .login-remember label').click( function() {
	
		if ( $(this).find('input[type="checkbox"]:checked').length ) {
			$(this).addClass('selected');
		} else {
			$(this).removeClass('selected');
		}
	
	});
	
	// WEEKLY LEADERBOARD TABBED
	
	$('.weekly-leaderboard-tabbed').tabs();
	
	
	//Reality Submission Form Javascript
	$('#submission-form-tabs').tabs();
	$('#submission-form-tabs').tabs( "option", "disabled", [ 1, 2, 3, 4, 5 ] );
	
	$('#submission-form-tabs').on( "tabsactivate", function( event, ui ) {
	
		if ( $(this).tabs( "option", "active" ) == 5 ) {
		
			$('span.next-tab a').attr('href','#submit').html('Submit Deal &rarr;');
		
		} else {
		
			$('span.next-tab a').attr('href','#next-tab').html('Next Step &rarr;');
		
		}
	
	} );
	
	$('form#deal-submission-form input[name="maker_card_id"]').keyup( function() {
	
		$(this).removeClass('validEntry').removeClass('invalidEntry');
	
		var card_id = $(this).val();
		
		if ( card_id.length == 5 ) {
		
			$(this).addClass('loading');
		
			var data = 'action=reality_sumbission_get_maker_card&maker_card_id=' + card_id;
			
			$.post( ajaxurl, data, function( response ) {
			
				if ( response != 0 ) {
				
					$('#maker-card .message').html( 'Got it!' );
					$('#card-header .card-container').html( response );
					$('form#deal-submission-form input[name="maker_card_id"]').addClass('validEntry').attr('disabled','disabled').removeClass('loading');
					
					var data = 'action=reality_card_preview&card_number=' + card_id;
          			$.ajax({
          				type:	"POST",
          				url:	ajaxurl,
          				data:	data,
          				dataType:	"html",
          				success: function(response, status) {
          					var cardhtml = '<li>' + response + '<input type="hidden" name="deal_cards[]" value="' + card_id + '"></li>';
          					console.log(status);
          					$('#deal-card-preview').append(cardhtml);
          					$('#submission-form-tabs').tabs( "option", "disabled", [] );
          				}
          			});
				
				} else {
				
					$('#maker-card .message').html( 'Still not a maker card unfortunately...' );
					$('form#deal-submission-form input[name="maker_card_id"]').removeClass('loading').addClass('invalidEntry');
				
				}
			
			});
		
		}
	
	});
	
	$('input#deal_youtube_id').change( function() {
		var vidID = $(this).val(),
			idPos = vidID.search('/?v='),
			length = vidID.length - 1;
			
		console.log( 'vidID = ' + vidID + ' and idPos = ' + idPos + ' and length = ' + length );
		
		if ( idPos != -1 ) {
			
			var substring = vidID.substring( idPos + 2 );
			var varAndPost = substring.search('&');
			
			if ( varAndPost != -1 ) {
				vidID = substring.substring( 0, varAndPost);
			} else {
				vidID = substring;
			}
			
		}
		
		$('#youtube_video_preview').remove();
			
		$(this).val(vidID).parent().parent().append('<div id="youtube_video_preview"><iframe width="560" height="315" src="http://www.youtube.com/embed/' + vidID + '?rel=0" frameborder="0" allowfullscreen></iframe></div>');
		
	
	});
	
	$('input.max-char,textarea.max-char').keyup( function() {
	
		var length = $(this).val().length,
			maxLength = $(this).attr('maxChar'),
			value = $(this).val();
			
		if ( length > maxLength ) $(this).val( value.substring(0, maxLength) );
		
		$(this).parent().find('.char-count').html(length);
	
	});
	
	$('.tabs-nav a').click( function(e) {
	
		e.preventDefault();
		
		var href = $(this).attr('href'),
			currentTab = $('#submission-form-tabs').tabs( "option", "active" ),
			nextTab = currentTab + 1,
			prevTab = currentTab - 1,
			tabs = $('#submission-form-tabs');
		
		
		if ( href == '#prev-tab' ) {
		
			tabs.tabs( "option", "active", prevTab );
		
		} else if ( href == '#next-tab' ) {
		
			tabs.tabs( "option", "active", nextTab );
		
		} else if ( href == '#submit' ) {
		
			if ( validateSubmissionForm( $('form#deal-submission-form') ) ) {
			
				$('form#deal-submission-form input[name="maker_card_id"]').removeAttr( 'disabled' );
				$('form#deal-submission-form').submit();
				
			} else {
			
				alert("Not all fields have been validated!");
			
			}
		
		}
	
	});
	
	$('html.touch input,html.touch textarea').live( 'focusin', function() {
	
		//if ( $(window).width() < 480 ) {
	
			console.log('Focus!!');
		
			var menu = $('div.top-bar'),
				height = menu.height(),
				position = 0 - height;
				
			console.log( position );
		
			$('div.top-bar').stop().animate({
				'top'	:	position + 'px'
			}, 500 );
		
		//}
	
	});
	
	$('html.touch input,html.touch textarea').live( 'focusout', function() {
	
		console.log('Focus Out');
	
		var menu = $('div.top-bar'),
			height = menu.css('height');
	
		$('div.top-bar').stop().animate({
			'top'	:	0
		}, 500 );
	
	});
	
	$('.dept_nav a.dept-nav-button').click( function(e) {
		e.preventDefault();
		
		
		
		$(this).addClass('loading');
		
		var container = $(this).parent().parent(),
			tablecontainer = container.find('.table-container'),
			dept = $(this).attr('data-dept'),
			size = container.attr('data-size'),
			type = container.attr('data-type'),
			title = container.attr('data-title'),
			request = 'action=reality_leader_board_dept_switch&dept=' + dept + '&size=' + size + '&title=' + title + '&type=' + type;
		
		container.find('a.dept-nav-button.active').removeClass('active');
		
		$.ajax({
			type:	'POST',
			url:	ajaxurl,
			data:	request,
			success: function(response) {
			
				tablecontainer.stop().animate({
					'opacity': 0.01
					}, 250, function() {
						container.find('.leader-board').remove();
						tablecontainer.append( response );
						
						$('.weekly-leaderboard-tabbed').tabs();
						
						tablecontainer.stop().animate({
							'opacity':	1
						},250);
					});
					
				
				
				$('a.dept-nav-button.loading').removeClass('loading').addClass('active');
			
			}
		})
	});
	
	
	
	$('a.new-photo-button').click( function(e) {
	
		e.preventDefault();
		
		$(this).parent().find('input#new-photo').click();
	
	});

});