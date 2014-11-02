/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarvis.dialog;

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

import com.mercandalli.jarvis.Application;
import com.mercandalli.jarvis.R;
import com.mercandalli.jarvis.SHA1;
import com.mercandalli.jarvis.listener.IListener;
import com.mercandalli.jarvis.listener.IPostExecuteListener;
import com.mercandalli.jarvis.model.ModelUser;
import com.mercandalli.jarvis.net.TaskPost;

public class DialogInit extends Dialog {

	boolean firstUse = true;
	IListener listenerLoginOK;
	
	public DialogInit(final Application app, IListener listenerLoginOK) {
		super(app);
		
		this.listenerLoginOK = listenerLoginOK;
		
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
				ModelUser user = new ModelUser();
				
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
				
				JSONObject json = new JSONObject();
				try {
					json.put("user", user.getJsonRegister());
				} catch (JSONException e1) {
					e1.printStackTrace();
				}
				
				if(firstUse)
					(new TaskPost(app.config.getUrlServer()+"user/register", new IPostExecuteListener() {
						@Override
						public void execute(JSONObject json, String body) {
							try {
								if(json!=null) {
									if(json.has("succeed"))
										if(json.getBoolean("succeed")) {
											DialogInit.this.dismiss();
											DialogInit.this.listenerLoginOK.execute();
										}
									if(json.has("toast"))										
										Toast.makeText(app, json.getString("toast"), Toast.LENGTH_SHORT).show();
								}
								else
									Toast.makeText(app, app.getString(R.string.server_error), Toast.LENGTH_SHORT).show();
							} catch (JSONException e) {e.printStackTrace();}
						}						
					}, json)).execute();
				else
					(new TaskPost(app.config.getUrlServer()+"user/login", new IPostExecuteListener() {
						@Override
						public void execute(JSONObject json, String body) {
							try {
								if(json!=null) {
									if(json.has("succeed"))
										if(json.getBoolean("succeed")) {
											DialogInit.this.dismiss();
											DialogInit.this.listenerLoginOK.execute();
										}
									if(json.has("toast"))										
										Toast.makeText(app, json.getString("toast"), Toast.LENGTH_SHORT).show();
								}
								else
									Toast.makeText(app, app.getString(R.string.server_error), Toast.LENGTH_SHORT).show();
							} catch (JSONException e) {e.printStackTrace();}
						}						
					}, json)).execute();				
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
