package com.bcm;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;

import javax.microedition.io.Connector;
import javax.microedition.io.HttpConnection;

import net.rim.device.api.i18n.Locale;
import net.rim.device.api.io.http.HttpProtocolConstants;
import net.rim.device.api.system.CoverageInfo;
import net.rim.device.api.system.RadioInfo;
import net.rim.device.api.system.WLANInfo;
import net.rim.device.api.ui.UiApplication;

/**
 * Class for receiving data from server.
 */
public class DataReceiver extends Thread {
	public static final String API_ASPX = "/Api.aspx";
	public final String BASE_URL = "https://app.bcmlogic.com/" + EntryPoint.SITE_NAME + API_ASPX;
	/** HTTP connection used to retrieve data. */
	private HttpConnection connection;
	/** This is where the raw result String object is maintained. */
	private String result;
	private boolean auth;
	public DataReceiver() {
	}

	/**
	 * Starts thread which downloads a data from server.
	 */
	public void run() {
		// boolean result = getData(0, 3);
	}

	public boolean getAllData(String login, String pass, int id, final IWaitableScreen waitable, String action, String arbitraryUrlParams) throws IOException {
		try {
			waitable.log("getAllData(...) start");
			waitable.log("getAllData(...) invoke startWaiting()");
			UiApplication.getUiApplication().invokeLater(new Runnable() {
				public void run() {
					if (waitable != null) {
						waitable.startWaiting();
					}
				}
			});
			String url = BASE_URL + "?action=" + action + "&password=" + pass + "&user=" + login + "&devid=" + id + "&lang=" + Locale.getDefault().getLanguage();
			url += arbitraryUrlParams == null ? "" : arbitraryUrlParams;
			waitable.log("getAllData(...) url: " + url);
			waitable.log("getAllData(...) invoke getViaHttpConnection(url)");
			result = getViaHttpConnection(url, waitable);
		} finally {
			UiApplication.getUiApplication().invokeLater(new Runnable() {
				public void run() {
					if (waitable != null) {
						waitable.stopWaiting();
						waitable.callback(result);
					}
				}
			});
		}
		return false;
	}

	public boolean authorize(String login, String pass, int id, final IWaitableScreen waitable) throws IOException {
		try {
			waitable.log("authorize(...) start");
			waitable.log("authorize(...) invoke startWaiting()");
			UiApplication.getUiApplication().invokeLater(new Runnable() {
				public void run() {
					if (waitable != null) {
						waitable.startWaiting();
					}
				}
			});
			String url = BASE_URL + "?action=validateUser&password=" + pass + "&user=" + login + "&id=" + id;
			waitable.log("authorize(...) url: " + url);
			waitable.log("authorize(...) invoke getViaHttpConnection(url) ");
			result = getViaHttpConnection(url, waitable);
			result += LoginFormScreen.AUTH_TOKEN;
			waitable.log("result.indexOf(\"ok\"): " + result.indexOf("ok"));  
			if (result.indexOf("ok") != -1) {
				auth = true;
			} else {
				auth = false;
			}
			UiApplication.getUiApplication().invokeLater(new Runnable() {
				public void run() {
					waitable.log("authorize(...) invokeLater callback");
					if (waitable != null) {
						waitable.callback(result);
					}
				}
			});
		} finally {
			UiApplication.getUiApplication().invokeLater(new Runnable() {
				public void run() {
					waitable.log("authorize(...) invokeLater stopWaiting");
					if (waitable != null) {
						waitable.stopWaiting();
					}
				}
			});
		}
		return auth;
	}

