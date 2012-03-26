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
import org.htmlparser.nodes.TagNode;
import org.htmlparser.nodes.TextNode;
import org.htmlparser.tags.TableColumn;
import org.htmlparser.tags.TableTag;
import org.htmlparser.util.NodeList;
import org.htmlparser.util.ParserException;

import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.os.Bundle;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView.LayoutParams;
import android.widget.BaseAdapter;
import android.widget.ListView;
import android.widget.TextView;

import com.bator.nfz.dlsl.util.ActivityUtil;

public class AddressesListActivity extends Activity {
	public final static String EXTRA_SERVICE_ID = "EXTRA_SERVICE_ID";
	private static final int DIALOG_ID_NO_NET = 1;
	private static final int DIALOG_PROGRESS = 2;
	
	ListView listView;
	List<Address> nodeList = new ArrayList<Address>();
	ProgressDialog progressDialog;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.banefits_list);
		((TextView) findViewById(R.id.benefit_list_prompt_id)).setText(getString(R.string.benefit_list_prompt_results));
		listView = (ListView) findViewById(R.id.benefit_list_list_id);
		String benefitId = getIntent().getStringExtra(LocationListActivity.EXTRA_BENEFIT_ID);
		String location = getIntent().getStringExtra(ServiceListActivity.EXTRA_LOCATION_ID);
		String serviceId = getIntent().getStringExtra(EXTRA_SERVICE_ID);
		startDownloading(benefitId, location, serviceId);
	}

	private void startDownloading(final String benefitId, final String location, final String serviceId) {
		if (ActivityUtil.isOnline(this)) {
			new Thread(new Runnable() {
				public void run() {
					try {
						boolean donwload = true;
						int i = 1;
						while (donwload) {
							runOnUiThread(new Runnable() { public void run() { showDialog(DIALOG_PROGRESS); }});
							//String locationEncoded = URLEncoder.encode(location);
							URL url = new URL("http://www.nfz-wroclaw.pl/gsl/gsleasyp.aspx?lev=3&val1=" + benefitId + "&val2=" + serviceId + "&val4=" + location + "&pagea=1&pageb=" + i);
							URLConnection connection = url.openConnection();
							Parser parser = new Parser(connection);
							NodeList nodes = parser.parse(new NodeFilter() {
								private static final long serialVersionUID = 8716046850571851002L;
								public boolean accept(Node node) {
									if (node instanceof TableColumn && node.getParent().getParent() instanceof TableTag) {
										return ((TableTag) node.getParent().getParent()).getAttribute("bordercolor") != null && ((TableTag) node.getParent().getParent()).getAttribute("bordercolor").equalsIgnoreCase("blue");
									}
									return false;
								}
							});
							if (nodes.size() == 0) break;
							for (Node node : nodes.toNodeArray()) {
								Address address = new Address(node);
								if (!nodeList.contains(address)) {
									nodeList.add(address);
								} else {
									donwload = false;
								}
							}
							updateProgressDialog(i++);
							updateList();
						}
						runOnUiThread(new Runnable() { public void run() { dismissDialog(DIALOG_PROGRESS); }});
					} catch (MalformedURLException e) {
						ActivityUtil.showErrDialog(AddressesListActivity.this, e);
					} catch (IOException e) {
						ActivityUtil.showErrDialog(AddressesListActivity.this, e);
					} catch (ParserException e) {
						ActivityUtil.showErrDialog(AddressesListActivity.this, e);
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
						TextView textView = new TextView(AddressesListActivity.this);
						textView.setLayoutParams(new LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.WRAP_CONTENT));
						textView.setText(nodeList.get(position).name.toUpperCase());
						textView.setTextAppearance(AddressesListActivity.this, android.R.style.TextAppearance_Large);
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
	static class Address {
		String name = "";
		String address = "";
		String register = "";
		String information = "";
		String email = "";
		String url = "";
		
		public Address(Node node) {
			boolean firstBrEncountered = false;
			for (int i  = 0; i < node.getChildren().size(); i++ ) {
				Node child = node.getChildren().elementAt(i);
				if (child instanceof TextNode && !firstBrEncountered) {
					name += ((TextNode) child).getText().trim();
				}
				if (child instanceof TagNode && ((TagNode)child).getRawTagName().equals("/b")) {
					firstBrEncountered = true;
				}
			}
		}
		@Override
		public int hashCode() {
			final int prime = 31;
			int result = 1;
			result = prime * result + ((address == null) ? 0 : address.hashCode());
			result = prime * result + ((email == null) ? 0 : email.hashCode());
			result = prime * result + ((information == null) ? 0 : information.hashCode());
			result = prime * result + ((name == null) ? 0 : name.hashCode());
			result = prime * result + ((register == null) ? 0 : register.hashCode());
			result = prime * result + ((url == null) ? 0 : url.hashCode());
			return result;
		}
		@Override
		public boolean equals(Object obj) {
			if (this == obj)
				return true;
			if (obj == null)
				return false;
			if (getClass() != obj.getClass())
				return false;
			Address other = (Address) obj;
			if (address == null) {
				if (other.address != null)
					return false;
			} else if (!address.equals(other.address))
				return false;
			if (email == null) {
				if (other.email != null)
					return false;
			} else if (!email.equals(other.email))
				return false;
			if (information == null) {
				if (other.information != null)
					return false;
			} else if (!information.equals(other.information))
				return false;
			if (name == null) {
				if (other.name != null)
					return false;
			} else if (!name.equals(other.name))
				return false;
			if (register == null) {
				if (other.register != null)
					return false;
			} else if (!register.equals(other.register))
				return false;
			if (url == null) {
				if (other.url != null)
					return false;
			} else if (!url.equals(other.url))
				return false;
			return true;
		}
	}
}
