package com.bator.ifonly;

import java.net.URI;
import java.util.ArrayList;

import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import android.os.Bundle;
import android.os.Handler;
import android.os.Handler.Callback;
import android.os.Message;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;

import com.bator.ifonly.util.Utils;
import com.bator.ifonly.util.Utils.VID_CATEGORY;

public class VideosListActivity extends ActivityBase implements Callback {
	public static final int VIDEO_LIST_SUCCESS = 1;
	private Document document;
	private Handler handler = new Handler(this);
	private ListView listView;
	private ArrayList<Video> videosList = new ArrayList<Video>();
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.video_list);
		listView = (ListView) findViewById(R.id.video_list_id);
		listView.setAdapter(new ArrayAdapter<Video>(this, R.layout.video_list, videosList) {
			@Override
			public View getView(int position, View convertView, ViewGroup parent) {
				View itemView = getLayoutInflater().inflate(R.layout.vide_list_item, null);
				TextView titleView = (TextView) itemView.findViewById(R.id.video_list_title);
				titleView.setText(videosList.get(position).title);
				return itemView;
			}
		});
		new Thread(new Runnable() {
			public void run() {
				try {
					String category = VID_CATEGORY.fromString(new URI(getIntent().getData().toString()).getHost()).getStr(VideosListActivity.this);
					document = Utils.getVideosDOM("published", category);
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
			parseVideoFeed();
		}
		return false;
	}
	private void parseVideoFeed() {
		NodeList entries = document.getElementsByTagName("entry");
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
			}
			videosList.add(videoObj);
		}
		((ArrayAdapter<String>)listView.getAdapter()).notifyDataSetChanged();
	}
	class Video {
		public String title;
	}
}