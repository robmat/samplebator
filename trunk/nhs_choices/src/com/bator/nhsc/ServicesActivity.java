package com.bator.nhsc;

import java.util.ArrayList;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import android.app.Activity;
import android.app.ProgressDialog;
import android.os.Bundle;
import android.util.Log;
import android.util.Pair;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView.LayoutParams;
import android.widget.ListView;
import android.widget.TextView;

import com.bator.nhsc.net.DownloadingAdapter;
import com.bator.nhsc.net.DownloadingAdapter.IDownloadingAdapterListener;
import com.bator.nhsc.util.XmlUtil;

public class ServicesActivity extends Activity implements IDownloadingAdapterListener<Pair<String, String>> {
    
	ProgressDialog progressDialog;
	String TAG = getClass().getSimpleName();
	DownloadingAdapter<Pair<String, String>> adapter;
	
	@Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.banefits_list);
        adapter = new DownloadingAdapter<Pair<String, String>>(this, "http://v1.syndication.nhschoices.nhs.uk/organisations.xml?apikey=PHRJCDTY");
        ListView listView = (ListView) findViewById(R.id.benefit_list_list_id);
        listView.setAdapter(adapter);
    }

	@Override
	public View getView(int i, View v, ViewGroup parent) {
		Pair<String, String> pair = adapter.getModel().get(i);
		TextView textView = new TextView(this);
		textView.setLayoutParams(new LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.WRAP_CONTENT));
		textView.setText(pair.first);
		return textView;
	}

	@Override
	public void donwloadStart() {
		Runnable r = new Runnable() {
			public void run() {
				progressDialog = new ProgressDialog(ServicesActivity.this);
				progressDialog.setTitle("Downloading data...");
				progressDialog.setProgressStyle(ProgressDialog.STYLE_HORIZONTAL);
				progressDialog.show();
			}
		};
		runOnUiThread(r);
	}

	@Override
	public void donwloadFinished() {
		Runnable r = new Runnable() {
			public void run() {
				progressDialog.dismiss();
				progressDialog = null;
			}
		};
		runOnUiThread(r);
	}

	@Override
	public void donwloadProgress(final int prorgess, final int max) {
		Runnable r = new Runnable() {
			public void run() {
				progressDialog.setProgress(prorgess);
				progressDialog.setMax(max);
			}
		};
		runOnUiThread(r);
	}

	@Override
	public void downloadError(Exception exception) {
		Log.e(TAG , "Error: ", exception);
	}

	@Override
	public void downloadParseResult(Document document, ArrayList<Pair<String, String>> model) {
		NodeList linkNodes = document.getElementsByTagName("Link");
		for (int i = 0; i < linkNodes.getLength(); i++) {
			Node linkNode = linkNodes.item(i);
			NodeList linkChildren = linkNode.getChildNodes();
			String text = "";
			String link = "";
			for (int j = 0; j < linkChildren.getLength(); j++) {
				Node linkChild = linkChildren.item(j);
				if (linkChild.getNodeName().equals("Text")) {
					text = XmlUtil.getTextFromNode(linkChild);
				}
				if (linkChild.getNodeName().equals("Uri")) {
					link = XmlUtil.getTextFromNode(linkChild);
				}
			}
			model.add(new Pair<String, String>(text, link));
		}
	}
}