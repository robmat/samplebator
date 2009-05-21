package sampl;

import java.util.Date;
import javax.faces.event.ActionEvent;
import javax.faces.event.ValueChangeEvent;
import org.richfaces.component.html.HtmlCalendar;
import org.richfaces.event.CurrentDateChangeEvent;
import org.richfaces.model.CalendarDataModel;

public class SampleBackingBean {
	private Date date = null;
	private CalendarDataModel model = new EvenOddCalendarModel(); 
	private HtmlCalendar calendar = null;
	public void actionListener(ActionEvent ae) {
		System.out.println("SampleBackingBean.actionListener(): " + ae);
		System.out.println("SampleBackingBean.actionListener(): " + calendar.getValue());
	}
	public void currentDateChangeListener(CurrentDateChangeEvent cdce) {
		System.out.println("SampleBackingBean.currentDateChangeListener(): " + cdce);
	}
	public void valueChangeListener(ValueChangeEvent vce) {
		System.out.println("SampleBackingBean.valueChangeListener(): " + vce);
	}
	public Date getDate() {
		System.out.println("SampleBackingBean.getDate(): " + date);
		return date;
	}
	public void setDate(Date date) {
		this.date = date;
		System.out.println("SampleBackingBean.setDate(): " + date);
	}
	public CalendarDataModel getModel() {
		return model;
	}
	public void setModel(CalendarDataModel model) {
		this.model = model;
	}
	public HtmlCalendar getCalendar() {
		return calendar;
	}
	public void setCalendar(HtmlCalendar calendar) {
		this.calendar = calendar;
	}
}
