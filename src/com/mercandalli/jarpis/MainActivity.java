/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarpis;

import android.app.Dialog;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;

import com.mercandalli.jarpis.model.User;
import com.mercandalli.jarpis.net.RegisterTask;

public class MainActivity extends ApplicationDrawer {	
	
    @Override
    public void onCreate(Bundle savedInstanceState) {    	
    	setContentView(R.layout.activity_main);
        super.onCreate(savedInstanceState);
        
        dialog = new Dialog(this);
        dialog.setContentView(R.layout.view_login);
        dialog.setTitle(R.string.app_name);
        dialog.setCancelable(false);
        
        if(config.getUrlServer()!=null)
        	if(!config.getUrlServer().equals(""))
        		((EditText) dialog.findViewById(R.id.server)).setHint(config.getUrlServer());
        
        if(config.getUserUsername()!=null)
        	if(!config.getUserUsername().equals(""))
        		((EditText) dialog.findViewById(R.id.username)).setHint(config.getUserUsername());
        
        if(config.getUserPassword()!=null)
        	if(!config.getUserPassword().equals(""))
        		((EditText) dialog.findViewById(R.id.password)).setHint(config.getUserPassword());
        
        
        ((Button) dialog.findViewById(R.id.signin)).setOnClickListener(new OnClickListener() {
			@Override
			public void onClick(View v) {
				
				User user = new User();
				
				if(!((EditText) dialog.findViewById(R.id.username)).getText().toString().equals("")) {
					user.username = ((EditText) dialog.findViewById(R.id.username)).getText().toString();
					config.setUserUsername(user.username);
				}
				else
					user.username = config.getUserUsername();
				
				if(!((EditText) dialog.findViewById(R.id.password)).getText().toString().equals("")) {
					user.password = SHA1.execute(((EditText) dialog.findViewById(R.id.password)).getText().toString());
					config.setUserPassword(user.password);
				}
				else
					user.password = config.getUserPassword();
				
				if(!((EditText) dialog.findViewById(R.id.server)).getText().toString().equals(""))
					config.setUrlServer(((EditText) dialog.findViewById(R.id.server)).getText().toString());
				
				(new RegisterTask(config.getUrlServer(), user)).execute();
				
				dialog.dismiss();
			}        	
        });
        dialog.show();
    }    
    
}
