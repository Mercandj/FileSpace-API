/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarvis;

import android.os.Bundle;

public class MainActivity extends ApplicationDrawer {	
	
    @Override
    public void onCreate(Bundle savedInstanceState) {    	
    	setContentView(R.layout.activity_main);
        super.onCreate(savedInstanceState);
        
        dialog = new DialogInit(this);        
    }    
    
}
