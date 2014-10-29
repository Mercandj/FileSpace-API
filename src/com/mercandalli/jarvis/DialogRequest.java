package com.mercandalli.jarvis;

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
				
				if(!((EditText) DialogRequest.this.findViewById(R.id.server)).getText().toString().equals(""))				
					(new PostTask(app.config.getUrlServer()+((EditText) DialogRequest.this.findViewById(R.id.server)).getText().toString(), new IPostExecuteListener() {
						@Override
						public void execute(JSONObject json) {
							Log.d("DialogRequest", ""+json.toString());
							Toast.makeText(app, ""+json.toString(), Toast.LENGTH_SHORT).show();
						}						
					}, user.getJsonRegister())).execute();
				DialogRequest.this.dismiss();
			}        	
        });
        DialogRequest.this.show();
	}
}
