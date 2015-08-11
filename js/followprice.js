jQuery(document).ready( function ( $ ) {

	var followpriceEnv = phpVars.followpriceEnv;

	function fpShowSection (section) {
		$( '.fp-wrap' ).hide();
		$( '.fp-button-header' ).removeClass("fp-selected");
		$( '.fp-selected-line' ).remove();
		$( section ).addClass("fp-selected");
		$( section ).after("<div class='fp-selected-line'></div>");
		if (section == '.fp-button-first') {
			$( '.fp-dashboard-wrap' ).show();
		} else {
			$( '.fp-settings-wrap' ).show();
		}
	}


	if (phpVars.activatedCount==1) {
		fpShowSection(".fp-button-second");
	} else {
		if (sessionStorage.fpMenuPosition == 1) {
			fpShowSection(".fp-button-second");
		} else {
			fpShowSection(".fp-button-first");
		}

	}


	$( '.fp-button-header' ).click( function(){
		if ($(this).hasClass("fp-button-first")) {
			sessionStorage.fpMenuPosition = 0;
			fpShowSection(".fp-button-first");
		} else if ($(this).hasClass("fp-button-second")) {
			sessionStorage.fpMenuPosition = 1;
			fpShowSection(".fp-button-second");
		};
	});

	function noRefreshSave(formId) {
		var c = 0;
		$(formId).trigger( "submit" );
		$(formId).submit(function(event) {
			if (c==0) {
				var b =  $(this).serialize();
				event.preventDefault();
				$.post( 'options.php', b ).error( function() {
		            if ($( ".fp-validation-status" ).length) { $( ".fp-validation-status" ).remove();}
		            $( ".fp-validation-container" ).append("<span class='fp-validation-status dashicons dashicons-yes'></span>");
		        }).success( function() {
		            if ($( ".fp-validation-status" ).length) { $( ".fp-validation-status" ).remove();}
		            $( ".fp-validation-container" ).append("<span class='fp-validation-status dashicons dashicons-yes'></span>");
		        });
	    	}
	        c=c+1;
	    });
	};

	//Key Validation
	var isValid = false;
	$( "#key" ).keydown(function(){
		if ($( ".fp-validation-status.dashicons-no" ).length) { $( ".fp-validation-status.dashicons-no" ).remove();}
	});


	$( '#key-submit' ).click( function(event){
		if (!isValid) {
			event.preventDefault();
		} else {
			isValid = false;
			// If key is on settings page, save without refresh
			if ( $('#key-submit').hasClass('fp-settings-key') ) {
				noRefreshSave('#fp-settings-key');
			};
			return true;
		}

		if ($( ".fp-validation-status" ).length) { $( ".fp-validation-status" ).remove();}

		var storeKey = $( "#key" ).val();

		var siteUrl = phpVars.siteUrl;

		var apiUrl = followpriceEnv + '/api/v1'

		function activateFail(message) {
			if ($( ".fp-validation-status" ).length) { $( ".fp-validation-status" ).remove();}
			if (phpVars.fpActivated==false) {
				if (!$( ".error" ).length) {
					$( ".fp-title-div" ).after( "<div style='display:none;' id='message' class='error'><p><strong>"+message+"</strong></p></div>" );
				}
				$( ".error" ).hide().fadeIn();
			};
			$( ".fp-validation-container" ).append("<span class='fp-validation-status dashicons dashicons-no'></span>");
		};

		$.ajax({
			url: apiUrl + '/stores/' + encodeURIComponent(siteUrl) + '/validate/' + encodeURIComponent(storeKey),
			type: 'GET',
			timeout: 7000,
			beforeSend: function() {
				$( ".fp-validation-container" ).append("<span class='fp-validation-status dashicons dashicons-update'></span>");
				$( ".fp-validation-status.dashicons-update" ).hide().fadeIn();
			},
			error: function(x, t, m) {
				if(t==="timeout") {
					activateFail("Connection timeout. Please try again.");
				} else {
					activateFail("An error occurred. Please try again.");
				}
			},
			success: function (rsp) {

				if (rsp && rsp.success && rsp.data) {
					//If response successful
					isValid = true;
					if ($( ".fp-validation-status" ).length) { $( ".fp-validation-status" ).remove();}
					$( ".fp-validation-container" ).append("<span class='fp-validation-status dashicons dashicons-yes'></span>");
					$( "#key-submit" ).trigger( "click" );
				} else {
					activateFail("Key Validation Failed");
				}
			}
		})
	})

	$( ".fp-block-toggle" ).click( function(){
		$( ".fp-block-key" ).show();
		$('html, body').scrollTop( $(document).height() );
	});
	
})