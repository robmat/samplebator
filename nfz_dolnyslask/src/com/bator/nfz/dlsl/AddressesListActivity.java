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
import org.htmlparser.tags.TableColumn;
import org.htmlparser.tags.TableTag;
import org.htmlparser.util.NodeList;
import org.htmlparser.util.ParserException;

import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.AbsListView.LayoutParams;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;

import com.bator.nfz.dlsl.util.ActivityUtil;
import com.bator.nfz.dlsl.util.Windows1250Encoding;

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
							String locationEncoded = Windows1250Encoding.encode(location);
							URL url = new URL("http://www.nfz-wroclaw.pl/gsl/gsleasyp.aspx?lev=3&val1=" + benefitId + "&val2=" + serviceId + "&val4=" + locationEncoded + "&pagea=1&pageb=" + i);
							Log.v("TAG", url.toString() + " " + locationEncoded);
							URLConnection connection = url.openConnection();
							Parser parser = new Parser(connection);
							NodeList nodes = parser.parse(new NodeFilter() {
								private static final long serialVersionUID = 8716046850571851002L;
								private boolean inResultsTable = false;
								//private TableTag tableTagResults;
								public boolean accept(Node node) {
									if (node instanceof TableTag && ((TableTag) node).getAttribute("bordercolor") != null && ((TableTag) node).getAttribute("bordercolor").equalsIgnoreCase("blue")) {
										inResultsTable = true;
										//tableTagResults = (TableTag) node;
									}
									if (inResultsTable && node instanceof TableColumn) {
										return node.toHtml().contains("<br />");
									}
									return false;
								}
							});
							if (nodes.size() == 0) break;
							for (Node node : nodes.toNodeArray()) {
								Address address = null;
								try {
									address = new Address(node);
								} catch (Exception e) {
									Log.e("TAG", "Error: ", e);
									continue;
								}
								if (!nodeList.contains(address) && address != null) {
									nodeList.add(address);
								} else {
									donwload = false;
								}
							}
							updateProgressDialog(i++);
							updateList();
						}
						runOnUiThread(new Runnable() { public void run() { dismissDialog(DIALOG_PROGRESS); }});
					} catch (final MalformedURLException e) {
						runOnUiThread(new Runnable() { public void run() { ActivityUtil.showErrDialog(AddressesListActivity.this, e); } }); 
					} catch (final IOException e) {
						runOnUiThread(new Runnable() { public void run() { ActivityUtil.showErrDialog(AddressesListActivity.this, e); } });
					} catch (final ParserException e) {
						runOnUiThread(new Runnable() { public void run() { ActivityUtil.showErrDialog(AddressesListActivity.this, e); } });
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
					public View getView(final int position, View convertView, ViewGroup parent) {
						LinearLayout linearLayout = new LinearLayout(AddressesListActivity.this);
						linearLayout.setLayoutParams(new LayoutParams(android.view.ViewGroup.LayoutParams.FILL_PARENT, android.view.ViewGroup.LayoutParams.WRAP_CONTENT));
						linearLayout.setOrientation(LinearLayout.VERTICAL);
						
						TextView textView = new TextView(AddressesListActivity.this);
						textView.setLayoutParams(new LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.WRAP_CONTENT));
						textView.setText(nodeList.get(position).name.toUpperCase());
						textView.setTextAppearance(AddressesListActivity.this, android.R.style.TextAppearance_Large);
						textView.setPadding(10, 10, 10, 10);
						linearLayout.addView(textView);
						
						textView = new TextView(AddressesListActivity.this);
						textView.setLayoutParams(new LayoutParams(LayoutParams.FILL_PARENT, LayoutParams.WRAP_CONTENT));
						textView.setText(getString(R.string.address) + ": " + nodeList.get(position).address);
						textView.setTextAppearance(AddressesListActivity.this, android.R.style.TextAppearance_Large);
						textView.setPadding(10, 10, 10, 10);
						linearLayout.addView(textView);
						
						ImageButton imageButton = new ImageButton(AddressesListActivity.this);
						imageButton.setLayoutParams(new LayoutParams(android.view.ViewGroup.LayoutParams.FILL_PARENT, android.view.ViewGroup.LayoutParams.WRAP_CONTENT));
						imageButton.setImageResource(android.R.drawable.ic_dialog_map);
						linearLayout.addView(imageButton);
						imageButton.setOnClickListener(new OnClickListener() {
							public void onClick(View v) {
								Intent intent = new Intent(getApplicationContext(), MapsActivity.class);
								intent.putExtra(MapsActivity.ADDRESS_KEY, nodeList.get(position).address);
								intent.putExtra(MapsActivity.ADDRESS_NAME, nodeList.get(position).name);
								startActivity(intent);
							}
						});
						
						Button button = null;
						if (!AddressesListActivity.this.isEmpty(nodeList.get(position).register)) {
							button = new Button(AddressesListActivity.this);
							button.setLayoutParams(new LayoutParams(android.view.ViewGroup.LayoutParams.FILL_PARENT, android.view.ViewGroup.LayoutParams.WRAP_CONTENT));
							button.setText(getString(R.string.register) + ": " + nodeList.get(position).register);
							button.setOnClickListener(new OnClickListener() {
								public void onClick(View v) {
									String number = nodeList.get(position).register;
									if (number.startsWith("0")) number = number.substring(1);
									if (number.contains("w.")) number = number.substring(0, number.indexOf("w."));
									Intent callIntent = new Intent(Intent.ACTION_CALL, Uri.parse("tel:" + number)); 
							        startActivity(callIntent);
								}
							});
							linearLayout.addView(button);
						}
						
						if (!AddressesListActivity.this.isEmpty(nodeList.get(position).information)) {
							button = new Button(AddressesListActivity.this);
							button.setLayoutParams(new LayoutParams(android.view.ViewGroup.LayoutParams.FILL_PARENT, android.view.ViewGroup.LayoutParams.WRAP_CONTENT));
							button.setText(getString(R.string.information) + ": " + nodeList.get(position).information);
							button.setOnClickListener(new OnClickListener() {
								public void onClick(View v) {
									String number = nodeList.get(position).information;
									if (number.startsWith("0")) number = number.substring(1);
									if (number.contains("w.")) number = number.substring(0, number.indexOf("w."));
									Intent callIntent = new Intent(Intent.ACTION_CALL, Uri.parse("tel:" + number)); 
							        startActivity(callIntent);
								}
							});
							linearLayout.addView(button);
						}
						
						if (!AddressesListActivity.this.isEmpty(nodeList.get(position).url)) {
							button = new Button(AddressesListActivity.this);
							button.setLayoutParams(new LayoutParams(android.view.ViewGroup.LayoutParams.FILL_PARENT, android.view.ViewGroup.LayoutParams.WRAP_CONTENT));
							button.setText(getString(R.string.wwwPage) + ": " + nodeList.get(position).url);
							button.setOnClickListener(new OnClickListener() {
								public void onClick(View v) {
									String url = nodeList.get(position).url;
									if (!url.startsWith("http://") && !url.startsWith("https://")) {
										   url = "http://" + url;
									}
									startActivity(new Intent(Intent.ACTION_VIEW, Uri.parse(url)));
								}
							});
							linearLayout.addView(button);
						}
						
						if (!AddressesListActivity.this.isEmpty(nodeList.get(position).email)) {
							button = new Button(AddressesListActivity.this);
							button.setLayoutParams(new LayoutParams(android.view.ViewGroup.LayoutParams.FILL_PARENT, android.view.ViewGroup.LayoutParams.WRAP_CONTENT));
							button.setText(getString(R.string.email) + ": " + nodeList.get(position).email);
							button.setOnClickListener(new OnClickListener() {
								public void onClick(View v) {
									Intent i = new Intent(Intent.ACTION_SEND);
									i.setType("message/rfc822") ;
									i.putExtra(Intent.EXTRA_EMAIL, new String[] { nodeList.get(position).email });
									i.putExtra(Intent.EXTRA_SUBJECT,"");
									i.putExtra(Intent.EXTRA_TEXT,"");
									startActivity(i);
								}
							});
							linearLayout.addView(button);
						}
						
						View view = new View(AddressesListActivity.this);
						view.setLayoutParams(new LayoutParams(android.view.ViewGroup.LayoutParams.FILL_PARENT, 2));
						view.setBackgroundColor(Color.parseColor("#FFFFFF"));
						linearLayout.addView(view);
						
						return linearLayout;
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
	private boolean isEmpty(String str) {
		return str == null || "".equals(str.trim());
	}
	static class Address {
		String name = "";
		String address = "";
		String register = "";
		String information = "";
		String email = "";
		String url = "";
		
		public Address(Node node) {
			String html = node.toHtml();
			String[] parts = html.split("<br />");
			name = parts[0].replaceAll("<td>", "").replaceAll("<b>", "").replaceAll("</b>", "").trim();
			address = parts[1];
			register = parts[2].replaceAll("rejestracja:", "").replaceAll("\\(", "").replaceAll("\\)", "").replaceAll(" ", "");
			information = parts[3].replaceAll("</td>", "").replaceAll("informacja:", "").replaceAll("\\(", "").replaceAll("\\)", "").replaceAll(" ", "");;
			if (parts.length > 4 && parts[4].contains("mailto:")) {
				email = parts[4].substring(parts[4].indexOf("mailto:") + "mailto:".length(), parts[4].indexOf("\">", parts[4].indexOf("mailto:")));
			}
			if (parts.length > 4 && parts[4].contains("newwin('")) {
				url = parts[4].substring(parts[4].indexOf("newwin('") + "newwin('".length(), parts[4].indexOf("')", parts[4].indexOf("newwin('")));
			}
			if (parts.length > 5 && parts[5].contains("mailto:")) {
				email = parts[5].substring(parts[5].indexOf("mailto:") + "mailto:".length(), parts[5].indexOf("\">", parts[5].indexOf("mailto:")));
			}
			if (parts.length > 5 && parts[5].contains("newwin('")) {
				url = parts[5].substring(parts[5].indexOf("newwin('") + "newwin('".length(), parts[5].indexOf("')", parts[5].indexOf("newwin('")));
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
