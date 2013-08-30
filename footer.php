

	<footer>
		<div class="full_width">
			<div class="wrapper">
					<div class="reality-logo"></div>
					<nav class="footer-menu">
						<?php wp_nav_menu( array( 'theme_location' => 'footer_menu', 'container' => false ) ); ?>
					</nav>
				</div>	
			<div class="clear"></div>
		</div>
		<div class="wrapper footer-info">
			Copyright 2013 <a href="http://cinema.usc.edu">USC School of Cinematic Args</a> | Website Designed by <a href="http://sabradesign.me">Sabra Design</a>
		</div>
	</footer>
	</div> <!-- end #page -->
	
	<?php if ( is_page_template('submission_form.php' ) ) : ?>
	
	<?php $users = get_users(); ?>
	<?php $cards = get_posts( array( 'post_type' => 'reality_cards', 'numberposts' => -1 ) ); ?>

	<?php $cards_auto = get_option( 'reality_submit_form_card_autosuggest' ); ?>
	<?php $authors_auto = get_option( 'reality_submit_form_author_autosuggest' ); ?>

<script>
jQuery(document).ready( function($) {
    var availableNameTags = [
      <?php foreach( $users as $user ) : ?>
      	'<?php echo addslashes($user->display_name); ?>',
      <?php endforeach; ?>
    ],
    	availableCardTags = [
    	<?php foreach( $cards as $card ) : ?>
    	<?php if ( !reality_is_maker_card( $card->post_name ) ) : ?>
    	{
    		label:	'<?php echo addslashes($card->post_name); ?>',
    		desc:	'<?php echo addslashes($card->post_title); ?>'
    	},
    	<?php endif; ?>
    	<?php endforeach; ?>	
    ];
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#deal_collaborators" ).bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
          	event.preventDefault();
        }
        
        $(this).removeClass('invalidEntry').removeClass('validEntry');
        
      }).autocomplete({
        minLength: <?php echo $authors_auto; ?>,
        appendTo: "#deal_collaborators_container",
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableNameTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          //this.value = terms.join( ", " );
          this.value = "";
          
          //Execute Ajax Call To Get User
          $(this).addClass('loading');
          var data = 'action=reality_get_user_preview&user=' + ui.item.value;
          $.ajax({
          	type:	"POST",
          	url:	ajaxurl,
          	data:	data,
          	dataType:	"html",
          	success: function(response, status) {
          		if ( response ) {
          			var cardhtml = '<li>' + response + '<div class="user_name">' + ui.item.value + '</div><div class="delete_user" onClick="jQuery(this).parent().remove();"></div></li>';
          			$('#deal-collaborators-preview').append(cardhtml);
          			
          			$('#deal_collaborators').removeClass('loading').addClass('validEntry');
          		} else {
          		
          			$('#deal_collaborators').removeClass('loading').addClass('invalidEntry');
          		
          		}
          	}
          });
          
          return false;
        }
      });
      
      $( "#deal_cards" ).bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
          event.preventDefault();
        }
        
        var value = $(this).val(),
        	length = value.length;
        	
        console.log( length );
        
        if ( length > 4 ) {
        
        	var value = value.substring(0, 4);
        	$(this).val( value );
        
        }
        
        $(this).removeClass('validEntry').removeClass('invalidEntry');
        
      }).autocomplete({
        minLength: <?php echo $cards_auto; ?>,
        appendTo: "#deal_cards_container",
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableCardTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          //this.value = terms.join( ", " );
          //this.value = '';
          
          //Execute Ajax Call To Get Cards
          var data = 'action=reality_card_preview&no_maker=true&card_number=' + ui.item.value;
          $(this).addClass('loading');
          $.ajax({
          	type:	"POST",
          	url:	ajaxurl,
          	data:	data,
          	dataType:	"html",
          	success: function(response, status) {
          		if ( response ) {
          			var cardhtml = '<li class="flipped">' + response + '<div class="delete_card" onClick="jQuery(this).parent().remove();"></div><input type="hidden" name="deal_cards[]" value="' + ui.item.value + '"></li>';
          			//console.log(status);
          			$('#deal-card-preview').append(cardhtml);
          			$('#deal_cards').removeClass('loading').addClass('validEntry').val('');
          		} else {
          			
          			$('#deal_cards').removeClass('loading').addClass('invalidEntry');
          			
          		}
          	}
          });
          
          return false;
        }
      }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
      	return $("<li>")
      		.append( "<a>" + item.label + " - " + item.desc + "</a>" ).appendTo( ul );
      };
});
</script>
	
	<?php endif; ?>
	
	<?php wp_footer(); ?>
	</body>
</html>