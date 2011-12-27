package com.bator.lockerz.pic.down;

import java.util.List;

public class GoogleResult {
	public List<Item> items;
	static class Item {
		public String title;
		public String link;
		public PageMap pagemap;
	}
	static class PageMap {
		public CSEImage[] cse_image = new CSEImage[0];
		public CSEImage[] cse_thumbnail = new CSEImage[0];
	}
	static class CSEImage {
		public String src;
	}
}
