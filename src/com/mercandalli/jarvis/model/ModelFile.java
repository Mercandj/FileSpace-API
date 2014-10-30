package com.mercandalli.jarvis.model;

import org.json.JSONException;
import org.json.JSONObject;

public class ModelFile {
	public String url;
	public String name;
	public String size;
	public boolean isDirectory;
	
	public JSONObject getJSONRequest() {
		if(url!=null) {
			JSONObject json = new JSONObject();			
			try {
				json.put("name", name);
				json.put("url", name);
			} catch (JSONException e) {
				e.printStackTrace();
				return null;
			}
			return json;
		}
		return null;
	}
}
