/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarvis;

import android.os.Bundle;

import com.mercandalli.jarvis.dialog.DialogInit;
import com.mercandalli.jarvis.fragment.FileManagerFragment;
import com.mercandalli.jarvis.fragment.FileManagerFragmentServer;
import com.mercandalli.jarvis.listener.IListener;

public class ActivityMain extends ApplicationDrawer {

	@Override
	public void onCreate(Bundle savedInstanceState) {
		setContentView(R.layout.activity_main);
		super.onCreate(savedInstanceState);

		dialog = new DialogInit(this, new IListener() {			
			@Override
			public void execute() {
				ActivityMain.this.config.connected = true;
				if(fragment instanceof FileManagerFragment) {
					FileManagerFragment fragmentFileManager = (FileManagerFragment) fragment;
					if(fragmentFileManager.listFragment[0]!=null)
						if(fragmentFileManager.listFragment[0] instanceof FileManagerFragmentServer) {
							FileManagerFragmentServer fragmentFileManagerFragment = (FileManagerFragmentServer) fragmentFileManager.listFragment[0];
							fragmentFileManagerFragment.refreshList();
						}
				}
			}
		});
	}
}
