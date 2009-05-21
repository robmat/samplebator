package sample.nav;

import java.io.IOException;

import javax.faces.application.NavigationHandler;
import javax.faces.context.FacesContext;

public class ParametryObsluga extends NavigationHandler {
	private NavigationHandler baza;
	public ParametryObsluga(NavigationHandler nh) {
		baza = nh;
	}
	public void handleNavigation(FacesContext fc, String metoda, String akcja) {
		if (akcja != null && akcja.startsWith("sample.nav.")) {
			final FabrykaNawigacji fab = FabrykaNawigacji.getInstancja();
			IObiektNawigacyjny on = fab.pobierzNawigacje(akcja);
			try {
				fc.getExternalContext().redirect(on.getStronaURL());
			} catch (IOException e) {
				e.printStackTrace();
				baza.handleNavigation(fc, metoda, akcja);
			}
			FabrykaNawigacji.getInstancja().dezaktywujNawigacje(akcja);
		} else {
			baza.handleNavigation(fc, metoda, akcja);
		}
	}
}