	/**
	 * Gets data from server via HTTP connection.
	 * 
	 * @param url
	 *            - url to the data
	 * @param waitable 
	 * @return String with downloaded data
	 * @throws IOException
	 */
	private String getViaHttpConnection(String url, IWaitableScreen waitable) throws IOException {
		result = null;
		InputStream is = null;
		int rc;
		waitable.log("getViaHttpConnection() start");
		try {
			waitable.log("getViaHttpConnection() checkWifiAvailable() invoked"); 
			if (checkWifiAvailable(waitable)) {
				url += ";interface=wifi";
			} else {
				url += ";deviceside=true";
			}
			System.err.println(url);
			waitable.log("final url: " + url);
			connection = (HttpConnection) Connector.open(url + ";ConnectionTimeout=5000", Connector.READ_WRITE);
			waitable.log("connection object created: " + connection.getClass().getName());
			connection.setRequestProperty(HttpProtocolConstants.HEADER_CONNECTION, "KeepAlive");
			waitable.log("Encoding: " + connection.getEncoding());
			System.out.println("Encoding: " + connection.getEncoding());
			// Getting the response code will open the connection,
			// send the request, and read the HTTP response headers.
			// The headers are stored until requested.
			rc = connection.getResponseCode();
			waitable.log("response code: " + rc);
			System.err.println(rc);
			if (rc == HttpConnection.HTTP_NOT_FOUND) {
				throw new IllegalArgumentException("Bad addres given! Http response code 404!");
			}
			if (rc == HttpConnection.HTTP_OK) {
				is = connection.openInputStream();
				// InputStreamReader isr = new InputStreamReader(is, "UTF-8");
				// is = new GZIPInputStream(is);
				// Get the ContentType
				String type = connection.getType();
				waitable.log("Type: " + type);
				System.out.println("Type: " + type);
				// Get the length and process the data
				int len = (int) connection.getLength();
				waitable.log("Length: " + len);
				int ch = 1;
				// int bytesRead = 0;
				System.out.println("Length: " + len);
				ByteArrayOutputStream baos = new ByteArrayOutputStream();
				OutputStreamWriter osw = new OutputStreamWriter(baos, "UTF-8");
				try {
					while (ch >= 0) {
						ch = is.read();
						osw.write(ch);
						// bytesRead++;
						// AppScreen.updateDebugLength(bytesRead);
					}
				} catch (Exception e) {
					waitable.log("exception: + " + e.getClass().getName() + " msg: " + e.getMessage());
					e.printStackTrace();
				}
				byte[] data = baos.toByteArray();
				// byte[] dataShort = new byte[data.length - 2];
				// System.arraycopy(data, 0, dataShort, 0, data.length - 2);
				ByteArrayInputStream bais = new ByteArrayInputStream(data);
				InputStreamReader isr = new InputStreamReader(bais, "UTF-8");
				StringBuffer out = new StringBuffer();
				ch = 1;
				while (ch >= 0) {
					ch = isr.read();
					out.append((char) ch);
				}
				//waitable.log("result clean: " + out.toString());
				result = out.toString().trim();
				//waitable.log("result trim: " + result);
				result = result.substring(0, result.length() - 2);
				//waitable.log("result cut: " + result);
				result = StrUtils.replaceAll(result, "\u0000", "");
				//waitable.log("result replace: " + result);
				//System.out.println(result);
			} else {
				result = null;
			}
		} finally {
			if (is != null) {
				is.close();
			}
			if (connection != null) {
				connection.close();
				waitable.log("connection closed");
			}
		}
		//System.out.println(result);
		return result;
	}

	/**
	 * Checks if Wi-Fi is available and ready to use.
	 * @param waitable 
	 * 
	 * @return true if WiFi is available; false if not
	 */
	private boolean checkWifiAvailable(IWaitableScreen waitable) {
		boolean wifiinrange = false;
		try {
			waitable.log("checkWifiAvailable() start");
			wifiinrange = false;
			
			waitable.log("CHECKING WLAN STATE, COVERAGE INFO: " + CoverageInfo.getCoverageStatus(RadioInfo.WAF_WLAN, true));
			waitable.log("CHECKING WLAN STATE, WLAN INFO: " +  WLANInfo.getWLANState());
			
			if (CoverageInfo.getCoverageStatus(RadioInfo.WAF_WLAN, true) == CoverageInfo.COVERAGE_DIRECT) {
				if (WLANInfo.getWLANState() == WLANInfo.WLAN_STATE_CONNECTED) {
					wifiinrange = true;
					System.out.println("CHECKING WLAN STATE = TRUE");
					waitable.log("CHECKING WLAN STATE = TRUE");
				}
			}
			//System.out.println("wifiinrange = " + wifiinrange);
			waitable.log("wifi in range = " + wifiinrange);
		} catch (Exception e) {
			return false;
		}
		return wifiinrange;
	}

	public String getResult() {
		return result;
	}

}
