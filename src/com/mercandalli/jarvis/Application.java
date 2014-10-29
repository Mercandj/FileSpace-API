package com.mercandalli.jarvis;

import android.app.Activity;
import android.app.Dialog;
import android.os.Bundle;

import com.mercandalli.jarvis.conf.Config;

public class Application extends Activity {
	
	public Config config;
	public Dialog dialog;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {		
		super.onCreate(savedInstanceState);
		
		config = new Config(this);
	}
	
}

