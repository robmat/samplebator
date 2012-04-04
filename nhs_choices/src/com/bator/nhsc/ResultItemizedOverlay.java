package com.bator.nhsc;

import java.io.ByteArrayInputStream;
import java.net.URL;
import java.net.URLConnection;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.apache.commons.io.IOUtils;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.drawable.Drawable;
import android.util.Log;
import android.view.LayoutInflater;
import android.widget.FrameLayout;

import com.bator.nhsc.util.CustomItem;
import com.bator.nhsc.util.XmlUtil;
import com.google.android.maps.GeoPoint;
import com.google.android.maps.ItemizedOverlay;
import com.google.android.maps.MapView;
import com.google.android.maps.OverlayItem;

public class ResultItemizedOverlay extends ItemizedOverlay<OverlayItem> {

	String TAG = getClass().getSimpleName();
	List<Entry> model = new ArrayList<Entry>();
	IResultListener listener;
	Drawable marker;
	
	public ResultItemizedOverlay(Drawable defaultMarker, Document document, IResultListener listener) {
		super(defaultMarker);
		this.marker = defaultMarker;
		this.listener = listener;
		parseDocument(document);
		populate();
		//this.listener.finishedLoading();
	}

	private void parseDocument(Document document) {
		try {
			parseResults(document);
			
			NodeList linkNodes = document.getElementsByTagName("link");
			int pageCount = 1;
			int nextPage = 1;
			String nextLink = null;
			for (int i = 0; i < linkNodes.getLength(); i++) {
				Node linkNode = linkNodes.item(i);
				if (linkNode.getAttributes().getNamedItem("rel") != null && linkNode.getAttributes().getNamedItem("rel").getNodeValue().equals("last")) {
					String link = linkNode.getAttributes().getNamedItem("href").getNodeValue();
					URL linkUrl = new URL(link);
					String page = linkUrl.toString().substring(linkUrl.toString().indexOf("page=") + "page=".length(), linkUrl.toString().length());
					pageCount = Integer.parseInt(page);
				}
				if (linkNode.getAttributes().getNamedItem("rel") != null && linkNode.getAttributes().getNamedItem("rel").getNodeValue().equals("next")) {
					nextLink = linkNode.getAttributes().getNamedItem("href").getNodeValue();
					URL linkUrl = new URL(nextLink);
					String nextPageStr = linkUrl.toString().substring(linkUrl.toString().indexOf("page=") + "page=".length(), linkUrl.toString().length());
					nextPage = Integer.parseInt(nextPageStr);
				}
			}
			if (nextPage <= pageCount && nextLink != null && nextPage != 3) {
				URL url = new URL(nextLink.replaceAll("\\?", ".xml?"));
				URLConnection connection = url.openConnection();
				String result = IOUtils.toString(connection.getInputStream());
				DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
				DocumentBuilder builder = factory.newDocumentBuilder();
				document = builder.parse(new ByteArrayInputStream(result.getBytes()));
				parseDocument(document);
			}
		} catch (Exception e) {
			Log.e(TAG, "error: ", e);
		}
	}

