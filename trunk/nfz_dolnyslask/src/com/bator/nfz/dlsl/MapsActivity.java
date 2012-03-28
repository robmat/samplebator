package com.bator.nfz.dlsl;

import android.os.Bundle;
import android.view.ViewGroup.LayoutParams;

import com.google.android.maps.MapActivity;
import com.google.android.maps.MapView;

public class MapsActivity extends MapActivity {
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		MapView mapView = new MapView(this, "0N_VRQz-BsODiJZjyzhqLG4xIaNy7cdoKoMTPYg");
		mapView.setLayoutParams(new LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.FILL_PARENT));
		setContentView(mapView);
	}

	@Override
	protected boolean isRouteDisplayed() {
		return false;
	}
}
