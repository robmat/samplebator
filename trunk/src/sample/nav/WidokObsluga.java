package sample.nav;

import javax.el.ELException;
import javax.faces.application.Application;
import javax.faces.context.FacesContext;

import com.sun.faces.application.ViewHandlerImpl;

public class WidokObsluga extends ViewHandlerImpl {
	@Override
	public String getActionURL(FacesContext fc, String viewId) {
		String result = viewId;
		try {
			if (result.contains("#{")) {
				final Application app = fc.getApplication();
				result = (String) app.evaluateExpressionGet(fc, viewId, String.class);
			}
		} catch (ELException e) {
			e.printStackTrace();
		}
		return result;
	}
}
