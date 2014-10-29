package com.mercandalli.jarpis;

import org.json.JSONException;
import org.json.JSONObject;

import android.app.Dialog;
import android.view.View;
import android.widget.Button;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ToggleButton;

import com.mercandalli.jarpis.model.User;
import com.mercandalli.jarpis.net.IPostExecuteListener;
import com.mercandalli.jarpis.net.PostTask;

public class DialogInit extends Dialog {

	boolean firstUse = true;
	
	public DialogInit(final Application app) {
		super(app);
		
		this.setContentView(R.layout.view_login);
		this.setTitle(R.string.app_name);
		this.setCancelable(false);
        
        if(app.config.getUrlServer()!=null)
        	if(!app.config.getUrlServer().equals(""))
        		((EditText) this.findViewById(R.id.server)).setText(app.config.getUrlServer());
        
        if(app.config.getUserUsername()!=null)
        	if(!app.config.getUserUsername().equals("")) {
        		((EditText) this.findViewById(R.id.username)).setText(app.config.getUserUsername());
        		firstUse = false;
        	}
        
        if(app.config.getUserPassword()!=null)
        	if(!app.config.getUserPassword().equals("")) {
        		((EditText) this.findViewById(R.id.password)).setHint("Hash Saved");  
		        firstUse = false;
			}
        
        bindRegisterLogin();
        
        ((ToggleButton) this.findViewById(R.id.toggleButton)).setOnCheckedChangeListener(new OnCheckedChangeListener() {
			@Override
			public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
				firstUse = isChecked;
				bindRegisterLogin();
			}        	
        });
	    
        ((Button) this.findViewById(R.id.signin)).setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				User user = new User();
				
				if(!((EditText) DialogInit.this.findViewById(R.id.username)).getText().toString().equals("")) {
					user.username = ((EditText) DialogInit.this.findViewById(R.id.username)).getText().toString();
					app.config.setUserUsername(user.username);
				}
				else
					user.username = app.config.getUserUsername();
				
				if(!((EditText) DialogInit.this.findViewById(R.id.password)).getText().toString().equals("")) {
					user.password = SHA1.execute(((EditText) DialogInit.this.findViewById(R.id.password)).getText().toString());
					app.config.setUserPassword(user.password);
				}
				else
					user.password = app.config.getUserPassword();
				
				if(!((EditText) DialogInit.this.findViewById(R.id.server)).getText().toString().equals(""))
					app.config.setUrlServer(((EditText) DialogInit.this.findViewById(R.id.server)).getText().toString());
				
				if(firstUse)
					(new PostTask(app.config.getUrlServer()+"register", new IPostExecuteListener() {
						@Override
						public void execute(JSONObject json) {
							try {
								if(json!=null) {
									if(json.has("succeed"))
										if(json.getBoolean("succeed"))
											DialogInit.this.dismiss();
									if(json.has("toast"))										
										Toast.makeText(app, json.getString("toast"), Toast.LENGTH_SHORT).show();
								}
							} catch (JSONException e) {e.printStackTrace();}
						}						
					}, user.getJsonRegister())).execute();
				else
					(new PostTask(app.config.getUrlServer()+"login", new IPostExecuteListener() {
						@Override
						public void execute(JSONObject json) {
							try {
								if(json!=null) {
									if(json.has("succeed"))
										if(json.getBoolean("succeed"))									
											DialogInit.this.dismiss();
									if(json.has("toast"))										
										Toast.makeText(app, json.getString("toast"), Toast.LENGTH_SHORT).show();
								}
							} catch (JSONException e) {e.printStackTrace();}
						}						
					}, user.getJsonLogin())).execute();				
			}        	
        });
        DialogInit.this.show();
	}

	public void bindRegisterLogin() {
		((ToggleButton) this.findViewById(R.id.toggleButton)).setChecked(firstUse);
		if(firstUse)
			((TextView) this.findViewById(R.id.label)).setText(R.string.dialog_register);
		else
			((TextView) this.findViewById(R.id.label)).setText(R.string.dialog_login);
	}
}
