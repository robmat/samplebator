package sampl;

import java.util.Date;
import org.joda.time.DateTime;
import org.richfaces.model.CalendarDataModel;
import org.richfaces.model.CalendarDataModelItem;

public class EvenOddCalendarModel implements CalendarDataModel {
	public static void main(String[] args) {
	}
	private CalendarDataModelItem getItemByDate(Date date) {
		DateTime dt = new DateTime(date);
		final boolean even = (dt.getDayOfMonth() % 2 == 0) ? true : false;
		return new CalendarDataModelItem() {
			public Object getData() {
				return null;
			}
			public int getDay() {
				return 0;
			}
			public String getStyleClass() {
				return even ? "even" : "odd";
			}
			public Object getToolTip() {
				return null;
			}

			public boolean hasToolTip() {
				return false;
			}
			public boolean isEnabled() {
				return true;
			}
		};
	}
	public CalendarDataModelItem[] getData(Date[] dates) {
		CalendarDataModelItem[] items = new CalendarDataModelItem[dates.length];
		for (int i = 0; i < items.length; i++) {
			items[i] = getItemByDate(dates[i]);
		}
		return items;
	}
	
	public Object getToolTip(Date arg0) {
		return "TOOLTIP";
	}
}
