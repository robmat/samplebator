package com.bcm;

import net.rim.device.api.ui.Field;
import net.rim.device.api.ui.Graphics;
import net.rim.device.api.ui.XYRect;
import net.rim.device.api.ui.component.LabelField;

public class CellularLabelfield extends LabelField {

	private String[] texts;
	private int[] widthPercent;
	private boolean underline = false;
	private boolean hasUrl;
	private String url;

	public CellularLabelfield(String[] texts, int[] widthPercent) {
		super(" ", Field.USE_ALL_WIDTH);
		this.texts = texts;
		this.widthPercent = widthPercent;
	}

	public CellularLabelfield(String[] texts, int[] widthPercent, long style) {
		super(" ", Field.USE_ALL_WIDTH | style);
		this.texts = texts;
		this.widthPercent = widthPercent;
	}

	public CellularLabelfield(String[] texts, int[] widthPercent, long style, boolean underline) {
		super(" ", Field.USE_ALL_WIDTH | style);
		this.texts = texts;
		this.widthPercent = widthPercent;
		this.underline = underline;
	}

	protected void paint(Graphics graphics) {
		XYRect rect = graphics.getClippingRect();
		int screenWidth = rect.width;
		float[] colWidths = new float[widthPercent.length];
		for (int i = 0; i < colWidths.length; i++) {
			colWidths[i] = (int) (((float) widthPercent[i] / 100) * screenWidth);
			String text = texts[i];
			int textWidth = graphics.getFont().getAdvance(text);
			boolean wide = false;
			while (textWidth > colWidths[i]) {
				wide = true;
				text = text.substring(0, text.length() - 1);
				textWidth = graphics.getFont().getAdvance(text + "...");
			}
			texts[i] = text + (wide ? "..." : "");
		}
		// Prepare widths for drawing
		for (int i = colWidths.length - 1; i > 0; i--) {
			colWidths[i] = colWidths[i - 1];
		}
		colWidths[0] = 0;
		for (int i = 2; i < colWidths.length; i++) {
			if (i < colWidths.length) {
				colWidths[i] += colWidths[i - 1];
			}
		}
		for (int i = 0; i < texts.length; i++) {
			graphics.drawText(texts[i], (int) colWidths[i], rect.y);
		}
		if (underline) {
			graphics.drawLine(rect.x, rect.y + rect.height - 1, rect.x + rect.width, rect.y + rect.height - 1);
		}
	}
	public void setHasUrl(boolean b) {
		hasUrl = b;
	}
	public boolean isHasUrl() {
		return hasUrl;
	}
	public void setUrl(String value) {
		url = value;
	}
	public String getUrl() {
		return url;
	}
}
