package samplel.beans;

import javax.faces.event.ActionEvent;

public class LoginZiarenko {
	private String user = "";
	private String pass = "";
	private String msg = "Podaj has³o i login.";
	private String poprzedniaStrona;
	private static final String USER = "user";
	private static final String PASS = "pass";
	
	public String loginAkcja() {
		if ("".equals(msg)) {
			return "toHomePage";
		}
		return "";
	}
	public void loginNasluch(ActionEvent ae) {
		if (USER.equals(user.trim()) && PASS.equals(pass.trim())) {
			msg = "";
		} else {
			msg = "Login lub has³o nie poprawne!";
		}
	}
	public boolean jestZalogowany() {
		return "".equals(msg);
	}
	public String getUser() {
		return user;
	}
	public void setUser(String user) {
		this.user = user;
	}
	public String getPass() {
		return pass;
	}
	public void setPass(String pass) {
		this.pass = pass;
	}
	public String getMsg() {
		return msg;
	}
	public void setMsg(String msg) {
		this.msg = msg;
	}
	public String getPoprzedniaStrona() {
		return poprzedniaStrona;
	}
	public void setPoprzedniaStrona(String poprzedniaStrona) {
		this.poprzedniaStrona = poprzedniaStrona;
	}
}
