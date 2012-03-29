package com.bator.nfz.dlsl;

import java.net.URL;
import java.net.URLConnection;
import java.net.URLEncoder;
import java.text.MessageFormat;

import org.apache.commons.io.IOUtils;

import android.graphics.Canvas;
import android.graphics.drawable.Drawable;
import android.os.Bundle;
import android.widget.Toast;

import com.bator.nfz.dlsl.util.ActivityUtil;
import com.google.android.maps.GeoPoint;
import com.google.android.maps.ItemizedOverlay;
import com.google.android.maps.MapActivity;
import com.google.android.maps.MapView;
import com.google.android.maps.OverlayItem;
import com.google.gson.Gson;

public class MapsActivity extends MapActivity {
	public static final String ADDRESS_KEY = "ADDRESS_KEY";
	public static final String ADDRESS_NAME = "ADDRESS_NAME";
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.map_view);
		final MapView mapView = (MapView) findViewById(R.id.mapView);
		final Drawable itemMarker = getResources().getDrawable(R.drawable.marker);
		mapView.setBuiltInZoomControls(true);
		Runnable r = new Runnable() {
			public void run() {
				try {
					String urlstr = "http://maps.googleapis.com/maps/api/geocode/json?address="+URLEncoder.encode(getIntent().getStringExtra(ADDRESS_KEY))+"&sensor=true";
					urlstr = MessageFormat.format(urlstr, URLEncoder.encode(getIntent().getStringExtra(ADDRESS_KEY)));
					URL url = new URL(urlstr);
					URLConnection connection = url.openConnection();
					String response = IOUtils.toString(connection.getInputStream());
					GeoCodeingResults results = new Gson().fromJson(response, GeoCodeingResults.class);
					if (results.results.length > 0) {
						double latitude = results.results[0].geometry.location.lat * 1000000;
						double longitude = results.results[0].geometry.location.lng * 1000000;
						final GeoPoint geoPoint = new GeoPoint((int) latitude, (int) longitude);
						runOnUiThread(new Runnable() { public void run() { 
							mapView.getController().animateTo(geoPoint); 
							mapView.getController().setZoom(16);
							mapView.invalidate();
							final OverlayItem item = new OverlayItem(geoPoint, getIntent().getStringExtra(ADDRESS_NAME), getIntent().getStringExtra(ADDRESS_KEY));
							item.setMarker(itemMarker);
							mapView.getOverlays().add(new Overlay(itemMarker, item));
						} });
					} else {
						runOnUiThread(new Runnable() { public void run() { 
							Toast.makeText(getApplicationContext(), getString(R.string.location_unavailable), Toast.LENGTH_LONG).show();
							finish(); 
						} });
					}
				} catch (final Exception e) {
					runOnUiThread(new Runnable() { public void run() { ActivityUtil.showErrDialog(MapsActivity.this, e); } });
				}
			}
		};
		new Thread(r).start();
	}

	@Override
	protected boolean isRouteDisplayed() {
		return false;
	}
	class Overlay extends ItemizedOverlay<OverlayItem> {

		private OverlayItem item;
		private Drawable defaultMarker;

		public Overlay(Drawable drawable, OverlayItem item) {
			super(drawable);
			this.item = item;
			this.defaultMarker = drawable;
			populate();
		}

		@Override
		protected OverlayItem createItem(int i) {
			return item;
		}

		@Override
		public int size() {
			return 1;
		}
		@Override
		public void draw(Canvas canvas, MapView mapview, boolean flag) {
			super.draw(canvas, mapview, flag);
			boundCenterBottom(defaultMarker);
		}
	}
	class GeoCodeingResults {
		String status;
		Result[] results;
	}
	class Result {
		Geometry geometry;
	}
	class Geometry {
		Location location;
	}
	class Location {
		double lat;
		double lng;
	}
}
