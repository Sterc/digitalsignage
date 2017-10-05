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
    $_lang['setting_narrowcasting.branding_url_desc']        		= 'De URL waar de branding knop heen verwijst, indien leeg wordt de branding knop niet getoond.';
    $_lang['setting_narrowcasting.branding_url_help']         		= 'Branding (help)';
    $_lang['setting_narrowcasting.branding_url_help_desc']    		= 'De URL waar de branding help knop heen verwijst, indien leeg wordt de branding help knop niet getoond.';
    $_lang['setting_narrowcasting.auto_create_sync']				= 'Automatisch synchroniseren';
	$_lang['setting_narrowcasting.auto_create_sync_desc']			= 'Automatisch synchroniseren wanneer er geen synchronisatie beschikbaar is.';
	$_lang['setting_narrowcasting.request_resource']				= 'Standaardverzoek';
	$_lang['setting_narrowcasting.request_resource_desc']			= 'De ID van de resource die u als standaardverzoek wilt gebruiken.';
	$_lang['setting_narrowcasting.export_resource']					= 'Exportverzoek';
	$_lang['setting_narrowcasting.export_resource_desc']			= 'De ID van de resource die u als exportverzoek wilt gebruiken.';
    $_lang['setting_narrowcasting.request_param_broadcast']			= 'Uitzending parameter';
    $_lang['setting_narrowcasting.request_param_broadcast_desc']	= 'De naam van de GET-parameter om de uitzending te identificeren.';
	$_lang['setting_narrowcasting.request_param_player']			= 'Mediaspeler parameter';
	$_lang['setting_narrowcasting.request_param_player_desc']		= 'De naam van de GET-parameter om de mediaspeler te identificeren.';
	$_lang['setting_narrowcasting.context']							= 'Context';
	$_lang['setting_narrowcasting.context_desc']					= 'De standaard narrowcasting context.';
	$_lang['setting_narrowcasting.templates']						= 'Templates';
	$_lang['setting_narrowcasting.template_desc']					= 'De beschikbare templates voor een uitzending. Template ID\'s comma gescheiden toevoegen.';
	$_lang['setting_narrowcasting.media_source']					= 'Media source';
	$_lang['setting_narrowcasting.media_source_desc']				= 'De media source die wordt gebruikt voor media bestanden.';
	
	$_lang['narrowcasting.broadcast']								= 'Uitzending';
	$_lang['narrowcasting.broadcasts']								= 'Uitzendingen';
	$_lang['narrowcasting.broadcasts_desc']							= 'Hier kun je alle uitzendingen van je narrowcasting beheren. Wijzigingen die hier en onder slides gedaan worden zijn niet rechtstreeks live te zien, deze kunnen alleen bekeken worden met behulp van de \'uitzending voorbeeld\'. De uitzending moet eerst gesynchroniseerd worden voordat de wijzigingen live te zien zijn, indien er wijzigingen zijn die nog niet gesynchroniseerd zijn wordt dit aangegeven met een <i class="icon icon-exclamation-triangle red"></i>.';
	$_lang['narrowcasting.broadcast_create']						= 'Nieuwe uitzending';
	$_lang['narrowcasting.broadcast_update']						= 'Uitzending wijzigen';
	$_lang['narrowcasting.broadcast_remove']						= 'Uitzending verwijderen';
	$_lang['narrowcasting.broadcast_remove_confirm']				= 'Weet je zeker dat je deze uitzending wilt verwijderen?';
    $_lang['narrowcasting.broadcast_preview']						= 'Uitzending voorbeeld';
    $_lang['narrowcasting.broadcast_sync']							= 'Uitzending data synchroniseren';
    $_lang['narrowcasting.broadcast_sync_confirm']					= 'Weet je zeker dat je deze uitzending data wilt synchroniseren?';
    $_lang['narrowcasting.broadcast_slides']						= 'Slides toevoegen (interne slides)';
    $_lang['narrowcasting.broadcast_slides_desc']					= 'Hier kun je alle slides (interne slides) van de uitzending beheren. Sleep & Drop slides van de "beschikbare slides" naar "geselecteerde slides" om slides aan de uitzending toe te voegen.';
    $_lang['narrowcasting.broadcast_slide_remove']					= 'Verwijder slide';
    $_lang['narrowcasting.broadcast_feeds']							= 'Feeds toevoegen (externe slides)';
    $_lang['narrowcasting.broadcast_feeds_desc']					= 'Hier kun je alle feeds (externe slides) van de uitzending beheren.';
    $_lang['narrowcasting.broadcast_feed_create']					= 'Nieuwe feed';
    $_lang['narrowcasting.broadcast_feed_update']					= 'Feed wijzigen';
    $_lang['narrowcasting.broadcast_feed_remove']					= 'Feed verwijderen';
    $_lang['narrowcasting.broadcast_feed_remove_confirm']			= 'Weet je zeker dat je deze feed wilt verwijderen?';

    $_lang['narrowcasting.label_broadcast_name']					= 'Naam';
    $_lang['narrowcasting.label_broadcast_name_desc']				= 'Naam van de uitzending.';
    $_lang['narrowcasting.label_broadcast_description']				= 'Omschrijving';
    $_lang['narrowcasting.label_broadcast_description_desc']		= 'Korte omschrijving van de uitzending.';
    $_lang['narrowcasting.label_broadcast_template']				= 'Template';
    $_lang['narrowcasting.label_broadcast_template_desc']			= 'De template van de uitzending.';
    $_lang['narrowcasting.label_broadcast_ticker_url']				= 'Ticker URL';
	$_lang['narrowcasting.label_broadcast_ticker_url_desc']			= 'De URL van de feed voor de ticker.';
    $_lang['narrowcasting.label_broadcast_slides']					= 'Slides';
    $_lang['narrowcasting.label_broadcast_slides_desc']				= 'De slides van de uitzending.';
    $_lang['narrowcasting.label_broadcast_feeds']					= 'Feeds';
    $_lang['narrowcasting.label_broadcast_feeds_desc']				= 'De feeds van de uitzending.';
    $_lang['narrowcasting.label_broadcast_players']					= 'Mediaspelers';
    $_lang['narrowcasting.label_broadcast_players_desc']			= 'The mediaboxes to broadcast the broadcast.';
    $_lang['narrowcasting.label_broadcast_last_sync']				= 'Laatste data synchronisatie';
    $_lang['narrowcasting.label_broadcast_last_sync_desc']			= '';
    $_lang['narrowcasting.label_broadcast_preview_player']			= 'Mediaspeler';
    $_lang['narrowcasting.label_broadcast_preview_player_desc']		= 'Selecteer een mediaspeler om een voorbeeld van de uitzending uit te zenden.';

    $_lang['narrowcasting.label_feed_key']							= 'Sleutel';
    $_lang['narrowcasting.label_feed_key_desc']						= 'De sleutel van de feed.';
    $_lang['narrowcasting.label_feed_name']							= 'Naam';
    $_lang['narrowcasting.label_feed_name_desc']					= 'De naam van de feed.';
    $_lang['narrowcasting.label_feed_url']							= 'URL';
    $_lang['narrowcasting.label_feed_url_desc']						= 'De URL van de feed.';
    $_lang['narrowcasting.label_feed_time']							= 'Looptijd';
    $_lang['narrowcasting.label_feed_time_desc']					= 'De looptijd van de feed in seconden of minuten.';
    $_lang['narrowcasting.label_feed_frequency']					= 'Frequentie';
    $_lang['narrowcasting.label_feed_frequency_desc']				= 'De frequentie van de slides in de feed.';
    $_lang['narrowcasting.label_feed_published']					= 'Gepubliceerd';
    $_lang['narrowcasting.label_feed_published_desc']				= '';

    $_lang['narrowcasting.slide']									= 'Slide';
    $_lang['narrowcasting.slides']									= 'Slides';
    $_lang['narrowcasting.slides_desc']								= 'Hier kun je alle slides van je narrowcasting beheren.';
    $_lang['narrowcasting.slide_create']							= 'Nieuwe slide';
    $_lang['narrowcasting.slide_update']							= 'Slide wijzigen';
    $_lang['narrowcasting.slide_duplicate']							= 'Slide dupliceren';
    $_lang['narrowcasting.slide_remove']							= 'Slide verwijderen';
    $_lang['narrowcasting.slide_remove_confirm']					= 'Weet je zeker dat je deze slide wilt verwijderen?';

    $_lang['narrowcasting.label_slide_name']						= 'Titel';
    $_lang['narrowcasting.label_slide_name_desc']					= 'De titel van de slide.';
    $_lang['narrowcasting.label_slide_type']						= 'Type';
    $_lang['narrowcasting.label_slide_type_desc']					= 'Type van de slide.';
    $_lang['narrowcasting.label_slide_time']						= 'Looptijd';
    $_lang['narrowcasting.label_slide_time_desc']					= 'De looptijd van de slide in seconden of minuten.';
    $_lang['narrowcasting.label_slide_published']					= 'Gepubliceerd';
    $_lang['narrowcasting.label_slide_published_desc']				= '';

    $_lang['narrowcasting.player']									= 'Mediaspeler';
    $_lang['narrowcasting.players']									= 'Mediaspelers';
    $_lang['narrowcasting.players_desc']							= 'Hier kun je alle mediaspelers van je narrowcasting systeem beheren. Een mediaspeler heeft standaard een vertraging van 5 minuten. Wijzigingen die hier gedaan worden, zijn dus 5 minuten later live te zien (synchronisatie). Mediaspelers met een <i class="icon icon-circle green"></i> staan momenteel aan en zenden een uitzending uit, mediaspelers met een <i class="icon icon-circle red"></i> staan uit.';
    $_lang['narrowcasting.player_create']							= 'Nieuwe mediaspeler';
    $_lang['narrowcasting.player_update']							= 'Mediaspeler wijzigen';
    $_lang['narrowcasting.player_remove']							= 'Mediaspeler verwijderen';
    $_lang['narrowcasting.player_remove_confirm']					= 'Weet je zeker dat je deze mediaspeler wilt verwijderen?';
    $_lang['narrowcasting.player_restart']                          = 'Mediaspeler herstarten';
    $_lang['narrowcasting.player_restart_confirm']                  = 'Weet je zeker dat je deze mediaspeler wilt herstarten?';
    $_lang['narrowcasting.player_restart_cancel']                   = 'Mediaspeler herstarten annuleren';
    $_lang['narrowcasting.player_restart_cancel_confirm']           = 'Weet je zeker dat je deze mediaspeler herstarten wilt annuleren?';
    $_lang['narrowcasting.player_view']								= 'Mediaspeler verbinden';
    $_lang['narrowcasting.player_view_desc']						= 'Gebruik de onderstaande URL in de browser van je mediaspeler om deze te verbinden met het narrowcasting systeem.';
    $_lang['narrowcasting.player_schedule']							= 'Mediaspeler schema';
    $_lang['narrowcasting.player_schedule_create']					= 'Nieuw schema';
    $_lang['narrowcasting.player_schedule_update']					= 'Schema wijzigen';
    $_lang['narrowcasting.player_schedule_remove']					= 'Scheme verwijderen';
    $_lang['narrowcasting.player_schedule_remove_confirm']			= 'Weet je zeker dat je deze schema wilt verwijderen?';
    $_lang['narrowcasting.player_calendar']							= 'Kalender';

    $_lang['narrowcasting.label_player_key']						= 'Sleutel';
    $_lang['narrowcasting.label_player_key_desc']					= 'De sleutel van de mediaspeler.';
    $_lang['narrowcasting.label_player_name']						= 'Naam';
    $_lang['narrowcasting.label_player_name_desc']					= 'De naam van de mediaspeler.';
    $_lang['narrowcasting.label_player_description']				= 'Omschrijving';
    $_lang['narrowcasting.label_player_description_desc']			= 'Korte omschrijving van de mediaspeler.';
    $_lang['narrowcasting.label_player_resolution']					= 'Resolutie';
    $_lang['narrowcasting.label_player_resolution_desc']			= 'De resolutie van de speler (breedte x hoogte (zonder spaties)).';
    $_lang['narrowcasting.label_player_mode']						= 'Modus';
    $_lang['narrowcasting.label_player_mode_desc']					= 'De modus van de speler (landschap of portret).';
    $_lang['narrowcasting.label_player_type']						= 'Type';
    $_lang['narrowcasting.label_player_type_desc']					= 'De type mediaspeler.';
    $_lang['narrowcasting.label_player_online']						= 'Online';
    $_lang['narrowcasting.label_player_online_desc']				= '';
    $_lang['narrowcasting.label_player_current_broadcast']			= 'Huidige uitzending';
    $_lang['narrowcasting.label_player_current_broadcast_desc']		= 'De huidige uitzending van de mediaspeler.';
    $_lang['narrowcasting.label_player_next_sync']                  = 'Vernieuwd over';
    $_lang['narrowcasting.label_player_next_sync_desc']             = '';

    $_lang['narrowcasting.slide_type']								= 'Slide type';
	$_lang['narrowcasting.slide_types']								= 'Slide types';
	$_lang['narrowcasting.slide_types_desc']						= 'Hier kun je alle beschikbare slides van je narrowcasting beheren.';
	$_lang['narrowcasting.slide_type_create']						= 'Nieuwe slide type';
	$_lang['narrowcasting.slide_type_update']						= 'Slide type wijzigen';
	$_lang['narrowcasting.slide_type_remove']						= 'Slide type verwijderen';
	$_lang['narrowcasting.slide_type_remove_confirm']				= 'Weet je zeker dat je deze slide type wilt verwijderen?';
	$_lang['narrowcasting.slide_type_data']					        = 'Slide type velden';

	$_lang['narrowcasting.label_slide_type_key']					= 'Sleutel';
    $_lang['narrowcasting.label_slide_type_key_desc']				= 'De sleutel van de slide type.';
	$_lang['narrowcasting.label_slide_type_name']					= 'Naam';
    $_lang['narrowcasting.label_slide_type_name_desc']				= 'De naam van de slide type.';
    $_lang['narrowcasting.label_slide_type_description']			= 'Omschrijving';
    $_lang['narrowcasting.label_slide_type_description_desc']		= 'De omschrijving van de slide type.';
    $_lang['narrowcasting.label_slide_type_icon']					= 'Icoon';
    $_lang['narrowcasting.label_slide_type_icon_desc']				= 'De icoon van de slide type.';
    $_lang['narrowcasting.label_slide_type_time']					= 'Looptijd';
	$_lang['narrowcasting.label_slide_type_time_desc']				= 'De standaard looptijd van de slide in seconden of minuten.';
    $_lang['narrowcasting.label_slide_type_data']					= 'Velden';
    $_lang['narrowcasting.label_slide_type_data_desc']				= '';

    $_lang['narrowcasting.slide_type_data_create']					= 'Nieuw veld';
    $_lang['narrowcasting.slide_type_data_update']					= 'Veld wijzigen';
    $_lang['narrowcasting.slide_type_data_remove']					= 'Veld verwijderen';
    $_lang['narrowcasting.slide_type_data_remove_confirm']			= 'Weet je zeker dat je dit veld wilt verwijderen?';

    $_lang['narrowcasting.label_slide_type_data_key']               = 'Sleutel';
    $_lang['narrowcasting.label_slide_type_data_key_desc']          = 'De sleutel van het veld.';
    $_lang['narrowcasting.label_slide_type_data_xtype']             = 'Type';
    $_lang['narrowcasting.label_slide_type_data_xtype_desc']        = 'De xtype van het veld.';
    $_lang['narrowcasting.label_slide_type_data_label']             = 'Label';
    $_lang['narrowcasting.label_slide_type_data_label_desc']        = 'De label van het veld.';
    $_lang['narrowcasting.label_slide_type_data_description']       = 'Omschrijving';
    $_lang['narrowcasting.label_slide_type_data_description_desc']  = 'De omschrijving van het veld.';
    $_lang['narrowcasting.label_slide_type_data_value']             = 'Waarde';
    $_lang['narrowcasting.label_slide_type_data_value_desc']        = 'De standaard waarde van het veld.';

    $_lang['narrowcasting.label_schedule_broadcast']				= 'Uitzending';
    $_lang['narrowcasting.label_schedule_broadcast_desc']			= 'De uitzending van het schema.';
    $_lang['narrowcasting.label_schedule_description']				= 'Omschrijving';
    $_lang['narrowcasting.label_schedule_description_desc']			= 'Korte omschrijving van het schema.';
    $_lang['narrowcasting.label_schedule_type']						= 'Schema type';
    $_lang['narrowcasting.label_schedule_type_desc']				= 'Type van het schema.';
    $_lang['narrowcasting.label_schedule_day']						= 'Dag';
    $_lang['narrowcasting.label_schedule_day_desc']					= '';
    $_lang['narrowcasting.label_schedule_entire_day']				= 'Hele dag';
    $_lang['narrowcasting.label_schedule_entire_day_desc']			= '';
    $_lang['narrowcasting.label_schedule_start_time']				= 'Begintijd';
    $_lang['narrowcasting.label_schedule_start_time_desc']			= '';
    $_lang['narrowcasting.label_schedule_start_date']				= 'Begindatum';
    $_lang['narrowcasting.label_schedule_start_date_desc']			= '';
    $_lang['narrowcasting.label_schedule_end_time']					= 'Eindtijd';
    $_lang['narrowcasting.label_schedule_end_time_desc']			= '';
    $_lang['narrowcasting.label_schedule_end_date']					= 'Einddatum';
    $_lang['narrowcasting.label_schedule_end_date_desc']			= '';
    $_lang['narrowcasting.label_schedule_date']						= 'Datum';
    $_lang['narrowcasting.label_schedule_date_dec']					= '';

	$_lang['narrowcasting.default_view']							= 'Standaard weergave';
	$_lang['narrowcasting.admin_view']								= 'Admin weergave';
    $_lang['narrowcasting.auto_refresh_grid']						= 'Automatisch vernieuwen';
    $_lang['narrowcasting.filter_broadcast']						= 'Filter op uitzending';
    $_lang['narrowcasting.show_broadcast_preview']					= 'Uitzending voorbeeld bekijken';
    $_lang['narrowcasting.sync_never']								= 'Nooit';
    $_lang['narrowcasting.selected_slides']							= 'Geselecteerde slides';
    $_lang['narrowcasting.available_slides']						= 'Beschikbare slides';
    $_lang['narrowcasting.slide_more_options']						= 'Selecteer een slide type voor meer opties.';
    $_lang['narrowcasting.portrait']								= 'Portret';
    $_lang['narrowcasting.landscape']								= 'Landschap';
    $_lang['narrowcasting.schedule_day']							= 'Dag';
    $_lang['narrowcasting.schedule_date']							= 'Datum';
    $_lang['narrowcasting.schedule_time_format_entire_day']			= '(hele dag)';
    $_lang['narrowcasting.schedule_time_format_set']				= '(van [[+start_time]] tot [[+end_time]])';
    $_lang['narrowcasting.schedule_date_format_set']				= '[[+start_date]] tot [[+end_date]]';
    $_lang['narrowcasting.schedule_date_format_set_long']			= '[[+start_date]] (van [[+start_time]]) tot [[+end_date]] (tot [[+end_time]])';
    $_lang['narrowcasting.error_broadcast_sync']					= 'Er is een fout opgetreden tijdens het synchroniseren van de uitzending.';
    $_lang['narrowcasting.error_broadcast_player_exists']			= 'Deze mediabox is al gekoppeld met deze uitzending. Kies een andere mediabox.';
    $_lang['narrowcasting.error_broadcast_schedule_exists']			= 'Er is al een schema voor deze dag: [[+schedule]]. Geef een ander schema op.';
    $_lang['narrowcasting.error_player_resolution']					= 'Niet een geldige resolutie (breedte x hoogte (zonder spaties)).';
	$_lang['narrowcasting.error_resource_object']					= 'Er is een fout opgetreden tijdens het opslaan van de uitzending resource.';
	$_lang['narrowcasting.error_slide_type_character']				= 'De sleutel bevat niet toegestane tekens. Definieer een andere sleutel.';
	$_lang['narrowcasting.error_slide_type_exists']					= 'Een slide type met deze sleutel bestaat reeds. Definieer een andere sleutel.';
    $_lang['narrowcasting.error_slide_type_not_exists']				= 'Een slide type met deze sleutel bestaat niet.';
    $_lang['narrowcasting.error_slide_type_data_character']			= 'De sleutel bevat niet toegestane tekens. Definieer een andere sleutel.';
    $_lang['narrowcasting.error_slide_type_date_exists']			= 'Een veld met deze sleutel bestaat reeds. Definieer een andere sleutel';
    $_lang['narrowcasting.slide_name_duplicate']                    = 'Duplicaat van [[+name]]';
	$_lang['narrowcasting.next_sync']                               = '[[+time]] minuten';
    $_lang['narrowcasting.preview_resolution']                      = ' (resolutie [[+resolution]])';

?>