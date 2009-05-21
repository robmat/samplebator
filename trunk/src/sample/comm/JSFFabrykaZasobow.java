package sample.comm;

import javax.faces.application.Application;
import javax.faces.context.FacesContext;

import samplel.beans.LoginZiarenko;
import samplel.beans.RezultatyZiarenko;
import samplel.beans.SzukajZiarenko;


public class JSFFabrykaZasobow extends AFabrykaZasobow {

	@Override
	public RezultatyZiarenko getRezultatyZiarenko() {
		FacesContext fc = FacesContext.getCurrentInstance();
		if (fc != null) {
			Application app = fc.getApplication();
			RezultatyZiarenko ziarenko = (RezultatyZiarenko) app.evaluateExpressionGet(fc, "#{rezultatyZiarenko}", RezultatyZiarenko.class);
			return ziarenko == null ? new RezultatyZiarenko() : ziarenko;
		} else {
			return new RezultatyZiarenko();
		}
	}
	@Override
	public SzukajZiarenko getSzukajZiarenko() {
		FacesContext fc = FacesContext.getCurrentInstance();
		if (fc != null) {
			Application app = fc.getApplication();
			SzukajZiarenko ziarenko = (SzukajZiarenko) app.evaluateExpressionGet(fc, "#{szukajZiarenko}", SzukajZiarenko.class);
			return ziarenko == null ? new SzukajZiarenko() : ziarenko;
		} else {
			return new SzukajZiarenko();
		}
	}
	@Override
	public LoginZiarenko getLoginZiarenko() {
		FacesContext fc = FacesContext.getCurrentInstance();
		if (fc != null) {
			Application app = fc.getApplication();
			LoginZiarenko ziarenko = (LoginZiarenko) app.evaluateExpressionGet(fc, "#{loginZiarenko}", LoginZiarenko.class);
			return ziarenko == null ? new LoginZiarenko() : ziarenko;
		} else {
			return new LoginZiarenko();
		}
	}
}
