/* Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon */
function responsive_menu_account() {
    var x = document.getElementById("responsive-account-menu");
    if (x.className === "menu-account") {
        x.className += " responsive";
    } else {
        x.className = "menu-account";
    }
}

function responsive_search_bar() {
    var x = document.getElementById("search-form");
    if (x.className === "panel-responsive") {
        x.className += " responsive-bar";
    } else {
        x.className = "panel-responsive";
    }
}

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
