package com.bcm;

import net.rim.device.api.system.EncodedImage;
import net.rim.device.api.ui.Color;
import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.Font;
import net.rim.device.api.ui.Graphics;

public class LongButtonField extends Field {

	private static EncodedImage backgroundDisabled;
	private static EncodedImage backgroundEnabled;
	private EncodedImage background = backgroundDisabled;
	private int highlightColour = Color.GRAY;
	private int fieldWidth;
	private int fieldHeight;
	private String text;
	private int padding = 12;
	private int margin = 8;
	static {
		backgroundDisabled = EncodedImage.getEncodedImageResource("btn_dis.jpg");
		backgroundEnabled = EncodedImage.getEncodedImageResource("btn_ena.jpg");
	}

	public LongButtonField(String text, long style) {
		super(Field.FOCUSABLE);
		this.text = text;
		Font defaultFont = Font.getDefault();
		fieldHeight = defaultFont.getHeight() + padding + margin;
		fieldWidth = defaultFont.getAdvance(text) + margin;
	}

	protected boolean navigationClick(int status, int time) {
		fieldChangeNotify(1);
		return true;
	}

	protected void onFocus(int direction) {
		background = backgroundEnabled;
		highlightColour = Color.WHITE;
		invalidate();
	}

	protected void onUnfocus() {
		background = backgroundDisabled;
		highlightColour = Color.GRAY;
		invalidate();
	}

	public int getPreferredWidth() {
		return fieldWidth;
	}

	public int getPreferredHeight() {
		return fieldHeight;
	}

	protected void layout(int w, int h) {
		fieldWidth = w;
		setExtent(getPreferredWidth(), getPreferredHeight());
	}

	protected void drawFocus(Graphics graphics, boolean on) {

	}

	protected void fieldChangeNotify(int context) {
		getChangeListener().fieldChanged(this, context);
	}

	protected void paint(Graphics graphics) {
		int marginHalf = margin >> 1;
		EncodedImage img = ImageUtils.resize(background, fieldWidth - margin, fieldHeight - margin);
		graphics.drawImage(marginHalf, marginHalf, fieldWidth - marginHalf, fieldHeight - marginHalf, img, 0, 0, 0);
		graphics.setColor(highlightColour);
		int textWidth = graphics.getFont().getAdvance(text);
		int textHeight = graphics.getFont().getHeight();
		graphics.drawText(text, (fieldWidth - margin - textWidth) >> 1, (fieldHeight - margin + (padding >> 1) - textHeight) >> 1);
	}
}
