package com.bator.nhsc.net;

import java.io.ByteArrayInputStream;
import java.io.IOException;
import java.net.URL;
import java.net.URLConnection;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;

import org.apache.commons.io.IOUtils;
import org.w3c.dom.Document;
import org.xml.sax.SAXException;

import android.util.Log;

public class NetUtil {
	static String TAG = NetUtil.class.getSimpleName();

	public static Document getXmlFromUrl(String urlStr) throws SAXException, IOException, ParserConfigurationException {
		URL url = new URL(urlStr);
		Log.v(TAG, "Opening URL: " + url.toString());
		URLConnection connection = url.openConnection();
		String resultXML = IOUtils.toString(connection.getInputStream());
		Log.v(TAG, "Result: " + resultXML);
		DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
		DocumentBuilder builder = factory.newDocumentBuilder();
		return builder.parse(new ByteArrayInputStream(resultXML.getBytes()));
	}
}
