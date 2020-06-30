jQuery( function ( $ ) {
	'use strict';

	var wrapper = $( '.demo-browser' ),
        running = false;
    
    if ( ! wrapper.length ) return;
    
    /**
     * RUN IMPORT
     * args is neccessary args needed to run importings
     * item is a jQuery selector of wrap
     * @since 4.0
     */
    var run_import = function( args ) {
        
        // if running
        if ( running ) return false;
        
        if ( ! args.slug ) return;
        
        // if it's not in predefined demo
        if ( ! FOX_IMPORT.demos[ args.slug ] ) return;
        
        /**
         * set up new data
         */
        var data = new FormData();
        
        data.append( 'action', 'ocdi_import_demo_data' );
		data.append( 'security', FOX_IMPORT.ajax_nonce );
		data.append( 'selected', args.slug );
        data.append( 'import', args.import );
        
        demoAjaxCall( data );
        
        // add active class to the demo item being imported
        $( '.demo[data-demo="' + args.slug + '"]' ).addClass( 'active' );
        
	}
    
    function start_loading() {
        
        /**
         * set up loading environment
         */
        wrapper.addClass( 'loading' );
        running = true;
        
    }
    
    function end_loading() {
    
        /**
         * set up loading environment
         */
        wrapper
        .removeClass( 'loading' )
        .find( '.demo' )
        .removeClass( 'active' );
        running = false;
        
    }
    
    function show_message( msg ) {
        
        wrapper.find( '.message' ).html( msg );
        
    }
    
    /**
     * AJAX CALL DATA
     */
    function demoAjaxCall( data ) {
        
        $.ajax({
			method:      'POST',
			url:         FOX_IMPORT.ajax_url,
			data:        data,
			contentType: false,
			processData: false,
			beforeSend:  function() {
                
                start_loading();
                
			}
		})
		.done( function( response ) {
            
            console.log( response );
            
			if ( 'undefined' !== typeof response.status && 'newAJAX' === response.status ) {
				demoAjaxCall( data );
			}
            
			else if ( 'undefined' !== typeof response.status && 'customizerAJAX' === response.status ) {
				// Fix for data.set and data.delete, which they are not supported in some browsers.
				var newData = new FormData();
				newData.append( 'action', 'ocdi_import_customizer_data' );
				newData.append( 'security', FOX_IMPORT.ajax_nonce );

				demoAjaxCall( newData );
			}
			else if ( 'undefined' !== typeof response.status && 'afterAllImportAJAX' === response.status ) {
				// Fix for data.set and data.delete, which they are not supported in some browsers.
				var newData = new FormData();
				newData.append( 'action', 'ocdi_after_import_data' );
				newData.append( 'security', FOX_IMPORT.ajax_nonce );
				demoAjaxCall( newData );
			}
			else if ( 'undefined' !== typeof response.message ) {
                
				show_message( response.message );
				end_loading();

				// Trigger custom event, when OCDI import is complete.
				$( document ).trigger( 'ocdiImportComplete' );
			}
			else {
                
                show_message( response );
                end_loading();
                
			}
		})
		.fail( function( error ) {
            
            console.error( error );
            
            show_message( error.statusText + ' (' + error.status + ')' );
            end_loading();
            
		});
        
    }
    
    /**
     * Start Binding Events
     */
    $( document ).on( 'click', '.fox-import-btn', function( e ) {
        var btn = $( this ),
            item = btn.closest( '.demo' );
        e.preventDefault();
        var args = {
            import: btn.data( 'import' ),
            slug: btn.data( 'slug' )
        }
        
        displayConfirmationPopup( args, item );
        
    });

	/**
	 * No or Single predefined demo import button click.
	 */
	$( '.js-ocdi-import-data' ).on( 'click', function () {

		// Reset response div content.
		$( '.js-ocdi-ajax-response' ).empty();

		// Prepare data for the AJAX call
		var data = new FormData();
		data.append( 'action', 'ocdi_import_demo_data' );
		data.append( 'security', ocdi.ajax_nonce );
		data.append( 'selected', $( '#ocdi__demo-import-files' ).val() );
		if ( $('#ocdi__content-file-upload').length ) {
			data.append( 'content_file', $('#ocdi__content-file-upload')[0].files[0] );
		}
		if ( $('#ocdi__widget-file-upload').length ) {
			data.append( 'widget_file', $('#ocdi__widget-file-upload')[0].files[0] );
		}
		if ( $('#ocdi__customizer-file-upload').length ) {
			data.append( 'customizer_file', $('#ocdi__customizer-file-upload')[0].files[0] );
		}
		if ( $('#ocdi__redux-file-upload').length ) {
			data.append( 'redux_file', $('#ocdi__redux-file-upload')[0].files[0] );
			data.append( 'redux_option_name', $('#ocdi__redux-option-name').val() );
		}

		// AJAX call to import everything (content, widgets, before/after setup)
		demoAjaxCall( data );

	});


	/**
	 * Grid Layout import button click.
	 */
	$( '.js-ocdi-gl-import-data' ).on( 'click', function () {
		var selectedImportID = $( this ).val();
		var $itemContainer   = $( this ).closest( '.js-ocdi-gl-item' );

		// If the import confirmation is enabled, then do that, else import straight away.
		if ( ocdi.import_popup ) {
			displayConfirmationPopup( selectedImportID, $itemContainer );
		}
		else {
			gridLayoutImport( selectedImportID, $itemContainer );
		}
	});


	/**
	 * Grid Layout categories navigation.
	 */
	(function () {
		// Cache selector to all items
		var $items = $( '.js-ocdi-gl-item-container' ).find( '.js-ocdi-gl-item' ),
			fadeoutClass = 'ocdi-is-fadeout',
			fadeinClass = 'ocdi-is-fadein',
			animationDuration = 200;

		// Hide all items.
		var fadeOut = function () {
			var dfd = jQuery.Deferred();

			$items
				.addClass( fadeoutClass );

			setTimeout( function() {
				$items
					.removeClass( fadeoutClass )
					.hide();

				dfd.resolve();
			}, animationDuration );

			return dfd.promise();
		};

		var fadeIn = function ( category, dfd ) {
			var filter = category ? '[data-categories*="' + category + '"]' : 'div';

			if ( 'all' === category ) {
				filter = 'div';
			}

			$items
				.filter( filter )
				.show()
				.addClass( 'ocdi-is-fadein' );

			setTimeout( function() {
				$items
					.removeClass( fadeinClass );

				dfd.resolve();
			}, animationDuration );
		};

		var animate = function ( category ) {
			var dfd = jQuery.Deferred();

			var promise = fadeOut();

			promise.done( function () {
				fadeIn( category, dfd );
			} );

			return dfd;
		};

		$( '.js-ocdi-nav-link' ).on( 'click', function( event ) {
			event.preventDefault();

			// Remove 'active' class from the previous nav list items.
			$( this ).parent().siblings().removeClass( 'active' );

			// Add the 'active' class to this nav list item.
			$( this ).parent().addClass( 'active' );

			var category = this.hash.slice(1);

			// show/hide the right items, based on category selected
			var $container = $( '.js-ocdi-gl-item-container' );
			$container.css( 'min-width', $container.outerHeight() );

			var promise = animate( category );

			promise.done( function () {
				$container.removeAttr( 'style' );
			} );
		} );
	}());


	/**
	 * Grid Layout search functionality.
	 */
	$( '.js-ocdi-gl-search' ).on( 'keyup', function( event ) {
		if ( 0 < $(this).val().length ) {
			// Hide all items.
			$( '.js-ocdi-gl-item-container' ).find( '.js-ocdi-gl-item' ).hide();

			// Show just the ones that have a match on the import name.
			$( '.js-ocdi-gl-item-container' ).find( '.js-ocdi-gl-item[data-name*="' + $(this).val().toLowerCase() + '"]' ).show();
		}
		else {
			$( '.js-ocdi-gl-item-container' ).find( '.js-ocdi-gl-item' ).show();
		}
	} );

	/**
	 * ---------------------------------------
	 * --------Helper functions --------------
	 * ---------------------------------------
	 */

	/**
	 * Prepare grid layout import data and execute the AJAX call.
	 *
	 * @param int selectedImportID The selected import ID.
	 * @param obj $itemContainer The jQuery selected item container object.
	 */
	function gridLayoutImport( selectedImportID, $itemContainer ) {
		// Reset response div content.
		$( '.js-ocdi-ajax-response' ).empty();

		// Hide all other import items.
		$itemContainer.siblings( '.js-ocdi-gl-item' ).fadeOut( 500 );

		$itemContainer.animate({
			opacity: 0
		}, 500, 'swing', function () {
			$itemContainer.animate({
				opacity: 1
			}, 500 )
		});

		// Hide the header with category navigation and search box.
		$itemContainer.closest( '.js-ocdi-gl' ).find( '.js-ocdi-gl-header' ).fadeOut( 500 );

		// Append a title for the selected demo import.
		$itemContainer.parent().prepend( '<h3>' + ocdi.texts.selected_import_title + '</h3>' );

		// Remove the import button of the selected item.
		$itemContainer.find( '.js-ocdi-gl-import-data' ).remove();

		// Prepare data for the AJAX call
		var data = new FormData();
		data.append( 'action', 'ocdi_import_demo_data' );
		data.append( 'security', ocdi.ajax_nonce );
		data.append( 'selected', selectedImportID );

		// AJAX call to import everything (content, widgets, before/after setup)
		demoAjaxCall( data );
	}

	/**
	 * Display the confirmation popup.
	 *
	 * @param int selectedImportID The selected import ID.
	 * @param obj $itemContainer The jQuery selected item container object.
	 */
	function displayConfirmationPopup( args, item ) {
        
		var $dialogContiner         = $( '#js-ocdi-modal-content' );
		var currentFilePreviewImage = FOX_IMPORT.demos[ args.slug ]['image'] || '';
		var previewImageContent     = '';
		var importNotice            = FOX_IMPORT.demos[ args.slug ]['notice'] || '';
		var importNoticeContent     = '';
		var dialogOptions           = $.extend(
			{
				'dialogClass': 'wp-dialog',
				'resizable':   false,
				'height':      'auto',
				'modal':       true
			},
			FOX_IMPORT.dialog_options,
			{
				'buttons':
				[
					{
						text: FOX_IMPORT.texts.dialog_no,
						click: function() {
							$(this).dialog('close');
						}
					},
					{
						text: FOX_IMPORT.texts.dialog_yes,
						class: 'button  button-primary',
						click: function() {
							$(this).dialog('close');
                            run_import( args );
						}
					}
				]
			});
        
        // in case we only import settings
        if ( args.import == 'settings' ) {
            importNotice = FOX_IMPORT.texts.import_settings;
        }

		if ( '' === currentFilePreviewImage ) {
			previewImageContent = '<p>' + FOX_IMPORT.texts.missing_preview_image + '</p>';
		}
		else {
			previewImageContent = '<div class="ocdi__modal-image-container"><img src="' + currentFilePreviewImage + '" /></div>'
		}

		// Prepare notice output.
		if( '' !== importNotice ) {
			importNoticeContent = '<div class="ocdi__modal-notice  ocdi__demo-import-notice">' + importNotice + '</div>';
		}

		// Populate the dialog content.
		$dialogContiner.prop( 'title', FOX_IMPORT.texts.dialog_title );
		$dialogContiner.html(
			'<p class="ocdi__modal-item-title">' + FOX_IMPORT.demos[ args.slug ]['name'] + '</p>' +
			previewImageContent +
			importNoticeContent
		);

		// Display the confirmation popup.
		$dialogContiner.dialog( dialogOptions );
	}
    
} );
