package samplel.beans;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;
import java.util.Random;
import java.util.ResourceBundle;

import javax.faces.application.Application;
import javax.faces.component.html.HtmlDataTable;
import javax.faces.context.ExternalContext;
import javax.faces.context.FacesContext;
import javax.faces.event.ActionEvent;
import javax.servlet.http.HttpServletRequest;

import sample.model.Osoba;
import sample.nav.FabrykaNawigacji;
import sample.nav.IObiektNawigacyjny;
import sample.nav.IParametr;
import sample.nav.ProstyParametr;

public class MojeZiarenko {
	private List<Osoba> osoby = new ArrayList<Osoba>();
	private HtmlDataTable tabela = null;
	private String ile = "5";
	public MojeZiarenko() {
		super();
		osoby.add(new Osoba(1, "Zbyszek", "Jaster"));
		osoby.add(new Osoba(2, "Janek", "Gower"));
		osoby.add(new Osoba(3, "Marian", "Forester"));
		osoby.add(new Osoba(4, "Tadek", "Hylios"));
		osoby.add(new Osoba(5, "Cecyl", "Fawor"));
	}
	public String nawiguj() {
		final FabrykaNawigacji fab = FabrykaNawigacji.getInstancja();
		final IObiektNawigacyjny on = fab.getNowaNawigacja();
		on.setStrona("/sample/pages/home/tableSample.faces");
		final IParametr param = new ProstyParametr("parametr", "wartosc_parametru");
		on.dodajParametr(param);
		return on.getId();
	}
	public String getWartosc() {
		final FacesContext fc = FacesContext.getCurrentInstance();
		final ExternalContext ec = fc.getExternalContext();
		final HttpServletRequest hsr = (HttpServletRequest) ec.getRequest();
		return (String) hsr.getParameter("wartosc");
	}
	public static String generujString(int length) {
		StringBuilder strBuild = new StringBuilder();
		for (int i = 0; i < length; i++) {
			Random wheel = new Random();
			int low = 65;
			int high = 90;
			int m = wheel.nextInt(high - low + 1) + low;
			strBuild.append((char) m);
		}
		return strBuild.toString();
	}
	public void akcja(ActionEvent ae) {
		try {
			int ileInt = Integer.parseInt(ile);
			osoby = new ArrayList<Osoba>();
			for (int i = 0; i < ileInt; i++) {
				Osoba os = new Osoba(i, generujString(5), generujString(6));
				osoby.add(os);
			}
		} catch (NumberFormatException e) {
			e.printStackTrace();
		}
	}
	//Default locale
	public static Locale getLokalizacja() {
		final FacesContext kontekst = FacesContext.getCurrentInstance();
		final Application aplikacja = kontekst.getApplication();
		final Locale lokalizaja = aplikacja.getDefaultLocale();
		return lokalizaja;
	}
	public static void setLokalizacja(Locale lokalizacja) {
		final FacesContext kontekst = FacesContext.getCurrentInstance();
		final Application aplikacja = kontekst.getApplication();
		aplikacja.setDefaultLocale(lokalizacja);
	}
	public String getEtykietaPrzycisku() {
		final Locale lokalizacja = getLokalizacja();
		if (lokalizacja != null) {
			if (lokalizacja.equals(Locale.ENGLISH)) {
				return "Search";
			} else if (lokalizacja.equals(new Locale("pl"))) {
				return "Szukaj";
			}
		}
		return "lbl_domyslnaEtykieta";
	}
	public String getEtykietaPrzycisku2() {
		final Locale lokalizacja = getLokalizacja();
		final ResourceBundle etykiety = ResourceBundle.getBundle("etykiety", lokalizacja);
		final String etykieta = etykiety.getString("przycisk_lbl");
		return etykieta;
	}
	public HtmlDataTable getTabela() {
		return tabela;
	}
	public void setTabela(HtmlDataTable tabela) {
		this.tabela = tabela;
	}
	public List<Osoba> getOsoby() {
		return osoby;
	}
	public void setOsoby(List<Osoba> osoby) {
		this.osoby = osoby;
	}
	public String getIle() {
		return ile;
	}
	public void setIle(String ile) {
		this.ile = ile;
	}
}
