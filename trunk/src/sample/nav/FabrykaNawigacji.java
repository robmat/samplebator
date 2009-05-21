package sample.nav;

import java.util.ArrayList;
import java.util.List;

import javax.faces.context.ExternalContext;
import javax.faces.context.FacesContext;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpSession;

public class FabrykaNawigacji {
	private List<IObiektNawigacyjny> onLista = new ArrayList<IObiektNawigacyjny>();
	private static final String SP = "FabrykaNawigacji";
	public static FabrykaNawigacji getInstancja() {
		final FacesContext fc = FacesContext.getCurrentInstance();
		final ExternalContext ec = fc.getExternalContext();
		final HttpServletRequest hsr = (HttpServletRequest) ec.getRequest();
		final HttpSession sesja = hsr.getSession();
		FabrykaNawigacji fab = (FabrykaNawigacji) sesja.getAttribute(SP);
		if (fab == null) {
			fab = new FabrykaNawigacji();
			sesja.setAttribute(SP, fab);
		}
		return fab;
	}
	public IObiektNawigacyjny getNowaNawigacja() {
		IObiektNawigacyjny on = new ObiektNawigacyjny();
		onLista.add(on);
		return on;
	}
	public IObiektNawigacyjny pobierzNawigacje(final String id) {
		IObiektNawigacyjny on = new IObiektNawigacyjny() {
			public void dodajParametr(IParametr param) {}
			public String getId() {	return id; }
			public List<IParametr> getParametry() {	return null; }
			public String getStrona() {	return null; }
			public String getStronaURL() { return null;	}
			public void setStrona(String strona) {}
			public void usunParametr(IParametr param) {}
			public boolean equals(Object obj) {
				if (obj instanceof IObiektNawigacyjny) {
					IObiektNawigacyjny on = (IObiektNawigacyjny) obj;
					return getId().equals(on.getId());
				} else {
					return false;
				}
			}
		};
		int indeks = onLista.indexOf(on);
		if (indeks != -1) {
			return onLista.get(indeks);
		} else {
			return null;
		}
	}
	public boolean dezaktywujNawigacje(final String id) {
		IObiektNawigacyjny on = new IObiektNawigacyjny() {
			public void dodajParametr(IParametr param) {}
			public String getId() {	return id; }
			public List<IParametr> getParametry() {	return null; }
			public String getStrona() {	return null; }
			public String getStronaURL() { return null;	}
			public void setStrona(String strona) {}
			public void usunParametr(IParametr param) {}
			public boolean equals(Object obj) {
				if (obj instanceof IObiektNawigacyjny) {
					IObiektNawigacyjny on = (IObiektNawigacyjny) obj;
					return getId().equals(on.getId());
				} else {
					return false;
				}
			}
		};
		return onLista.remove(on);
	}
}
