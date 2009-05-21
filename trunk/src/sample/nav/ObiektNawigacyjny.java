package sample.nav;

import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

import javax.faces.context.ExternalContext;
import javax.faces.context.FacesContext;
import javax.servlet.http.HttpServletRequest;

public class ObiektNawigacyjny implements IObiektNawigacyjny {
	private List<IParametr> parametry = new ArrayList<IParametr>();
	private String strona;
	
	public void dodajParametr(IParametr param) {
		if (param == null)
			return;
		if (parametry == null) {
			parametry = new ArrayList<IParametr>();
		}
		parametry.add(param);
	}
	public boolean equals(Object obj) {
		if (obj instanceof IObiektNawigacyjny) {
			IObiektNawigacyjny on = (IObiektNawigacyjny) obj;
			return getId().equals(on.getId());
		} else {
			return false;
		}
	}

	public String getId() {
		return this.toString();
	}
	public List<IParametr> getParametry() {
		return parametry;
	}
	public String getStrona() {
		return strona;
	}
	public String getStronaURL() {
		return podajStroneURL();
	}
	private String podajStroneURL() {
		final StringBuilder rezultat = new StringBuilder();
		if (strona == null || "".equals(strona)) {
			throw new IllegalStateException("Strona musi zostac podana aby zredagowac URL!");
		}
		rezultat.append(strona);
		if (parametry != null && !parametry.isEmpty()) {
			rezultat.append("?");
			for (IParametr p : parametry) {
				rezultat.append(p.getNazwa());
				rezultat.append("=");
				rezultat.append(p.getWartosc());
				if (parametry.indexOf(p) != parametry.size() - 1) {
					rezultat.append("&");
				}
			}
		}
		try {
			final FacesContext fc = FacesContext.getCurrentInstance();
			final ExternalContext ec = fc.getExternalContext();
			final HttpServletRequest hsr = (HttpServletRequest) ec.getRequest();
			final String appUrl = hsr.getRequestURL().toString();
			final String nazwa = hsr.getContextPath(); 
			final String contekst = appUrl.split(nazwa)[0];
			final URL url = new URL(contekst + rezultat.toString());
			return url.toString();
		} catch (MalformedURLException e) {
			e.printStackTrace();
		}
		return rezultat.toString();
	}
	public void setStrona(String strona_) {
		strona = strona_;
	}
	public void usunParametr(IParametr param) {
		if (param == null)
			return;
		if (parametry == null) {
			parametry = new ArrayList<IParametr>();
			return;
		}
		if (parametry.contains(param)) {
			parametry.remove(param);
		}
	}
}
