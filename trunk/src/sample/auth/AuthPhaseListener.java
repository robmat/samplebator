package sample.auth;

import java.io.IOException;

import javax.faces.context.ExternalContext;
import javax.faces.context.FacesContext;
import javax.faces.event.PhaseEvent;
import javax.faces.event.PhaseId;
import javax.faces.event.PhaseListener;
import javax.servlet.http.HttpServletRequest;

import sample.comm.AFabrykaZasobow;
import samplel.beans.LoginZiarenko;


public class AuthPhaseListener implements PhaseListener {
	private static final long serialVersionUID = 2248969403858299677L;
	private static final String LOGIN_PAGE = "/sample/pages/home/loginPage.faces";
	public AuthPhaseListener() {
		super();
	}
	public void afterPhase(PhaseEvent pe) {
		final PhaseId phaseid = pe.getPhaseId();
		final AFabrykaZasobow fabryka = AFabrykaZasobow.getInstancja();
		final LoginZiarenko lz = fabryka.getLoginZiarenko();
		final FacesContext fc = pe.getFacesContext();
		final ExternalContext ec = fc.getExternalContext();
		final HttpServletRequest sr = (HttpServletRequest) ec.getRequest(); 
		final String page = sr.getRequestURI();
		final boolean autoryzowany = lz.jestZalogowany();
		if (phaseid == PhaseId.RESTORE_VIEW || phaseid == PhaseId.INVOKE_APPLICATION) {
			if (!LOGIN_PAGE.equals(page)) {
				if (autoryzowany){
					System.out.println("INFO: Authorization ok on page: " + page);
				} else {
					System.out.println("INFO: Authorization fault on page: " + page);
					try {
						lz.setPoprzedniaStrona(page);
						pe.getFacesContext().getExternalContext().redirect(LOGIN_PAGE);
					} catch (IOException e) {
						System.out.println("ERROR: Mozliwe ze nie ma takiej strony!");
					}
				}
			}
		}
	}
	public void beforePhase(PhaseEvent arg0) {
	}

	public PhaseId getPhaseId() {
		return PhaseId.ANY_PHASE;
	}

}
