package com.bcm;

import net.rim.device.api.ui.Font;
import net.rim.device.api.ui.Graphics;
import net.rim.device.api.ui.component.LabelField;

public class MultiRowLabelField extends LabelField {

	private String[] lbl;
	private Object arbitraryData;
	private INavListener navListener;

	public MultiRowLabelField(String[] lbl, long style) {
		super("", style);
		this.lbl = lbl;
	}

	public int getPreferredHeight() {
		int height = getFont().getHeight() * (lbl == null ? 0 : lbl.length);
		return height;
	}

	public int getPreferredWidth() {
		int width = 0;
		Font f = getFont();
		if (lbl != null) {
			for (int i = 0; i < lbl.length; i++) {
				if (f.getAdvance(lbl[i]) > width) {
					width = f.getAdvance(lbl[i]);
				}
			}
		}
		return width > EntryPoint.sw ? width : EntryPoint.sw;
	}

	protected void layout(int width, int height) {
		setExtent(getPreferredWidth(), getPreferredHeight());
	}

	protected void paint(Graphics g) {
		if (lbl != null) {
			int y = 0;
			int rowHeight = g.getFont().getHeight();
			for (int i = 0; i < lbl.length; i++) {
				g.drawText(lbl[i], 0, y);
				y += rowHeight;
			}
		}
	}

	protected boolean navigationClick(int arg0, int arg1) {
		if (navListener != null) {
			navListener.navClcik(this);
		}
		return true;
	}

	public Object getArbitraryData() {
		return arbitraryData;
	}

	public void setArbitraryData(Object arbitraryData) {
		this.arbitraryData = arbitraryData;
	}

	public void setNavListener(INavListener l) {
		this.navListener = l;
	}
}
