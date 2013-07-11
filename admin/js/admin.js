jQuery(document).ready( function($) {

	function reality_calculate_deal_value() {
		var $total = 0;
		
		if ( $('input[name="REALITY_premultiplier_bonus"]').val() != '' ) {
			var premultiplier = parseInt($('input[name="REALITY_premultiplier_bonus"]').val());
		} else {
			var premultiplier = 0;
		}
		
		if ( $('input[name="REALITY_multiplier"]').val() != '' ) {
			var multiplier = parseInt($('input[name="REALITY_multiplier"]').val());
		} else {
			var multiplier = 1;
		}
		
		if ( $('input[name="REALITY_postmultiplier_bonus"]').val() != '' ) {
			var postmultiplier = parseInt($('input[name="REALITY_postmultiplier_bonus"]').val());
		} else {
			var postmultiplier = 0;
		}
		
		$('div#REALITY_deal-cards input[name^="REALITY_card_"]:checked').each( function() {
			$total += parseInt($(this).val());
		});
		
		
		$total += premultiplier;
		$total = $total * multiplier;
		$total += postmultiplier;
		
		// ADD AWARDS
		$('div#REALITY_deal-awards input[type="checkbox"]:checked').each( function() {
			var value = $('input#award-' + $(this).val()).val();
			console.log( 'This deals value is ' + value );
			$total += parseInt( value );
		});
		
		
		console.log('New value = ' + $total );
		
		return $total;
	}
	
	$('div#REALITY_deal-cards input[name^="REALITY_card_"],input[name="REALITY_premultiplier_bonus"],input[name="REALITY_multiplier"],input[name="REALITY_postmultiplier_bonus"],#REALITY_deal-awards input[type="checkbox"]').change( function() {
		$('input[name^="REALITY_total_value"]').val(reality_calculate_deal_value());
	});
	
	$('input#REALITY_card_front_title_firstline,input#REALITY_card_front_title_secondline,input#REALITY_card_back_title,input#REALITY_card_back_setinfo,textarea#REALITY_card_front_description,textarea#REALITY_card_back_description,textarea#REALITY_card_front_powerup').keyup( function(event) {
	
		var field = $(this).attr('name');
	
		$('div.' + field).html( $(this).val() );
	
	});
	
	$('select#REALITY_card_type').change( function() {
	
		var value = $(this).val();
		
		$('.reality-card-background div').attr('class','').addClass(value);
		$('.reality-card-container').attr('class','reality-card-container').addClass(value);
	
	});
	
	$('select#REALITY_card_connections').change( function() {
	
		var value = $(this).val();
		
		$('.reality-card-connections div').attr('class','').addClass(value);
	
	});
	
	function reality_points_delete_row() {
	
		var	row = $(this).parent().parent(),
			activityName = row.find('input.activity_name').val();
		
		console.log('activityName = ' + activityName);	
		
		if ( activityName != null && activityName != '' ) {
			
			var r = confirm('Are you sure you would like to delete point values for "' + activityName + '?"');
				
			if (r==true)
			{
				  row.remove();
			}
		} else {
			row.remove();
		}
	
	};
	
	$('form#reality_points_options_form a.add_row').click( function() {
	
		var row = $('form#reality_points_options_form table tbody tr:last-child'),
			number = row.index() + 1;
		
		var newRow = row.clone();
			
		newRow.appendTo('form#reality_points_options_form table tbody');
		
		newRow.find('input').each( function() {
			
			var newName = 'points_value[' + number + '][' + $(this).attr('class') + ']';
			
			$(this).val('').attr('name', newName);
		});
		
		
	});
	
	$('form#reality_ranks_options_form a.add_row').click( function() {
	
		var row = $('form#reality_ranks_options_form table tbody tr:last-child'),
			number = row.index() + 1;
		
		var newRow = row.clone();
			
		newRow.appendTo('form#reality_ranks_options_form table tbody');
		
		newRow.find('input').each( function() {
			
			var newName = 'ranks_value[' + number + '][' + $(this).attr('class') + ']';
			
			$(this).val('').attr('name', newName);
		});
		
		
	});
	
	$('#main-menu-tabs').tabs();
	
	// Date picker for date inputs
    $( "input.startdate" ).datepicker({
      altField: '#' + $(this).attr('id'),
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 3,
      onClose: function( selectedDate ) {
        $( this ).parent().parent().find( 'input.enddate' ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "input.enddate" ).datepicker({
      altField: '#' + $(this).attr('id'),
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 3,
      onClose: function( selectedDate ) {
        $(this).parent().parent().find( "input.startdate" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
    
    $('input#REALITY_deal_video_link,input#REALITY_deal_justification_video_link').change( function() {
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
	
	$('.weekly-leaderboard-tabbed').tabs();
	
	$('#add-departments a.add-department').click( function(e) {
	
		e.preventDefault;
		var input = $(this).parent().find('input.hidden').clone().removeClass('hidden').after('<br>');
		
		$(this).before( input );
	
	});
	
	$('#add-departments a.delete-department').click( function(e) {
	
		e.preventDefault;
		var dept = $(this).parent().find('input.reality_player_departments').val();
		var r = confirm( 'Are you sure you want to delete the "' + dept + '" Department?' );
		
		if ( r ) {
			$(this).parent().remove();
		}
	
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

});