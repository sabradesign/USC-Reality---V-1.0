<?php
/*

******************************************************************************************
********************************** ACTION REFERENCE **************************************
******************************************************************************************

------------------------------------------------------------------------------------------
add_action( 'reality_after_update_user_points', $function_to_add, $priority, 3 );
------------------------------------------------------------------------------------------
This action executes immediately after a user's points are updated.

PASSED VARIABLES
$user_id = (int) The id of the user whose points have been updated
$points = (int) Their new score
$user_previous_points = (int) Their score prior to the update

------------------------------------------------------------------------------------------
add_action( 'reality_after_update_user_rank', $function_to_add, $priority, 5 );
------------------------------------------------------------------------------------------
This action executes immediately after a user's rank has been updated.

PASSED VARIABLES
$user_id = (int) The id of the user whose rank has been updated
$current_rank = (string) The user's new rank
$previous_rank = (string) The user's previous rank
$current_points = (int) User's new score
$previous_points = (int) User's previous score

------------------------------------------------------------------------------------------
add_action( 'reality_after_add_deal_activity', $function_to_add, $priority, 1 );
------------------------------------------------------------------------------------------
This action is executed immediately after a deal activity is created and the deal point
value is recorded for player score calculation.

PASSED VARIABLES
$post_ID = (int) The id of the Deal CPT



******************************************************************************************
********************************** FILTER REFERENCE **************************************
******************************************************************************************

------------------------------------------------------------------------------------------
add_filter( 'reality_before_create_parallel_user_tax', $term_info, 1, 2 );
------------------------------------------------------------------------------------------
Executed before a parallel user authors-tax taxonomy term is created:

PASSED VARIABLES:
$name = (string) Name of the term. Default: User First and Last Name or Username if not set.
$term_args = (array) {$term_args['slug'] and $term_args['description']}
		Sets the slug and description of term.  Default: slug = User ID,
		description = User Email

------------------------------------------------------------------------------------------
add_filter( 'reality_before_create_parallel_awards_tax', $term_info, 1, 2 );
------------------------------------------------------------------------------------------
Executed before a parallel award awards-tax taxonomy term is created:

PASSED VARIABLES:
$name = (string) Name of the term. Default: Award CPT Title
$term_args = (array) {$term_args['slug'] and $term_args['description']}
		Sets the slug and description of term.  Default: slug = Award CTP Post ID,
		description = Award CPT Excerpt

------------------------------------------------------------------------------------------
add_filter( 'reality_before_create_parallel_cards_tax', $term_info, 1, 2 );
------------------------------------------------------------------------------------------
Executed before a parallel card cards-tax taxonomy term is created:

PASSED VARIABLES:
$name = (string) Name of the term. Default: Card CPT Slug (to synchronize with card ID)
$term_args = (array) {$term_args['slug'] and $term_args['description']}
		Sets the slug and description of term.  Default: slug = Card CTP Post ID,
		description = Card CPT Title
		
------------------------------------------------------------------------------------------
add_filter( 'reality_deal_approval_activity_args', $deal_activity_args, 1, 1 );
------------------------------------------------------------------------------------------

*** ADD DOCUMENTATION ***

------------------------------------------------------------------------------------------
add_filter( 'reality_activity_points_modifier', $filter_function, 1, 1 );
------------------------------------------------------------------------------------------
This filter modifies the point value of an activity when it is submitted to the database.

PASSED VARIABLES:
$activity_value = (int) The total calculated activity value.
$activity_id = (int) The id of the activity generating the value.
$activity_type = (string) The slug of the activity type.

------------------------------------------------------------------------------------------
add_filter( 'reality_level_up_activity_args', $filter_function, 1, 3 );
------------------------------------------------------------------------------------------
This filter modifies the arguments passed to create the activity for when users level up.

PASSED VARIABLES:
$levelup_activity_args = (array) The arguments being passed to the bp_activity function.
$user_id = (int) The id of the user who is leveling up.
$new_rank = (string) The title of their new rank.
$previous_rank = (string) The title of thier previous rank.

------------------------------------------------------------------------------------------
add_filter( 'reality_save_deal_standings', $filter_function, 1, 2 );
------------------------------------------------------------------------------------------
This filter modifies the standings information saved to a deal post by the 
reality_calculate_deal_standings() function.

PASSED VARIABLES:
$deal_standings = (Array) Contains all standings information for the deal.
	$deal_standings['points_rank'] = the current deal ranking in terms of points
$post_ID = (int) The id of the deal CPT.

------------------------------------------------------------------------------------------
add_filter( 'reality_update_user_standings_info', $filter_function, 1, 1 );
------------------------------------------------------------------------------------------
This filter modifies the standings information saved to a user's meta.

PASSED VARIABLES:
$standings_info = (array) Contains all of the standings information for the user.
	$standings_info['points_rank'] = The current user ranking in point values.
$player = (object) A player object retrieved by the WP_User() class.

------------------------------------------------------------------------------------------
add_filter( 'reality_save_parallel_awards_tax_info', $filter_function, 1, 1 );
------------------------------------------------------------------------------------------
This filter modifies the information saved to the parallel awards taxonomy description
field for quick access.

PASSED VARIABLES:
$award_info = (array) This variable stores the preset information that is saved to the 
	parallel awards taxonomy.
	$award_info['description'] = (string) The award description
	$award_info['value'] = (int) The award point value
$post_id = (int) The ID of the award CPT

------------------------------------------------------------------------------------------
add_filter( 'reality_calculated_user_points', $filter_function, 1, 2 );
------------------------------------------------------------------------------------------
This filter modifies the amount of points a user has when they are recalculated.  Use this
function to add additional scoring elements to players

PASSED VARIABLES:
$points = (int) The point value that is being submitted to the user.
$user_id = (int) The ID of the user


*/
?>