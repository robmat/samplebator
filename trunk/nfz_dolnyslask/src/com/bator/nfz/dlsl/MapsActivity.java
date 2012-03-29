package com.bator.nfz.dlsl;

import java.io.IOException;
import java.util.List;

import android.graphics.Canvas;
import android.graphics.drawable.Drawable;
import android.location.Address;
import android.location.Geocoder;
import android.os.Bundle;

import com.bator.nfz.dlsl.util.ActivityUtil;
import com.google.android.maps.GeoPoint;
import com.google.android.maps.ItemizedOverlay;
import com.google.android.maps.MapActivity;
import com.google.android.maps.MapView;
import com.google.android.maps.OverlayItem;

public class MapsActivity extends MapActivity {
	public static final String ADDRESS_KEY = "ADDRESS_KEY";
	public static final String ADDRESS_NAME = "ADDRESS_NAME";
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.map_view);
		final MapView mapView = (MapView) findViewById(R.id.mapView);
		final Drawable itemMarker = getResources().getDrawable(android.R.drawable.btn_plus);
		mapView.setBuiltInZoomControls(true);
		Runnable r = new Runnable() {
			public void run() {
				try {
					Geocoder geocoder = new Geocoder(MapsActivity.this);
					List<Address> addresses = geocoder.getFromLocationName(getIntent().getStringExtra(ADDRESS_KEY), Integer.MAX_VALUE);
					if (addresses.size() > 0) {
						double latitude = addresses.get(0).getLatitude() * 1000000;
						double longitude = addresses.get(0).getLongitude() * 1000000;
						final GeoPoint geoPoint = new GeoPoint((int) latitude, (int) longitude);
						runOnUiThread(new Runnable() { public void run() { 
							mapView.getController().animateTo(geoPoint); 
							mapView.getController().setZoom(16);
							mapView.invalidate();
							final OverlayItem item = new OverlayItem(geoPoint, getIntent().getStringExtra(ADDRESS_NAME), getIntent().getStringExtra(ADDRESS_KEY));
							item.setMarker(itemMarker);
							mapView.getOverlays().add(new Overlay(itemMarker, item));
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
}
