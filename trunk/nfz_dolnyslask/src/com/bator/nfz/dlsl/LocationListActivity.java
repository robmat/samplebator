package com.bator.nfz.dlsl;

import java.io.IOException;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;
import java.util.ArrayList;

import org.htmlparser.Node;
import org.htmlparser.NodeFilter;
import org.htmlparser.Parser;
import org.htmlparser.tags.OptionTag;
import org.htmlparser.tags.SelectTag;
import org.htmlparser.util.NodeList;
import org.htmlparser.util.ParserException;

import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView.LayoutParams;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.BaseAdapter;
import android.widget.ListView;
import android.widget.TextView;

import com.admob.android.ads.AdView;
import com.bator.nfz.dlsl.util.ActivityUtil;

public class LocationListActivity extends Activity implements OnItemClickListener {
	public static final String EXTRA_BENEFIT_ID = "EXTRA_BENEFIT_ID";
	private static final int DIALOG_ID_NO_NET = 1;
	private static final int DIALOG_PROGRESS = 2;
	
	ListView listView;
	ArrayList<String> nodeList = new ArrayList<String>();
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.banefits_list);
		((TextView) findViewById(R.id.benefit_list_prompt_id)).setText(getString(R.string.benefit_list_prompt_localization));
		listView = (ListView) findViewById(R.id.benefit_list_list_id);
		String benefitId = getIntent().getStringExtra(EXTRA_BENEFIT_ID);
		showDialog(DIALOG_PROGRESS);
		startListDownloading(benefitId);
		
		AdView adView = (AdView) findViewById(R.id.adMobAd);
		adView.requestFreshAd();
	}
	private void startListDownloading(final String benefitId) {
		if (ActivityUtil.isOnline(this)) {
			new Thread(new Runnable() {
				public void run() {
					try {
						URL url = new URL("http://www.nfz-wroclaw.pl/gsl/gsleasyp.aspx?lev=2&val1=" + benefitId + "&val2=0&val3=0&pagea=1");
						URLConnection connection = url.openConnection();
						Parser parser = new Parser(connection);
						NodeList nodes = parser.parse(new NodeFilter() {
							private static final long serialVersionUID = 8716046850571851002L;
							public boolean accept(Node node) {
								return node != null && node instanceof OptionTag && node.getParent() instanceof SelectTag && ((SelectTag)node.getParent()).getAttribute("id").equals("_lev4");
							}
						});
						for (Node node : nodes.toNodeArray()) {
							nodeList.add(((OptionTag)node).getValue().equals("") ?  getString(R.string.whole_lower_silesia) : ((OptionTag)node).getValue());
						}
						updateList();
					} catch (final MalformedURLException e) {
						runOnUiThread(new Runnable() { public void run() { ActivityUtil.showErrDialog(LocationListActivity.this, e); } }); 
					} catch (final IOException e) {
						runOnUiThread(new Runnable() { public void run() { ActivityUtil.showErrDialog(LocationListActivity.this, e); } }); 
					} catch (final ParserException e) {
						runOnUiThread(new Runnable() { public void run() { ActivityUtil.showErrDialog(LocationListActivity.this, e); } }); 
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
						TextView textView = new TextView(LocationListActivity.this);
						textView.setLayoutParams(new LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.WRAP_CONTENT));
						textView.setText(nodeList.get(position).toUpperCase());
						textView.setTextAppearance(LocationListActivity.this, android.R.style.TextAppearance_Large);
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
				listView.setOnItemClickListener(LocationListActivity.this);
				dismissDialog(DIALOG_PROGRESS);
			}
		};
		runOnUiThread(runnable);
	}
	protected Dialog onCreateDialog(int id) {
		if (id == DIALOG_ID_NO_NET) {
			return ActivityUtil.showDialog(this, R.string.err, R.string.no_net_err);
		}
		if (id == DIALOG_PROGRESS) {
			return ProgressDialog.show(this, getString(R.string.wait_title), getString(R.string.download_msg), true, false);
		}
		return super.onCreateDialog(id);
	}
	public void onItemClick(AdapterView<?> arg0, View arg1, int index, long arg3) {
		Intent intent = new Intent(getApplicationContext(), ServiceListActivity.class);
		intent.putExtra(EXTRA_BENEFIT_ID, getIntent().getStringExtra(EXTRA_BENEFIT_ID));
		intent.putExtra(ServiceListActivity.EXTRA_LOCATION_ID, nodeList.get(index).equals(getString(R.string.whole_lower_silesia)) ? "" : nodeList.get(index));
		startActivity(intent);
	}
}	
