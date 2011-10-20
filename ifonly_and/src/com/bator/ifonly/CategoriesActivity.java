package com.bator.ifonly;

import java.util.HashMap;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.Handler.Callback;
import android.os.Message;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;

import com.bator.ifonly.util.Utils;
import com.bator.ifonly.util.Utils.VID_CATEGORY;

public class CategoriesActivity extends ActivityBase implements Callback {
	private Handler handler = new Handler(this);
	private int VIDEO_LIST_ERROR = 1;
	private int REFRESH_MSG = 2;
	private HashMap<VID_CATEGORY, Integer> countMap = null;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.categories);
		setTopBarTitle(getString(R.string.categories_title));
		backButtonListenerSetup();
		((ListView) findViewById(R.id.categories_list_id)).setAdapter(new ArrayAdapter<VID_CATEGORY>(this, R.layout.category_list_item, VID_CATEGORY.values()) {
			@Override
			public View getView(int position, View convertView, ViewGroup parent) {
				View itemView = getLayoutInflater().inflate(R.layout.category_list_item, null);
				((ImageView)itemView.findViewById(R.id.categories_list_item_image_id)).setImageResource(VID_CATEGORY.values()[position].getImageResId());
				((TextView)itemView.findViewById(R.id.categories_list_category_title_id)).setText(VID_CATEGORY.values()[position].getStr(CategoriesActivity.this));
				String title = countMap == null || countMap.get(VID_CATEGORY.values()[position]) == null ? "(0)" : "(" + countMap.get(VID_CATEGORY.values()[position]) + ")";
				((TextView)itemView.findViewById(R.id.categories_list_category_count_id)).setText(title);
				return itemView;
			}
		});
		findViewById(R.id.video_list_about_btn_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				startActivity(new Intent(Utils.ABOUT_ACTION));
			}
		});
		findViewById(R.id.video_list_feedback_btn_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Intent i = new Intent(Intent.ACTION_SEND);
				i.setType("text/plain");
				i.putExtra(Intent.EXTRA_EMAIL, new String[] { "s.v.rook@bath.ac.uk" });
				i.putExtra(Intent.EXTRA_SUBJECT, "Your feedback subject.");
				i.putExtra(Intent.EXTRA_TEXT, "Your feedback.");
				startActivity(i);
			}
		});
		findViewById(R.id.video_list_categories_btn_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				startActivity(new Intent(Utils.CATEGORIES_ACTION));
			}
		});
		((ListView) findViewById(R.id.categories_list_id)).setOnItemClickListener(new AdapterView.OnItemClickListener() {
			@Override
			public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
				VID_CATEGORY vidCat =  VID_CATEGORY.values()[position];
				Intent i = new Intent(Utils.VIDEO_LIST_ACTION, Uri.parse("category://" + vidCat.toString()));
				startActivity(i);
			}
		});
		new Thread(new Runnable() {
			public void run() {
				try {
					Document doc = Utils.getVideosDOM("viewCount", "");
					NodeList mediaContentNodes = doc.getElementsByTagName("media:title");
					countMap = new HashMap<VID_CATEGORY, Integer>();
					for (int i = 0; i < mediaContentNodes.getLength(); i++) {
						Node elem = mediaContentNodes.item(i).getFirstChild();
						String title = elem.getNodeValue();
						for (VID_CATEGORY vidCat : VID_CATEGORY.values()) {
							if (title.contains(vidCat.getStr(CategoriesActivity.this))) {
								if (!countMap.containsKey(vidCat)) {
									countMap.put(vidCat, 1);
								} else {
									Integer count = countMap.get(vidCat);
									countMap.put(vidCat, ++count);
								}
							}
						}
					}
					handler.sendEmptyMessage(REFRESH_MSG);
				} catch (Exception e) {
					Log.e("VideosListActivity", "onCreate", e);
					Message m = new Message();
					m.what = VIDEO_LIST_ERROR;
					m.obj = e.getClass().toString() + ": " + e.getMessage();
					handler.sendMessage(m);
				}
			}
		}).start();
	}
	@SuppressWarnings("unchecked")
	@Override
	public boolean handleMessage(Message msg) {
		if (msg.what == VIDEO_LIST_ERROR) {
			AlertDialog.Builder builder = new AlertDialog.Builder(this);
			builder.setTitle(R.string.video_list_error);
			builder.setMessage(msg.obj.toString());
			builder.setNegativeButton("Ok", new DialogInterface.OnClickListener() {
				@Override
				public void onClick(DialogInterface dialog, int which) {
					playPlak();
					dialog.dismiss();
				}
			});
		}
		if (msg.what == REFRESH_MSG) {
			((ArrayAdapter<VID_CATEGORY>) ((ListView) findViewById(R.id.categories_list_id)).getAdapter()).notifyDataSetChanged();
		}
		return false;
	}
}
