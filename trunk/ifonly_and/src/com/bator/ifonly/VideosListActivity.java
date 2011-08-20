package com.bator.ifonly;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
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
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;

import com.bator.ifonly.util.Utils;
import com.bator.ifonly.util.Utils.VID_CATEGORY;

public class VideosListActivity extends ActivityBase implements Callback {
	public static final SimpleDateFormat parseDateFormat = new SimpleDateFormat("yyyy-MM-dd'T'hh:mm:ss.SSS'Z'");
	public static final SimpleDateFormat displayDateFormat = new SimpleDateFormat("MMMM dd, yyyy");
	public static final int VIDEO_LIST_SUCCESS = 1;
	private Document document;
	private Handler handler = new Handler(this);
	private ListView listView;
	private ArrayList<Video> videosList = new ArrayList<Video>();
	private VID_CATEGORY vidCategory;
	private Dialog dialog;
	private String orderByStr = "published";
	private String queryString = "";
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		vidCategory = VID_CATEGORY.fromString(getIntent().getData().getHost());
		setContentView(R.layout.video_list);
		setTopBarTitle(vidCategory.getStr(this));
		backButtonListenerSetup();
		listView = (ListView) findViewById(R.id.video_list_id);
		listView.setAdapter(new ArrayAdapter<Video>(this, R.layout.video_list, videosList) {
			@Override
			public View getView(int position, View convertView, ViewGroup parent) {
				View itemView = getLayoutInflater().inflate(R.layout.vide_list_item, null);
				TextView titleView = (TextView) itemView.findViewById(R.id.video_list_title);
				titleView.setText(videosList.get(position).title);
				TextView dateView = (TextView) itemView.findViewById(R.id.video_list_date);
				dateView.setText(displayDateFormat.format(videosList.get(position).date));
				TextView durationView = (TextView) itemView.findViewById(R.id.video_list_duration);
				durationView.setText(getString(R.string.video_list_seconds, videosList.get(position).duration));
				ImageView categoryImage = (ImageView) itemView.findViewById(R.id.video_list_category_image);
				categoryImage.setImageResource(vidCategory.getImageResId());
				return itemView;
			}
		});
		fetchData();
		listView.setOnItemClickListener(new OnItemClickListener() {
			@Override
			public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
				startActivity(new Intent(Intent.ACTION_VIEW, Uri.parse(videosList.get(position).url)));
			}
		});
		findViewById(R.id.video_list_order_by_btn_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				String[] options = { getString(R.string.video_list_order_by_date), getString(R.string.video_list_order_by_views) };
				AlertDialog.Builder builder = new AlertDialog.Builder(VideosListActivity.this);
				builder.setSingleChoiceItems(options, orderByStr.equals("published") ? 0 : 1, new DialogInterface.OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {
						orderByStr = which == 0 ? "published" : "viewCount";
						dialog.dismiss();
						fetchData();
					}
				});
				AlertDialog dialog = builder.create();
				dialog.setOwnerActivity(VideosListActivity.this);
				dialog.show();
			}
		});
		findViewById(R.id.video_list_search_btn_id).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				showDialog(-1);
			}
		});
	}
	@Override
	protected Dialog onCreateDialog(final int id) {
		final AlertDialog.Builder alert = new AlertDialog.Builder(this);
		final EditText input = new EditText(this);
		alert.setView(input);
		alert.setPositiveButton(getString(R.string.video_list_search), new DialogInterface.OnClickListener() {
			public void onClick(DialogInterface dialog, int whichButton) {
				queryString = input.getText().toString().trim();
				fetchData();
			}
		});
		alert.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
			public void onClick(DialogInterface dialog, int whichButton) {
				dialog.cancel();
			}
		});
		return alert.create();
	}
	public void fetchData() {
		dialog = ProgressDialog.show(this, "", getString(R.string.video_list_progress_dialog_title), true, false);
		new Thread(new Runnable() {
			public void run() {
				try {
					String category = vidCategory.getStr(VideosListActivity.this);
					String query = (category.contains(" ") ? category.substring(0, category.indexOf(" ")) : category) + " " + queryString;
					document = Utils.getVideosDOM(orderByStr, query);
					handler.sendEmptyMessage(VIDEO_LIST_SUCCESS);
				} catch (Exception e) {
					Log.e("VideosListActivity", "onCreate", e);
				}
			}
		}).start();
	}
	@Override
	public boolean handleMessage(Message msg) {
		if (msg.what == VIDEO_LIST_SUCCESS) {
			dialog.dismiss();
			parseVideoFeed();
		}
		return false;
	}
	@SuppressWarnings("unchecked")
	private void parseVideoFeed() {
		try {
			videosList.clear();
			NodeList entries = document.getElementsByTagName("entry");
			NodeList durations = document.getElementsByTagName("yt:duration");
			for (int i = 0; i < entries.getLength(); i++) {
				Video videoObj = new Video();
				NodeList entryChildren = entries.item(i).getChildNodes();
				for (int j = 0; j < entryChildren.getLength(); j++) {
					Node entryChild = entryChildren.item(j);
					if ("title".equals(entryChild.getNodeName())) {
						entryChild.normalize();
						String title = entryChild.getFirstChild().getNodeValue();
						videoObj.title = title;
					}
					if ("published".equals(entryChild.getNodeName())) {
						entryChild.normalize();
						String published = entryChild.getFirstChild().getNodeValue();
						videoObj.date = parseDateFormat.parse(published);
					}
					if ("media:group".equals(entryChild.getNodeName())) {
						NodeList mediaContentChildren = entryChild.getChildNodes();
						for (int k = 0; k < mediaContentChildren.getLength(); k++) {
							Node mediaContentChild = mediaContentChildren.item(k);
							if ("media:content".equals(mediaContentChild.getNodeName()) && videoObj.url == null) {
								videoObj.url = mediaContentChild.getAttributes().getNamedItem("url").getNodeValue();
							}
						}
					}
					videoObj.duration = Integer.parseInt(durations.item(i).getAttributes().getNamedItem("seconds").getNodeValue());
				}
				videosList.add(videoObj);
			}
			((ArrayAdapter<String>) listView.getAdapter()).notifyDataSetChanged();
			document = null;
		} catch (Exception e) {
			Log.e("VideosListActivity", "parseVideoFeed", e);
		}
	}
	
	class Video {
		public String title;
		public Date date;
		public int duration;
		public String url;
	}
}