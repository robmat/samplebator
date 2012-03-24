package com.bator.nfz.dlsl;

import java.io.IOException;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLConnection;

import org.htmlparser.Node;
import org.htmlparser.NodeFilter;
import org.htmlparser.Parser;
import org.htmlparser.tags.OptionTag;
import org.htmlparser.util.NodeList;
import org.htmlparser.util.ParserException;

import android.app.Activity;
import android.app.Dialog;
import android.os.Bundle;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView.LayoutParams;
import android.widget.BaseAdapter;
import android.widget.ListView;
import android.widget.TextView;

import com.bator.nfz.dlsl.prefs.PrefsUtils;
import com.bator.nfz.dlsl.util.ActivityUtil;

public class BenefitsListActivity extends Activity {

	private static final int DIALOG_ID_FIRST_USE = 1;
	private static final int DIALOG_ID_NO_NET = 2;
	ListView listView = null;
	NodeList nodeList = null;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.banefits_list);
		listView = (ListView) findViewById(R.id.benefit_list_list_id);
		checkIfFirstUse();
		startListDownloading();
	}

	private void startListDownloading() {
		if (ActivityUtil.isOnline(this)) {
			new Thread(new Runnable() {
				public void run() {
					try {
						URL url = new URL("http://www.nfz-wroclaw.pl/gsl/gsleasyp.aspx");
						URLConnection connection = url.openConnection();
						Parser parser = new Parser(connection);
						nodeList = parser.parse(new NodeFilter() {
							private static final long serialVersionUID = 8716046850571851002L;
							public boolean accept(Node node) {
								return node instanceof OptionTag;
							}
						});
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
						textView.setText(((OptionTag)nodeList.elementAt(position)).getOptionText());
						textView.setTextAppearance(BenefitsListActivity.this, android.R.style.TextAppearance_Large);
						textView.setPadding(10, 10, 10, 10);
						return textView;
					}

					public long getItemId(int position) {
						return position;
					}

					public Object getItem(int position) {
						return nodeList.elementAt(position);
					}

					public int getCount() {
						return nodeList.size();
					}
				});
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
		return super.onCreateDialog(id);
	}
}