	private void parseResults(Document document) {
		NodeList entriesNodesList = document.getElementsByTagName("entry");
		for (int i = 0; i < entriesNodesList.getLength(); i++) {
			Entry entry = new Entry();
			Node entryNode = entriesNodesList.item(i);
			entry.detailsLink = XmlUtil.getTextFromNode(XmlUtil.getChildElementByName(entryNode, "id")) + ".xml?apikey=PHRJCDTY";
			entry.name = XmlUtil.getTextFromNode(XmlUtil.getChildElementByName(entryNode, "title"));
			Node contentNode = XmlUtil.getChildElementByName(entryNode, "content");
			Node summaryNode = XmlUtil.getChildElementByName(contentNode, "s:organisationSummary");
			Node addressNode = XmlUtil.getChildElementByName(summaryNode, "s:address");
			Node[] addressLineNodes = XmlUtil.getChildElementsByName(addressNode, "s:addressLine");
			entry.addressLines = new String[addressLineNodes.length];
			for (int j = 0; j < addressLineNodes.length; j++) {
				entry.addressLines[j] = XmlUtil.getTextFromNode(addressLineNodes[j]);
			}
			entry.postcode =  XmlUtil.getTextFromNode(XmlUtil.getChildElementByName(addressNode, "s:postcode"));
			Node geographicNode = XmlUtil.getChildElementByName(summaryNode, "s:geographicCoordinates");
			entry.lat = Double.parseDouble(XmlUtil.getTextFromNode(XmlUtil.getChildElementByName(geographicNode, "s:latitude")));
			entry.lon = Double.parseDouble(XmlUtil.getTextFromNode(XmlUtil.getChildElementByName(geographicNode, "s:longitude")));
			Node contactNode = XmlUtil.getChildElementByName(summaryNode, "s:contact");
			entry.telephone = XmlUtil.getTextFromNode(XmlUtil.getChildElementByName(contactNode, "s:telephone"));
			entry.email = XmlUtil.getTextFromNode(XmlUtil.getChildElementByName(contactNode, "s:email"));
			model.add(entry);
		}
	}

	@Override
	protected OverlayItem createItem(final int i) {
		double latInt = model.get(i).lat * 1000000;
		double lonInt = model.get(i).lon * 1000000;
		GeoPoint geoPoint = new GeoPoint((int) latInt, (int) lonInt);
//		OverlayItem overlayItem = new OverlayItem(geoPoint, "title", "message") {
//			private BitmapDrawable bitmapDrawable;
//
//			@Override
//			public Drawable getMarker(int stateBitset) {
//				if (bitmapDrawable == null) {
//					LayoutInflater inflater = LayoutInflater.from(listener.getContext());
//					TextView textView = (TextView) inflater.inflate(R.layout.marker_layout, null, false);
//					textView.setText(model.get(i).name);
//					textView.setDrawingCacheEnabled(true);
//					textView.measure(MeasureSpec.makeMeasureSpec(0, MeasureSpec.UNSPECIFIED), MeasureSpec.makeMeasureSpec(0, MeasureSpec.UNSPECIFIED));
//					textView.layout(0, 0, textView.getMeasuredWidth(), textView.getMeasuredHeight());
//					textView.buildDrawingCache(true);
//					Bitmap markerBitmap = Bitmap.createBitmap(textView.getDrawingCache());
//					textView.setDrawingCacheEnabled(false);
//					bitmapDrawable = new BitmapDrawable(markerBitmap);
//				}
//				return bitmapDrawable;
//			}
//		};
//		overlayItem.setMarker(marker);
		LayoutInflater inflater = LayoutInflater.from(listener.getContext());
		FrameLayout frame = (FrameLayout) inflater.inflate(R.layout.marker_layout, null, false);
		OverlayItem overlayItem = new CustomItem(geoPoint, model.get(i).name, "", frame, listener.getContext());
		return overlayItem;
	}
	@Override
	protected boolean onTap(int index) {
		Log.v(TAG, "Tapped: " + model.get(index).toString());
		return super.onTap(index);
	}
	@Override
	public int size() {
		return model.size();
	}
	@Override
	public void draw(Canvas canvas, MapView mapview, boolean flag) {
		super.draw(canvas, mapview, false);
		boundCenterBottom(marker);
	}
	public static class Entry {
		//public String externalLink;
		public String detailsLink;
		public String name;
		public String[] addressLines;
		public String postcode;
		public double lon;
		public double lat;
		public String telephone;
		public String email;

		@Override
		public String toString() {
			return "Entry [name=" + name + ", detailsLink=" + detailsLink + ",  addressLines=" + Arrays.toString(addressLines) + ", postcode=" + postcode + ", lon=" + lon + ", lat=" + lat + ", telephone=" + telephone + ", email=" + email + "]";
		}
	}
	public static interface IResultListener {
		void finishedLoading();
		Context getContext();
	}
}
