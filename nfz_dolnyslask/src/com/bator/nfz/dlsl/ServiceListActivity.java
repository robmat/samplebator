package com.bator.nfz.dlsl;

import java.io.IOException;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.util.ArrayList;
import java.util.List;

import org.htmlparser.Node;
import org.htmlparser.NodeFilter;
import org.htmlparser.Parser;
import org.htmlparser.tags.LinkTag;
import org.htmlparser.util.NodeList;
import org.htmlparser.util.ParserException;

import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.util.Pair;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView.LayoutParams;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.BaseAdapter;
import android.widget.ListView;
import android.widget.TextView;

import com.admob.android.ads.AdManager;
import com.admob.android.ads.AdView;
import com.bator.nfz.dlsl.util.ActivityUtil;

public class ServiceListActivity extends Activity implements OnItemClickListener {
	public static final String EXTRA_LOCATION_ID = "EXTRA_LOCATION_ID";
	private static final int DIALOG_ID_NO_NET = 1;
	private static final int DIALOG_PROGRESS = 2;
	
	ListView listView;
	List<Pair<String, String>> nodeList = new ArrayList<Pair<String,String>>();
	ProgressDialog progressDialog;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.banefits_list);
		((TextView) findViewById(R.id.benefit_list_prompt_id)).setText(getString(R.string.benefit_list_prompt_service));
		listView = (ListView) findViewById(R.id.benefit_list_list_id);
		String benefitId = getIntent().getStringExtra(LocationListActivity.EXTRA_BENEFIT_ID);
		String location = getIntent().getStringExtra(EXTRA_LOCATION_ID);
		startListDownloading(benefitId, location);
		
		AdManager.setTestDevices( new String[] { "F84AF8E1636C8787E6E1078071B9EFE7" } );
		AdView adView = (AdView) findViewById(R.id.adMobAd);
		adView.requestFreshAd();
	}

	private void startListDownloading(final String benefitId, String location) {
		if (ActivityUtil.isOnline(this)) {
			new Thread(new Runnable() {
				public void run() {
					try {
						boolean donwload = true;
						int i = 1;
						while (donwload) {
							runOnUiThread(new Runnable() { public void run() { showDialog(DIALOG_PROGRESS); }});
							URL url = new URL("http://www.nfz-wroclaw.pl/gsl/gsleasyp.aspx?lev=2&val1=" + benefitId + "&val2=0&val3=0&pagea=" + i);
							URLConnection connection = url.openConnection();
							Parser parser = new Parser(connection);
							NodeList nodes = parser.parse(new NodeFilter() {
								private static final long serialVersionUID = 8716046850571851002L;

								public boolean accept(Node node) {
									return node != null && node instanceof LinkTag && ((LinkTag) node).getAttribute("class") != null && ((LinkTag) node).getAttribute("class").equals("red");
								}
							});
							for (Node node : nodes.toNodeArray()) {
								String val2 = ((LinkTag) node).getAttribute("onclick");
								val2 = val2.substring(val2.indexOf("val2=") + "val2=".length(), val2.indexOf("&", val2.indexOf("val2=")));
								if (!nodeList.contains(new Pair<String, String>(((LinkTag) node).getLinkText(), val2))) {
									nodeList.add(new Pair<String, String>(((LinkTag) node).getLinkText(), val2));
								} else {
									donwload = false;
								}
							}
							updateProgressDialog(i++);
							updateList();
						}
						runOnUiThread(new Runnable() { public void run() { dismissDialog(DIALOG_PROGRESS); }});
					} catch (final MalformedURLException e) {
						runOnUiThread(new Runnable() { public void run() { ActivityUtil.showErrDialog(ServiceListActivity.this, e); } }); 
					} catch (final IOException e) {
						runOnUiThread(new Runnable() { public void run() { ActivityUtil.showErrDialog(ServiceListActivity.this, e); } }); 
					} catch (final ParserException e) {
						runOnUiThread(new Runnable() { public void run() { ActivityUtil.showErrDialog(ServiceListActivity.this, e); } }); 
					}
				}

			}).start();
		} else {
			showDialog(DIALOG_ID_NO_NET);
		}
	}
	private void updateList() {
		Runnable runnable = new Runnable() {
			public void run() {
				listView.setAdapter(new BaseAdapter() {
					public View getView(int position, View convertView, ViewGroup parent) {
						TextView textView = new TextView(ServiceListActivity.this);
						textView.setLayoutParams(new LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.WRAP_CONTENT));
						textView.setText(nodeList.get(position).first.toUpperCase());
						textView.setTextAppearance(ServiceListActivity.this, android.R.style.TextAppearance_Large);
						textView.setPadding(10, 10, 10, 10);
						return textView;
					}

					public long getItemId(int position) {
						return position;
					}

					public Object getItem(int position) {
						return nodeList.get(position);
					}

					public int getCount() {
						return nodeList.size();
					}
				});
				listView.setOnItemClickListener(ServiceListActivity.this);
			}
		};
		runOnUiThread(runnable);
	}
	protected Dialog onCreateDialog(int id) {
		if (id == DIALOG_ID_NO_NET) {
			return ActivityUtil.showDialog(this, R.string.err, R.string.no_net_err);
		}
		if (id == DIALOG_PROGRESS) {
			progressDialog = new ProgressDialog(this);
			progressDialog.setTitle(getString(R.string.wait_title));
			progressDialog.setMessage(getString(R.string.download_msg));
			progressDialog.setIndeterminate(false);
			progressDialog.setCancelable(false);
			progressDialog.setMax(1);
			progressDialog.setProgressStyle(ProgressDialog.STYLE_HORIZONTAL);
			return progressDialog;
		}
		return super.onCreateDialog(id);
	}
	public void updateProgressDialog(final int progress) {
		runOnUiThread(new Runnable() {
			public void run() {
				if (progressDialog != null) {
					progressDialog.setProgress(progress);
					progressDialog.setMax(progress + 1);
				}
			}
		});
	}
	public void onItemClick(AdapterView<?> arg0, View arg1, int index, long arg3) {
		Intent intent = new Intent(getApplicationContext(), AddressesListActivity.class);
		intent.putExtra(LocationListActivity.EXTRA_BENEFIT_ID, getIntent().getStringExtra(LocationListActivity.EXTRA_BENEFIT_ID));
		intent.putExtra(ServiceListActivity.EXTRA_LOCATION_ID, getIntent().getStringExtra(ServiceListActivity.EXTRA_LOCATION_ID));
		intent.putExtra(AddressesListActivity.EXTRA_SERVICE_ID, nodeList.get(index).second);
		startActivity(intent);
	}
}
