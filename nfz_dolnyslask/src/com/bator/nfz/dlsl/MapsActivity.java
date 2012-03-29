package com.bator.nfz.dlsl;

import java.io.IOException;
import java.util.List;

import android.location.Address;
import android.location.Geocoder;
import android.os.Bundle;

import com.bator.nfz.dlsl.util.ActivityUtil;
import com.google.android.maps.GeoPoint;
import com.google.android.maps.MapActivity;
import com.google.android.maps.MapView;

public class MapsActivity extends MapActivity {
	public static final String ADDRESS_KEY = "ADDRESS_KEY";
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.map_view);
		final MapView mapView = (MapView) findViewById(R.id.mapView);
		mapView.setBuiltInZoomControls(true);
		Runnable r = new Runnable() {
			public void run() {
				try {
					Geocoder geocoder = new Geocoder(MapsActivity.this);
					List<Address> addresses = geocoder.getFromLocationName(getIntent().getStringExtra(ADDRESS_KEY), Integer.MAX_VALUE);
					for (Address address : addresses) {
						final GeoPoint geoPoint = new GeoPoint((int) address.getLatitude(), (int) address.getLongitude());
						runOnUiThread(new Runnable() { public void run() { 
							mapView.getController().animateTo(geoPoint); 
							mapView.getController().setZoom(16);
							mapView.invalidate();
						} });
					}
				} catch (final IOException e) {
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
}
