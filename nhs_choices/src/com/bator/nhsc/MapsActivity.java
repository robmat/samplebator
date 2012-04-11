package com.bator.nhsc;

import java.io.ByteArrayInputStream;
import java.net.URL;
import java.net.URLConnection;
import java.text.MessageFormat;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.apache.commons.io.IOUtils;
import org.w3c.dom.Document;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.DialogInterface.OnClickListener;
import android.content.Intent;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.provider.Settings;
import android.util.Log;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.Animation.AnimationListener;
import android.view.animation.AnimationUtils;

import com.bator.nhsc.ResultItemizedOverlay.IResultListener;
import com.bator.nhsc.view.IndicatorView;
import com.google.android.maps.GeoPoint;
import com.google.android.maps.MapActivity;
import com.google.android.maps.MapView;
import com.google.gson.Gson;

public class MapsActivity extends MapActivity implements LocationListener, IResultListener, android.view.View.OnClickListener, AnimationListener {
	String TAG = getClass().getSimpleName();
	public static final String URI_KEY = "URI_KEY";
	LocationManager locationManager;
	MapView mapView;
	private int locationProvidedCount = 0;
	private boolean locationPopupShown = false;
	ResultItemizedOverlay resultItemizedOverlay;
	IndicatorView indicatorView;
	@Override
	protected void onCreate(Bundle icicle) {
		super.onCreate(icicle);
		setContentView(R.layout.map_view);
		mapView = (MapView) findViewById(R.id.mapView);
		mapView.setBuiltInZoomControls(true);
		locationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
		locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, MapsActivity.this);
		locationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, 0, 0, MapsActivity.this);
		indicatorView = (IndicatorView) findViewById(R.id.title_bar_activity_indicator_id);
		findViewById(R.id.search_bar_btn).setOnClickListener(this);
		findViewById(R.id.search_btn).setOnClickListener(this);
	}
	@Override
	protected boolean isRouteDisplayed() {
		return false;
	}
	public void onLocationChanged(android.location.Location location) {
		Log.v(TAG, location.toString());
		if (locationProvidedCount < 1) {
			final double latitude = 52.955464;//location.getLatitude();
			int lat = (int) (latitude * 1E6);
			final double longitude = -1.158772;// location.getLongitude();
			int lon = (int) (longitude * 1E6);
			mapView.getController().animateTo(new GeoPoint(lat, lon));
			mapView.getController().setZoom(16);
			new Thread(new Runnable() {
				public void run() {
					try {
						startIndidcator();
						URL url = new URL(MessageFormat.format("http://maps.googleapis.com/maps/api/geocode/json?latlng={0},{1}&sensor=true", String.valueOf(latitude), String.valueOf(longitude)));
						URLConnection connection = url.openConnection();
						String result = IOUtils.toString(connection.getInputStream());
						GeoCodingResults results = new Gson().fromJson(result, GeoCodingResults.class);
						Log.v(TAG, results.getPostalCode());
						String urlExtra = getIntent().getStringExtra(URI_KEY);
						urlExtra = urlExtra.substring(0, urlExtra.indexOf("?")) + "/postcode/" + results.getPostalCode().replaceAll(" ", "") + ".xml?apikey=PHRJCDTY&range=100";
						url = new URL(urlExtra);
						connection = url.openConnection();
						result = IOUtils.toString(connection.getInputStream());
						DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
						DocumentBuilder builder = factory.newDocumentBuilder();
						Document document = builder.parse(new ByteArrayInputStream(result.getBytes()));
						resultItemizedOverlay = new ResultItemizedOverlay(getResources().getDrawable(R.drawable.marker), document, MapsActivity.this);
						finishedLoading();
					} catch (Exception e) {
						Log.e(TAG, "error: ", e);
					} finally {
						stopIndidcator();
					}
				}
			}).start();
			locationProvidedCount++;
		} else {
			locationManager.removeUpdates(this);
		}
	}
	public void onProviderDisabled(String provider) {
		Log.v(TAG, "Provider disabled: " + provider);
		if (!locationPopupShown) {
			AlertDialog.Builder builder = new AlertDialog.Builder(this);
			builder.setTitle("Warning");
			builder.setMessage("Your GPS is disabled, do you want to enable it to find You location?");
			builder.setPositiveButton("Yes", new OnClickListener() {
				public void onClick(DialogInterface dialog, int which) {
					dialog.dismiss();
					startActivity(new Intent(Settings.ACTION_LOCATION_SOURCE_SETTINGS));
				}
			});
			builder.setNegativeButton("No", new OnClickListener() {
				public void onClick(DialogInterface dialog, int which) {
					dialog.dismiss();
				}
			});
			builder.create().show();
			locationPopupShown = true;
		}
	}
	public void onProviderEnabled(String provider) {
		
	}
	public void onStatusChanged(String provider, int status, Bundle extras) {
		
	}
	protected void onDestroy() {
		super.onDestroy();
		locationManager.removeUpdates(this);
	}
	public void finishedLoading() {
		if (resultItemizedOverlay != null) {
			runOnUiThread(new Runnable() {
				public void run() {
					mapView.getOverlays().add(resultItemizedOverlay);
					mapView.invalidate();
				}
			});
		}
	}
	public Context getContext() {
		return this;
	}
	public void startIndidcator() {
		runOnUiThread(new Runnable() { public void run() { indicatorView.setVisibility(View.VISIBLE); }});
	}
	public void stopIndidcator() {
		runOnUiThread(new Runnable() { public void run() { indicatorView.setVisibility(View.GONE); }});
	}
	public void onClick(View v) {
		if (v.getId() == R.id.search_bar_btn) {
			findViewById(R.id.search_bar_layout).setVisibility(View.VISIBLE);
			findViewById(R.id.search_bar_layout).startAnimation(AnimationUtils.loadAnimation(this, R.anim.slide_from_up));
		}
		if (v.getId() == R.id.search_btn) {
			Animation animation = AnimationUtils.loadAnimation(this, R.anim.slide_to_up);
			animation.setAnimationListener(this);
			findViewById(R.id.search_bar_layout).startAnimation(animation);
		}
	}
	class GeoCodingResults {
		String status;
		Result[] results;
		
		public String getPostalCode() {
			for (Result result : results) {
				for (AddressComponent addressComponent : result.address_components) {
					for (String type : addressComponent.types) {
						if ("postal_code".equals(type)) {
							return addressComponent.long_name;
						}
					}
				}
			}
			return null;
		}
	}
	class Result {
		Geometry geometry;
		String[] types;
		String formatted_address;
		AddressComponent[] address_components;
	}
	class AddressComponent {
		String[] types;
		String long_name;
		String short_name;
	}
	class Geometry {
		Location location;
	}
	class Location {
		double lat;
		double lng;
	}
	public void onAnimationEnd(Animation arg0) {
		findViewById(R.id.search_bar_layout).setVisibility(View.GONE);
	}
	public void onAnimationRepeat(Animation arg0) {
		
	}
	public void onAnimationStart(Animation arg0) {
		
	}
}
