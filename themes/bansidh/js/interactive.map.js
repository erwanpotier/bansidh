    jQuery(document).ready(function() {
      jQuery('#vmap').vectorMap({ map: 'france_fr',
   			hoverOpacity: 0.5,
			hoverColor: "#EC0000",
			backgroundColor: "#ffffff",
			color: "#FACC2E",
			borderColor: "#000000",
			selectedColor: "#EC0000",
			enableZoom: true,
			showTooltip: true,
		    onRegionClick: function(element, code, region) {
            $url = "/bansidh/"+ code +"";
            window.location.replace($url);
            }
		});


	});


