package com.bator.nhsc.net;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.InputStream;
import java.net.URL;
import java.net.URLConnection;
import java.util.ArrayList;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.Document;

import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;

public class DownloadingAdapter<T> extends BaseAdapter {
	String TAG = getClass().getSimpleName();

	ArrayList<T> model = new ArrayList<T>();
	IDownloadingAdapterListener<T> listener;
	String url = "";

	public DownloadingAdapter(IDownloadingAdapterListener<T> listener, String url) {
		super();
		this.listener = listener;
		this.url = url;
		startDownload();
	}

	public void startDownload() {
		Runnable r = new Runnable() {
			public void run() {
				try {
					listener.donwloadStart();
					URL url = new URL(DownloadingAdapter.this.url);
					URLConnection connection = url.openConnection();
					InputStream inputStream = connection.getInputStream();
					int length = connection.getContentLength();
					int b = -1;
					int count = 0;
					ByteArrayOutputStream baos = new ByteArrayOutputStream();
					while ((b = inputStream.read()) != -1) {
						baos.write(b);
						count++;
						if (count % 100 == 0) {
							listener.donwloadProgress(count, length);
						}
					}
					String resultStr = new String(baos.toByteArray());
					Log.v(TAG, resultStr);
					parseResponse(resultStr);
					listener.donwloadProgress(count, length);
					listener.donwloadFinished();
					listener.runOnUiThread(new Runnable() { public void run() { notifyDataSetChanged(); }});
				} catch (Exception e) {
					listener.downloadError(e);
				}
			}

		};
		new Thread(r).start();
	}

	private void parseResponse(String resultStr) {
		try {
			DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
			DocumentBuilder builder = factory.newDocumentBuilder();
			Document dom = builder.parse(new ByteArrayInputStream(resultStr.getBytes()));
			listener.downloadParseResult(dom, model);
		} catch (Exception e) {
			listener.downloadError(e);
		}
	}

	public int getCount() {
		return model.size();
	}

	public Object getItem(int i) {
		return model.get(i);
	}

	public long getItemId(int i) {
		return i;
	}

	public View getView(int i, View v, ViewGroup parent) {
		return listener.getView(i, v, parent);
	}

	public static interface IDownloadingAdapterListener<T> {
		View getView(int i, View v, ViewGroup parent);

		void donwloadStart();

		void donwloadFinished();

		void donwloadProgress(int prorgess, int max);

		void downloadError(Exception exception);
		
		void downloadParseResult(Document document, ArrayList<T> model);
		
		void runOnUiThread(Runnable r);
	}

	public ArrayList<T> getModel() {
		return model;
	}
}
