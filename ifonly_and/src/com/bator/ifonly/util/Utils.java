package com.bator.ifonly.util;

import java.io.ByteArrayInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.Reader;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.List;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.FactoryConfigurationError;
import javax.xml.parsers.ParserConfigurationException;

import org.apache.http.HeaderElement;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.HttpVersion;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.conn.scheme.PlainSocketFactory;
import org.apache.http.conn.scheme.Scheme;
import org.apache.http.conn.scheme.SchemeRegistry;
import org.apache.http.conn.ssl.SSLSocketFactory;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.impl.conn.tsccm.ThreadSafeClientConnManager;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpParams;
import org.apache.http.params.HttpProtocolParams;
import org.apache.http.protocol.HTTP;
import org.w3c.dom.Document;
import org.xml.sax.SAXException;

import android.content.Context;
import android.content.Intent;
import android.net.ParseException;
import android.net.Uri;
import android.util.Log;
import android.view.View;
import android.webkit.WebView;
import android.webkit.WebViewClient;

import com.bator.ifonly.ActivityBase;
import com.bator.ifonly.R;

public class Utils {
	public static final String MAIN_MENU_ACTION = "ifonly.mainmenu";
	public static final String CHOOSE_VIDEO_SOURCE_ACTION = "ifonly.video.source";
	public static final String CHOOSE_CATEGORY_ACTION = "ifonly.choose.category";
	public static final String UPLOAD_VIDEO_ACTION = "ifonly.video.upload";
	public static final String VIDEO_LIST_ACTION = "ifonly.video.list";
	public static final String COMPETITION_ACTION = "ifonly.competition";
	public static final String ABOUT_ACTION = "ifonly.about";
	public enum VID_CATEGORY {
		HOUSEHOLD, GARDEN_TOOLS, ELECTRICAL_GOODS, TOOLS_MACHINERY, PERSONAL_PRODUCTS, MISC;
		public String getStr(Context context) {
			switch (this) {
			case HOUSEHOLD:
				return context.getString(R.string.main_menu_household_products_lbl);
			case GARDEN_TOOLS:
				return context.getString(R.string.main_menu_garden_lbl);
			case ELECTRICAL_GOODS:
				return context.getString(R.string.main_menu_electrical_lbl);
			case TOOLS_MACHINERY:
				return context.getString(R.string.main_menu_tools_lbl);
			case PERSONAL_PRODUCTS:
				return context.getString(R.string.main_menu_personal_lbl);
			case MISC:
				return context.getString(R.string.main_menu_misc_lbl);
			}
			return "Unrecognized";
		}
		public static VID_CATEGORY fromString(String vidCat) {
			for (VID_CATEGORY cat : VID_CATEGORY.values()) {
				if (cat.toString().equals(vidCat)) {
					return cat;
				}
			}
			return null;
		}
		public static String[] getStrArr(Context context) {
			List<String> strArr = new ArrayList<String>();
			for (VID_CATEGORY cat : VID_CATEGORY.values()) {
				strArr.add(cat.getStr(context));
			}
			return strArr.toArray(new String[strArr.size()]);
		}
		public int getImageResId() {
			switch (this) {
			case HOUSEHOLD:
				return R.drawable.household;
			case GARDEN_TOOLS:
				return R.drawable.garden;
			case ELECTRICAL_GOODS:
				return R.drawable.electrical;
			case TOOLS_MACHINERY:
				return R.drawable.tools;
			case PERSONAL_PRODUCTS:
				return R.drawable.personal_products;
			case MISC:
				return R.drawable.misc;
			}
			return R.drawable.misc;
		}
		public static VID_CATEGORY getCategoryFromTitle(String title, Context context) {
			for (String catStr : getStrArr(context)) {
				if (title.contains(catStr)) {
					for (VID_CATEGORY vidCat : VID_CATEGORY.values()) {
						if (vidCat.getStr(context).equals(catStr)) {
							return vidCat;
						}
					}
				}
			}
			return null;
		}
	};

	public static class LaunchActivityListener implements View.OnClickListener {
		private String action;
		private ActivityBase activity;
		private Uri data;

		public LaunchActivityListener(String action, ActivityBase activity, Uri data) {
			this.action = action;
			this.activity = activity;
			this.data = data;
		}

