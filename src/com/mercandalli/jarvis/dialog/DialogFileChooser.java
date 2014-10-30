package com.mercandalli.jarvis.dialog;

import android.app.Dialog;

import com.mercandalli.jarvis.Application;
import com.mercandalli.jarvis.R;

public class DialogFileChooser extends Dialog {
	
	public DialogFileChooser(final Application app) {
		super(app);
		
		this.setContentView(R.layout.view_request);
		this.setTitle(R.string.app_name);
		this.setCancelable(true);
        
        DialogFileChooser.this.show();
	}
}
