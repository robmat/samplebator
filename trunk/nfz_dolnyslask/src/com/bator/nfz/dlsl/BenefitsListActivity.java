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

import com.bator.nfz.dlsl.prefs.PrefsUtils;
import com.bator.nfz.dlsl.util.ActivityUtil;
import com.google.gson.Gson;

public class BenefitsListActivity extends Activity implements OnItemClickListener {

	private static final String NODE_LIST_KEYS_SAVED = "NODE_LIST_KEYS_SAVED";
	private static final String NODE_LIST_VALS_SAVED = "NODE_LIST_VALS_SAVED";
	private static final int DIALOG_ID_FIRST_USE = 1;
	private static final int DIALOG_ID_NO_NET = 2;
	private static final int DIALOG_PROGRESS = 3;
	
	ListView listView = null;
	ArrayList<String> nodeListKeys = new ArrayList<String>();
	ArrayList<String> nodeListVals = new ArrayList<String>();
	
	@SuppressWarnings("unchecked")
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.banefits_list);
		listView = (ListView) findViewById(R.id.benefit_list_list_id);
		checkIfFirstUse();
		if (savedInstanceState == null) {
			showDialog(DIALOG_PROGRESS);
			startListDownloading();
		} else if (savedInstanceState != null && savedInstanceState.getString(NODE_LIST_VALS_SAVED) != null && savedInstanceState.getString(NODE_LIST_KEYS_SAVED) != null) {
			nodeListKeys = new Gson().fromJson(savedInstanceState.getString(NODE_LIST_KEYS_SAVED), ArrayList.class);
			nodeListVals = new Gson().fromJson(savedInstanceState.getString(NODE_LIST_VALS_SAVED), ArrayList.class);
			updateList();
		}
	}

	@Override
	protected void onSaveInstanceState(Bundle outState) {
		super.onSaveInstanceState(outState);
		outState.putString(NODE_LIST_KEYS_SAVED, new Gson().toJson(nodeListKeys));
		outState.putString(NODE_LIST_VALS_SAVED, new Gson().toJson(nodeListVals));
	}
	
	private void startListDownloading() {
		if (ActivityUtil.isOnline(this)) {
			new Thread(new Runnable() {
				public void run() {
					try {
						URL url = new URL("http://www.nfz-wroclaw.pl/gsl/gsleasyp.aspx");
						URLConnection connection = url.openConnection();
						Parser parser = new Parser(connection);
						NodeList nodes = parser.parse(new NodeFilter() {
							private static final long serialVersionUID = 8716046850571851002L;
							public boolean accept(Node node) {
								return node instanceof OptionTag;
							}
						});
						for (Node node : nodes.toNodeArray()) {
							nodeListKeys.add(((OptionTag) node).getOptionText());
							nodeListVals.add(((OptionTag) node).getValue());
						}
						updateList();
					} catch (MalformedURLException e) {
						ActivityUtil.showErrDialog(BenefitsListActivity.this, e);
					} catch (IOException e) {
						ActivityUtil.showErrDialog(BenefitsListActivity.this, e);
					} catch (ParserException e) {
						ActivityUtil.showErrDialog(BenefitsListActivity.this, e);
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
						TextView textView = new TextView(BenefitsListActivity.this);
						textView.setLayoutParams(new LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.WRAP_CONTENT));
						textView.setText(nodeListKeys.get(position));
						textView.setTextAppearance(BenefitsListActivity.this, android.R.style.TextAppearance_Large);
						textView.setPadding(10, 10, 10, 10);
						return textView;
					}

					public long getItemId(int position) {
						return position;
					}

					public Object getItem(int position) {
						return nodeListKeys.get(position);
					}

					public int getCount() {
						return nodeListKeys.size();
					}
				});
				listView.setOnItemClickListener(BenefitsListActivity.this);
				dismissDialog(DIALOG_PROGRESS);
			}
		};
		runOnUiThread(runnable);
	}
	
	private void checkIfFirstUse() {
		if (!PrefsUtils.getBoolean(PrefsUtils.PREFS_KEY_FIRST_LIST_DIALOG)) {
			showDialog(DIALOG_ID_FIRST_USE);
			PrefsUtils.setBoolean(PrefsUtils.PREFS_KEY_FIRST_LIST_DIALOG, true);
		}
	}

	@Override
	protected Dialog onCreateDialog(int id) {
		if (id == DIALOG_ID_FIRST_USE) {
			ActivityUtil.showDialog(this, R.string.benefit_list_first_use_dialog_title, R.string.benefit_list_first_use_dialog_msg);
		}
		if (id == DIALOG_ID_NO_NET) {
			ActivityUtil.showDialog(this, R.string.err, R.string.no_net_err);
		}
		if (id == DIALOG_PROGRESS) {
			return ProgressDialog.show(this, getString(R.string.wait_title), getString(R.string.download_msg), true, false);
		}
		return super.onCreateDialog(id);
	}

	public void onItemClick(AdapterView<?> arg0, View arg1, int arg2, long arg3) {
		String value = nodeListVals.get(arg2);
		Intent intent = new Intent(getApplicationContext(), DetailedBenefitsListActivity.class);
		intent.putExtra(DetailedBenefitsListActivity.EXTRA_BENEFIT_ID, value);
		startActivity(intent);
	}
}