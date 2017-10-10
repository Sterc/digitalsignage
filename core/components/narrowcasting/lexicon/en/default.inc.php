<?php

	/**
	 * Narrowcasting
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
	 *
	 * Narrowcasting is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Narrowcasting is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Narrowcasting; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */

	$_lang['narrowcasting'] 										= 'Narrowcasting';
	$_lang['narrowcasting.desc'] 									= '';

	$_lang['area_narrowcasting']									= 'Narrowcasting';

    $_lang['setting_narrowcasting.branding_url']              		= 'Branding';
    $_lang['setting_narrowcasting.branding_url_desc']         		= 'The URL of the branding button, if the URL is empty the branding button won\'t be shown.';
    $_lang['setting_narrowcasting.branding_url_help']         		= 'Branding (help)';
    $_lang['setting_narrowcasting.branding_url_help_desc']   		= 'The URL of the branding help button, if the URL is empty the branding help button won\'t be shown.';
    $_lang['setting_narrowcasting.auto_create_sync']				= 'Auto create synchronize';
	$_lang['setting_narrowcasting.auto_create_sync_desc']			= 'Create automatically an synchronize when no synchronize available.';
	$_lang['setting_narrowcasting.request_resource']				= 'Default request';
	$_lang['setting_narrowcasting.request_resource_desc']			= 'The ID of the resource that you want to use as default request.';
	$_lang['setting_narrowcasting.export_resource']					= 'Export request';
	$_lang['setting_narrowcasting.export_resource_desc']			= 'The ID of the resource that you want to use as export request.';
    $_lang['setting_narrowcasting.request_param_broadcast']			= 'Broadcast parameter';
    $_lang['setting_narrowcasting.request_param_broadcast_desc']	= 'The name of the GET-parameter to identify the broadcast.';
	$_lang['setting_narrowcasting.request_param_player']			= 'Player parameter';
	$_lang['setting_narrowcasting.request_param_player_desc']		= 'The name of the GET-parameter to identify the player.';
	$_lang['setting_narrowcasting.context']							= 'Context';
	$_lang['setting_narrowcasting.context_desc']					= 'The default narrowcasting context.';
	$_lang['setting_narrowcasting.templates']						= 'Templates';
	$_lang['setting_narrowcasting.template_desc']					= 'The available templates for a broadcast. Separate multiple template ID\'s with a comma.';
	$_lang['setting_narrowcasting.media_source']					= 'Media source';
	$_lang['setting_narrowcasting.media_source_desc']				= 'The media source that is used for media files.';

	$_lang['narrowcasting.broadcast']								= 'Broadcast';
	$_lang['narrowcasting.broadcasts']								= 'Broadcasts';
	$_lang['narrowcasting.broadcasts_desc']							= 'Here you can manage all broadcasts for your narrowcasting system. Modifications that are done here or under slides can not be shown live, you can only see this with the \'preview broadcast\'. THe broadcast needs to be synchronized first before you can see the modifications live, when there are modifications that are not synchronized a <i class="icon icon-exclamation-triangle red"></i> will be shown.';
	$_lang['narrowcasting.broadcast_create']						= 'Create new broadcast';
	$_lang['narrowcasting.broadcast_update']						= 'Update broadcast';
    $_lang['narrowcasting.broadcast_duplicate']						= 'Duplicate broadcast';
	$_lang['narrowcasting.broadcast_remove']						= 'Delete broadcast';
	$_lang['narrowcasting.broadcast_remove_confirm']				= 'Are you sure you want to delete this broadcast?';
	$_lang['narrowcasting.broadcast_preview']						= 'Preview broadcast';
	$_lang['narrowcasting.broadcast_sync']							= 'Synchronize broadcast';
	$_lang['narrowcasting.broadcast_sync_confirm']					= 'Are you sure you want to sync this broadcast?';
	$_lang['narrowcasting.broadcast_slides']						= 'Add slides (internal slides)';
	$_lang['narrowcasting.broadcast_slides_desc']					= 'Here you can manage all the internal slides of the broadcast. Drag & Drop slides from the "available slides" to "selected slide" to add slides.';
	$_lang['narrowcasting.broadcast_slide_remove']					= 'Delete slide';
	$_lang['narrowcasting.broadcast_feeds']							= 'Add feeds (external slides)';
	$_lang['narrowcasting.broadcast_feeds_desc']					= 'Here you can manage all the external slides of the broadcast.';
	$_lang['narrowcasting.broadcast_feed_create']					= 'Create new feed';
	$_lang['narrowcasting.broadcast_feed_update']					= 'Update feed';
	$_lang['narrowcasting.broadcast_feed_remove']					= 'Delete feed';
	$_lang['narrowcasting.broadcast_feed_remove_confirm']			= 'Are you sure you want to delete this feed?';

	$_lang['narrowcasting.label_broadcast_name']					= 'Name';
	$_lang['narrowcasting.label_broadcast_name_desc']				= 'The name of the broadcast.';
	$_lang['narrowcasting.label_broadcast_description']				= 'Description';
	$_lang['narrowcasting.label_broadcast_description_desc']		= 'A short description of the broadcast.';
	$_lang['narrowcasting.label_broadcast_template']				= 'Template';
	$_lang['narrowcasting.label_broadcast_template_desc']			= 'The template of the broadcast.';
	$_lang['narrowcasting.label_broadcast_ticker_url']				= 'Ticker URL';
	$_lang['narrowcasting.label_broadcast_ticker_url_desc']			= 'The URL of the feed for the ticker.';
	$_lang['narrowcasting.label_broadcast_slides']					= 'Slides';
	$_lang['narrowcasting.label_broadcast_slides_desc']				= 'The slides to broadcast the broadcast.';
	$_lang['narrowcasting.label_broadcast_feeds']					= 'Feeds';
	$_lang['narrowcasting.label_broadcast_feeds_desc']				= 'The feeds to broadcast the broadcast.';
	$_lang['narrowcasting.label_broadcast_players']					= 'Mediaboxes';
	$_lang['narrowcasting.label_broadcast_players_desc']			= 'The mediaboxes to broadcast the broadcast.';
	$_lang['narrowcasting.label_broadcast_last_sync']				= 'Last synchronized';
	$_lang['narrowcasting.label_broadcast_last_sync_desc']			= '';
	$_lang['narrowcasting.label_broadcast_preview_player']			= 'Player';
	$_lang['narrowcasting.label_broadcast_preview_player_desc']		= 'Select the player to show the broadcast preview.';

	$_lang['narrowcasting.label_feed_key']							= 'Key';
	$_lang['narrowcasting.label_feed_key_desc']						= 'The key of the feed.';
	$_lang['narrowcasting.label_feed_name']							= 'Name';
	$_lang['narrowcasting.label_feed_name_desc']					= 'The name of the feed.';
	$_lang['narrowcasting.label_feed_url']							= 'URL';
	$_lang['narrowcasting.label_feed_url_desc']						= 'The URL of the feed.';
	$_lang['narrowcasting.label_feed_time']							= 'Duration';
	$_lang['narrowcasting.label_feed_time_desc']					= 'The duration of the slide in seconds or minutes.';
	$_lang['narrowcasting.label_feed_frequency']					= 'Frequency';
	$_lang['narrowcasting.label_feed_frequency_desc']				= 'The frequency of slides of the feed.';
	$_lang['narrowcasting.label_feed_published']					= 'Published';
	$_lang['narrowcasting.label_feed_published_desc']				= '';

	$_lang['narrowcasting.slide']									= 'Slide';
	$_lang['narrowcasting.slides']									= 'Slides';
	$_lang['narrowcasting.slides_desc']								= 'Here you can manage all the slides for your narrowcasting.';
	$_lang['narrowcasting.slide_create']							= 'Create new slide';
	$_lang['narrowcasting.slide_update']							= 'Update slide';
    $_lang['narrowcasting.slide_duplicate']							= 'Duplicate slide';
	$_lang['narrowcasting.slide_remove']							= 'Delete slide';
	$_lang['narrowcasting.slide_remove_confirm']					= 'Are you sure you want to delete this slide?';

	$_lang['narrowcasting.label_slide_name']						= 'Title';
	$_lang['narrowcasting.label_slide_name_desc']					= 'The title of the slide.';
	$_lang['narrowcasting.label_slide_type']						= 'Type';
	$_lang['narrowcasting.label_slide_type_desc']					= 'The type of the slide.';
	$_lang['narrowcasting.label_slide_time']						= 'Duration';
	$_lang['narrowcasting.label_slide_time_desc']					= 'The duration of the slide in seconds.';
	$_lang['narrowcasting.label_slide_published']					= 'Published';
	$_lang['narrowcasting.label_slide_published_desc']				= '';
	$_lang['narrowcasting.label_slide_broadcasts']                  = 'Broadcasts';
	$_lang['narrowcasting.label_slide_broadcasts_desc']             = 'The broadcasts of the slide.';

	$_lang['narrowcasting.player']									= 'Player';
	$_lang['narrowcasting.players']									= 'Players';
	$_lang['narrowcasting.players_desc']							= 'Here you can manage all players for your narrowcasting system. A player has a default delay of 5 minutes. Modifications that you do here, will be live after 5 minutes (synchronization). A player with a <i class="icon icon-circle green"></i> are current on and broadcasting a broadcast, players with a <i class="icon icon-exclamation-triangle red"></i> are off.';
	$_lang['narrowcasting.player_create']							= 'Create new player';
	$_lang['narrowcasting.player_update']							= 'Update player';
	$_lang['narrowcasting.player_remove']							= 'Delete player';
	$_lang['narrowcasting.player_remove_confirm']					= 'Are you sure you want to delete this player?';
    $_lang['narrowcasting.player_restart']                          = 'Restart player';
    $_lang['narrowcasting.player_restart_confirm']                  = 'Are you sure you want to restart this player?';
    $_lang['narrowcasting.player_restart_cancel']                   = 'Cancel player restart';
    $_lang['narrowcasting.player_restart_cancel_confirm']           = 'Are you sure you want to cancel the player restart?';
    $_lang['narrowcasting.player_view']								= 'Connect player';
	$_lang['narrowcasting.player_view_desc']						= 'Use the following URL in the browser of your player to connect with the narrowcasting system.';
	$_lang['narrowcasting.player_schedule']							= 'Schedule player';
	$_lang['narrowcasting.player_schedule_create']					= 'Create new schedule';
	$_lang['narrowcasting.player_schedule_update']					= 'Update schedule';
	$_lang['narrowcasting.player_schedule_remove']					= 'Delete schedule';
	$_lang['narrowcasting.player_schedule_remove_confirm']			= 'Are you sure you want to delete this schedule?';
	$_lang['narrowcasting.player_calendar']							= 'Calendar';

	$_lang['narrowcasting.label_player_key']						= 'Key';
	$_lang['narrowcasting.label_player_key_desc']					= 'The key of the player.';
	$_lang['narrowcasting.label_player_name']						= 'Name';
	$_lang['narrowcasting.label_player_name_desc']					= 'The name of the player.';
	$_lang['narrowcasting.label_player_description']				= 'Description';
	$_lang['narrowcasting.label_player_description_desc']			= 'A short description of the player.';
	$_lang['narrowcasting.label_player_resolution']					= 'Resolution';
	$_lang['narrowcasting.label_player_resolution_desc']			= 'The resolution of the player (width x height (without spaces)).';
	$_lang['narrowcasting.label_player_mode']						= 'Mode';
	$_lang['narrowcasting.label_player_mode_desc']					= 'The mode of the player (landscape or portrait).';
	$_lang['narrowcasting.label_player_type']						= 'Type';
	$_lang['narrowcasting.label_player_type_desc']					= 'The type of the player.';
	$_lang['narrowcasting.label_player_online']						= 'Online';
	$_lang['narrowcasting.label_player_online_desc']				= '';
	$_lang['narrowcasting.label_player_current_broadcast']			= 'Current broadcast';
	$_lang['narrowcasting.label_player_current_broadcast_desc']		= 'The current broadcast of the player.';
    $_lang['narrowcasting.label_player_next_sync']                  = 'Refresh about';
    $_lang['narrowcasting.label_player_next_sync_desc']             = '';

	$_lang['narrowcasting.slide_type']								= 'Slide type';
	$_lang['narrowcasting.slide_types']								= 'Slide types';
	$_lang['narrowcasting.slide_types_desc']						= 'Here you can manage all the available slide types for your narrowcasting.';
	$_lang['narrowcasting.slide_type_create']						= 'Create new slide type';
	$_lang['narrowcasting.slide_type_update']						= 'Update slide type';
	$_lang['narrowcasting.slide_type_remove']						= 'Delete slide type';
	$_lang['narrowcasting.slide_type_remove_confirm']				= 'Are you sure you want to delete this slide type?';
    $_lang['narrowcasting.slide_type_data']					        = 'Slide types fields';

	$_lang['narrowcasting.label_slide_type_key']					= 'Key';
    $_lang['narrowcasting.label_slide_type_key_desc']				= 'The key of the slide type.';
	$_lang['narrowcasting.label_slide_type_name']					= 'Name';
    $_lang['narrowcasting.label_slide_type_name_desc']				= 'The name of the slide type.';
    $_lang['narrowcasting.label_slide_type_description']			= 'Description';
    $_lang['narrowcasting.label_slide_type_description_desc']		= 'The description of the slide type.';
    $_lang['narrowcasting.label_slide_type_icon']					= 'Icon';
    $_lang['narrowcasting.label_slide_type_icon_desc']				= 'The icon of the slide type.';
    $_lang['narrowcasting.label_slide_type_time']					= 'Duration';
	$_lang['narrowcasting.label_slide_type_time_desc']				= 'The default duration of the slide in seconds or minutes.';
    $_lang['narrowcasting.label_slide_type_data']					= 'Fields';
    $_lang['narrowcasting.label_slide_type_data_desc']				= '';

    $_lang['narrowcasting.slide_type_data_create']					= 'Create field';
    $_lang['narrowcasting.slide_type_data_update']					= 'Update field';
    $_lang['narrowcasting.slide_type_data_remove']					= 'Delete field';
    $_lang['narrowcasting.slide_type_data_remove_confirm']			= 'Are you sure you want to delete this field?';

    $_lang['narrowcasting.label_slide_type_data_key']               = 'Key';
    $_lang['narrowcasting.label_slide_type_data_key_desc']          = 'The key of the field.';
    $_lang['narrowcasting.label_slide_type_data_xtype']             = 'Type';
    $_lang['narrowcasting.label_slide_type_data_xtype_desc']        = 'The xtype of the field.';
    $_lang['narrowcasting.label_slide_type_data_label']             = 'Label';
    $_lang['narrowcasting.label_slide_type_data_label_desc']        = 'The label of the field.';
    $_lang['narrowcasting.label_slide_type_data_description']       = 'Description';
    $_lang['narrowcasting.label_slide_type_data_description_desc']  = 'The description of the field.';
    $_lang['narrowcasting.label_slide_type_data_value']             = 'Value';
    $_lang['narrowcasting.label_slide_type_data_value_desc']        = 'The default value of the field.';

	$_lang['narrowcasting.label_schedule_broadcast']				= 'Broadcast';
	$_lang['narrowcasting.label_schedule_broadcast_desc']			= 'The broadcast of the schedule.';
	$_lang['narrowcasting.label_schedule_description']				= 'Description';
	$_lang['narrowcasting.label_schedule_description_desc']			= 'A short description of the schedule.';
	$_lang['narrowcasting.label_schedule_type']						= 'Schedule type';
	$_lang['narrowcasting.label_schedule_type_desc']				= 'The schedule type of the schedule.';
	$_lang['narrowcasting.label_schedule_day']						= 'Day';
	$_lang['narrowcasting.label_schedule_day_desc']					= '';
	$_lang['narrowcasting.label_schedule_entire_day']				= 'Entire day';
	$_lang['narrowcasting.label_schedule_entire_day_desc']			= '';
	$_lang['narrowcasting.label_schedule_start_time']				= 'Begin time';
	$_lang['narrowcasting.label_schedule_start_time_desc']			= '';
	$_lang['narrowcasting.label_schedule_start_date']				= 'Begin date';
	$_lang['narrowcasting.label_schedule_start_date_desc']			= '';
	$_lang['narrowcasting.label_schedule_end_time']					= 'End time';
	$_lang['narrowcasting.label_schedule_end_time_desc']			= '';
	$_lang['narrowcasting.label_schedule_end_date']					= 'End date';
	$_lang['narrowcasting.label_schedule_end_date_desc']			= '';
	$_lang['narrowcasting.label_schedule_date']						= 'Date';
	$_lang['narrowcasting.label_schedule_date_dec']					= '';

	$_lang['narrowcasting.default_view']							= 'Default view';
	$_lang['narrowcasting.admin_view']								= 'Admin view';
	$_lang['narrowcasting.auto_refresh_grid']						= 'Auto refresh';
	$_lang['narrowcasting.filter_broadcast']						= 'Filter on broadcast';
	$_lang['narrowcasting.show_broadcast_preview']					= 'Show broadcast preview';
	$_lang['narrowcasting.sync_never']								= 'Never';
	$_lang['narrowcasting.selected_slides']							= 'Selected slides';
	$_lang['narrowcasting.available_slides']						= 'Available slides';
	$_lang['narrowcasting.slide_more_options']						= 'Select a slide type for more options.';
	$_lang['narrowcasting.portrait']								= 'Portrait';
	$_lang['narrowcasting.landscape']								= 'Landscape';
	$_lang['narrowcasting.schedule_day']							= 'Day';
	$_lang['narrowcasting.schedule_date']							= 'Date';
	$_lang['narrowcasting.schedule_time_format_entire_day']			= '(entire day)';
	$_lang['narrowcasting.schedule_time_format_set']				= '(from [[+start_time]] till [[+end_time]])';
	$_lang['narrowcasting.schedule_date_format_set']				= '[[+start_date]] till [[+end_date]]';
	$_lang['narrowcasting.schedule_date_format_set_long']			= '[[+start_date]] (van [[+start_time]]) tot [[+end_date]] (tot [[+end_time]])';
	$_lang['narrowcasting.error_broadcast_sync']					= 'An error has occurred during synchronizing the broadcast.';
	$_lang['narrowcasting.error_broadcast_player_exists']			= 'This player is already connected to this broadcast. Specify another mediabox.';
	$_lang['narrowcasting.error_broadcast_schedule_exists']			= 'There is already a schedule for this day: [[+schedule]]. Specify another schedule.';
	$_lang['narrowcasting.error_player_resolution']					= 'Not a valid resolution (width x height (without spaces)).';
	$_lang['narrowcasting.error_resource_object']					= 'An error has occurred during saving the broadcast resource.';
    $_lang['narrowcasting.error_slide_type_character']				= 'The key contains forbidden characters. Please specify another key.';
    $_lang['narrowcasting.error_slide_type_exists']					= 'A slide type with this key already exists. Please specify another key.';
    $_lang['narrowcasting.error_slide_type_not_exists']				= 'A slide type with this key doest not exists.';
    $_lang['narrowcasting.error_slide_type_data_character']			= 'The key contains forbidden characters. Please specify another key.';
    $_lang['narrowcasting.error_slide_type_date_exists']			= 'A field with this key already exists. Please specify another key.';
    $_lang['narrowcasting.broadcast_name_duplicate']                = 'Copy from [[+name]]';
    $_lang['narrowcasting.slide_name_duplicate']                    = 'Copy from [[+name]]';
    $_lang['narrowcasting.next_sync']                               = '[[+time]] minutes';
    $_lang['narrowcasting.preview_resolution']                      = ' (resolutie [[+resolution]])';

?>