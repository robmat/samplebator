package sample.translate;

import java.beans.FeatureDescriptor;
import java.util.Iterator;

import javax.el.ELContext;
import javax.el.ELException;
import javax.el.ELResolver;
import javax.el.PropertyNotFoundException;
import javax.el.PropertyNotWritableException;


public class TlumaczeniaELResolver extends ELResolver {
	public static final String EL_TAG = "tlumacz";
	@Override
	public Class<?> getCommonPropertyType(ELContext arg0, Object arg1) {
		System.out.println("TlumaczeniaELResolver.getCommonPropertyType()");;
		return null;
	}
	@Override
	public Iterator<FeatureDescriptor> getFeatureDescriptors(ELContext arg0, Object arg1) {
		System.out.println("TlumaczeniaELResolver.getFeatureDescriptors()");
		return null;
	}
	@Override
	public Class<?> getType(ELContext arg0, Object arg1, Object arg2) throws NullPointerException, PropertyNotFoundException, ELException {
		System.out.println("TlumaczeniaELResolver.getType()");
		return String.class;
	}
	@Override
	public Object getValue(ELContext context, Object base, Object property) throws NullPointerException, PropertyNotFoundException, ELException {
		Object rezultat = null;
		if (base == null && ((String)property).equalsIgnoreCase(EL_TAG)){
			ATlumacz tlumacz = ATlumacz.getInstancja();
			rezultat = tlumacz;
		}
		if (base instanceof ATlumacz) {
			rezultat = ((ATlumacz) base).getEtykieta((String) property);
		}
		context.setPropertyResolved(rezultat != null);
		return rezultat;
	}
	@Override
	public boolean isReadOnly(ELContext arg0, Object arg1, Object arg2) throws NullPointerException, PropertyNotFoundException, ELException {
		System.out.println("TlumaczeniaELResolver.isReadOnly()");
		return true;
	}
	@Override
	public void setValue(ELContext arg0, Object arg1, Object arg2, Object arg3) throws NullPointerException, PropertyNotFoundException, PropertyNotWritableException, ELException {
		System.out.println("TlumaczeniaELResolver.setValue()");
	}
}