		public void onClick(View v) {
			activity.playPlak();
			if (data != null) {
				activity.startActivity(new Intent(action, data));
			} else {
				activity.startActivity(new Intent(action));
			}
		}
	}

	public static DefaultHttpClient getClient() {
		DefaultHttpClient ret = null;

		// sets up parameters
		HttpParams params = new BasicHttpParams();
		HttpProtocolParams.setVersion(params, HttpVersion.HTTP_1_1);
		HttpProtocolParams.setContentCharset(params, "utf-8");
		//params.setBooleanParameter("http.protocol.expect-continue", false);

		// registers schemes for both http and https
		SchemeRegistry registry = new SchemeRegistry();
		PlainSocketFactory socketFactory = PlainSocketFactory.getSocketFactory();
		registry.register(new Scheme("http", socketFactory, 80));
		final SSLSocketFactory sslSocketFactory = SSLSocketFactory.getSocketFactory();
		sslSocketFactory.setHostnameVerifier(SSLSocketFactory.ALLOW_ALL_HOSTNAME_VERIFIER);
		registry.register(new Scheme("https", sslSocketFactory, 443));

		ThreadSafeClientConnManager manager = new ThreadSafeClientConnManager(params, registry);
		ret = new DefaultHttpClient(manager, params);
		return ret;
	}
	public static Document getVideosDOM(String orderBy, String query) throws FactoryConfigurationError, ClientProtocolException, IOException, ParserConfigurationException, SAXException {
		DefaultHttpClient client = Utils.getClient();
		String urlStr = YoutubeService.YOUTUBE_FEEDS_URL.replace(YoutubeService.USER_TOKEN, "IfOnlyApp");
		urlStr += "?max-results=50&start-index=1&v=2";
		urlStr += "&orderby=" + orderBy;
		urlStr += "&q=" + URLEncoder.encode(query.trim());
		HttpResponse response = client.execute(new HttpGet(urlStr));
		Log.d("Utils", "URL: " + urlStr);
		String responseStr = Utils.getResponseBody(response);
		DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
		DocumentBuilder docBuilder = factory.newDocumentBuilder();
		Document doc = docBuilder.parse(new ByteArrayInputStream(responseStr.getBytes()));
		return doc;
	}
	public static String getResponseBody(HttpResponse response) {
		String response_text = null;
		HttpEntity entity = null;
		try {
			entity = response.getEntity();
			response_text = _getResponseBody(entity);
		} catch (ParseException e) {
			e.printStackTrace();
		} catch (IOException e) {
			if (entity != null) {
				try {
					entity.consumeContent();
				} catch (IOException e1) {
				}
			}
		}
		return response_text;
	}

	public static String _getResponseBody(final HttpEntity entity) throws IOException, ParseException {
		if (entity == null) {
			throw new IllegalArgumentException("HTTP entity may not be null");
		}
		InputStream instream = entity.getContent();
		if (instream == null) {
			return "";
		}
		if (entity.getContentLength() > Integer.MAX_VALUE) {
			throw new IllegalArgumentException("HTTP entity too large to be buffered in memory");
		}
		String charset = getContentCharSet(entity);
		if (charset == null) {
			charset = HTTP.DEFAULT_CONTENT_CHARSET;
		}
		Reader reader = new InputStreamReader(instream, charset);
		StringBuilder buffer = new StringBuilder();
		try {
			char[] tmp = new char[1024];
			int l;
			while ((l = reader.read(tmp)) != -1) {
				buffer.append(tmp, 0, l);
			}
		} finally {
			reader.close();
		}
		return buffer.toString();
	}

	public static String getContentCharSet(final HttpEntity entity) throws ParseException {
		if (entity == null) {
			throw new IllegalArgumentException("HTTP entity may not be null");
		}
		String charset = null;
		if (entity.getContentType() != null) {
			HeaderElement values[] = entity.getContentType().getElements();
			if (values.length > 0) {
				NameValuePair param = values[0].getParameterByName("charset");
				if (param != null) {
					charset = param.getValue();
				}
			}
		}
		return charset;
	}
	public static class LinkEnabledWebViewClient extends WebViewClient {
	    @Override
	    public boolean shouldOverrideUrlLoading(WebView view, String url) {
	        Intent i = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
	        view.getContext().startActivity(i);
	        return true;
	    }
	}
}
