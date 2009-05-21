package sample.translate;

import java.util.Locale;
import java.util.ResourceBundle;

import samplel.beans.MojeZiarenko;

public class ZasobyTlumacz extends ATlumacz {
	public String getEtykieta(String klucz) {
		final Locale lokacja = MojeZiarenko.getLokalizacja();
		final ResourceBundle etykiety = ResourceBundle.getBundle("etykiety", lokacja);
		return etykiety.getString(klucz);
	}
}
