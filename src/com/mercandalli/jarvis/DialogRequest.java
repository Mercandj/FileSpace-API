package com.mercandalli.jarvis;

import org.json.JSONException;
import org.json.JSONObject;

import android.app.Dialog;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.mercandalli.jarvis.model.User;
import com.mercandalli.jarvis.net.IPostExecuteListener;
import com.mercandalli.jarvis.net.PostTask;

public class DialogRequest extends Dialog {
	
	public DialogRequest(final Application app) {
		super(app);
		
		this.setContentView(R.layout.view_request);
		this.setTitle(R.string.app_name);
		this.setCancelable(true);
	    
        ((Button) this.findViewById(R.id.request)).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				User user = new User();				
				user.username = app.config.getUserUsername();
				user.password = app.config.getUserPassword();
				
				JSONObject json = new JSONObject();
				try {
					json.put("user", user.getJsonRegister());					
					if(!((EditText) DialogRequest.this.findViewById(R.id.json)).getText().toString().replace(" ", "").equals(""))
						json.put("content", new JSONObject(((EditText) DialogRequest.this.findViewById(R.id.json)).getText().toString()));					
				} catch (JSONException e1) {
					e1.printStackTrace();
				}
				
				if(!((EditText) DialogRequest.this.findViewById(R.id.server)).getText().toString().equals(""))
					(new PostTask(app.config.getUrlServer()+((EditText) DialogRequest.this.findViewById(R.id.server)).getText().toString(), new IPostExecuteListener() {
						@Override
						public void execute(JSONObject json) {
							if(json==null) {
								Log.d("DialogRequest", "json == null");
								Toast.makeText(app, "json == null", Toast.LENGTH_SHORT).show();
							}
							else {
								Log.d("DialogRequest", ""+json.toString());
								Toast.makeText(app, ""+json.toString(), Toast.LENGTH_SHORT).show();
							}
						}						
					}, json)).execute();
				DialogRequest.this.dismiss();
			}        	
        });
        DialogRequest.this.show();
	}
}
