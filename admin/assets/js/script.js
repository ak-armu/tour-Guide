var dltPlaces = document.querySelectorAll( '.dlt-place' );

dltPlaces.forEach( function( val )
{
	val.addEventListener( 'click', function( e )
	{
		if ( confirm( 'Do you want to delete the place?' ) )
		{
			e.target.parentElement.submit();
		}
	} );
} );

var places = document.getElementById( 'places' );

places.addEventListener( 'change', function( e )
{
	e.target.parentElement.submit();
} );

var map;

function showMap( startlat, startlon )
{
	document.getElementById( 'map' ).style.display = 'block';
	
	var options = {
		center: [startlat, startlon],
		zoom: 15
	}

	map = L.map( 'map', options );
	
	var nzoom = 30;

	L.tileLayer( '//{s}.tile.osm.org/{z}/{x}/{y}.png', { attribution: 'OSM' } ).addTo( map );

	var myMarker = L.marker( [startlat, startlon], { title: "Coordinates", alt: "Coordinates", draggable: true } ).addTo( map ).on( 'dragend', function()
	{
		var lat = myMarker.getLatLng().lat.toFixed(8);

		var lon = myMarker.getLatLng().lng.toFixed(8);

		var czoom = map.getZoom();
		
		if( czoom < 18 ) { nzoom = czoom + 2; }
		
		if( nzoom > 18 ) { nzoom = 18; }
		
		if( czoom != 18 ) { map.setView( [lat, lon], nzoom ); } else { map.setView( [lat, lon] ); }
		
		myMarker.bindPopup( "Lat " + lat + "<br />Lon " + lon ).openPopup();
	} );
}

function destroy_map()
{
	map.eachLayer( function( layer )
	{
        layer.remove();
    } );

	map.remove();

    map = null;

    document.getElementById( 'map' ).style.display = 'none';
}

function get_coordinates_from_address( $address )
{
	var xmlhttp = new XMLHttpRequest();

	var url = "//nominatim.openstreetmap.org/search?format=json&limit=3&q=" + $address;
 	
 	xmlhttp.onreadystatechange = function()
 	{
   		if ( this.readyState == 4 && this.status == 200 )
   		{
		    var result = JSON.parse( this.responseText );
		    
		    showMap( result[0].lat, result[0].lon );
   		}
 	}
 	
 	xmlhttp.open( "GET", url, true );
 	
 	xmlhttp.send();
}

var mapModal = document.querySelectorAll( '.mapModal' );

mapModal.forEach( function( val )
{
	val.addEventListener( 'click', function( e )
	{
		get_coordinates_from_address( e.target.dataset.place );
	} );
} );

document.getElementById( 'mapModal' ).addEventListener( 'hide.bs.modal', function( e )
{
	destroy_map();
} );

var selectPlace = document.querySelectorAll( '.selectPlace' );

var selectedPlaces = [];

selectPlace.forEach( function( val )
{
	val.addEventListener( 'click', function( e )
	{
		var place_id = e.target.dataset.place_id;

		if ( selectedPlaces.includes( place_id ) )
		{
			e.target.innerText = 'Select';

			var index = selectedPlaces.indexOf( place_id );

			if( index != -1 )
			{
			   selectedPlaces.splice( index, 1 );
			}
		}
		else
		{
			selectedPlaces.push( place_id );

			e.target.innerText = 'Selected';
		}
	} );
} );

var createPlan = document.querySelectorAll( '.createPlan' );

var places = [];

createPlan.forEach( function( val )
{
	val.addEventListener( 'click', function( e )
	{
		if ( selectedPlaces.length < 1 )
		{
			alert( 'Please select atleast one place first.' ); return;
		}

		var createPlanModal = new bootstrap.Modal( document.getElementById( 'createPlanModal' ),
		{
			keyboard: false
		} );

		if ( selectedPlaces.length )
		{
			var xmlhttp = new XMLHttpRequest();

			var url = "/Tourist-Guide/includes/getPlace.php?places=" + selectedPlaces.join( ',' );
		 	
		 	xmlhttp.onreadystatechange = function()
		 	{
		   		if ( this.readyState == 4 && this.status == 200 )
		   		{
				    places = JSON.parse( this.responseText );
		   		}
		 	}
		 	
		 	xmlhttp.open( "GET", url, true );
		 	
		 	xmlhttp.send();
		}

		createPlanModal.show();
	} );
} );

document.getElementById( 'DaysFormControlInput' ).addEventListener( 'input', function( e )
{
	var days = parseInt( e.target.value );

	var out = '<div class="row">';

	var placeNames = '';

	if ( places.length )
	{
		for ( var j = 0; j < places.length; j++ )
		{
			placeNames += '<div class="form-check"><input class="form-check-input checkedPlaces" type="checkbox" value="'+ places[j].id +'"><label class="form-check-label">' + places[j].name + '</label></div>';
		}
	}

	if ( ! isNaN( days ) )
	{
		for ( var i = 0; i < days; i++ )
		{
			var tmp = i + 1;
			
			out += '<div class="col" id="day_' + i + '"><div class="card"><div class="card-body"><h5 class="card-title">Day ' + tmp + '</h5>' + placeNames + '</div></div></div>';
		}
	}

	out += '</div>';

	document.getElementById( 'planForDays' ).innerHTML = out + '<input type="hidden" id="totalDays" value=" ' + tmp + ' ">';
} );

document.getElementById( 'createPlanBtn' ).addEventListener( 'click', function( e )
{
	var days = parseInt( document.getElementById( 'totalDays' ).value );

	var data = {};

	if ( ! isNaN( days ) )
	{
		var checkedPlaceIds = [];
		
		for ( var i = 0; i < days; i++ )
		{
			var checkedPlaces = document.querySelectorAll( '#day_' + i + ' input[type=checkbox]:checked' );

			for ( var j = 0; j < checkedPlaces.length; j++ )
			{
				checkedPlaceIds.push( { day: i + 1, val: checkedPlaces[j].value } );
			}
		}
	}

	data.days = days;

	data.daysPlans = checkedPlaceIds;

	var xmlhttp = new XMLHttpRequest();

	var url = "/Tourist-Guide/includes/createPlan.php";
 	
 	xmlhttp.onreadystatechange = function()
 	{
   		if ( this.readyState == 4 && this.status == 200 )
   		{
		    response = JSON.parse( this.responseText );

		    alert( response.message ); window.location.reload();
   		}
 	}
 	
 	xmlhttp.open( "POST", url, true );

 	xmlhttp.send( JSON.stringify( Object.assign( {}, data ) ) );

} );