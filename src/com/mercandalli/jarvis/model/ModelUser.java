/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarvis.model;

import org.json.JSONException;
import org.json.JSONObject;

public class ModelUser {
	public String username;
	public String password;
	
	public ModelUser() {
		
	}
	
	public ModelUser(String username, String password) {
		super();
		this.username = username;
		this.password = password;
	}

	public JSONObject getJsonRegister() {
		if(username!=null && password!=null) {
			JSONObject json = new JSONObject();			
			try {
				json.put("username", username);
				json.put("password", password);
			} catch (JSONException e) {
				e.printStackTrace();
				return null;
			}
			return json;
		}
		return null;
	}
	
	public JSONObject getJsonLogin() {
		return getJsonRegister();
	}
}
