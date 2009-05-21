package sample.nav;

import java.io.IOException;

import javax.faces.application.NavigationHandler;
import javax.faces.context.FacesContext;

import sample.comm.AFabrykaZasobow;
import samplel.beans.LoginZiarenko;

public class NawigacjaObluga extends NavigationHandler {
	private NavigationHandler baza;
	public NawigacjaObluga(NavigationHandler nh) {
		baza = nh;
	}
	public void handleNavigation(FacesContext fc, String metoda, String akcja) {
		final AFabrykaZasobow fabryka = AFabrykaZasobow.getInstancja();
		final LoginZiarenko loginZiarenko = fabryka.getLoginZiarenko();
		final String poprzedniaStrona = loginZiarenko.getPoprzedniaStrona();
		if (poprzedniaStrona != null) {
			try {
				loginZiarenko.setPoprzedniaStrona(null);
				fc.getExternalContext().redirect(poprzedniaStrona);
			} catch (IOException e) {
				System.out.println("ERROR: Mapowanie servletu " + poprzedniaStrona + " nie znalezione!");
				System.out.println("ERROR: Mozliwe ze nie ma takiej strony!");
				baza.handleNavigation(fc, metoda, akcja);
			}
		} else {
			baza.handleNavigation(fc, metoda, akcja);
		}
	}
}
