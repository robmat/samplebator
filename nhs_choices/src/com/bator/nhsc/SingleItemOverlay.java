package com.bator.nhsc;

import android.app.Activity;
import android.graphics.Canvas;
import android.graphics.drawable.Drawable;
import android.util.Log;
import android.view.LayoutInflater;
import android.widget.FrameLayout;

import com.bator.nhsc.ResultItemizedOverlay.Entry;
import com.bator.nhsc.util.CustomItem;
import com.google.android.maps.GeoPoint;
import com.google.android.maps.ItemizedOverlay;
import com.google.android.maps.MapView;
import com.google.android.maps.OverlayItem;

public class SingleItemOverlay extends ItemizedOverlay<OverlayItem> {
	String TAG = getClass().getSimpleName();
	
	private Drawable defaultMarker;
	private Entry entry;
	private Activity activity;
	
	public SingleItemOverlay(Drawable drawable, Entry entry, Activity activity) {
		super(drawable);
		this.defaultMarker = drawable;
		this.entry = entry;
		this.activity = activity;
		populate();
	}
	@Override
	protected OverlayItem createItem(final int i) {
		double latInt = entry.lat * 1E6;
		double lonInt = entry.lon * 1E6;
		GeoPoint geoPoint = new GeoPoint((int) latInt, (int) lonInt);
		LayoutInflater inflater = LayoutInflater.from(activity);
		FrameLayout frame = (FrameLayout) inflater.inflate(R.layout.marker_layout, null, false);
		OverlayItem overlayItem = new CustomItem(geoPoint, entry.name, "", frame, activity);
		return overlayItem;
	}
	@Override
	public int size() {
		return 1;
	}
	@Override
	protected boolean onTap(int index) {
		Log.v(TAG, "Tapped");
		return super.onTap(index);
	}
	@Override
	public void draw(Canvas canvas, MapView mapview, boolean flag) {
		super.draw(canvas, mapview, false);
		boundCenterBottom(defaultMarker);
	}
}