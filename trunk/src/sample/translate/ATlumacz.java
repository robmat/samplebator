package sample.translate;

import java.lang.reflect.InvocationTargetException;

public abstract class ATlumacz {
	public abstract String getEtykieta(String klucz);
	@SuppressWarnings("unchecked")
	public static ATlumacz getInstancja() {
		try {
			Class klasa = Class.forName("sample.translate.ZasobyTlumacz");
			ATlumacz tlumacz = (ATlumacz) klasa.getConstructor().newInstance();
			return tlumacz;
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
}
