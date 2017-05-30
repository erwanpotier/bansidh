    jQuery(document).ready(function() {
      jQuery('#vmap').vectorMap({ map: 'france_fr',
   			hoverOpacity: 0.5,
			hoverColor: "#EC0000",
			backgroundColor: "#fff4e6",
			color: "#FACC2E",
			borderColor: "#000000",
			selectedColor: "#EC0000",
			enableZoom: false,
			showTooltip: false,
		    onRegionClick: function(element, code, region) {
            $url = "/bansidh/"+ code +"";
            window.location.replace($url);
            }
		});

	});

