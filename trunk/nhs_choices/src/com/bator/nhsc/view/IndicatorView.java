package com.bator.nhsc.view;

import com.bator.nhsc.R;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Paint;
import android.graphics.RectF;
import android.util.AttributeSet;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.ImageView;

public class IndicatorView extends ImageView {

	public IndicatorView(Context context) { super(context); init(context); }
	public IndicatorView(Context context, AttributeSet attrs, int defStyle) { super(context, attrs, defStyle); init(context); }
	public IndicatorView(Context context, AttributeSet attrs) {	super(context, attrs); init(context); }

	private void init(Context context) {
		Animation animation = AnimationUtils.loadAnimation(getContext(), R.anim.indicator_rotation);
		startAnimation(animation);
	}
	@Override
	public void setVisibility(int visibility) {
		super.setVisibility(visibility);
		if (visibility == View.VISIBLE) {
			getAnimation().startNow();
		} else {
			getAnimation().reset();
			invalidate();
		}
	}
	@Override
	protected void onDraw(Canvas canvas) {
		if (getVisibility() == View.VISIBLE) {
			super.onDraw(canvas);
		} else {
			Paint paint = new Paint();
			paint.setAlpha(0);
			canvas.drawRect(new RectF(0, 0, getWidth(), getHeight()), paint);
		}
	}
}