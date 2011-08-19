package com.bator.ifonly.util;

import java.io.ByteArrayInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.Reader;
import java.util.ArrayList;
import java.util.List;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.FactoryConfigurationError;

import org.apache.http.HeaderElement;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.HttpVersion;
import org.apache.http.NameValuePair;
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

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.net.ParseException;
import android.net.Uri;
import android.util.Log;
import android.view.View;

import com.bator.ifonly.R;

public class Utils {
	public static final String MAIN_MENU_ACTION = "ifonly.mainmenu";
	public static final String CHOOSE_VIDEO_SOURCE_ACTION = "ifonly.video.source";
	public static final String CHOOSE_CATEGORY_ACTION = "ifonly.choose.category";
	public static final String UPLOAD_VIDEO_ACTION = "ifonly.video.upload";
	public static final String VIDEO_LIST_ACTION = "ifonly.video.list";

	public enum VID_CATEGORY {
		HOUSEHOLD, GARDEN_TOOLS, ELECTRICAL_GOODS, TOOLS_MACHINERY, PERSONAL_PRODUCTS, MISC;
		public String getStr(Context context) {
			switch (this) {
			case HOUSEHOLD:
				return context.getString(R.string.main_menu_household_lbl);
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
			return MISC;
		}
		public static String[] getStrArr(Context context) {
			List<String> strArr = new ArrayList<String>();
			for (VID_CATEGORY cat : VID_CATEGORY.values()) {
				strArr.add(cat.getStr(context));
			}
			return strArr.toArray(new String[strArr.size()]);
		}
	};

	public static class LaunchActivityListener implements View.OnClickListener {
		private String action;
		private Activity activity;
		private Uri data;

		public LaunchActivityListener(String action, Activity activity, Uri data) {
			this.action = action;
			this.activity = activity;
			this.data = data;
		}

		public void onClick(View v) {
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
		params.setBooleanParameter("http.protocol.expect-continue", false);

		// registers schemes for both http and https
		SchemeRegistry registry = new SchemeRegistry();
		registry.register(new Scheme("http", PlainSocketFactory.getSocketFactory(), 80));
		final SSLSocketFactory sslSocketFactory = SSLSocketFactory.getSocketFactory();
		sslSocketFactory.setHostnameVerifier(SSLSocketFactory.BROWSER_COMPATIBLE_HOSTNAME_VERIFIER);
		registry.register(new Scheme("https", sslSocketFactory, 443));

		ThreadSafeClientConnManager manager = new ThreadSafeClientConnManager(params, registry);
		ret = new DefaultHttpClient(manager, params);
		return ret;
	}
	public static Document getVideosDOM(String orderBy, String query) throws FactoryConfigurationError {
		DefaultHttpClient client = Utils.getClient();
		try {
			String urlStr = YoutubeService.YOUTUBE_FEEDS_URL.replace(YoutubeService.USER_TOKEN, "IfOnlyApp");
			urlStr += "?max-results=50";
			urlStr += "&orderby=" + orderBy;
			urlStr += "&q=" + query;
			HttpResponse response = client.execute(new HttpGet(urlStr));
			String responseStr = Utils.getResponseBody(response);
			DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
			DocumentBuilder docBuilder = factory.newDocumentBuilder();
			Document doc = docBuilder.parse(new ByteArrayInputStream(responseStr.getBytes()));
			return doc;
		} catch (Exception e) {
			Log.e("Utils", "getVideosDOM", e);
		}
		return null;
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
}
