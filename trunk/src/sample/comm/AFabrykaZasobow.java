package sample.comm;

import java.lang.reflect.InvocationTargetException;
import java.util.Locale;
import java.util.ResourceBundle;

import samplel.beans.LoginZiarenko;
import samplel.beans.RezultatyZiarenko;
import samplel.beans.SzukajZiarenko;

public abstract class AFabrykaZasobow {
	public abstract SzukajZiarenko getSzukajZiarenko();
	public abstract LoginZiarenko getLoginZiarenko();
	public abstract RezultatyZiarenko getRezultatyZiarenko();
	@SuppressWarnings("unchecked")
	public static AFabrykaZasobow getInstancja() {
		try {
			Class klasa = Class.forName("sample.comm.JSFFabrykaZasobow");
			AFabrykaZasobow fabryka = (AFabrykaZasobow) klasa.getConstructor().newInstance();
			return fabryka;
		} catch (ClassNotFoundException e) {
			e.printStackTrace();
		} catch (SecurityException e) {
			e.printStackTrace();
		} catch (NoSuchMethodException e) {
			e.printStackTrace();
		} catch (IllegalArgumentException e) {
			e.printStackTrace();
		} catch (InstantiationException e) {
			e.printStackTrace();
		} catch (IllegalAccessException e) {
			e.printStackTrace();
		} catch (InvocationTargetException e) {
			e.printStackTrace();
		} catch (ClassCastException e) {
			e.printStackTrace();
		}
		return null;
	}
	public static void main(String[] args) {
		AFabrykaZasobow fab = AFabrykaZasobow.getInstancja();
		RezultatyZiarenko ziarenko = fab.getRezultatyZiarenko();
		System.out.println("AFabrykaZasobow.main():" + ziarenko);
		ResourceBundle bundle = ResourceBundle.getBundle("etykiety", new Locale("PL"));
		String lbl = bundle.getString("lbl_przycisk");
		System.out.println(lbl);
	}
}
