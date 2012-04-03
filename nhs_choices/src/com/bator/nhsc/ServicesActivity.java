package com.bator.nhsc;

import java.util.ArrayList;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.util.Pair;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView.LayoutParams;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;

import com.bator.nhsc.net.DownloadingAdapter;
import com.bator.nhsc.net.DownloadingAdapter.IDownloadingAdapterListener;
import com.bator.nhsc.util.XmlUtil;

public class ServicesActivity extends Activity implements IDownloadingAdapterListener<Pair<String, String>>, OnItemClickListener {
    
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
        listView.setOnItemClickListener(this);
    }

	public View getView(int i, View v, ViewGroup parent) {
		Pair<String, String> pair = adapter.getModel().get(i);
		LinearLayout linearLayout = new LinearLayout(this);
		linearLayout.setLayoutParams(new LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.WRAP_CONTENT));
		linearLayout.setBackgroundDrawable(getResources().getDrawable(R.drawable.item_back));
		linearLayout.setGravity(Gravity.CENTER);
		
		TextView textView = new TextView(this);
		textView.setLayoutParams(new LayoutParams(LayoutParams.WRAP_CONTENT, LayoutParams.WRAP_CONTENT));
		textView.setText(pair.first);
		textView.setTextAppearance(ServicesActivity.this, android.R.style.TextAppearance_Medium);
		textView.setGravity(Gravity.CENTER);
		
		linearLayout.addView(textView);
		return linearLayout;
	}

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

	public void donwloadFinished() {
		Runnable r = new Runnable() {
			public void run() {
				progressDialog.dismiss();
				progressDialog = null;
			}
		};
		runOnUiThread(r);
	}

	public void donwloadProgress(final int prorgess, final int max) {
		Runnable r = new Runnable() {
			public void run() {
				progressDialog.setProgress(prorgess);
				progressDialog.setMax(max);
			}
		};
		runOnUiThread(r);
	}

	public void downloadError(Exception exception) {
		Log.e(TAG , "Error: ", exception);
	}

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
			if (text.equals("Strategic Health Authorities") || text.equals("GP-led Health Centres")) {
				continue;
			}
			model.add(new Pair<String, String>(text, link));
		}
	}

	public void onItemClick(AdapterView<?> listView, View view, int index, long id) {
		Intent intent = new Intent(getApplicationContext(), MapsActivity.class);
		intent.putExtra(MapsActivity.URI_KEY, adapter.getModel().get(index).second);
		startActivity(intent);
	}
}