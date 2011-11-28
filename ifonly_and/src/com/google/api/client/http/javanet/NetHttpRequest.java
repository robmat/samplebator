package com.google.api.client.http.javanet;

import java.io.IOException;
import java.net.HttpURLConnection;
import java.net.URL;

import javax.net.ssl.HttpsURLConnection;

import org.apache.http.conn.ssl.AllowAllHostnameVerifier;

import com.google.api.client.http.HttpContent;
import com.google.api.client.http.LowLevelHttpRequest;
import com.google.api.client.http.LowLevelHttpResponse;

/**
 * @author Yaniv Inbar
 */
final class NetHttpRequest extends LowLevelHttpRequest {

  private final HttpURLConnection connection;
  private HttpContent content;

  NetHttpRequest(String requestMethod, String url) throws IOException {
    HttpURLConnection connection =
        this.connection = (HttpURLConnection) new URL(url).openConnection();
    connection.setRequestMethod(requestMethod);
    connection.setUseCaches(false);
    connection.setInstanceFollowRedirects(false);
	if (connection instanceof HttpsURLConnection) {
		((HttpsURLConnection) connection).setHostnameVerifier(new AllowAllHostnameVerifier());
	}
  }

  @Override
  public void addHeader(String name, String value) {
    connection.addRequestProperty(name, value);
  }

  @Override
  public void setTimeout(int connectTimeout, int readTimeout) {
    connection.setReadTimeout(readTimeout);
    connection.setConnectTimeout(connectTimeout);
  }

  @Override
  public LowLevelHttpResponse execute() throws IOException {
    HttpURLConnection connection = this.connection;
    // write content
    if (content != null) {
      String contentType = content.getType();
      if (contentType != null) {
        addHeader("Content-Type", contentType);
      }
      String contentEncoding = content.getEncoding();
      if (contentEncoding != null) {
        addHeader("Content-Encoding", contentEncoding);
      }
      long contentLength = content.getLength();
      if (contentLength >= 0) {
        addHeader("Content-Length", Long.toString(contentLength));
      }
      if (contentLength != 0) {
        // setDoOutput(true) will change a GET method to POST, so only if contentLength != 0
        connection.setDoOutput(true);
        // see http://developer.android.com/reference/java/net/HttpURLConnection.html
        if (contentLength >= 0 && contentLength <= Integer.MAX_VALUE) {
          connection.setFixedLengthStreamingMode((int) contentLength);
        } else {
          connection.setChunkedStreamingMode(0);
        }
        content.writeTo(connection.getOutputStream());
      }
    }
    // connect
    connection.connect();
    return new NetHttpResponse(connection);
  }

  @Override
  public void setContent(HttpContent content) {
    this.content = content;
  }
}
