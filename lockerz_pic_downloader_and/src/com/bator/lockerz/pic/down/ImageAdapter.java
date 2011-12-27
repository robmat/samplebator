package com.bator.lockerz.pic.down;

import java.util.List;
import android.content.Context;
import android.graphics.Bitmap;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Gallery;
import android.widget.ImageView;

public class ImageAdapter extends BaseAdapter {
    int mGalleryItemBackground;
    private Context mContext;
    private List<Bitmap> bitmaps;
    public ImageAdapter(Context c, List<Bitmap> bitmaps) {
        mContext = c;
        this.bitmaps = bitmaps;
    }

    public int getCount() {
        return bitmaps.size();
    }

    public Object getItem(int position) {
        return position;
    }

    public long getItemId(int position) {
        return position;
    }

    public View getView(int position, View convertView, ViewGroup parent) {
		ImageView imageView = new ImageView(mContext);
		imageView.setImageBitmap(bitmaps.get(position));
		imageView.setLayoutParams(new Gallery.LayoutParams(150, 100));
		imageView.setScaleType(ImageView.ScaleType.FIT_XY);
		imageView.setBackgroundResource(mGalleryItemBackground);
		return imageView;
    }
}