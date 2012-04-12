package com.bator.nhsc.util;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.graphics.drawable.StateListDrawable;
import android.util.Log;
import android.view.View.MeasureSpec;
import android.widget.FrameLayout;
import android.widget.TextView;

import com.bator.nhsc.R;
import com.google.android.maps.GeoPoint;
import com.google.android.maps.OverlayItem;

public class CustomItem extends OverlayItem {

	// text we want to overlay on top of our marker
	// for trulia this is going to be the price of the property
	private String label;

	// this is the marker we will send back to the SingleItemOverlay to use when drawing
	private StateListDrawable marker = null;

	// this framelayout holds the view we will be turning into a drawable
	private FrameLayout markerLayout;

	// since we are going to use a StateListDrawable we will need to access
	// a few of the state values from android
	private int stateSelected = android.R.attr.state_selected;
	private int statePressed = android.R.attr.state_pressed;
	private int stateFocused = android.R.attr.state_focused;

	// we need a Context and Resource objects to pull in the marker drawables
	// private Context context;
	// private Resources res;

	// these are the resource ids for the two differnt marker states
	public int MARKER_DRAWABLE_UNVIEWED = R.drawable.ic_map_marker_unviewed;
	public int MARKER_DRAWABLE_SELECTED = R.drawable.ic_map_marker_selected;

	// Constructor which besides a GeoPoint, laben and snippet also gets pass
	// our
	// marker frame layout as well as the current application context
	public CustomItem(GeoPoint pt, String label, String snippet, FrameLayout markerLayout, Context context) {
		super(pt, label, snippet);

		// set our class vars
		this.markerLayout = markerLayout;
		this.label = label;
		// this.context = context;
		// this.res = context.getResources();

		// now generate the state list drawable
		this.marker = generateStateListDrawable();
	}

	// main function where we create our Drawable by using the marker layout,
	// adding our string label, then converting to a bitmap drawable
	public Drawable generateMarker(boolean selected) {

		Bitmap viewCapture = null;
		Drawable drawOverlay = null;

		// make sure our marker layout isn't null
		if (markerLayout != null) {

			// set the text string into the view before we turn it into an image
			TextView textView = (TextView) markerLayout.findViewById(R.id.marker_text);
			textView.setText(label);

			// calling setBackgroundResource seems to overwrite our padding in
			// the layout;
			// the following is a hack i found in this google bug report; fixes
			// gravity issue as well
			// http://code.google.com/p/android/issues/detail?id=17885
			int paddingTop = textView.getPaddingTop();
			int paddingLeft = textView.getPaddingLeft();
			int paddingRight = textView.getPaddingRight();
			int paddingBottom = textView.getPaddingBottom();

			// set the background bubble image based on whether its selected or
			// not
			if (selected) {
				textView.setBackgroundResource(MARKER_DRAWABLE_SELECTED);
			} else {
				textView.setBackgroundResource(MARKER_DRAWABLE_UNVIEWED);
			}

			// part of the hack above to reset the padding specified in the
			// marker layout
			textView.setPadding(paddingLeft, paddingTop, paddingRight, paddingBottom);

			// we need to enable the drawing cache
			markerLayout.setDrawingCacheEnabled(true);

			// this is the important code
			// Without it the view will have a dimension of 0,0 and the bitmap
			// will be null
			markerLayout.measure(MeasureSpec.makeMeasureSpec(0, MeasureSpec.UNSPECIFIED), MeasureSpec.makeMeasureSpec(0, MeasureSpec.UNSPECIFIED));
			markerLayout.layout(0, 0, markerLayout.getMeasuredWidth(), markerLayout.getMeasuredHeight());

			// we need to build our drawing cache
			markerLayout.buildDrawingCache(true);

			// not null? then we are ready to capture our bitmap image
			if (markerLayout.getDrawingCache() != null) {
				viewCapture = Bitmap.createBitmap(markerLayout.getDrawingCache());

				// if the view capture is not null we should turn off the
				// drawing cache
				// and then create our marker drawable with the view capture
				if (viewCapture != null) {
					markerLayout.setDrawingCacheEnabled(false);
					drawOverlay = new BitmapDrawable(viewCapture);
					return drawOverlay;
				}
			} else {
				Log.d("CustomMapMarkers", "Item * generateMarker *** getDrawingCache is null");
			}
		}
		Log.d("CustomMapMarkers", "Item * generateMarker *** returning null");
		return null;
	}

	/*
	 * (copied from the Google API docs) Sets the state of a drawable to match a
	 * given state bitset. This is done by converting the state bitset bits into
	 * a state set of R.attr.state_pressed, R.attr.state_selected and
	 * R.attr.state_focused attributes, and then calling {@link
	 * Drawable.setState(int[])}.
	 */
	public static void setState(final Drawable drawable, final int stateBitset) {
		final int[] states = new int[3];
		int index = 0;
		if ((stateBitset & ITEM_STATE_PRESSED_MASK) > 0)
			states[index++] = android.R.attr.state_pressed;
		if ((stateBitset & ITEM_STATE_SELECTED_MASK) > 0)
			states[index++] = android.R.attr.state_selected;
		if ((stateBitset & ITEM_STATE_FOCUSED_MASK) > 0)
			states[index++] = android.R.attr.state_focused;

		drawable.setState(states);
	}

	@Override
	public StateListDrawable getMarker(int stateBitset) {

		if (marker == null)
			return null;

		setState((Drawable) marker, stateBitset);
		marker.setBounds(0, 0, marker.getIntrinsicWidth(), marker.getIntrinsicHeight());
		return (marker);
	}

	public Drawable getDefaultMarker() {
		if (marker == null)
			marker = generateStateListDrawable();

		return marker;
	}

	// We create our statelist drawable by setting various states and the marker
	// to be displayed by that state;
	// the generateMarker() function takes in whether the drawable is selected
	// or not selected
	private StateListDrawable generateStateListDrawable() {
		StateListDrawable drawables = new StateListDrawable();
		drawables.addState(new int[] { stateSelected }, generateMarker(true));
		drawables.addState(new int[] { statePressed }, generateMarker(true));
		drawables.addState(new int[] { stateFocused }, generateMarker(true));
		drawables.addState(new int[] {}, generateMarker(false));
		return drawables;
	}
}