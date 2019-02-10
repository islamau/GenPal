// This will let you use the .remove() function later on
if (!('remove' in Element.prototype)) {
  Element.prototype.remove = function() {
    if (this.parentNode) {
      this.parentNode.removeChild(this);
    }
  };
}

mapboxgl.accessToken = 'pk.eyJ1Ijoib3NoYXVncm8iLCJhIjoiY2pyeTkzdno2MDIwdzN5c2ozZHM2NzNnciJ9.FlfKkErN4C6bYyw9S-kI6Q';
// This adds the map to your page
var map = new mapboxgl.Map({
  // container id specified in the HTML
  container: 'map',
  // style URL
  style: 'mapbox://styles/mapbox/light-v10',
  // initial position in [lon, lat] format
  center: [-79.6986, 43.4690],
  // initial zoom
  zoom: 11
});

var volunteers = {
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [
          -79.709495,
          43.462631
        ]
      },
      "properties": {
        "phoneFormatted": "(647) 807-1903",
        "phone": "6478071903",
        "address": "45 Nadia Pl",
        "city": "Oakville",
        "country": "Canada",
        "name": "Barry Allen",
        "postalCode": "L6H 1K1",
        "state": "ON"
      }
    },
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [
          -79.704868,
          43.498853
        ]
      },
      "properties": {
        "phoneFormatted": "(416) 927-4810",
        "phone": "4169274810",
        "address": "2434 Sylvia Dr",
        "city": "Oakville",
        "country": "Canada",
        "name": "Clark Kent",
        "postalCode": "L6H 0C9",
        "state": "ON"
      }
    },
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [
          -79.726772,
          43.374809
        ]
      },
      "properties": {
        "phoneFormatted": "(647) 822-9823",
        "phone": "6478229823",
        "address": "181 Warner Dr",
        "city": "Oakville",
        "country": "Canada",
        "name": "Bruce Wayne",
        "postalCode": "L6L 6E3",
        "state": "ON"
      }
    },
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [
          -79.698429,
          43.498587
        ]
      },
      "properties": {
        "phoneFormatted": "(905) 153-4050",
        "phone": "9051534050",
        "address": "2409 Bluestream Dr",
        "city": "Oakville",
        "country": "Canada",
        "name": "Diana Prince",
        "postalCode": "L6H 7J8",
        "state": "ON"
      }
    },
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [
          -79.780338,
          43.425616
        ]
      },
      "properties": {
        "phoneFormatted": "(416) 321-4561",
        "phone": "4163214561",
        "address": "3292 Skipton Ln",
        "city": "Oakville",
        "country": "Canada",
        "name": "Arthur Currie",
        "postalCode": "L6M 0K2",
        "state": "ON"
      }
    }
  ]
};

// This adds the data to the map
map.on('load', function (e) {
  // This is where your '.addLayer()' used to be, instead add only the source without styling a layer
  map.addSource("places", {
    "type": "geojson",
    "data": volunteers
  });
  // Initialize the list
  buildLocationList(volunteers);

});

// This is where your interactions with the symbol layer used to be
// Now you have interactions with DOM markers instead
volunteers.features.forEach(function(marker, i) {
  // Create an img element for the marker
  var el = document.createElement('div');
  el.id = "marker-" + i;
  el.className = 'marker';
  // Add markers to the map at all points
  new mapboxgl.Marker(el, {offset: [0, -23]})
      .setLngLat(marker.geometry.coordinates)
      .addTo(map);

  el.addEventListener('click', function(e){
      // 1. Fly to the point
      flyToStore(marker);

      // 2. Close all other popups and display popup for clicked store
      createPopUp(marker);

      // 3. Highlight listing in sidebar (and remove highlight for all other listings)
      var activeItem = document.getElementsByClassName('active');

      e.stopPropagation();
      if (activeItem[0]) {
         activeItem[0].classList.remove('active');
      }

      var listing = document.getElementById('listing-' + i);
      listing.classList.add('active');

  });
});


function flyToStore(currentFeature) {
  map.flyTo({
      center: currentFeature.geometry.coordinates,
      zoom: 15
    });
}

function createPopUp(currentFeature) {
  var popUps = document.getElementsByClassName('mapboxgl-popup');
  if (popUps[0]) popUps[0].remove();


  var popup = new mapboxgl.Popup({closeOnClick: false})
        .setLngLat(currentFeature.geometry.coordinates)
        .setHTML('<h3>Volunteer</h3>' +
          '<h4>' + currentFeature.properties.address + '</h4>')
        .addTo(map);
}


function buildLocationList(data) {
  for (i = 0; i < data.features.length; i++) {
    var currentFeature = data.features[i];
    var prop = currentFeature.properties;

    var listings = document.getElementById('listings');
    var listing = listings.appendChild(document.createElement('div'));
    listing.className = 'item';
    listing.id = "listing-" + i;

    var link = listing.appendChild(document.createElement('a'));
    link.href = '#';
    link.className = 'title';
    link.dataPosition = i;
    link.innerHTML = prop.name;

    var details = listing.appendChild(document.createElement('div'));
    details.innerHTML = prop.city;
    if (prop.phone) {
      details.innerHTML += ' &middot; ' + prop.phoneFormatted;
    }



    link.addEventListener('click', function(e){
      // Update the currentFeature to the store associated with the clicked link
      var clickedListing = data.features[this.dataPosition];

      // 1. Fly to the point
      flyToStore(clickedListing);

      // 2. Close all other popups and display popup for clicked store
      createPopUp(clickedListing);

      // 3. Highlight listing in sidebar (and remove highlight for all other listings)
      var activeItem = document.getElementsByClassName('active');

      if (activeItem[0]) {
         activeItem[0].classList.remove('active');
      }
      this.parentNode.classList.add('active');

    });
  }
}
