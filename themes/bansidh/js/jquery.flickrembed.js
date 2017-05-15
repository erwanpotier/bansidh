/**
 * Function to get images from Flickr
 *
 * @setId The flickr set the images belong to.
 */  
/*function getImgs(setId) {
  var URL = "https://api.flickr.com/services/rest/" +  // Wake up the Flickr API gods.
    "?method=flickr.photosets.getPhotos" +  // Get photo from a photoset. http://www.flickr.com/services/api/flickr.photosets.getPhotos.htm
    "&api_key={{aa967c3dfd87b299a6800f62d6daec5c}}" +  // API key. Get one here: http://www.flickr.com/services/apps/create/apply/
    "&photoset_id=" + setId +  // The set ID.
    "&privacy_filter=1" +  // 1 signifies all public photos.
    "&per_page=12" + // For the sake of this example I am limiting it to 20 photos.
    "&format=json&nojsoncallback=1";  // Er, nothing much to explain here.

  // See the API in action here: http://www.flickr.com/services/api/explore/flickr.photosets.getPhotos
  $.getJSON(URL, function(data){
    $.each(data.photoset.photo, function(i, item){
      // Creating the image URL. Info: http://www.flickr.com/services/api/misc.urls.html
      var img_src = "http://farm" + item.farm + ".static.flickr.com/" + item.server + "/" + item.id + "_" + item.secret + "_m.jpg";
      var img_thumb = $("<img/>").attr("src", img_src).css("margin", "8px")
      $(img_thumb).appendTo(".flickr");
    });
  });
}

$(document).ready(function() {
  getImgs("72157632700264359"); // Call the function!72157678572104561
});
*/
// Pull photos from Flickr
 


(function() {
  var flickrID = '120866877@N03'; // idgettr.com
  var flickerAPI = 'http://api.flickr.com/services/feeds/photos_public.gne?id=' + flickrID + '&size=b&lang=en-us&format=json&jsoncallback=?';
  $.getJSON( flickerAPI, {
    tags: "tremplinbobital",
    tagmode: "any",
    format: "json"
  })
    .done(function( data ) {
    $.each( data.items, function( i, item ) {
      var image = $("<img>").attr( "src", item.media.m.replace('_m', '_z'));
      var post = $("<div class='post'>").append(image).appendTo("#images");
      if ( i === 50 ) {
        return false;
      }
    });
  });
})();