package com.bator.lockerz.pic.down;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.ByteArrayInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.List;
import org.apache.commons.io.IOUtils;
import android.app.Activity;
import android.app.ProgressDialog;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Gallery;
import com.bator.lockerz.pic.down.GoogleResult.Item;
import com.google.gson.Gson;

public class MainActivity extends Activity {
    public EditText searchField;
	public Button searchButton;
	public GoogleResult googleResult;
	public Gallery gallery;
	public ProgressDialog progressDialog;
	
	public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        searchField = (EditText) findViewById(R.id.search_term);
        gallery = (Gallery) findViewById(R.id.gallery_id);
        searchButton = (Button) findViewById(R.id.search_btn);
        searchButton.setOnClickListener(new OnClickListener() {
			public void onClick(View v) {
				new Thread(new Runnable() {
					public void run() {
						try {
							String searchTerm = URLEncoder.encode(searchField.getText().toString(), "UTF-8");
							URL url = new URL("https://www.googleapis.com/customsearch/v1?key=AIzaSyAuB8t_taV21SB-_g7FToCC0muLAwovEqk&cx=008955871564969737526:yjozf0asskm&q=" + searchTerm);
							InputStream is = url.openStream();
							//String str = IOUtils.toString(is);
							//System.out.println(str);
							Gson gson = new Gson();
							googleResult = gson.fromJson(new BufferedReader(new InputStreamReader(is)), GoogleResult.class);
							for (int i = 0; i < googleResult.items.size(); i++) {
								if (googleResult.items.get(i).pagemap == null || googleResult.items.get(i).pagemap.cse_image.length == 0)  {
									googleResult.items.remove(i);
									i--;
								}
							}
							final List<Bitmap> bitmaps = new ArrayList<Bitmap>();
							for (int i = 0; i < googleResult.items.size(); i++) {
								Log.v(MainActivity.class.getName(), "title: " + googleResult.items.get(i).title + " link: " + googleResult.items.get(i).link);
								url = new URL(googleResult.items.get(i).pagemap.cse_image[0].src);
								is = url.openStream();
								byte[] bytes = IOUtils.toByteArray(new BufferedInputStream(is));
								Bitmap bitmap = BitmapFactory.decodeByteArray(bytes, 0, bytes.length);
								bitmaps.add(bitmap);
							}
							
							runOnUiThread(new Runnable() {
								public void run() {
									gallery.setAdapter(new ImageAdapter(MainActivity.this, bitmaps));
								}
							});
						} catch (MalformedURLException e) {
							Log.e(MainActivity.class.getName(), "Error: ", e);
						} catch (UnsupportedEncodingException e) {
							Log.e(MainActivity.class.getName(), "Error: ", e);
						} catch (IOException e) {
							Log.e(MainActivity.class.getName(), "Error: ", e);
						}
					}
				}).start();
			}
		});
    }
}