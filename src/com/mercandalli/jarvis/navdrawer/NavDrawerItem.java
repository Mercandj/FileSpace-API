/**
 * Personal Project : Control server
 *
 * MERCANDALLI Jonathan
 */

package com.mercandalli.jarvis.navdrawer;

import com.mercandalli.jarvis.listener.IListener;

import android.widget.CompoundButton.OnCheckedChangeListener;

/**
 * Sliding Menu stuff
 * @author Jonathan
 *
 */
public class NavDrawerItem {	
	public String title;
	public int icon;
	public int SLIDING_MENU_TAB;
	public boolean isImage;
	public IListener listenerClick = null;
	
	public boolean initChecked = false;
	public OnCheckedChangeListener onCheckedChangeListener = null;
	
	public NavDrawerItem(String title, int icon, int SLIDING_MENU_TAB) {
		super();
		this.title = title;
		this.icon = icon;
		this.SLIDING_MENU_TAB = SLIDING_MENU_TAB;
		this.isImage = true;
	}
	
	public NavDrawerItem(String title, int icon, IListener listenerClick, int SLIDING_MENU_TAB) {
		super();
		this.title = title;
		this.icon = icon;
		this.SLIDING_MENU_TAB = SLIDING_MENU_TAB;
		this.isImage = true;
		this.listenerClick = listenerClick;
	}
	
	public NavDrawerItem(String title, boolean initChecked, OnCheckedChangeListener onCheckedChangeListener, int SLIDING_MENU_TAB) {
		super();
		this.title = title;
		this.SLIDING_MENU_TAB = SLIDING_MENU_TAB;
		this.initChecked = initChecked;
		this.onCheckedChangeListener = onCheckedChangeListener;		
		this.isImage = false;
	}
	
	public NavDrawerItem(String title, int SLIDING_MENU_TAB) {
		super();
		this.title = title;
		this.SLIDING_MENU_TAB = SLIDING_MENU_TAB;
		this.isImage = false;
	}
	
	public NavDrawerItem(String title, IListener listenerClick, int SLIDING_MENU_TAB) {
		super();
		this.title = title;
		this.SLIDING_MENU_TAB = SLIDING_MENU_TAB;
		this.isImage = false;
		this.listenerClick = listenerClick;
	}
	
	@Override
	public boolean equals(Object o) {
		return this.title.equals(((NavDrawerItem)o).title) && this.icon==((NavDrawerItem)o).icon;
	}
}
