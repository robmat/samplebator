package com.bator.nhsc;

import java.util.List;

import org.w3c.dom.Document;

import android.graphics.drawable.Drawable;

import com.google.android.maps.ItemizedOverlay;
import com.google.android.maps.OverlayItem;

public class ResultItemizedOverlay extends ItemizedOverlay<OverlayItem> {

	public ResultItemizedOverlay(Drawable defaultMarker, List<Document> document) {
		super(defaultMarker);
		parseDocument(document);
		populate();
	}

	private void parseDocument(Document document) {
		
	}

	@Override
	protected OverlayItem createItem(int i) {
		return null;
	}

	@Override
	public int size() {
		return 0;
	}

}
